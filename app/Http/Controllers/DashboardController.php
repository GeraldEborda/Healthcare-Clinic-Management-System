<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'stats' => [
                'patients' => Patient::count(),
                'doctors' => Doctor::count(),
                'services' => Service::where('is_active', true)->count(),
                'appointments' => Appointment::count(),
                'revenue' => Transaction::sum('paid'),
                'outstanding' => Transaction::sum('balance'),
            ],
            'upcomingAppointments' => Appointment::with(['patient', 'doctor', 'service'])
                ->whereDate('appointment_date', '>=', now()->toDateString())
                ->orderBy('appointment_date')
                ->orderBy('start_time')
                ->limit(5)
                ->get(),
            'recentTransactions' => Transaction::with('appointment.patient')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
