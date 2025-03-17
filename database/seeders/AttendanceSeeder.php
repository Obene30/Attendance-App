<?php
// database/seeders/AttendanceSeeder.php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        Attendance::create([
            'attendee_id' => 1,
            'status' => 'present',
            'category' => 'Men',
            'created_at' => Carbon::now()->subDays(5),
        ]);
        
        Attendance::create([
            'attendee_id' => 2,
            'status' => 'absent',
            'category' => 'Women',
            'created_at' => Carbon::now()->subDays(6),
        ]);

        // Add more dummy data for testing
    }
}
