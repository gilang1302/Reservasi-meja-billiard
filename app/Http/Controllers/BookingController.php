<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Table;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();

        return view(
            'booking.index',
            compact('bookings')
        );
    }

    public function create()
    {
        $tables = Table::all();

        return view(
            'booking.create',
            compact('tables')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'table_id' => 'required',
            'booking_date' => 'required'
        ]);

        Booking::create([
            'customer_name' => $request->customer_name,
            'table_id' => $request->table_id,
            'booking_date' => $request->booking_date,
            'status' => 'Pending'
        ]);

        return redirect('/booking');
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);

        return view(
            'booking.edit',
            compact('booking')
        );
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'customer_name' => $request->customer_name,
            'booking_date' => $request->booking_date,
            'status' => $request->status
        ]);

        return redirect('/booking');
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();

        return redirect('/booking');
    }
}