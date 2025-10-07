<?php

namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class VolunteerBooking extends Model
    {
        use HasFactory;
        protected $table = 'volunteer_bookings';
        protected $fillable = [
            'volunteer_id',
            'ngo_id',
            'booking_code',
            'booking_date',
            'start_time',
            'end_time',
            'lunch_time',
            'status'
        ];

        protected $casts = [
            'booking_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'lunch_time' => 'datetime:H:i',
        ];

        // Relationship with volunteer (user)
        public function volunteer()
        {
            return $this->belongsTo(User::class, 'volunteer_id');
        }

        // Relationship with NGO
        public function ngo()
        {
            return $this->belongsTo(Ngo::class);
        }

        // Relationship with checkin
        public function checkin()
        {
            return $this->hasOne(VolunteerCheckin::class, 'booking_id');
        }

        // Scope for active bookings
        public function scopeActive($query)
        {
            return $query->whereIn('status', ['booked', 'checked_in']);
        }

        // Check if booking can be checked in
        public function canCheckin()
        {
            return $this->status === 'booked' &&
                $this->booking_date == now()->format('Y-m-d');
        }

        // Check if booking can be checked out
        public function canCheckout()
        {
            return $this->status === 'checked_in' &&
                $this->checkin &&
                !$this->checkin->checkout_time;
        }
    }
