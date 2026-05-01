@extends('layouts.app', ['title' => 'Receipt'])

@section('content')
    <div class="page-head">
        <div class="section-title">
            <h1>Receipt #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <p>Digital receipt for recorded payment.</p>
        </div>
        <div class="actions print-actions">
            <button type="button" onclick="window.print()">Print Receipt</button>
            <a class="button secondary" href="{{ route('transactions.index') }}">Back to Billing</a>
        </div>
    </div>

    <div class="table-wrap">
        <div class="grid grid-2" style="margin-bottom: 24px;">
            <div>
                <h3>Patient</h3>
                <p><strong>{{ $transaction->appointment->patient->name }}</strong></p>
                <p class="meta">{{ $transaction->appointment->patient->email }} | {{ $transaction->appointment->patient->phone }}</p>
            </div>
            <div>
                <h3>Payment</h3>
                <p><strong>{{ ucfirst($transaction->status) }}</strong></p>
                <p class="meta">Method: {{ $transaction->payment_method ?: 'Not specified' }} | Date: {{ $transaction->updated_at->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="grid grid-3">
            <div class="stat"><div class="muted">Amount</div><strong>PHP {{ number_format($transaction->amount, 2) }}</strong></div>
            <div class="stat"><div class="muted">Paid</div><strong>PHP {{ number_format($transaction->paid, 2) }}</strong></div>
            <div class="stat"><div class="muted">Refunded</div><strong>PHP {{ number_format($transaction->refunded_amount, 2) }}</strong></div>
            <div class="stat"><div class="muted">Balance</div><strong>PHP {{ number_format($transaction->balance, 2) }}</strong></div>
        </div>

        @if ($transaction->refunded_at)
            <div class="record" style="margin-top: 24px;">
                <strong>Refund recorded {{ $transaction->refunded_at->format('M d, Y h:i A') }}</strong>
                <div class="meta">{{ $transaction->refund_reason }}</div>
            </div>
        @endif
    </div>
@endsection
