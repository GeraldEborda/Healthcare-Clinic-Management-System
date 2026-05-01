@extends('layouts.app', ['title' => 'Doctors'])

@section('content')
    <div class="grid grid-2">
        <div class="panel">
            <div class="page-head">
                <div class="section-title">
                    <h1>Doctors</h1>
                    <p>Manage providers, schedules, and consultation fees.</p>
                </div>
            </div>
            @include('doctors._form')
        </div>
        <div class="table-wrap">
            <div class="page-head">
                <div class="section-title">
                    <h3>Medical Team</h3>
                    <p>Filter by name, specialization, or qualification.</p>
                </div>
                <span class="badge">{{ $doctors->total() }} records</span>
            </div>
            <form method="GET" action="{{ route('doctors.index') }}" class="filters">
                <label class="wide">Search
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, specialization, or qualification">
                </label>
                <div class="actions">
                    <button type="submit">Filter</button>
                    <a class="button secondary link-button" href="{{ route('doctors.index') }}">Reset</a>
                </div>
            </form>
            <table>
                <thead><tr><th>Doctor</th><th>Schedule</th><th>Fee</th><th></th></tr></thead>
                <tbody>
                @forelse ($doctors as $doctor)
                    <tr>
                        <td data-label="Doctor"><strong>{{ $doctor->name }}</strong><div class="meta">{{ $doctor->specialization }}</div></td>
                        <td data-label="Schedule">{{ implode(', ', $doctor->available_days ?? []) ?: 'Not set' }}<div class="meta">{{ implode(', ', $doctor->time_slots ?? []) ?: 'No time slots' }}</div></td>
                        <td data-label="Fee">PHP {{ number_format($doctor->consultation_fee, 2) }}</td>
                        <td data-label="Actions">
                            <div class="actions">
                                <a class="button secondary" href="{{ route('doctors.edit', $doctor) }}">Edit</a>
                                <form class="inline-form" method="POST" action="{{ route('doctors.destroy', $doctor) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="danger" type="submit" onclick="return confirm('Delete this doctor?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">No doctors yet.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $doctors->links() }}</div>
        </div>
    </div>
@endsection
