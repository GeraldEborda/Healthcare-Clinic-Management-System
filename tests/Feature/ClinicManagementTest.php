<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_directory_can_be_searched(): void
    {
        Patient::create([
            'name' => 'Ana Reyes',
            'email' => 'ana@example.com',
            'phone' => '09170000001',
            'date_of_birth' => '1990-01-01',
        ]);

        Patient::create([
            'name' => 'Ben Santos',
            'email' => 'ben@example.com',
            'phone' => '09170000002',
            'date_of_birth' => '1988-01-01',
        ]);

        $response = $this->get(route('patients.index', ['search' => 'Ana']));

        $response->assertOk();
        $response->assertSee('Ana Reyes');
        $response->assertDontSee('Ben Santos');
    }

    public function test_transaction_uses_appointment_service_fee_when_amount_is_blank(): void
    {
        $patient = Patient::create([
            'name' => 'Ana Reyes',
            'email' => 'ana@example.com',
            'phone' => '09170000001',
            'date_of_birth' => '1990-01-01',
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. Cruz',
            'specialization' => 'Family Medicine',
            'consultation_fee' => 500,
        ]);

        $service = Service::create([
            'name' => 'General Checkup',
            'doctor_id' => $doctor->id,
            'fee' => 750,
            'type' => 'consultation',
            'is_active' => true,
        ]);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '09:30',
            'status' => 'completed',
        ]);

        $response = $this->post(route('transactions.store'), [
            'appointment_id' => $appointment->id,
            'amount' => null,
            'paid' => 250,
            'payment_method' => 'Cash',
        ]);

        $response->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas(Transaction::class, [
            'appointment_id' => $appointment->id,
            'amount' => 750,
            'paid' => 250,
            'balance' => 500,
            'status' => 'partial',
        ]);
    }

    public function test_billing_report_loads_successfully(): void
    {
        $response = $this->get(route('transactions.report'));

        $response->assertOk();
        $response->assertSee('Billing Report');
        $response->assertSee('Financial summary');
    }
}
