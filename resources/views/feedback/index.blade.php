@extends('layouts.app')

@section('content')

<h2>Feedback</h2>

<form action="/feedback" method="POST">

@csrf

<input
type="text"
name="customer_name"
placeholder="Nama">

<textarea
name="message">
</textarea>

<button type="submit">
Kirim
</button>

</form>

<table border="1">

@foreach($feedbacks as $feedback)

<tr>

<td>{{ $feedback->customer_name }}</td>

<td>{{ $feedback->message }}</td>

</tr>

@endforeach

</table>

@endsection
