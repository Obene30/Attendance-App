<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs for both Admin and Shepherd roles.
     */
    public function index()
    {
        // Filter activity logs based on authenticated user's role (optional if needed)
        $logs = ActivityLog::with('user')
            ->whereIn('action', [
                'create_attendee',
                'assign_attendee',
                'mark_attendance',
                'view_attendance',
                'shepherd_attendance',
                'create_group',
                'add_group_member',
                'view_weekly_report',
                'view_monthly_report',
                'view_shepherd_report',
                'export_pdf',
                'export_excel',
                'user_login',
            ])
            ->latest()
            ->paginate(20);

        return view('attendance.logs', compact('logs'));
    }

    /**
     * Helper method to log activity anywhere in the app.
     */
    public static function log($action, $description)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
