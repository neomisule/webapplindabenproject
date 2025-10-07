<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerCheckin extends Model
{
    use HasFactory;
    protected $table = 'volunteer_checkins';

    protected $fillable = [
        'booking_id',
        'checkin_time',
        'checkout_time',
        'lunch_duration',
        'total_working_hours',
        'notes',
        'status'
    ];

    protected $casts = [
        'checkin_time' => 'datetime',
        'checkout_time' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(VolunteerBooking::class);
    }

    public function checkoutRequest()
    {
        return $this->hasOne(VolunteerCheckoutRequest::class, 'checkin_id');
    }
}
