@extends('layouts.app')

@section('content')
<h2>Tambah Booking</h2>

<form action="/booking" method="POST">
@csrf

<input
type="text"
name="customer_name"
placeholder="Nama Customer">

<select name="table_id">
@foreach($tables as $table)
<option value="{{ $table->id }}">
Meja {{ $table->table_number }}
</option>
@endforeach
</select>

<input type="date" name="booking_date">

<button type="submit">
Simpan
</button>

</form>
@endsection
