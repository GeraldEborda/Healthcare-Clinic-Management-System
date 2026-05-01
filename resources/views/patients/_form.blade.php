@php($editing = isset($patient))
<form method="POST" action="{{ $editing ? route('patients.update', $patient) : route('patients.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif
    <div class="form-grid">
        <label>Name <input type="text" name="name" value="{{ old('name', $patient->name ?? '') }}" required></label>
        <label>Email <input type="email" name="email" value="{{ old('email', $patient->email ?? '') }}" required></label>
        <label>Phone <input type="text" name="phone" value="{{ old('phone', $patient->phone ?? '') }}" required></label>
        <label>Date of Birth <input type="date" name="date_of_birth" value="{{ old('date_of_birth', isset($patient) ? $patient->date_of_birth?->format('Y-m-d') : '') }}" required></label>
        <label class="full">Emergency Contact <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $patient->emergency_contact ?? '') }}"></label>
        <label class="full">Address <textarea name="address">{{ old('address', $patient->address ?? '') }}</textarea></label>
        <label class="full">Medical History <textarea name="medical_history">{{ old('medical_history', $patient->medical_history ?? '') }}</textarea></label>
    </div>
    <div class="actions">
        <button type="submit">{{ $editing ? 'Update Patient' : 'Add Patient' }}</button>
        @if ($editing)
            <a class="button secondary" href="{{ route('patients.index') }}">Cancel</a>
        @endif
    </div>
</form>
