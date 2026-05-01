<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Clinic Admin',
            'email' => 'admin@clinic.test',
        ]);

        $doctors = collect([
            [
                'name' => 'Dr. Sofia Reyes',
                'specialization' => 'Family Medicine',
                'qualifications' => 'MD, Primary Care Specialist',
                'consultation_fee' => 800,
                'available_days' => ['Mon', 'Tue', 'Thu'],
                'time_slots' => ['09:00-12:00', '14:00-17:00'],
            ],
            [
                'name' => 'Dr. Marco Santos',
                'specialization' => 'Pediatrics',
                'qualifications' => 'MD, Pediatrician',
                'consultation_fee' => 950,
                'available_days' => ['Wed', 'Fri', 'Sat'],
                'time_slots' => ['10:00-13:00', '15:00-18:00'],
            ],
        ])->map(fn (array $doctor) => Doctor::create($doctor));

        $patients = collect([
            [
                'name' => 'Angela Cruz',
                'email' => 'angela@example.com',
                'phone' => '09171234567',
                'date_of_birth' => '1993-04-12',
                'address' => 'Quezon City',
                'medical_history' => 'Seasonal allergies',
                'emergency_contact' => 'Luis Cruz - 09179876543',
            ],
            [
                'name' => 'Noah Garcia',
                'email' => 'noah@example.com',
                'phone' => '09181231234',
                'date_of_birth' => '1988-09-30',
                'address' => 'Makati City',
                'medical_history' => 'Hypertension monitoring',
                'emergency_contact' => 'Mia Garcia - 09185551234',
            ],
        ])->map(fn (array $patient) => Patient::create($patient));

        $services = collect([
            [
                'name' => 'General Consultation',
                'description' => 'Routine clinic consultation and evaluation.',
                'doctor_id' => $doctors[0]->id,
                'fee' => 800,
                'type' => 'consultation',
                'is_active' => true,
            ],
            [
                'name' => 'Pediatric Consultation',
                'description' => 'Child wellness and follow-up consultation.',
                'doctor_id' => $doctors[1]->id,
                'fee' => 950,
                'type' => 'consultation',
                'is_active' => true,
            ],
            [
                'name' => 'Basic Lab Request',
                'description' => 'Lab coordination and result review.',
                'doctor_id' => null,
                'fee' => 500,
                'type' => 'lab_test',
                'is_active' => true,
            ],
        ])->map(fn (array $service) => Service::create($service));

        $appointment = Appointment::create([
            'patient_id' => $patients[0]->id,
            'doctor_id' => $doctors[0]->id,
            'service_id' => $services[0]->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '09:30',
            'status' => 'confirmed',
            'notes' => 'First visit consultation.',
        ]);

        Transaction::create([
            'appointment_id' => $appointment->id,
            'amount' => 800,
            'paid' => 500,
            'balance' => 300,
            'status' => 'partial',
            'payment_method' => 'Cash',
        ]);

        Appointment::create([
            'patient_id' => $patients[1]->id,
            'doctor_id' => $doctors[1]->id,
            'service_id' => $services[1]->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '10:30',
            'status' => 'pending',
            'notes' => 'Follow-up pediatric evaluation.',
        ]);
    }
}
