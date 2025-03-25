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
        $records = Attendance::with(['attendee', 'markedBy'])->latest()->paginate(20);
    
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
    // ✅ Role check
    if (!Auth::user()->hasRole('Admin')) {
        abort(403, 'Unauthorized access');
    }

    $date = $request->input('date');

    // ✅ Get all shepherds
    $shepherds = User::role('Shepherd')->get();

    foreach ($shepherds as $shepherd) {
        $query = $shepherd->attendances()->with('attendee')->latest();

        if ($date) {
            $query->whereDate('date', $date);
        }

        // ✅ Paginate attendances and set relation
        $shepherd->setRelation(
            'attendances',
            $query->paginate(5, ['*'], "page_shepherd_{$shepherd->id}")
        );
    }

    return view('admin.shepherd-attendance', compact('shepherds'));
}



}
