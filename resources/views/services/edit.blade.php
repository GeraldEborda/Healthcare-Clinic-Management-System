@extends('layouts.app', ['title' => 'Edit Service'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head"><h1>Edit Service</h1></div>
        @include('services._form', ['service' => $service])
    </div>
@endsection
