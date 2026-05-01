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
        <div class="stat"><div class="muted">Gross Paid</div><strong>PHP {{ number_format($summary['paid'], 2) }}</strong></div>
        <div class="stat"><div class="muted">Outstanding</div><strong>PHP {{ number_format($summary['balance'], 2) }}</strong></div>
        <div class="stat"><div class="muted">Refunded</div><strong>PHP {{ number_format($summary['refunded'], 2) }}</strong></div>
        <div class="stat"><div class="muted">Net Revenue</div><strong>PHP {{ number_format($summary['net_paid'], 2) }}</strong></div>
    </div>

    <div class="grid grid-2" style="margin-bottom: 24px;">
        <div class="table-wrap">
            <div class="page-head">
                <div class="section-title">
                    <h3>Revenue by Doctor</h3>
                    <p>Net paid amount after refunds.</p>
                </div>
            </div>
            <table>
                <thead><tr><th>Doctor</th><th>Transactions</th><th>Net Revenue</th></tr></thead>
                <tbody>
                @forelse ($doctorRevenue as $row)
                    <tr>
                        <td data-label="Doctor"><strong>{{ $row['name'] }}</strong></td>
                        <td data-label="Transactions">{{ $row['count'] }}</td>
                        <td data-label="Net Revenue">PHP {{ number_format($row['paid'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">No doctor revenue yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-wrap">
            <div class="page-head">
                <div class="section-title">
                    <h3>Revenue by Service</h3>
                    <p>Net paid amount by consultation or service type.</p>
                </div>
            </div>
            <table>
                <thead><tr><th>Service</th><th>Transactions</th><th>Net Revenue</th></tr></thead>
                <tbody>
                @forelse ($serviceRevenue as $row)
                    <tr>
                        <td data-label="Service"><strong>{{ $row['name'] }}</strong></td>
                        <td data-label="Transactions">{{ $row['count'] }}</td>
                        <td data-label="Net Revenue">PHP {{ number_format($row['paid'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">No service revenue yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
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
                    <th>Refunded</th>
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
                    <td data-label="Refunded">PHP {{ number_format($transaction->refunded_amount, 2) }}</td>
                    <td data-label="Balance">PHP {{ number_format($transaction->balance, 2) }}</td>
                    <td data-label="Status">{{ ucfirst($transaction->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="muted">No transactions match this report.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
