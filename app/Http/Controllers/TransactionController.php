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

    public function report(Request $request): View
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'status' => ['nullable', 'in:' . implode(',', array_keys($this->statuses()))],
        ]);

        $transactions = Transaction::with(['appointment.patient', 'appointment.doctor', 'appointment.service'])
            ->when($validated['date_from'] ?? null, fn ($query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($validated['date_to'] ?? null, fn ($query, string $date) => $query->whereDate('created_at', '<=', $date))
            ->when($validated['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->latest()
            ->get();

        return view('transactions.report', [
            'transactions' => $transactions,
            'statuses' => $this->statuses(),
            'filters' => $validated,
            'doctorRevenue' => $transactions
                ->groupBy(fn (Transaction $transaction) => $transaction->appointment->doctor->name)
                ->map(fn ($items, string $doctor) => [
                    'name' => $doctor,
                    'paid' => $items->sum('paid') - $items->sum('refunded_amount'),
                    'count' => $items->count(),
                ])
                ->sortByDesc('paid')
                ->values(),
            'serviceRevenue' => $transactions
                ->groupBy(fn (Transaction $transaction) => $transaction->appointment->service?->name ?? 'Consultation')
                ->map(fn ($items, string $service) => [
                    'name' => $service,
                    'paid' => $items->sum('paid') - $items->sum('refunded_amount'),
                    'count' => $items->count(),
                ])
                ->sortByDesc('paid')
                ->values(),
            'summary' => [
                'count' => $transactions->count(),
                'amount' => $transactions->sum('amount'),
                'paid' => $transactions->sum('paid'),
                'refunded' => $transactions->sum('refunded_amount'),
                'net_paid' => $transactions->sum('paid') - $transactions->sum('refunded_amount'),
                'balance' => $transactions->sum('balance'),
                'paid_count' => $transactions->where('status', 'paid')->count(),
                'partial_count' => $transactions->where('status', 'partial')->count(),
                'unpaid_count' => $transactions->where('status', 'unpaid')->count(),
            ],
        ]);
    }

    public function invoice(Transaction $transaction): View
    {
        return view('transactions.invoice', [
            'transaction' => $transaction->load(['appointment.patient', 'appointment.doctor', 'appointment.service']),
        ]);
    }

    public function receipt(Transaction $transaction): View
    {
        return view('transactions.receipt', [
            'transaction' => $transaction->load(['appointment.patient', 'appointment.doctor', 'appointment.service']),
        ]);
    }

    public function refund(Request $request, Transaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'refunded_amount' => ['required', 'numeric', 'min:0.01', 'max:' . $transaction->paid],
            'refund_reason' => ['nullable', 'string', 'max:255'],
        ]);

        if ($transaction->appointment->status !== 'cancelled') {
            throw ValidationException::withMessages([
                'refunded_amount' => 'Refunds are only allowed for cancelled appointments.',
            ]);
        }

        $transaction->update([
            'refunded_amount' => $validated['refunded_amount'],
            'refunded_at' => now(),
            'refund_reason' => $validated['refund_reason'] ?? 'Cancelled appointment',
            'balance' => 0,
            'status' => 'refunded',
        ]);

        return redirect()->route('transactions.index')->with('status', 'Refund recorded.');
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
