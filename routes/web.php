<?php

use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::group(["middleware" => "auth"], function(){
    Route::get('/', [PatientController::class, 'index'])->name('index');
    // Route::get('/patient/create', [PatientController::class, 'create'])->name('patient.create');
    Route::post('/patient/store', [PatientController::class, 'store'])->name('patient.store');

    Route::get('/medication', [MedicationController::class, 'index'])->name('medication.index');
    Route::post('/medication/store', [MedicationController::class, 'store'])->name('medication.store');
    Route::patch('/medication/{id}/update', [MedicationController::class, 'update'])->name('medication.update');
    Route::delete('/medication/{id}/destroy', [MedicationController::class, 'destroy'])->name('medication.destroy');
    Route::get('/prescritption/search-medications', [MedicationController::class, 'search'])->name('medication.search');

    Route::get('/prescription/{id}/create', [PrescriptionController::class, 'create'])->name('prescription.create');
    Route::post('/prescription/{id}', [PrescriptionController::class, 'store'])->name('prescription.store');
    Route::patch('/prescription/{id}/update', [PrescriptionController::class, 'updatePrescription'])->name('prescription.update');
    Route::delete('/prescription/{id}/destroy', [PrescriptionController::class, 'destroy'])->name('prescription.destroy');

    Route::get('/prescription/{id}/remaining', [PrescriptionController::class, 'editDurationAndRemainingQuantity'])->name('duration.remain.edit');
    Route::patch('/prescritpion/{id}/update/calculate', [PrescriptionController::class, 'updateDurationAndRemainingQuantites'])->name('duration.remain.update');
    Route::get('/prescritpion/{id}/pack', [PrescriptionController::class, 'implementUnitDosePackaging'])->name('pack.select');
    Route::post('prescription/{id}/pack/show', [PrescriptionController::class, 'showUnitDosePackaging'])->name('pack.show');

    Route::patch('prescription/{id}/revised_remaining/update', [PrescriptionController::class, 'updateRevisedRemaining'])->name('revised.remain.update');
    Route::get('prescription/{id}/adjust', [PrescriptionController::class, 'adjust'])->name('adjust.select');
    Route::post('prescription/{id}/adjust/show', [PrescriptionController::class, 'showAdjustments'])->name('adjust.show');
    Route::patch('prescription/{id}/adjust/update', [PrescriptionController::class, 'updateSelectedRemainingMedication'])->name('adjust.update');
});


