@extends('layouts.app', ['title' => 'Appointment Calendar'])

@section('content')
    <div class="page-head">
        <div class="section-title">
            <h1>Calendar View</h1>
            <p>Scheduled consultations for {{ $month->format('F Y') }}.</p>
        </div>
        <a class="button secondary" href="{{ route('appointments.index') }}">Back to Appointment Desk</a>
    </div>

    <div class="table-wrap" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('appointments.calendar') }}" class="filters">
            <label>Month
                <input type="month" name="month" value="{{ $month->format('Y-m') }}">
            </label>
            <div class="actions">
                <button type="submit">View Month</button>
            </div>
        </form>
    </div>

    <div class="grid grid-3">
        @for ($day = $month->copy()->startOfMonth(); $day <= $month->copy()->endOfMonth(); $day->addDay())
            @php($dateKey = $day->format('Y-m-d'))
            <div class="panel">
                <div class="page-head">
                    <div class="section-title">
                        <h3>{{ $day->format('M d') }}</h3>
                        <p>{{ $day->format('l') }}</p>
                    </div>
                    <span class="badge">{{ ($appointmentsByDate[$dateKey] ?? collect())->count() }}</span>
                </div>
                <div class="stack">
                    @forelse ($appointmentsByDate[$dateKey] ?? [] as $appointment)
                        <div class="record">
                            <strong>{{ \Illuminate\Support\Str::of($appointment->start_time)->substr(0, 5) }} {{ $appointment->patient->name }}</strong>
                            <div class="meta">{{ $appointment->doctor->name }} | {{ $appointment->service?->name ?? 'Consultation' }}</div>
                            <div class="meta">{{ ucfirst($appointment->status) }}</div>
                        </div>
                    @empty
                        <div class="muted">No consultations.</div>
                    @endforelse
                </div>
            </div>
        @endfor
    </div>
@endsection
