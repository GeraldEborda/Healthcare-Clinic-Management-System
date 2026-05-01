@extends('layouts.app', ['title' => 'Transactions'])

@section('content')
    <div class="grid grid-2">
        <div class="panel">
            <div class="page-head"><h1>Transactions</h1></div>
            @include('transactions._form')
        </div>
        <div class="table-wrap">
            <div class="page-head">
                <h3>Billing Records</h3>
                <span class="badge">{{ $transactions->total() }} records</span>
            </div>
            <form method="GET" action="{{ route('transactions.index') }}" class="filters">
                <label class="wide">Search
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Patient, service, or payment method">
                </label>
                <label>Status
                    <select name="status">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="actions">
                    <button type="submit">Filter</button>
                    <a class="button secondary link-button" href="{{ route('transactions.index') }}">Reset</a>
                </div>
            </form>
            <table>
                <thead><tr><th>Patient</th><th>Billing</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td data-label="Patient"><strong>{{ $transaction->appointment->patient->name }}</strong><div class="meta">{{ $transaction->appointment->appointment_date->format('M d, Y') }}</div></td>
                        <td data-label="Billing">Amount: PHP {{ number_format($transaction->amount, 2) }}<div class="meta">Paid: PHP {{ number_format($transaction->paid, 2) }} | Balance: PHP {{ number_format($transaction->balance, 2) }}</div></td>
                        <td data-label="Status">{{ ucfirst($transaction->status) }}<div class="meta">{{ $transaction->payment_method ?: 'No payment method' }}</div></td>
                        <td data-label="Actions">
                            <div class="actions">
                                <a class="button secondary" href="{{ route('transactions.edit', $transaction) }}">Edit</a>
                                <form class="inline-form" method="POST" action="{{ route('transactions.destroy', $transaction) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="danger" type="submit" onclick="return confirm('Delete this transaction?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">No transactions yet.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $transactions->links() }}</div>
        </div>
    </div>
@endsection
