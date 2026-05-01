@extends('layouts.app', ['title' => 'Edit Appointment'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head">
            <div class="section-title">
                <h1>Edit Appointment</h1>
                <p>Adjust appointment details and status.</p>
            </div>
        </div>
        @include('appointments._form', ['appointment' => $appointment])
    </div>
@endsection
