@extends('layouts.app')

@section('content')

<h2>Transaksi</h2>

<table border="1">

@foreach($transactions as $transaction)

<tr>

<td>{{ $transaction->id }}</td>

<td>{{ $transaction->status }}</td>

<td>
<a href="/transaction/{{ $transaction->id }}">
Detail
</a>
</td>

</tr>

@endforeach

</table>

@endsection
