@extends('layouts.app', ['title' => 'Appointments'])

@section('content')
    <div class="grid grid-2">
        <div class="panel">
            <div class="page-head">
                <div class="section-title">
                    <h1>Appointments</h1>
                    <p>Book visits while preventing doctor schedule conflicts.</p>
                </div>
            </div>
            @include('appointments._form')
        </div>
        <div class="table-wrap">
            <div class="page-head">
                <div class="section-title">
                    <h3>Schedule</h3>
                    <p>Filter the calendar by date, doctor, and status.</p>
                </div>
                <span class="badge">{{ $appointments->total() }} records</span>
            </div>
            <form method="GET" action="{{ route('appointments.index') }}" class="filters">
                <label class="wide">Search
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Patient, doctor, or service">
                </label>
                <label>Status
                    <select name="status">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Doctor
                    <select name="doctor_id">
                        <option value="">All doctors</option>
                        @foreach ($doctors as $doctorOption)
                            <option value="{{ $doctorOption->id }}" @selected((string) request('doctor_id') === (string) $doctorOption->id)>{{ $doctorOption->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Date
                    <input type="date" name="appointment_date" value="{{ request('appointment_date') }}">
                </label>
                <div class="actions">
                    <button type="submit">Filter</button>
                    <a class="button secondary link-button" href="{{ route('appointments.index') }}">Reset</a>
                </div>
            </form>
            <table>
                <thead><tr><th>Patient</th><th>Doctor & Service</th><th>Schedule</th><th></th></tr></thead>
                <tbody>
                @forelse ($appointments as $appointment)
                    <tr>
                        <td data-label="Patient"><strong>{{ $appointment->patient->name }}</strong><div class="meta">{{ ucfirst($appointment->status) }}</div></td>
                        <td data-label="Doctor & Service">{{ $appointment->doctor->name }}<div class="meta">{{ $appointment->service?->name ?? 'No service selected' }}</div></td>
                        <td data-label="Schedule">{{ $appointment->appointment_date->format('M d, Y') }}<div class="meta">{{ \Illuminate\Support\Str::of($appointment->start_time)->substr(0, 5) }} - {{ \Illuminate\Support\Str::of($appointment->end_time)->substr(0, 5) }}</div></td>
                        <td data-label="Actions">
                            <div class="actions">
                                <a class="button secondary" href="{{ route('appointments.edit', $appointment) }}">Edit</a>
                                <form class="inline-form" method="POST" action="{{ route('appointments.destroy', $appointment) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="danger" type="submit" onclick="return confirm('Delete this appointment?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">No appointments yet.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $appointments->links() }}</div>
        </div>
    </div>
@endsection
