@extends('layouts.app', ['title' => 'Edit Patient'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head"><h1>Edit Patient</h1></div>
        @include('patients._form', ['patient' => $patient])
    </div>
@endsection
