<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');
Route::get('/transactions/report', [TransactionController::class, 'report'])->name('transactions.report');
Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');
Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');
Route::post('/transactions/{transaction}/refund', [TransactionController::class, 'refund'])->name('transactions.refund');
Route::get('/appointments/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

Route::resources([
    'patients' => PatientController::class,
    'doctors' => DoctorController::class,
    'services' => ServiceController::class,
    'appointments' => AppointmentController::class,
    'transactions' => TransactionController::class,
    'inventory' => InventoryItemController::class,
], [
    'except' => ['show', 'create'],
]);
