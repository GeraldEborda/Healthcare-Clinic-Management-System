<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        return view('transactions.index', [
            'transactions' => Transaction::with(['appointment.patient', 'appointment.service'])
                ->when(request('search'), function ($query, string $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('payment_method', 'like', "%{$search}%")
                            ->orWhereHas('appointment.patient', fn ($query) => $query->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('appointment.service', fn ($query) => $query->where('name', 'like', "%{$search}%"));
                    });
                })
                ->when(request('status'), fn ($query, string $status) => $query->where('status', $status))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'appointments' => Appointment::with(['patient', 'doctor', 'service', 'transaction'])
                ->orderByDesc('appointment_date')
                ->get(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Transaction::create($this->validatedData($request));

        return redirect()->route('transactions.index')->with('status', 'Transaction added.');
    }

    public function edit(Transaction $transaction): View
    {
        return view('transactions.edit', [
            'transaction' => $transaction,
            'appointments' => Appointment::with(['patient', 'doctor', 'service', 'transaction'])
                ->orderByDesc('appointment_date')
                ->get(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $transaction->update($this->validatedData($request, $transaction));

        return redirect()->route('transactions.index')->with('status', 'Transaction updated.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('status', 'Transaction removed.');
    }

    private function validatedData(Request $request, ?Transaction $transaction = null): array
    {
        $validated = $request->validate([
            'appointment_id' => [
                'required',
                'exists:appointments,id',
                Rule::unique('transactions', 'appointment_id')->ignore($transaction?->id),
            ],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:100'],
        ]);

        $amount = $validated['amount'] ?? $this->defaultAmount((int) $validated['appointment_id']);

        if ((float) $validated['paid'] > (float) $amount) {
            throw ValidationException::withMessages([
                'paid' => 'The paid amount must not be greater than the total amount.',
            ]);
        }

        $balance = max(0, (float) $amount - (float) $validated['paid']);
        $status = match (true) {
            (float) $validated['paid'] <= 0 => 'unpaid',
            $balance <= 0 => 'paid',
            default => 'partial',
        };

        return array_merge($validated, [
            'amount' => $amount,
            'balance' => $balance,
            'status' => $status,
        ]);
    }

    private function defaultAmount(int $appointmentId): float
    {
        $appointment = Appointment::with(['service', 'doctor'])->findOrFail($appointmentId);

        return (float) ($appointment->service?->fee ?? $appointment->doctor->consultation_fee);
    }

    private function statuses(): array
    {
        return [
            'unpaid' => 'Unpaid',
            'partial' => 'Partial',
            'paid' => 'Paid',
            'refunded' => 'Refunded',
        ];
    }
}
