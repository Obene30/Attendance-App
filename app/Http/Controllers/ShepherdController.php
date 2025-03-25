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



class ShepherdController extends Controller
{
    public function myAttendees()
{
    $attendees = \App\Models\Attendee::where('user_id', auth()->id())->paginate(10);
    return view('shepherd.attendees', compact('attendees'));
}

}
