@php($editing = isset($service))
<form method="POST" action="{{ $editing ? route('services.update', $service) : route('services.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif
    <label>Name <input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" required></label>
    <label>Assigned Doctor
        <select name="doctor_id">
            <option value="">General service</option>
            @foreach ($doctors as $doctor)
                <option value="{{ $doctor->id }}" @selected(old('doctor_id', $service->doctor_id ?? '') == $doctor->id)>{{ $doctor->name }}</option>
            @endforeach
        </select>
    </label>
    <label>Type
        <select name="type" required>
            @foreach ($types as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $service->type ?? 'consultation') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label>Fee <input type="number" min="0" step="0.01" name="fee" value="{{ old('fee', $service->fee ?? '') }}" required></label>
    <label>Description <textarea name="description">{{ old('description', $service->description ?? '') }}</textarea></label>
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true))> Active service</label>
    <div class="actions">
        <button type="submit">{{ $editing ? 'Update Service' : 'Add Service' }}</button>
        @if ($editing)
            <a class="button secondary" href="{{ route('services.index') }}">Cancel</a>
        @endif
    </div>
</form>
