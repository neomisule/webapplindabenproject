<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerBooking;
use App\Models\Ngo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingsExport;
use PDF;

class BookingsController extends Controller
{
    public function index(Request $request)
    {
        $query = VolunteerBooking::with(['ngo', 'volunteer', 'checkin'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc');

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // NGO filter
        if ($request->filled('ngo_id')) {
            $query->where('ngo_id', $request->ngo_id);
        }

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('booking_date', [
                $request->from_date,
                $request->to_date
            ]);
        } elseif ($request->filled('from_date')) {
            $query->where('booking_date', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->where('booking_date', '<=', $request->to_date);
        }

        $bookings = $query->paginate(20);

        $ngos = Ngo::where('status', 1)->get();
        $statuses = [
            'booked' => 'Booked',
            'checked_in' => 'Checked In',
            'checked_out' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        return view('admin.pages.bookings.index', compact('bookings', 'ngos', 'statuses'));
    }

    public function show($id)
    {
        $booking = VolunteerBooking::with(['ngo', 'volunteer', 'checkin'])
            ->findOrFail($id);

        return view('admin.pages.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:booked,checked_in,checked_out,cancelled'
        ]);

        $booking = VolunteerBooking::findOrFail($id);
        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Booking status updated successfully!');
    }

    // public function export(Request $request)
    // {
    //     $query = VolunteerBooking::with(['ngo', 'volunteer', 'checkin'])
    //         ->orderBy('booking_date', 'desc')
    //         ->orderBy('start_time', 'desc');

    //     // Apply the same filters as index method
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->filled('ngo_id')) {
    //         $query->where('ngo_id', $request->ngo_id);
    //     }

    //     if ($request->filled('from_date') && $request->filled('to_date')) {
    //         $query->whereBetween('booking_date', [
    //             $request->from_date,
    //             $request->to_date
    //         ]);
    //     } elseif ($request->filled('from_date')) {
    //         $query->where('booking_date', '>=', $request->from_date);
    //     } elseif ($request->filled('to_date')) {
    //         $query->where('booking_date', '<=', $request->to_date);
    //     }

    //     $bookings = $query->get();
    //     $columns = $request->columns ?? [
    //         'id', 'volunteer_name', 'ngo_name', 'booking_date',
    //         'time_slot', 'working_hours', 'status'
    //     ];

    //     $fileName = 'bookings-' . date('Y-m-d') . '.' . $request->format;

    //     if ($request->format == 'pdf') {
    //         $pdf = PDF::loadView('admin.exports.bookings-pdf', [
    //             'bookings' => $bookings,
    //             'columns' => $columns
    //         ]);
    //         return $pdf->download($fileName);
    //     }

    //     return Excel::download(new BookingsExport($bookings, $columns), $fileName);
    // }
}
