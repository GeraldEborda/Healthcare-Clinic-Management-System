@extends('layouts.app', ['title' => 'Edit Doctor'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head">
            <div class="section-title">
                <h1>Edit Doctor</h1>
                <p>Update provider profile, availability, and fees.</p>
            </div>
        </div>
        @include('doctors._form', ['doctor' => $doctor])
    </div>
@endsection
