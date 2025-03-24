<?php

use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\GroupController;




Route::get('/', function () {
    return view('welcome'); // Change to dashboard if needed
});

Route::resource('attendees', AttendeeController::class);
Route::get('/attendance', [AttendanceController::class, 'markAttendance'])->name('attendance.index');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/report/{period}', [AttendanceController::class, 'report'])->name('attendance.report');


Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
Route::get('/attendance/view', [AttendanceController::class, 'viewAttendance'])->name('attendance.view');



// Show the form to mark attendance
Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');

// Store the marked attendance
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');



Route::get('/dashboard', [AttendanceController::class, 'dashboard'])->name('dashboard');


Route::get('/dashboard', [AttendanceController::class, 'dashboard'])->name('dashboard');


//Route::resource('attendance', AttendanceController::class);


// Routes for importing/exporting attendance data
Route::post('attendance/import', [AttendanceController::class, 'import'])->name('attendance.import');
Route::get('attendance/download', [AttendanceController::class, 'download'])->name('attendance.download');


Auth::routes();


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
})->name('logout');


// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Attendee Management
Route::resource('attendees', AttendeeController::class);

// Attendance Management
//Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');  // View all attendance
Route::get('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');  // Mark attendance page
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');  // Store attendance data
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');

//weekly report
Route::get('/attendance/report/weekly', [AttendanceController::class, 'weeklyReport'])->name('attendance.report');
Route::get('/attendance/download/weekly', [AttendanceController::class, 'downloadWeeklyReport'])->name('attendance.download');
Route::get('/attendance/export-excel', [AttendanceController::class, 'exportExcel'])->name('attendance.exportExcel');
Route::get('/attendance/export-pdf', [AttendanceController::class, 'exportPDF'])->name('attendance.exportPDF');


Route::get('/attendance-report', [AttendanceController::class, 'report'])->name('attendance.report');

//mark attendance
Route::get('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');


//monthly report
Route::get('/attendance/report/monthly', [AttendanceController::class, 'monthlyReport'])->name('attendance.report.monthly');



//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/attendance/logs', [ActivityLogController::class, 'index'])->name('attendance.logs');


Route::get('/attendance/export-excel', function () {
    return Excel::download(new AttendanceExport, 'attendance.xlsx');
})->name('attendance.exportExcel');


//create group


Route::resource('groups', GroupController::class);
Route::post('/groups/{group}/addMember', [GroupController::class, 'addMember'])->name('groups.addMember');
Route::delete('/groups/{group}/removeMember/{user}', [GroupController::class, 'removeMember'])->name('groups.removeMember');



