<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;


class AdminController extends Controller
{


    public function shepherdReport()
    {
        $records = Attendance::with(['attendee', 'markedBy'])->latest()->paginate(10);
    
        // Prepare data for charts
        $allData = Attendance::selectRaw('date, status, COUNT(*) as total')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
    
        $dates = $allData->pluck('date')->unique()->values();
        $statusGroups = ['Present' => [], 'Absent' => []];
    
        foreach ($dates as $date) {
            $daily = $allData->where('date', $date);
            $statusGroups['Present'][] = $daily->where('status', 'Present')->sum('total');
            $statusGroups['Absent'][] = $daily->where('status', 'Absent')->sum('total');
        }
    
        // Pie chart data
        $totalPresent = Attendance::where('status', 'Present')->count();
        $totalAbsent = Attendance::where('status', 'Absent')->count();
    
        return view('admin.shepherd-report', compact('records', 'dates', 'statusGroups', 'totalPresent', 'totalAbsent'));
    }
    
    public function viewShepherdAttendance(Request $request)
{
    $date = $request->input('date');

    $users = User::whereHas('roles', function ($query) {
        $query->whereIn('name', ['Shepherd', 'Admin']);
    })->get();

    foreach ($users as $user) {
        $query = $user->attendances()->with('attendee')->latest();

        if ($date) {
            $query->whereDate('date', $date);
        }

        // paginate each user's attendance records
        $user->setRelation(
            'attendances',
            $query->paginate(5, ['*'], "page_user_{$user->id}")
        );
    }

    // ✅ Log the view action
    \App\Http\Controllers\ActivityLogController::log('shepherd_attendance', 'Viewed shepherds\' attendance.');

    return view('admin.shepherd-attendance', ['shepherds' => $users]);
}



}
