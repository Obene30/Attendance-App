<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ExternalMember;
use App\Http\Controllers\ActivityLogController;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('users')->paginate(5);
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:groups,name',
        ]);

        $group = Group::create($request->only('name'));

        ActivityLogController::log('create_group', 'Created new group: ' . $group->name);

        return redirect()->route('groups.index')->with('success', 'Group created successfully!');
    }

    public function edit(Group $group)
    {
        $users = User::all();
        return view('groups.edit', compact('group', 'users'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|unique:groups,name,' . $group->id,
            'user_names' => 'nullable|string',
            'external_members' => 'nullable|string',
        ]);

        $group->name = $request->name;
        $group->save();

        // Handle internal members
        if ($request->filled('user_names')) {
            $inputs = array_map('trim', explode(',', $request->input('user_names')));
            $users = User::all();
            $matchedUserIds = [];
            $notFound = [];

            foreach ($inputs as $input) {
                $matched = false;
                foreach ($users as $user) {
                    $fullName = strtolower(trim($user->first_name . ' ' . $user->last_name));
                    if (
                        strtolower($user->email) === strtolower($input) ||
                        strtolower($user->username ?? '') === strtolower($input) ||
                        strtolower($fullName) === strtolower($input)
                    ) {
                        $matchedUserIds[] = $user->id;
                        $matched = true;
                        break;
                    }
                }

                if (! $matched) {
                    $notFound[] = $input;
                }
            }

            if (!empty($matchedUserIds)) {
                $group->users()->syncWithoutDetaching($matchedUserIds);
                ActivityLogController::log('add_group_member', 'Added members to group: ' . $group->name);
            }

            if (!empty($notFound)) {
                return back()->with('error', 'Some members were not found: ' . implode(', ', $notFound));
            }
        }

        // Handle external members
        if ($request->filled('external_members')) {
            $externalNames = array_map('trim', explode(',', $request->external_members));
            foreach ($externalNames as $name) {
                ExternalMember::create([
                    'group_id' => $group->id,
                    'name' => $name
                ]);
            }
        }

        return redirect()->route('groups.edit', $group)->with('success', 'Group updated successfully!');
    }

    public function removeUser(Group $group, User $user)
    {
        $group->users()->detach($user->id);

        ActivityLogController::log('remove_group_member', "Removed user {$user->first_name} {$user->last_name} from group {$group->name}");

        return redirect()->back()->with('success', 'Internal member removed successfully.');
    }

    public function removeExternalMember(Group $group, ExternalMember $external)
    {
        if ($external->group_id === $group->id) {
            $external->delete();

            ActivityLogController::log('remove_external_member', "Removed external member {$external->name} from group {$group->name}");

            return redirect()->back()->with('success', 'External member removed successfully.');
        }

        return redirect()->back()->with('error', 'Member does not belong to this group.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }
}
