<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendee;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Count total attendees
        $totalAttendees = Attendee::count();

        // Count attendance for this week
        $weeklyAttendance = Attendance::whereBetween('date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        // Count attendance for this month
        $monthlyAttendance = Attendance::whereBetween('date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->count();

        return view('dashboard', compact('totalAttendees', 'weeklyAttendance', 'monthlyAttendance'));
    }
}
