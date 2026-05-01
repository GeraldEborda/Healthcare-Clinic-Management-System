@extends('layouts.app', ['title' => 'Edit Inventory Item'])

@section('content')
    <div class="panel" style="max-width: 760px;">
        <div class="page-head">
            <div class="section-title">
                <h1>Edit Inventory Item</h1>
                <p>Update stock counts and reorder information.</p>
            </div>
        </div>
        @include('inventory._form', ['item' => $item])
    </div>
@endsection
