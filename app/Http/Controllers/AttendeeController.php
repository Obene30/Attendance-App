<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendee;
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

    public function index()
    {
        if (auth()->user()->hasRole('Shepherd')) {
            $attendees = Attendee::where('user_id', auth()->id())->paginate(10);
        } else {
            $attendees = Attendee::paginate(10);
        }

        return view('attendees.index', compact('attendees'));
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

    /**
     * Show public attendee registration form
     */
    public function showForm()
    {
        return view('attendees.register');
    }

    /**
     * Handle public form submission
     */
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
}
