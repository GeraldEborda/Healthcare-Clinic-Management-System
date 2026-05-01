@extends('layouts.app', ['title' => 'Edit Transaction'])

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div class="page-head">
            <div class="section-title">
                <h1>Edit Transaction</h1>
                <p>Update payment details and recalculated balance.</p>
            </div>
        </div>
        @include('transactions._form', ['transaction' => $transaction])
    </div>
@endsection
