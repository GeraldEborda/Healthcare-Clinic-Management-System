@php($editing = isset($appointment))
<form method="POST" action="{{ $editing ? route('appointments.update', $appointment) : route('appointments.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif
    <label>Patient
        <select name="patient_id" required>
            <option value="">Select patient</option>
            @foreach ($patients as $patientOption)
                <option value="{{ $patientOption->id }}" @selected(old('patient_id', $appointment->patient_id ?? '') == $patientOption->id)>{{ $patientOption->name }}</option>
            @endforeach
        </select>
    </label>
    <label>Doctor
        <select name="doctor_id" required>
            <option value="">Select doctor</option>
            @foreach ($doctors as $doctorOption)
                <option value="{{ $doctorOption->id }}" @selected(old('doctor_id', $appointment->doctor_id ?? '') == $doctorOption->id)>{{ $doctorOption->name }}</option>
            @endforeach
        </select>
    </label>
    <label>Service
        <select name="service_id">
            <option value="">No linked service</option>
            @foreach ($services as $serviceOption)
                <option value="{{ $serviceOption->id }}" @selected(old('service_id', $appointment->service_id ?? '') == $serviceOption->id)>{{ $serviceOption->name }}</option>
            @endforeach
        </select>
    </label>
    <div class="grid grid-2">
        <label>Appointment Date <input type="date" name="appointment_date" value="{{ old('appointment_date', isset($appointment) ? $appointment->appointment_date?->format('Y-m-d') : '') }}" required></label>
        <label>Status
            <select name="status" required>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $appointment->status ?? 'pending') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
    </div>
    <div class="grid grid-2">
        <label>Start Time <input type="time" name="start_time" value="{{ old('start_time', isset($appointment) ? \Illuminate\Support\Str::of($appointment->start_time)->substr(0, 5) : '') }}" required></label>
        <label>End Time <input type="time" name="end_time" value="{{ old('end_time', isset($appointment) ? \Illuminate\Support\Str::of($appointment->end_time)->substr(0, 5) : '') }}" required></label>
    </div>
    <label>Notes <textarea name="notes">{{ old('notes', $appointment->notes ?? '') }}</textarea></label>
    <div class="actions">
        <button type="submit">{{ $editing ? 'Update Appointment' : 'Schedule Appointment' }}</button>
        @if ($editing)
            <a class="button secondary" href="{{ route('appointments.index') }}">Cancel</a>
        @endif
    </div>
</form>
