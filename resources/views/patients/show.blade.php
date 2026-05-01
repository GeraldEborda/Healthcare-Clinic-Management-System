@extends('layouts.app', ['title' => 'Patient History'])

@section('content')
    <div class="page-head">
        <div class="section-title">
            <h1>{{ $patient->name }}</h1>
            <p>Visit history, contact details, and recorded medical notes.</p>
        </div>
        <div class="actions">
            <a class="button secondary" href="{{ route('patients.edit', $patient) }}">Edit Profile</a>
            <a class="button secondary" href="{{ route('patients.index') }}">Back to Patients</a>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="panel">
            <div class="section-title" style="margin-bottom: 18px;">
                <h3>Patient Profile</h3>
                <p>{{ $patient->email }} | {{ $patient->phone }}</p>
            </div>
            <div class="stack">
                <div class="record"><strong>Date of Birth</strong><div class="meta">{{ $patient->date_of_birth->format('M d, Y') }}</div></div>
                <div class="record"><strong>Emergency Contact</strong><div class="meta">{{ $patient->emergency_contact ?: 'Not listed' }}</div></div>
                <div class="record"><strong>Address</strong><div class="meta">{{ $patient->address ?: 'Not listed' }}</div></div>
                <div class="record"><strong>Medical History</strong><div class="meta">{{ $patient->medical_history ?: 'No history recorded' }}</div></div>
            </div>
        </div>

        <div class="table-wrap">
            <div class="page-head">
                <div class="section-title">
                    <h3>Visit History</h3>
                    <p>Appointments and related billing records.</p>
                </div>
                <span class="badge">{{ $patient->appointments->count() }} visits</span>
            </div>
            <table>
                <thead><tr><th>Date</th><th>Doctor & Service</th><th>Status</th><th>Billing</th></tr></thead>
                <tbody>
                @forelse ($patient->appointments as $appointment)
                    <tr>
                        <td data-label="Date">{{ $appointment->appointment_date->format('M d, Y') }}<div class="meta">{{ \Illuminate\Support\Str::of($appointment->start_time)->substr(0, 5) }} - {{ \Illuminate\Support\Str::of($appointment->end_time)->substr(0, 5) }}</div></td>
                        <td data-label="Doctor & Service"><strong>{{ $appointment->doctor->name }}</strong><div class="meta">{{ $appointment->service?->name ?? 'Consultation' }}</div></td>
                        <td data-label="Status">{{ ucfirst($appointment->status) }}</td>
                        <td data-label="Billing">
                            @if ($appointment->transaction)
                                PHP {{ number_format($appointment->transaction->amount, 2) }}
                                <div class="meta">{{ ucfirst($appointment->transaction->status) }}</div>
                            @else
                                <span class="muted">No billing yet</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">No visits recorded yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
