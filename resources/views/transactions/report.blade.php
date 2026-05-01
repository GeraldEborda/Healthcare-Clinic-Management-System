@extends('layouts.app', ['title' => 'Billing Report'])

@section('content')
    <div class="page-head">
        <div class="section-title">
            <h1>Billing Report</h1>
            <p>Financial summary for transactions, payments, and outstanding balances.</p>
        </div>
        <div class="actions print-actions">
            <button type="button" onclick="window.print()">Print Report</button>
            <a class="button secondary" href="{{ route('transactions.index') }}">Back to Billing</a>
        </div>
    </div>

    <div class="table-wrap print-actions" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('transactions.report') }}" class="filters">
            <label>Date From
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
            </label>
            <label>Date To
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
            </label>
            <label>Status
                <select name="status">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <div class="actions">
                <button type="submit">Apply Filters</button>
                <a class="button secondary" href="{{ route('transactions.report') }}">Reset</a>
            </div>
        </form>
    </div>

    <div class="grid grid-3" style="margin-bottom: 24px;">
        <div class="stat"><div class="muted">Transactions</div><strong>{{ $summary['count'] }}</strong></div>
        <div class="stat"><div class="muted">Total Amount</div><strong>PHP {{ number_format($summary['amount'], 2) }}</strong></div>
        <div class="stat"><div class="muted">Total Paid</div><strong>PHP {{ number_format($summary['paid'], 2) }}</strong></div>
        <div class="stat"><div class="muted">Outstanding</div><strong>PHP {{ number_format($summary['balance'], 2) }}</strong></div>
        <div class="stat"><div class="muted">Paid Records</div><strong>{{ $summary['paid_count'] }}</strong></div>
        <div class="stat"><div class="muted">Partial / Unpaid</div><strong>{{ $summary['partial_count'] + $summary['unpaid_count'] }}</strong></div>
    </div>

    <div class="table-wrap">
        <div class="page-head">
            <div class="section-title">
                <h3>Transaction Details</h3>
                <p>Generated {{ now()->format('M d, Y h:i A') }}</p>
            </div>
            <span class="badge">{{ $transactions->count() }} records</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Doctor & Service</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td data-label="Date">{{ $transaction->created_at->format('M d, Y') }}</td>
                    <td data-label="Patient">
                        <strong>{{ $transaction->appointment->patient->name }}</strong>
                        <div class="meta">{{ $transaction->appointment->appointment_date->format('M d, Y') }}</div>
                    </td>
                    <td data-label="Doctor & Service">
                        {{ $transaction->appointment->doctor->name }}
                        <div class="meta">{{ $transaction->appointment->service?->name ?? 'Consultation' }}</div>
                    </td>
                    <td data-label="Amount">PHP {{ number_format($transaction->amount, 2) }}</td>
                    <td data-label="Paid">PHP {{ number_format($transaction->paid, 2) }}</td>
                    <td data-label="Balance">PHP {{ number_format($transaction->balance, 2) }}</td>
                    <td data-label="Status">{{ ucfirst($transaction->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="muted">No transactions match this report.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
