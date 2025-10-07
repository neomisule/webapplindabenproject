<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ngo extends Model
{
    protected $fillable = [
        'name',
        'date',
        'start_time',
        'end_time',
        'volunteers_needed',
        'address',
        'slug',
        'status',
        'for_staff',
        'role',
        'program',
        'allow_partial',
        'min_hours_per_volunteer',
        'recurring_group_id'

    ];

    public function staffBookings()
    {
        return $this->hasMany(VolunteerBooking::class, 'ngo_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(VolunteerBooking::class);
    }

    public function availableSlots()
    {
        $booked = $this->bookings()->active()->count();
        return max(0, $this->volunteers_needed - $booked);
    }

    public function hasAvailableSlots()
    {
        return $this->availableSlots() > 0;
    }

public function tasks()
{
    return $this->belongsToMany(Task::class, 'ngo_task', 'ngo_id', 'task_id');
}
}
