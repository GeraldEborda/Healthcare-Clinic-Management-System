@php($editing = isset($transaction))
<form method="POST" action="{{ $editing ? route('transactions.update', $transaction) : route('transactions.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif
    <label>Appointment
        <select name="appointment_id" required>
            <option value="">Select appointment</option>
            @foreach ($appointments as $appointmentOption)
                @php($hasTransaction = $appointmentOption->transaction && (!$editing || $appointmentOption->transaction->id !== $transaction->id))
                @php($defaultAmount = $appointmentOption->service?->fee ?? $appointmentOption->doctor->consultation_fee)
                <option value="{{ $appointmentOption->id }}" data-amount="{{ $defaultAmount }}" @selected(old('appointment_id', $transaction->appointment_id ?? '') == $appointmentOption->id) @disabled($hasTransaction)>
                    {{ $appointmentOption->patient->name }} - {{ $appointmentOption->appointment_date->format('M d, Y') }}{{ $appointmentOption->service ? ' - '.$appointmentOption->service->name : '' }} - PHP {{ number_format($defaultAmount, 2) }}
                </option>
            @endforeach
        </select>
    </label>
    <div class="grid grid-2">
        <label>Total Amount <input type="number" min="0" step="0.01" name="amount" value="{{ old('amount', $transaction->amount ?? '') }}" placeholder="Auto-filled from appointment"></label>
        <label>Paid Amount <input type="number" min="0" step="0.01" name="paid" value="{{ old('paid', $transaction->paid ?? '') }}" required></label>
    </div>
    <label>Payment Method <input type="text" name="payment_method" value="{{ old('payment_method', $transaction->payment_method ?? '') }}" placeholder="Cash, Card, Transfer"></label>
    <div class="actions">
        <button type="submit">{{ $editing ? 'Update Transaction' : 'Add Transaction' }}</button>
        @if ($editing)
            <a class="button secondary" href="{{ route('transactions.index') }}">Cancel</a>
        @endif
    </div>
</form>

<script>
    (() => {
        const appointment = document.querySelector('select[name="appointment_id"]');
        const amount = document.querySelector('input[name="amount"]');

        if (!appointment || !amount) {
            return;
        }

        appointment.addEventListener('change', () => {
            const selected = appointment.options[appointment.selectedIndex];

            if (!amount.value && selected?.dataset.amount) {
                amount.value = Number(selected.dataset.amount).toFixed(2);
            }
        });
    })();
</script>
