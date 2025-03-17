@extends('layouts.app')

@section('content')
<div class="container">
  <!-- Divider between links -->
  <div class="divider"></div>
  <div class="divider"></div>
  <div class="divider"></div>
    <h2>MSCI Armley Church Dashboard</h2>
    <div class="divider"></div>
      <!-- Divider between links -->
      <div class="divider"></div>
        <!-- Divider between links -->
        <div class="divider"></div>


    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 p-3">
                
                <h4>👥 Total Attendees</h4>
                <p class="fs-3">{{ $totalAttendees }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 p-3">
                <h4>📅 Weekly Attendance</h4>
                <p class="fs-3">{{ $weeklyAttendance }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3 p-3">
                <h4>📆 Monthly Attendance</h4>
                <p class="fs-3">{{ $monthlyAttendance }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
