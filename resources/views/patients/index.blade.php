@extends('layouts.app', ['title' => 'Patients'])

@section('content')
    <div class="grid grid-2">
        <div class="panel">
            <div class="page-head">
                <div class="section-title">
                    <h1>Patients</h1>
                    <p>Register patients and keep contact details current.</p>
                </div>
                <span class="badge">{{ $patients->total() }} records</span>
            </div>
            @include('patients._form')
        </div>
        <div class="table-wrap">
            <div class="page-head">
                <div class="section-title">
                    <h3>Patient Directory</h3>
                    <p>Search records by name, email, or phone.</p>
                </div>
            </div>
            <form method="GET" action="{{ route('patients.index') }}" class="filters">
                <label class="wide">Search
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, email, or phone">
                </label>
                <div class="actions">
                    <button type="submit">Filter</button>
                    <a class="button secondary link-button" href="{{ route('patients.index') }}">Reset</a>
                </div>
            </form>
            <table>
                <thead><tr><th>Name</th><th>Contact</th><th>Details</th><th></th></tr></thead>
                <tbody>
                @forelse ($patients as $patient)
                    <tr>
                        <td data-label="Name"><strong>{{ $patient->name }}</strong><div class="meta">{{ $patient->email }}</div></td>
                        <td data-label="Contact">{{ $patient->phone }}<div class="meta">{{ $patient->emergency_contact ?: 'No emergency contact' }}</div></td>
                        <td data-label="Details">{{ $patient->date_of_birth->format('M d, Y') }}<div class="meta">{{ $patient->address ?: 'No address listed' }}</div></td>
                        <td data-label="Actions">
                            <div class="actions">
                                <a class="button secondary" href="{{ route('patients.show', $patient) }}">History</a>
                                <a class="button secondary" href="{{ route('patients.edit', $patient) }}">Edit</a>
                                <form class="inline-form" method="POST" action="{{ route('patients.destroy', $patient) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="danger" type="submit" onclick="return confirm('Delete this patient?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">No patients yet.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $patients->links() }}</div>
        </div>
    </div>
@endsection
