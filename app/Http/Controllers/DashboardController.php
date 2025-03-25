<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Attendee;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total stats
        $totalAttendees = Attendee::count();
        $weeklyAttendance = Attendance::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthlyAttendance = Attendance::whereMonth('date', now()->month)->count();

        // Get attendance dates for this month
        $dates = Attendance::selectRaw('DATE(date) as date')
                    ->whereMonth('date', now()->month)
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('date')
                    ->map(fn ($date) => Carbon::parse($date)->format('d M'));

        // Initialize status arrays
        $statusGroups = [
            'Present' => [],
            'Absent' => [],
        ];

        // Count Present and Absent by date
        foreach ($dates as $dateLabel) {
            $originalDate = Carbon::createFromFormat('d M', $dateLabel)->format('Y-m-d');

            $statusGroups['Present'][] = Attendance::whereDate('date', $originalDate)->where('status', 'Present')->count();
            $statusGroups['Absent'][] = Attendance::whereDate('date', $originalDate)->where('status', 'Absent')->count();
        }

        // Summary totals
        $totalPresent = array_sum($statusGroups['Present']);
        $totalAbsent = array_sum($statusGroups['Absent']);

        return view('dashboard', compact(
            'totalAttendees',
            'weeklyAttendance',
            'monthlyAttendance',
            'dates',
            'statusGroups',
            'totalPresent',
            'totalAbsent'
        ));
    }
}
