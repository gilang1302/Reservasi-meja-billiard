@extends('layouts.app')

@section('content')
<h2>Daftar Booking</h2>

<a href="/booking/create">Tambah Booking</a>

<table border="1">
    <tr>
        <th>Nama</th>
        <th>Tanggal</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach($bookings as $booking)
    <tr>
        <td>{{ $booking->customer_name }}</td>
        <td>{{ $booking->booking_date }}</td>
        <td>{{ $booking->status }}</td>
        <td>
            <a href="/booking/{{ $booking->id }}/edit">
                Edit
            </a>
        </td>
    </tr>
    @endforeach
</table>
@endsection
