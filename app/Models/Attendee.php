<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = ['full_name', 'address', 'dob', 'sex', 'category'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}


