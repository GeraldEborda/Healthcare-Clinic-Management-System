@extends('layouts.app', ['title' => 'Edit Service'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head">
            <div class="section-title">
                <h1>Edit Service</h1>
                <p>Update catalog details, pricing, and active status.</p>
            </div>
        </div>
        @include('services._form', ['service' => $service])
    </div>
@endsection
