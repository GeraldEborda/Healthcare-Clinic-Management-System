@extends('layouts.app', ['title' => 'Edit Patient'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head">
            <div class="section-title">
                <h1>Edit Patient</h1>
                <p>Update demographics, contact details, and clinical notes.</p>
            </div>
        </div>
        @include('patients._form', ['patient' => $patient])
    </div>
@endsection
