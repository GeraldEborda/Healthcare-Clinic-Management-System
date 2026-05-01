<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resources([
    'patients' => PatientController::class,
    'doctors' => DoctorController::class,
    'services' => ServiceController::class,
    'appointments' => AppointmentController::class,
    'transactions' => TransactionController::class,
], [
    'except' => ['show', 'create'],
]);
