<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(): View
    {
        return view('doctors.index', [
            'doctors' => Doctor::query()
                ->when(request('search'), function ($query, string $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('specialization', 'like', "%{$search}%")
                            ->orWhere('qualifications', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Doctor::create($this->validatedData($request));

        return redirect()->route('doctors.index')->with('status', 'Doctor added.');
    }

    public function edit(Doctor $doctor): View
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $doctor->update($this->validatedData($request));

        return redirect()->route('doctors.index')->with('status', 'Doctor updated.');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        $doctor->delete();

        return redirect()->route('doctors.index')->with('status', 'Doctor removed.');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'qualifications' => ['nullable', 'string'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
            'available_days_input' => ['nullable', 'string'],
            'time_slots_input' => ['nullable', 'string'],
        ]);

        return [
            'name' => $validated['name'],
            'specialization' => $validated['specialization'],
            'qualifications' => $validated['qualifications'] ?? null,
            'consultation_fee' => $validated['consultation_fee'],
            'available_days' => $this->toArray($validated['available_days_input'] ?? ''),
            'time_slots' => $this->toArray($validated['time_slots_input'] ?? ''),
        ];
    }

    private function toArray(string $value): array
    {
        return collect(explode(',', $value))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
