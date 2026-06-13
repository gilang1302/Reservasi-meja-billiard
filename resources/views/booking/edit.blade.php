@extends('layouts.app')

@section('content')

<form
action="/booking/{{ $booking->id }}"
method="POST">

@csrf
@method('PUT')

<input
type="text"
name="customer_name"
value="{{ $booking->customer_name }}">

<input
type="date"
name="booking_date"
value="{{ $booking->booking_date }}">

<select name="status">
<option>Pending</option>
<option>Approved</option>
<option>Cancelled</option>
</select>

<button type="submit">
Update
</button>

</form>

@endsection
