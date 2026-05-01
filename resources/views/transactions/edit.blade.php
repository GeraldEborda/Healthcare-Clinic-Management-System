@extends('layouts.app', ['title' => 'Edit Transaction'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head"><h1>Edit Transaction</h1></div>
        @include('transactions._form', ['transaction' => $transaction])
    </div>
@endsection
