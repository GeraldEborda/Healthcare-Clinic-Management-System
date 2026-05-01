@extends('layouts.app', ['title' => 'Invoice'])

@section('content')
    <div class="page-head">
        <div class="section-title">
            <h1>Invoice #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <p>Generated billing invoice for consultation and services.</p>
        </div>
        <div class="actions print-actions">
            <button type="button" onclick="window.print()">Print Invoice</button>
            <a class="button secondary" href="{{ route('transactions.index') }}">Back to Billing</a>
        </div>
    </div>

    <div class="table-wrap">
        <div class="grid grid-2" style="margin-bottom: 24px;">
            <div>
                <h3>Bill To</h3>
                <p><strong>{{ $transaction->appointment->patient->name }}</strong></p>
                <p class="meta">{{ $transaction->appointment->patient->email }} | {{ $transaction->appointment->patient->phone }}</p>
            </div>
            <div>
                <h3>Clinic Details</h3>
                <p><strong>HealthCare Clinic</strong></p>
                <p class="meta">Invoice Date: {{ $transaction->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <table>
            <thead><tr><th>Description</th><th>Doctor</th><th>Amount</th></tr></thead>
            <tbody>
                <tr>
                    <td data-label="Description">
                        <strong>{{ $transaction->appointment->service?->name ?? 'Consultation' }}</strong>
                        <div class="meta">Appointment: {{ $transaction->appointment->appointment_date->format('M d, Y') }}</div>
                    </td>
                    <td data-label="Doctor">{{ $transaction->appointment->doctor->name }}</td>
                    <td data-label="Amount">PHP {{ number_format($transaction->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="grid grid-3" style="margin-top: 24px;">
            <div class="stat"><div class="muted">Total</div><strong>PHP {{ number_format($transaction->amount, 2) }}</strong></div>
            <div class="stat"><div class="muted">Paid</div><strong>PHP {{ number_format($transaction->paid, 2) }}</strong></div>
            <div class="stat"><div class="muted">Balance</div><strong>PHP {{ number_format($transaction->balance, 2) }}</strong></div>
        </div>
    </div>
@endsection
