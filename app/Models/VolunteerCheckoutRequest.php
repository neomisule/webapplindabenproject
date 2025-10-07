<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerCheckoutRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkin_id',
        'volunteer_id',
        'lunch_duration',
        'checkout_time',
        'notes',
        'status',
        'processed_by',
        'processed_at'
    ];

    public function checkin()
    {
        return $this->belongsTo(VolunteerCheckin::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
