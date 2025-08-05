<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomCategoryController;

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


//ADMIN SECTION
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/add_room', function () {
    return view('/admin/room/add_room');
})->name('add_room');
Route::get('/manage_rooms', function () {
    return view('/admin/room/manage_rooms');
})->name('manage_rooms');

// --------------| Cat Routes |----------------------------------------

Route::get('/room-categories/create', [RoomCategoryController::class, 'create'])->name('room-categories.create');
Route::post('/room-categories', [RoomCategoryController::class, 'store'])->name('room-categories.store');
Route::put('/room-categories/{id}', [RoomCategoryController::class, 'update'])->name('room-categories.update');
Route::delete('/room-categories/{id}', [RoomCategoryController::class, 'destroy'])->name('room-categories.destroy');

//Route::get('/room-categories/{id}/edit', [RoomCategoryController::class, 'edit'])->name('room-categories.edit');
//Route::get('/room-categories', [RoomCategoryController::class, 'index'])->name('room-categories.index');


require __DIR__.'/auth.php';
