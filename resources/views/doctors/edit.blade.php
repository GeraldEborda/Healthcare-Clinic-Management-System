@extends('layouts.app', ['title' => 'Edit Doctor'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head"><h1>Edit Doctor</h1></div>
        @include('doctors._form', ['doctor' => $doctor])
    </div>
@endsection
