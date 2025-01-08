<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransicationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Categories
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/check-name', [CategoryController::class, 'checkCategoryName'])->name('categories.check-name');

    //Transications
    Route::resource('transications', TransicationController::class);

    Route::get('/transactions/export/pdf', [TransicationController::class, 'exportPdf'])->name('transactions.export.pdf');
    
});

require __DIR__.'/auth.php';
