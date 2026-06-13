@extends('layouts.app')

@section('content')

<form action="/table" method="POST">

@csrf

<input
type="text"
name="table_number"
placeholder="Nomor Meja">

<input
type="text"
name="table_type"
placeholder="Tipe">

<button type="submit">
Simpan
</button>

</form>

@endsection
