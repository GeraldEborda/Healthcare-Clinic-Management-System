@extends('layouts.app', ['title' => 'Services'])

@section('content')
    <div class="grid grid-2">
        <div class="panel">
            <div class="page-head"><h1>Services</h1></div>
            @include('services._form')
        </div>
        <div class="table-wrap">
            <div class="page-head">
                <h3>Service Catalog</h3>
                <span class="badge">{{ $services->total() }} records</span>
            </div>
            <form method="GET" action="{{ route('services.index') }}" class="filters">
                <label class="wide">Search
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Service name or description">
                </label>
                <label>Type
                    <select name="type">
                        <option value="">All types</option>
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
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
                <label>Status
                    <select name="status">
                        <option value="">Any status</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </label>
                <div class="actions">
                    <button type="submit">Filter</button>
                    <a class="button secondary link-button" href="{{ route('services.index') }}">Reset</a>
                </div>
            </form>
            <table>
                <thead><tr><th>Service</th><th>Doctor</th><th>Fee</th><th></th></tr></thead>
                <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td data-label="Service"><strong>{{ $service->name }}</strong><div class="meta">{{ $types[$service->type] ?? $service->type }}</div></td>
                        <td data-label="Doctor">{{ $service->doctor?->name ?? 'General' }}<div class="meta">{{ $service->is_active ? 'Active' : 'Inactive' }}</div></td>
                        <td data-label="Fee">PHP {{ number_format($service->fee, 2) }}</td>
                        <td data-label="Actions">
                            <div class="actions">
                                <a class="button secondary" href="{{ route('services.edit', $service) }}">Edit</a>
                                <form class="inline-form" method="POST" action="{{ route('services.destroy', $service) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="danger" type="submit" onclick="return confirm('Delete this service?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">No services yet.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $services->links() }}</div>
        </div>
    </div>
@endsection
