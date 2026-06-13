@extends('layouts.app')

@section('content')

<h2>Inventory</h2>

<form action="/inventory" method="POST">

@csrf

<input
type="text"
name="item_name"
placeholder="Nama Barang">

<input
type="number"
name="quantity"
placeholder="Jumlah">

<button type="submit">
Simpan
</button>

</form>

<table border="1">

@foreach($inventories as $inventory)

<tr>

<td>{{ $inventory->item_name }}</td>

<td>{{ $inventory->quantity }}</td>

</tr>

@endforeach

</table>

@endsection
