@php($editing = isset($doctor))
<form method="POST" action="{{ $editing ? route('doctors.update', $doctor) : route('doctors.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif
    <div class="form-grid">
        <label>Name <input type="text" name="name" value="{{ old('name', $doctor->name ?? '') }}" required></label>
        <label>Specialization <input type="text" name="specialization" value="{{ old('specialization', $doctor->specialization ?? '') }}" required></label>
        <label>Consultation Fee <input type="number" min="0" step="0.01" name="consultation_fee" value="{{ old('consultation_fee', $doctor->consultation_fee ?? '') }}" required></label>
        <label>Available Days <input type="text" name="available_days_input" value="{{ old('available_days_input', isset($doctor) ? implode(', ', $doctor->available_days ?? []) : '') }}" placeholder="Mon, Tue, Wed"></label>
        <label class="full">Time Slots <input type="text" name="time_slots_input" value="{{ old('time_slots_input', isset($doctor) ? implode(', ', $doctor->time_slots ?? []) : '') }}" placeholder="09:00-12:00, 13:00-17:00"></label>
        <label class="full">Qualifications <textarea name="qualifications">{{ old('qualifications', $doctor->qualifications ?? '') }}</textarea></label>
    </div>
    <div class="actions">
        <button type="submit">{{ $editing ? 'Update Doctor' : 'Add Doctor' }}</button>
        @if ($editing)
            <a class="button secondary" href="{{ route('doctors.index') }}">Cancel</a>
        @endif
    </div>
</form>
