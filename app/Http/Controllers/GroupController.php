<?php
namespace App\Http\Controllers;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;


class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('users')->paginate(5); // Adjust number per page if needed

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

    Group::create($request->only('name'));

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
    ]);

    // Update group name
    $group->name = $request->name;
    $group->save();

    // Sync users if any were submitted
    if ($request->filled('user_names')) {
        $names = array_map('trim', explode(',', $request->input('user_names')));
        $users = User::all();
        $matchedUserIds = [];
        $notFound = [];

        foreach ($names as $fullName) {
            $matched = false;
            foreach ($users as $user) {
                $userFullName = trim($user->first_name . ' ' . $user->last_name);
                if (strcasecmp($userFullName, $fullName) === 0) {
                    $matchedUserIds[] = $user->id;
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $notFound[] = $fullName;
            }
        }

        $group->users()->syncWithoutDetaching($matchedUserIds);

        if (!empty($notFound)) {
            return back()->with('error', 'Some users were not found: ' . implode(', ', $notFound));
        }
    }

    return redirect()->route('groups.index')->with('success', 'Group updated successfully.');
}

    


public function destroy(Group $group)
{
    $group->delete();
    return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
}

}

