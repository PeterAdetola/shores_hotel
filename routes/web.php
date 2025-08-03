<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index.index');
});
Route::get('/getlodged', function () {
    return view('getlodged');
})->name('getlodged');
Route::get('/typesOfRoom', function () {
    return view('typesOfRoom');
})->name('typesOfRoom');
Route::get('/typesOfApartment', function () {
    return view('typesOfApartment');
})->name('typesOfApartment');
Route::get('/chosen_lodge', function () {
    return view('chosen_lodge');
})->name('chosen_lodge');
Route::get('/confirmReservation', function () {
    return view('confirmReservation');
})->name('confirmReservation');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
