<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(): View
    {
        return view('patients.index', [
            'patients' => Patient::query()
                ->when(request('search'), function ($query, string $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Patient::create($this->validatedData($request));

        return redirect()->route('patients.index')->with('status', 'Patient added.');
    }

    public function show(Patient $patient): View
    {
        return view('patients.show', [
            'patient' => $patient->load([
                'appointments' => fn ($query) => $query
                    ->with(['doctor', 'service', 'transaction'])
                    ->orderByDesc('appointment_date')
                    ->orderByDesc('start_time'),
            ]),
        ]);
    }

    public function edit(Patient $patient): View
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $patient->update($this->validatedData($request, $patient->id));

        return redirect()->route('patients.index')->with('status', 'Patient updated.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('status', 'Patient removed.');
    }

    private function validatedData(Request $request, ?int $patientId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:patients,email,' . $patientId],
            'phone' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'address' => ['nullable', 'string'],
            'medical_history' => ['nullable', 'string'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
