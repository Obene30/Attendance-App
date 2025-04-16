<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitation extends Model
{
    protected $fillable = [
        'attendee_id',
        'shepherd_id',
        'admin_comment',
        'shepherd_comment',
        'status',
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class);
    }

    public function shepherd()
    {
        return $this->belongsTo(User::class, 'shepherd_id');
    }
}

