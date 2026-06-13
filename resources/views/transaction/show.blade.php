@extends('layouts.app')

@section('content')

<h2>Detail Transaksi</h2>

<p>ID : {{ $transaction->id }}</p>

<p>Status : {{ $transaction->status }}</p>

<a href="/transaction/approve/{{ $transaction->id }}">
Approve
</a>

@endsection
