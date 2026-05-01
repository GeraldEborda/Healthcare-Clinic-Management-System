<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        return view('appointments.index', $this->viewData([
            'appointments' => Appointment::with(['patient', 'doctor', 'service', 'transaction'])
                ->when(request('search'), function ($query, string $search) {
                    $query->where(function ($query) use ($search) {
                        $query->whereHas('patient', fn ($query) => $query->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('doctor', fn ($query) => $query->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('service', fn ($query) => $query->where('name', 'like', "%{$search}%"));
                    });
                })
                ->when(request('status'), fn ($query, string $status) => $query->where('status', $status))
                ->when(request()->filled('doctor_id'), fn ($query) => $query->where('doctor_id', request('doctor_id')))
                ->when(request('appointment_date'), fn ($query, string $date) => $query->whereDate('appointment_date', $date))
                ->orderByDesc('appointment_date')
                ->orderBy('start_time')
                ->paginate(10)
                ->withQueryString(),
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->ensureDoctorAvailability($data);

        Appointment::create($data);

        return redirect()->route('appointments.index')->with('status', 'Appointment scheduled.');
    }

    public function edit(Appointment $appointment): View
    {
        return view('appointments.edit', $this->viewData([
            'appointment' => $appointment,
        ]));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->ensureDoctorAvailability($data, $appointment->id);

        $appointment->update($data);

        return redirect()->route('appointments.index')->with('status', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->route('appointments.index')->with('status', 'Appointment removed.');
    }

    private function viewData(array $data = []): array
    {
        return $data + [
            'patients' => Patient::orderBy('name')->get(),
            'doctors' => Doctor::orderBy('name')->get(),
            'services' => Service::where('is_active', true)->orderBy('name')->get(),
            'statuses' => [
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ],
        ];
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'appointment_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function ensureDoctorAvailability(array $data, ?int $ignoreId = null): void
    {
        $conflict = Appointment::query()
            ->where('doctor_id', $data['doctor_id'])
            ->whereDate('appointment_date', $data['appointment_date'])
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'start_time' => 'The doctor already has an appointment during that time.',
            ]);
        }
    }
}
