<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::as('project.')->middleware('auth')->controller(ProjectController::class)->group(function () {
    Route::get('/', 'list')->middleware('verified')->name('list');
    Route::get('/projet/{id}', 'show')->name('show');
    Route::post('/project/create', 'store')->name('store');
    Route::put('/project/update/{id}', 'update')->name('update');
    Route::delete('/project/delete/{id}', 'delete')->name('delete');
});

Route::as('transaction.')->middleware('auth')->controller(TransactionController::class)->group(function () {
    Route::post('/transaction/add/{id}', 'add')->name('add');
    Route::put('/transaction/update/{id}', 'update')->name('update');
    Route::delete('transaction/delete/{id}', 'delete')->name('delete');
});

require __DIR__.'/auth.php';
