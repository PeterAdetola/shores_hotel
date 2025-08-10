<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\RoomController;

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
Route::get('/room_config', function () {
    return view('/admin/room/room_config');
})->name('room_config');


// --------------| Room Routes |----------------------------------------

Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

// --------------| Room Category Routes |----------------------------------------

Route::get('/room-categories/create', [RoomCategoryController::class, 'create'])->name('room-categories.create');
Route::post('/room-categories', [RoomCategoryController::class, 'store'])->name('room-categories.store');
Route::put('/room-categories/{id}', [RoomCategoryController::class, 'update'])->name('room-categories.update');
Route::delete('/room-categories/{id}', [RoomCategoryController::class, 'destroy'])->name('room-categories.destroy');

// --------------| Room Facility Routes |----------------------------------------

Route::prefix('facilities')->group(function () {
//    Route::get('/', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('/create', [FacilityController::class, 'create'])->name('facilities.create');
    Route::post('/', [FacilityController::class, 'store'])->name('facilities.store');
    Route::get('/{id}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::put('/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    Route::post('/facilities/reorder', [FacilityController::class, 'reorder'])->name('facilities.reorder');
    Route::delete('/facilities/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');


});

require __DIR__.'/auth.php';
