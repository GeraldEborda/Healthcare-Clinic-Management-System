@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="page-head">
        <div class="section-title">
            <h1>Dashboard</h1>
            <p>Welcome back. Here's your clinic overview.</p>
        </div>
    </div>

    <div class="grid grid-3" style="margin-bottom: 30px;">
        <div class="stat"><div class="muted">Total Patients</div><strong>{{ $stats['patients'] }}</strong></div>
        <div class="stat"><div class="muted">Active Doctors</div><strong>{{ $stats['doctors'] }}</strong></div>
        <div class="stat"><div class="muted">Active Services</div><strong>{{ $stats['services'] }}</strong></div>
        <div class="stat"><div class="muted">Today's Appointments</div><strong>{{ $stats['todayAppointments'] }}</strong></div>
        <div class="stat"><div class="muted">Completed Today</div><strong>{{ $stats['completedToday'] }}</strong></div>
        <div class="stat"><div class="muted">Revenue Collected</div><strong>PHP {{ number_format($stats['revenue'], 2) }}</strong></div>
        <div class="stat"><div class="muted">Outstanding Balance</div><strong>PHP {{ number_format($stats['outstanding'], 2) }}</strong></div>
    </div>

    <div class="grid grid-2">
        <div class="table-wrap">
            <div class="page-head">
                <div class="section-title">
                    <h3>Upcoming Appointments</h3>
                    <p>Next confirmed clinic visits and service requests.</p>
                </div>
                <a class="button secondary" href="{{ route('appointments.index') }}">Manage</a>
            </div>
            <div class="stack">
                @forelse ($upcomingAppointments as $appointment)
                    <div class="record">
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
                <div class="section-title">
                    <h3>Recent Transactions</h3>
                    <p>Latest billing activity and open balances.</p>
                </div>
                <a class="button secondary" href="{{ route('transactions.index') }}">Manage</a>
            </div>
            <div class="stack">
                @forelse ($recentTransactions as $transaction)
                    <div class="record">
                        <strong>{{ $transaction->appointment->patient->name }}</strong>
                        <div class="meta">Paid: PHP {{ number_format($transaction->paid, 2) }} | Balance: PHP {{ number_format($transaction->balance, 2) }}</div>
                        <div class="meta">Status: {{ ucfirst($transaction->status) }}</div>
                    </div>
                @empty
                    <div class="muted">No transactions recorded yet.</div>
                @endforelse
            </div>
        </div>

        <div class="table-wrap">
            <div class="page-head">
                <div class="section-title">
                    <h3>Doctor Availability</h3>
                    <p>Today’s patient flow by doctor.</p>
                </div>
                <a class="button secondary" href="{{ route('doctors.index') }}">Manage</a>
            </div>
            <div class="stack">
                @forelse ($doctorAvailability as $doctor)
                    <div class="record">
                        <strong>{{ $doctor->name }}</strong>
                        <div class="meta">{{ $doctor->specialization }} | {{ $doctor->today_appointments_count }} patients today</div>
                        <div class="meta">{{ implode(', ', $doctor->available_days ?? []) ?: 'Schedule not set' }}</div>
                    </div>
                @empty
                    <div class="muted">No doctors recorded yet.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
