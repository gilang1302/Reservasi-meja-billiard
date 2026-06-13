@extends('layouts.app')

@section('content')

<h2>Daftar Meja</h2>

<a href="/table/create">
Tambah Meja
</a>

<table border="1">

@foreach($tables as $table)

<tr>
<td>{{ $table->table_number }}</td>
<td>{{ $table->table_type }}</td>
<td>{{ $table->status }}</td>

<td>
<a href="/table/status/{{ $table->id }}">
Ubah Status
</a>
</td>

</tr>

@endforeach

</table>

@endsection
