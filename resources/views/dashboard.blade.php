@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="grid grid-3" style="margin-bottom: 16px;">
        <div class="stat"><div class="muted">Patients</div><strong>{{ $stats['patients'] }}</strong></div>
        <div class="stat"><div class="muted">Doctors</div><strong>{{ $stats['doctors'] }}</strong></div>
        <div class="stat"><div class="muted">Active Services</div><strong>{{ $stats['services'] }}</strong></div>
        <div class="stat"><div class="muted">Appointments</div><strong>{{ $stats['appointments'] }}</strong></div>
        <div class="stat"><div class="muted">Revenue Collected</div><strong>PHP {{ number_format($stats['revenue'], 2) }}</strong></div>
        <div class="stat"><div class="muted">Outstanding Balance</div><strong>PHP {{ number_format($stats['outstanding'], 2) }}</strong></div>
    </div>

    <div class="grid grid-2">
        <div class="table-wrap">
            <div class="page-head">
                <h3>Upcoming Appointments</h3>
                <a class="button secondary" href="{{ route('appointments.index') }}">Manage</a>
            </div>
            <div class="stack">
                @forelse ($upcomingAppointments as $appointment)
                    <div class="panel" style="padding: 14px;">
                        <strong>{{ $appointment->patient->name }}</strong>
                        <div class="meta">{{ $appointment->doctor->name }}{{ $appointment->service ? ' | '.$appointment->service->name : '' }}</div>
                        <div class="meta">{{ $appointment->appointment_date->format('M d, Y') }} | {{ \Illuminate\Support\Str::of($appointment->start_time)->substr(0, 5) }} - {{ \Illuminate\Support\Str::of($appointment->end_time)->substr(0, 5) }}</div>
                    </div>
                @empty
                    <div class="muted">No upcoming appointments yet.</div>
                @endforelse
            </div>
        </div>

        <div class="table-wrap">
            <div class="page-head">
                <h3>Recent Transactions</h3>
                <a class="button secondary" href="{{ route('transactions.index') }}">Manage</a>
            </div>
            <div class="stack">
                @forelse ($recentTransactions as $transaction)
                    <div class="panel" style="padding: 14px;">
                        <strong>{{ $transaction->appointment->patient->name }}</strong>
                        <div class="meta">Paid: PHP {{ number_format($transaction->paid, 2) }} | Balance: PHP {{ number_format($transaction->balance, 2) }}</div>
                        <div class="meta">Status: {{ ucfirst($transaction->status) }}</div>
                    </div>
                @empty
                    <div class="muted">No transactions recorded yet.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
