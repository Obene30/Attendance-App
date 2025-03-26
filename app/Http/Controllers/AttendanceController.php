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

    public function weeklyReport()
    {
        ActivityLogController::log('view_weekly_report', 'Viewed weekly attendance report.');

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

        return view('attendance.report', compact('chartData'));
    }

    public function report(Request $request)
    {
        $query = Attendance::query();

        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('attendee', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('created_at', $request->date);
        }

        $attendances = $query->paginate(15);

        ActivityLogController::log('view_weekly_report', 'Viewed filtered weekly report via report method.');

        return view('attendance.report', compact('attendances'));
    }
}
