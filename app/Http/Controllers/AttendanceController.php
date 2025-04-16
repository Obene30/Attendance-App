<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Attendee;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleSheetsService;
use App\Models\Group;
use App\Http\Controllers\ActivityLogController;

class AttendanceController extends Controller
{
    public function index() {}

    public function dashboard()
    {
        $weeklyData = $this->getAttendanceData('weekly');
        $monthlyData = $this->getAttendanceData('monthly');
        return view('dashboard', compact('weeklyData', 'monthlyData'));
    }

    public function show($id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return redirect()->route('attendance.index')->with('error', 'Attendance record not found.');
        }
        return view('attendance.show', compact('attendance'));
    }

    public function viewAttendance()
    {
        $attendances = Attendance::with('attendee')->orderBy('date', 'desc')->paginate(10);
        return view('attendance.view', compact('attendances'));
    }

    public function markAttendance()
    {
        $attendees = Attendee::paginate(10);
        return view('attendance.mark', compact('attendees'));
    }

    private function getAttendanceData($period)
    {
        $startDate = Carbon::now()->startOf($period);
        $endDate = Carbon::now()->endOf($period);
        $attendanceData = Attendance::whereMonth('date', now()->month)->get();

        $dates = $attendanceData->pluck('date')->toArray();
        $menData = $attendanceData->pluck('men')->toArray();
        $womenData = $attendanceData->pluck('women')->toArray();
        $childrenData = $attendanceData->pluck('children')->toArray();

        return view('your_view', compact('dates', 'menData', 'womenData', 'childrenData'));
    }

    public function create()
    {
        $attendees = Attendee::all();
        return view('attendance.create', compact('attendees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendees' => 'required|array',
            'status' => 'required|array',
            'comment' => 'nullable|array',
        ]);

        foreach ($request->attendees as $attendeeId) {
            Attendance::create([
                'attendee_id' => $attendeeId,
                'date' => $request->date,
                'status' => $request->status[$attendeeId] ?? 'absent',
                'comment' => $request->comment[$attendeeId] ?? null,
            ]);
        }

        ActivityLogController::log('mark_attendance', 'Marked attendance for date: ' . $request->date);

        return redirect()->back()->with('success', 'Attendance marked successfully!');
    }

    public function downloadWeeklyReport()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $attendances = Attendance::whereBetween('date', [$startOfWeek, $endOfWeek])->get();

        $weeklyData = [];
        foreach ($attendances as $attendance) {
            $date = $attendance->date;
            $category = $attendance->attendee->category;

            if (!isset($weeklyData[$date])) {
                $weeklyData[$date] = ['Male' => 0, 'Female' => 0, 'Children' => 0, 'total' => 0];
            }

            $weeklyData[$date][$category]++;
            $weeklyData[$date]['total']++;
        }

        ActivityLogController::log('export_pdf', 'Exported weekly attendance report as PDF.');

        $pdf = Pdf::loadView('attendance.weekly_pdf', compact('weeklyData'));
        return $pdf->download('weekly_attendance_report.pdf');
    }

    public function exportExcel()
    {
        ActivityLogController::log('export_excel', 'Exported attendance as Excel.');
        return Excel::download(new AttendanceExport, 'attendance_report.xlsx');
    }

    public function exportPDF()
    {
        $attendances = Attendance::all();
        ActivityLogController::log('export_pdf', 'Exported full attendance report as PDF.');
        $pdf = Pdf::loadView('attendance.pdf', compact('attendances'));
        return $pdf->download('attendance_report.pdf');
    }

    public function showAttendanceReport()
    {
        $startOfWeek = Carbon::now()->startOfWeek()->format('F d, Y');
        $endOfWeek = Carbon::now()->endOfWeek()->format('F d, Y');
        $currentDate = Carbon::now()->format('F d, Y h:i A');
        $attendances = Attendance::all();

        ActivityLogController::log('view_weekly_report', 'Viewed detailed weekly attendance report.');

        return view('attendance.report', compact('startOfWeek', 'endOfWeek', 'currentDate', 'attendances'));
    }

    public function monthlyReport(Request $request)
    {
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $attendanceData = Attendance::where('date', 'like', "$currentMonth%")
            ->with('attendee')
            ->get();

        $dates = $attendanceData->pluck('date')->unique()->values()->toArray();
        $menData = [];
        $womenData = [];
        $childrenData = [];

        foreach ($dates as $date) {
            $menData[] = $attendanceData->where('date', $date)->where('attendee.category', 'Men')->count();
            $womenData[] = $attendanceData->where('date', $date)->where('attendee.category', 'Women')->count();
            $childrenData[] = $attendanceData->where('date', $date)->where('attendee.category', 'Children')->count();
        }

        $totalMen = array_sum($menData);
        $totalWomen = array_sum($womenData);
        $totalChildren = array_sum($childrenData);

        ActivityLogController::log('view_monthly_report', 'Viewed monthly attendance report.');

        return view('attendance.monthly-report', compact('currentMonth', 'dates', 'menData', 'womenData', 'childrenData', 'totalMen', 'totalWomen', 'totalChildren'));
    }



    public function shepherdReport(Request $request)
{
    $query = Attendance::with(['attendee', 'markedBy']);
    $fallbackMessage = null;

    if ($request->filled('marked_by')) {
        $markedName = $request->input('marked_by');

        // Capture matching user IDs first
        $userMatches = \App\Models\User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$markedName}%"])
            ->pluck('id');

        if ($userMatches->isEmpty()) {
            $fallbackMessage = "No shepherd found matching '{$markedName}'.";
            $query->whereRaw('1 = 0'); // prevent any results
        } else {
            $query->whereIn('marked_by', $userMatches);
        }
    }

    if ($request->filled('date')) {
        $query->whereDate('date', $request->input('date'));
    }

    $records = $query->orderBy('date', 'desc')->paginate(10);

    $dates = $records->pluck('date')->unique()->sort()->values();
    $statusGroups = [
        'Present' => [],
        'Absent' => [],
    ];

    foreach ($dates as $date) {
        $statusGroups['Present'][] = $records->where('date', $date)->where('status', 'Present')->count();
        $statusGroups['Absent'][] = $records->where('date', $date)->where('status', 'Absent')->count();
    }

    $totalPresent = $records->where('status', 'Present')->count();
    $totalAbsent = $records->where('status', 'Absent')->count();

    return view('admin.shepherd-report', compact(
        'records', 'dates', 'statusGroups', 'totalPresent', 'totalAbsent', 'fallbackMessage'
    ));
}


    
    public function weeklyReport(Request $request)
    {
        ActivityLogController::log('view_weekly_report', 'Viewed weekly attendance report.');
    
        // Main filtered query
        $query = Attendance::with('attendee');
    
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status); // Should be "Present" or "Absent"
        }
    
        $attendances = $query->orderBy('date', 'desc')->paginate(15);
    
        // Chart Data (optional but can be useful)
        $attendanceData = DB::table('attendances')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN category = "Men" THEN 1 ELSE 0 END) as men'),
                DB::raw('SUM(CASE WHEN category = "Women" THEN 1 ELSE 0 END) as women'),
                DB::raw('SUM(CASE WHEN category = "Children" THEN 1 ELSE 0 END) as children')
            )
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();
    
        $chartData = [
            'labels' => $attendanceData->pluck('date'),
            'male' => $attendanceData->pluck('men'),
            'female' => $attendanceData->pluck('women'),
            'children' => $attendanceData->pluck('children'),
            'total_men' => $attendanceData->sum('men'),
            'total_women' => $attendanceData->sum('women'),
            'total_children' => $attendanceData->sum('children'),
        ];
    
        return view('attendance.report', compact('attendances', 'chartData'));
    }
    
    // In AttendeeController

public function requestVisitation(Request $request, Attendee $attendee)
{
    $request->validate([
        'shepherd_id' => 'required|exists:users,id',
        'admin_comment' => 'nullable|string',
    ]);

    $attendee->visitation()->updateOrCreate(
        [],
        [
            'shepherd_id' => $request->shepherd_id,
            'admin_comment' => $request->admin_comment,
            'is_done' => false,
        ]
    );

    return back()->with('success', 'Visitation request submitted!');
}

public function completeVisitation(Request $request, Attendee $attendee)
{
    $request->validate([
        'shepherd_comment' => 'nullable|string',
    ]);

    if ($attendee->visitation && $attendee->visitation->shepherd_id === auth()->id()) {
        $attendee->visitation->update([
            'shepherd_comment' => $request->shepherd_comment,
            'is_done' => true,
        ]);

        return back()->with('success', 'Visitation marked as completed.');
    }

    return back()->with('error', 'Unauthorized action.');
}


}
