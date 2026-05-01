<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('services.index', [
            'services' => Service::with('doctor')
                ->when(request('search'), function ($query, string $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
                })
                ->when(request('type'), fn ($query, string $type) => $query->where('type', $type))
                ->when(request()->filled('doctor_id'), fn ($query) => $query->where('doctor_id', request('doctor_id')))
                ->when(request()->filled('status'), fn ($query) => $query->where('is_active', request('status') === 'active'))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'doctors' => Doctor::orderBy('name')->get(),
            'types' => $this->types(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Service::create($this->validatedData($request));

        return redirect()->route('services.index')->with('status', 'Service added.');
    }

    public function edit(Service $service): View
    {
        return view('services.edit', [
            'service' => $service,
            'doctors' => Doctor::orderBy('name')->get(),
            'types' => $this->types(),
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validatedData($request));

        return redirect()->route('services.index')->with('status', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('services.index')->with('status', 'Service removed.');
    }

    private function validatedData(Request $request): array
    {
        return array_merge($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'fee' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:' . implode(',', array_keys($this->types()))],
            'is_active' => ['nullable', 'boolean'],
        ]), [
            'is_active' => $request->boolean('is_active'),
        ]);
    }

    private function types(): array
    {
        return [
            'consultation' => 'Consultation',
            'lab_test' => 'Lab Test',
            'procedure' => 'Procedure',
            'other' => 'Other',
        ];
    }
}
