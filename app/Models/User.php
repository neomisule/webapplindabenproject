<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone_number',
        'password',
        'status',
        'setup_token',
        'setup_token_expires',
        'approval_token',
        'approval_token_expires',
        'email_verified_at',
        'volunteer_requested_at',
        'volunteer_approved_at',
        'volunteer_approval_token',
        'volunteer_token_expires',
        'points',
        'strikes',
        'temporary_ban_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', (array)$roles)->exists();
    }

    public function bookings()
    {
        return $this->hasMany(VolunteerBooking::class, 'volunteer_id');
    }

    public function checkins()
    {
        return $this->hasManyThrough(
            VolunteerCheckin::class,
            VolunteerBooking::class,
            'volunteer_id',
            'booking_id',
            'id',
            'id'
        );
    }

    public function totalVolunteerHours()
    {
        $totalMinutes = $this->checkins()
            ->whereNotNull('total_working_hours')
            ->get()
            ->sum(function ($checkin) {
                list($hours, $minutes) = explode(':', $checkin->total_working_hours);
                return ($hours * 60) + $minutes;
            });

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return sprintf('%d hours %d minutes', $hours, $minutes);
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class, 'user_id');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_assignments', 'user_id', 'task_id')
            ->withPivot('status', 'assigned_by')
            ->withTimestamps();
    }
}
