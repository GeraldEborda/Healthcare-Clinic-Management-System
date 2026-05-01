@extends('layouts.app', ['title' => 'Edit Appointment'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head"><h1>Edit Appointment</h1></div>
        @include('appointments._form', ['appointment' => $appointment])
    </div>
@endsection
