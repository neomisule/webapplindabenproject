<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bookings;
    protected $columns;

    public function __construct($bookings, $columns)
    {
        $this->bookings = $bookings;
        $this->columns = $columns;
    }

    public function collection()
    {
        return $this->bookings;
    }

    public function headings(): array
    {
        $headings = [];

        if (in_array('id', $this->columns)) {
            $headings[] = 'ID';
        }
        if (in_array('volunteer_name', $this->columns)) {
            $headings[] = 'Volunteer Name';
        }
        if (in_array('ngo_name', $this->columns)) {
            $headings[] = 'NGO Name';
        }
        if (in_array('booking_date', $this->columns)) {
            $headings[] = 'Booking Date';
        }
        if (in_array('time_slot', $this->columns)) {
            $headings[] = 'Time Slot';
        }
        if (in_array('working_hours', $this->columns)) {
            $headings[] = 'Working Hours';
        }
        if (in_array('status', $this->columns)) {
            $headings[] = 'Status';
        }

        return $headings;
    }

    public function map($booking): array
    {
        $row = [];

        if (in_array('id', $this->columns)) {
            $row[] = $booking->id;
        }
        if (in_array('volunteer_name', $this->columns)) {
            $row[] = $booking->volunteer->name;
        }
        if (in_array('ngo_name', $this->columns)) {
            $row[] = $booking->ngo->name;
        }
        if (in_array('booking_date', $this->columns)) {
            $row[] = date('d M Y', strtotime($booking->booking_date));
        }
        if (in_array('time_slot', $this->columns)) {
            $row[] = date('h:i A', strtotime($booking->start_time)) . ' - ' .
                     date('h:i A', strtotime($booking->end_time));
        }
        if (in_array('working_hours', $this->columns)) {
            $row[] = $booking->checkin ? $booking->checkin->total_working_hours : 'N/A';
        }
        if (in_array('status', $this->columns)) {
            $row[] = ucfirst(str_replace('_', ' ', $booking->status));
        }

        return $row;
    }
}
