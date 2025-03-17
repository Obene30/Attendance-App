<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Fetch logs related to attendance actions
        $logs = ActivityLog::whereIn('action', [
            'create_attendee',
            'mark_attendance',
            'view_report',
            'export_pdf',
            'export_excel',
            'user_login'
        ])->latest()->paginate(10); // Paginate results

        return view('attendance.logs', compact('logs'));
    }
}
