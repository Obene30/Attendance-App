<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Attendee;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleSheetsService;
use App\Models\Group;
use App\Http\Controllers\ActivityLogController;

class ShepherdController extends Controller
{
    public function myAttendees()
    {
        if (!Auth::user()->hasRole('Shepherd')) {
            abort(403, 'Unauthorized access.');
        }

        $attendees = Attendee::where('user_id', Auth::id())->paginate(10);
        return view('shepherd.attendees', compact('attendees'));
    }

    public function showMarkAttendance(Request $request)
    {
        $attendees = Attendee::where('user_id', auth()->id())->get();
        $selectedDate = $request->date ?? now()->toDateString();

        $existingRecords = Attendance::where('marked_by', auth()->id())
            ->where('date', $selectedDate)
            ->with('attendee')
            ->get();

        return view('shepherd.attendance', compact('attendees', 'existingRecords', 'selectedDate'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'attendance' => 'required|array',
            'date' => 'required|date'
        ]);

        $date = $request->input('date');

        foreach ($request->attendance as $attendeeId => $data) {
            if (!empty($data['status'])) {
                $existing = Attendance::where('attendee_id', $attendeeId)
                    ->where('marked_by', auth()->id())
                    ->where('date', $date)
                    ->first();

                if ($existing) {
                    $existing->update([
                        'status' => $data['status'],
                        'comment' => $data['comment'] ?? null,
                    ]);
                } else {
                    Attendance::create([
                        'attendee_id' => $attendeeId,
                        'marked_by' => auth()->id(),
                        'status' => $data['status'],
                        'date' => $date,
                        'comment' => $data['comment'] ?? null,
                    ]);
                }
            }
        }

        // ✅ Log the attendance marking action
        ActivityLogController::log('mark_attendance', 'Marked attendance for date: ' . $date);

        return redirect()->route('attendance.mark', ['date' => $date])
            ->with('success', '✅ Attendance saved/updated successfully.');
    }

    

    public function viewAttendance()
    {
        $attendances = Attendance::with('attendee')
            ->where('marked_by', auth()->id())
            ->orderBy('date', 'desc')
            ->paginate(10);
// ✅ Log the view action
ActivityLogController::log('view_attendance', 'Viewed own attendance records.');

return view('shepherd.view-attendance', compact('attendances'));
    }

    public function destroyAttendance($id)
    {
        $attendance = Attendance::where('id', $id)
            ->where('marked_by', auth()->id())
            ->firstOrFail();

        $attendance->delete();

        // ✅ Log the deletion
        ActivityLogController::log('delete_attendance', 'Deleted attendance record ID: ' . $id);

        return redirect()->back()->with('success', '🗑️ Attendance record deleted.');
    }
}
