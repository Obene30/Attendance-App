<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['attendee_id', 'status', 'date', 'comment', 'marked_by'];

    public function attendee()
    {
        return $this->belongsTo(\App\Models\Attendee::class);
    }
    
    public function markedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'marked_by'); // 'marked_by' should exist on `attendances` table
    }

public function attendances()
{
    return $this->hasMany(\App\Models\Attendance::class);
}


}


