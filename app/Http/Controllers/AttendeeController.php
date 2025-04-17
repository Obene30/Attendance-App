<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendee;
use App\Models\User;
use App\Models\Visitation;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ActivityLogController;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AttendeeController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $query = Attendee::with(['shepherd', 'visitation']);
    
        if (auth()->user()->hasRole('Shepherd')) {
            $query->where('user_id', auth()->id());
        }
    
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                  ->orWhereHas('shepherd', fn($q) => $q->where('first_name', 'like', "%$search%")
                                                      ->orWhere('last_name', 'like', "%$search%"))
                  ->orWhereHas('visitation', fn($q) => $q->where('status', 'like', "%$search%"));
            });
        }
    
        $attendees = $query->paginate(10)->appends($request->only('search'));
    
        return view('attendees.index', compact('attendees'));
    }
    


    public function shepherdVisitations()
    {
        $visitations = \App\Models\Visitation::with('attendee')
            ->where('shepherd_id', auth()->id())
            ->latest()
            ->get();
    
        return view('shepherd.visitations', compact('visitations')); // ✅ FIXED: use 'visitations'
    }
    
    public function allVisitations(Request $request)
    {
        $query = \App\Models\Visitation::with(['attendee', 'shepherd']);
    
        if ($request->filled('shepherd')) {
            $query->whereHas('shepherd', function ($q) use ($request) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$request->shepherd}%"]);
            });
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        $visitations = $query->latest()->paginate(10);
    
        return view('admin.visitation-report', compact('visitations'));
    }
    
    



    public function create()
    {
        return view('attendees.create');
    }

    public function edit(Attendee $attendee)
    {
        if ($attendee->dob && strlen($attendee->dob) === 10) {
            $attendee->dob = \Carbon\Carbon::parse($attendee->dob)->format('m-d');
        }

        return view('attendees.edit', compact('attendee'));
    }

    public function update(Request $request, Attendee $attendee)
    {
        $request->validate([
            'full_name' => 'required|string',
            'address' => 'required|string',
            'dob' => ['required', 'regex:/^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/'],
            'sex' => 'required|string',
            'category' => 'required|string',
        ]);

        $attendee->update([
            'full_name' => $request->full_name,
            'address' => $request->address,
            'dob' => $request->dob,
            'sex' => $request->sex,
            'category' => $request->category,
        ]);

        ActivityLogController::log('update_attendee', 'Updated attendee: ' . $attendee->full_name);

        return redirect()->route('attendees.index')->with('success', 'Attendee updated successfully');
    }

    public function destroy(Attendee $attendee)
    {
        $attendee->delete();

        ActivityLogController::log('delete_attendee', 'Deleted attendee: ' . $attendee->full_name);

        return redirect()->route('attendees.index')->with('success', 'Attendee deleted successfully');
    }

    public function assign(Request $request, Attendee $attendee)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
        ]);

        $attendee->user_id = $request->user_id;
        $attendee->save();

        ActivityLogController::log('assign_attendee', 'Assigned shepherd to attendee: ' . $attendee->full_name);

        return back()->with('success', 'Shepherd assigned successfully.');
    }

    public function logs()
    {
        if (!auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized access');
        }

        return view('attendance.logs');
    }

    public function exportExcel()
    {
        if (!auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized access');
        }

        return response()->download(storage_path('attendance.xlsx'));
    }

    public function exportPDF()
    {
        if (!auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized access');
        }

        return response()->download(storage_path('attendance.pdf'));
    }

    public function showForm()
    {
        return view('attendees.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'required|string',
            'dob' => ['required', 'regex:/^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/'],
            'sex' => 'required|in:Male,Female',
            'category' => 'required|in:Men,Women,Children',
        ]);

        $attendee = Attendee::create([
            'full_name' => $request->full_name,
            'address' => $request->address,
            'dob' => $request->dob,
            'sex' => $request->sex,
            'category' => $request->category,
        ]);

        ActivityLogController::log('create_attendee', 'Added new attendee: ' . $attendee->full_name);

        return redirect()->back()->with('success', '🎉 Registration successful!, Thank you!');
    }

    public function requestVisitation(Request $request, Attendee $attendee)
{
    $request->validate([
        'shepherd_ids' => 'required|array|min:1',
        'shepherd_ids.*' => 'exists:users,id',
    ]);
    
    foreach ($request->shepherd_ids as $shepherdId) {
        Visitation::updateOrCreate(
            ['attendee_id' => $attendee->id, 'shepherd_id' => $shepherdId],
            ['admin_comment' => $request->admin_comment, 'status' => 'Pending']
        );
    }
    

    return redirect()->back()->with('success', 'Visitation request assigned to selected shepherd(s).');
}


public function cancelVisitation(Attendee $attendee)
{
    if ($attendee->visitation && $attendee->visitation->count()) {
        $attendee->visitation->each->delete(); // This safely deletes all related visitations
        return back()->with('success', 'All visitation requests for this attendee have been cancelled.');
    }

    return back()->with('error', 'No visitation found for this attendee.');
}



    public function completeVisitation(Request $request, Attendee $attendee)
    {
        $request->validate([
            'shepherd_comment' => 'nullable|string|max:1000',
        ]);

        $visitation = Visitation::where('attendee_id', $attendee->id)
            ->where('shepherd_id', auth()->id())
            ->firstOrFail();

        $visitation->update([
            'shepherd_comment' => $request->shepherd_comment,
            'status' => 'Completed',
        ]);

        return redirect()->back()->with('success', 'Visitation marked as completed.');
    }
}