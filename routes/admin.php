<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorController;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::post('users/{user}/send-test-whatsapp', [UserController::class, 'sendTestWhatsApp'])->name('users.send-test-whatsapp');
Route::resource('patients', PatientController::class);
Route::resource('doctors', DoctorController::class)->except(['show']);
Route::resource('appointments', \App\Http\Controllers\Admin\AppointmentController::class);

Route::get('/schedules', \App\Livewire\Admin\ScheduleManager::class)->name('schedules.index');
