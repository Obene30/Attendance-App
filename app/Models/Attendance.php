<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['attendee_id', 'status', 'date', 'comment'];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class);
    }
}


