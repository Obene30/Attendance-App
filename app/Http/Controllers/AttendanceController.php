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




class AttendanceController extends Controller
{
    public function index(){
        
    }

    // Show Dashboard with Weekly/Monthly Report
    public function dashboard()
    {
        // Weekly data
        $weeklyData = $this->getAttendanceData('weekly');

        // Monthly data
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
    
// View all marked attendance
public function viewAttendance()
{
    $attendances = Attendance::with('attendee')->orderBy('date', 'desc')->paginate(10);
    return view('attendance.view', compact('attendances'));
}


public function markAttendance()
{
    $attendees = Attendee::paginate(10); // ✅ Ensure pagination is used
    return view('attendance.mark', compact('attendees'));
}


    // Get Attendance Data by category for weekly/monthly report
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



// Show the form to mark attendance
public function create()
{
    // Get all attendees
    $attendees = Attendee::all();
    
    return view('attendance.create', compact('attendees'));
}

// Store the attendance data
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

    return redirect()->back()->with('success', 'Attendance marked successfully!');
}




// Generate Weekly Report



// Download Weekly Report as PDF
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

    $pdf = Pdf::loadView('attendance.weekly_pdf', compact('weeklyData'));
    return $pdf->download('weekly_attendance_report.pdf');
}

//export excel
public function exportExcel()
    {
        return Excel::download(new AttendanceExport, 'attendance_report.xlsx');
    }


    public function exportPDF()
    {
        $attendances = Attendance::all();

        $pdf = Pdf::loadView('attendance.pdf', compact('attendances'));

        return $pdf->download('attendance_report.pdf');
    }


    public function showAttendanceReport()
    {
        // Calculate the start and end of the current week
        $startOfWeek = Carbon::now()->startOfWeek()->format('F d, Y');
        $endOfWeek = Carbon::now()->endOfWeek()->format('F d, Y');
    
        // Get the current date and time
        $currentDate = Carbon::now()->format('F d, Y h:i A');
    
        // Fetch attendance data
        $attendances = Attendance::all(); // Or adjust this query to fetch the relevant data for your report
    
        // Pass the data to the view
        return view('attendance.report', compact('startOfWeek', 'endOfWeek', 'currentDate', 'attendances'));
    }
    



    
    
    public function ActivityLog()
    {
        // Get the authenticated user's name
        $userName = Auth::user()->name;
    
        // Create a new activity log entry
        ActivityLog::create([
            'user' => $userName,  // Store the user's name instead of their ID
            'action' => 'mark_attendance',
            'description' => 'Marked attendance for Sunday service',
        ]);
    }
    





    public function monthlyReport(Request $request)
    {
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m')); // Default to current month
    
        // Fetch attendance for the selected month, with attendee details
        $attendanceData = Attendance::where('date', 'like', "$currentMonth%")
            ->with('attendee') // Load related attendee data
            ->get();
    
        // Extract unique dates
        $dates = $attendanceData->pluck('date')->unique()->values()->toArray();
    
        // Initialize arrays for category-based data
        $menData = [];
        $womenData = [];
        $childrenData = [];
    
        foreach ($dates as $date) {
            $menData[] = $attendanceData
                ->where('date', $date)
                ->where('attendee.category', 'Men') // Filter by category
                ->count();
    
            $womenData[] = $attendanceData
                ->where('date', $date)
                ->where('attendee.category', 'Women')
                ->count();
    
            $childrenData[] = $attendanceData
                ->where('date', $date)
                ->where('attendee.category', 'Children')
                ->count();
        }
    
        // Total counts for pie chart
        $totalMen = array_sum($menData);
        $totalWomen = array_sum($womenData);
        $totalChildren = array_sum($childrenData);
    
        return view('attendance.monthly-report', compact('currentMonth', 'dates', 'menData', 'womenData', 'childrenData', 'totalMen', 'totalWomen', 'totalChildren'));
    }
    




//weekly report


public function weeklyReport()
{
    // Fetch attendance data for the current week (or adjust the dates as needed)
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

    // Format the data for Chart.js
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

    // Filtering by category
    if ($request->has('category') && !empty($request->category)) {
        $query->whereHas('attendee', function ($q) use ($request) {
            $q->where('category', $request->category);
        });
    }

    // Filtering by date
    if ($request->has('date') && !empty($request->date)) {
        $query->whereDate('created_at', $request->date);
    }

    $attendances = $query->paginate(10); // Use pagination

    return view('attendance.report', compact('attendances'));
}



}