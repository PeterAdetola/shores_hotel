<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CitibarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GetLodgedController;
use App\Http\Controllers\BookingController;



//Route::get('/', function () {
//    return view('index.index');
//});
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/citibar', [CitibarController::class, 'index'])->name('citibar');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
//Route::get('/getlodged', [GetLodgedController::class, 'getlodged'])->name('getlodged');
//Route::get('/rooms/filter', [GetLodgedController::class, 'filter'])->name('rooms.filter');
Route::get('/getlodged', [GetLodgedController::class, 'index'])->name('getlodged');
Route::get('/getlodged/filter', [GetLodgedController::class, 'filter'])->name('getlodged.filter');
//Route::get('/getRoom', [GetRoomController::class, 'getRoom'])->name('getRoom');
//Route::get('/getApartment', [GetApartmentController::class, 'getApartment'])->name('getApartment');
//Route::get('/chosen_lodge/{slug}', [GetLodgedController::class, 'getlodge'])->name('chosen_lodge');
//Route::get('/room/{categorySlug}/{roomId}', [GetLodgedController::class, 'showRoom'])->name('chosen_lodge');
Route::get('/chosen_lodge/{categorySlug}/{roomId}', [GetLodgedController::class, 'showRoom'])->name('chosen_lodge')->where(['roomId' => '[0-9]+']);



//Route::get('/getlodged', function () {
//    return view('getlodged');
//})->name('getlodged');

Route::get('/getRooms', function () {
    return view('getRooms');
})->name('getRooms');

Route::get('/getApartments', function () {
    return view('getApartments');
})->name('getApartments');

//Route::get('/chosen_lodge', function () {
//    return view('chosen_lodge');
//})->name('chosen_lodge');

Route::get('/confirmReservation', function () {
    return view('confirmReservation');
})->name('confirmReservation');

Route::get('/bookedSuccessfully', function () {
    return view('bookedSuccessfully');
})->name('bookedSuccessfully');


//ADMIN SECTION
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// --------------| Booking Routes |----------------------------------------
Route::post('/make_booking', [BookingController::class, 'storeRoomDetails'])->name('make.booking');
Route::post('/store_booking', [BookingController::class, 'storeBooking'])->name('store.booking');
Route::get('/all_bookings', [BookingController::class, 'getAllBookings'])->name('get.all_bookings');
Route::get('/processed_bookings', [BookingController::class, 'getProcessedBookings'])->name('get.processed_bookings');
Route::get('/unprocessed_bookings', [BookingController::class, 'getUnprocessedBookings'])->name('get.unprocessed_bookings');

// --------------| Room Routes |----------------------------------------

//Route::get('/room/create', [RoomController::class, 'create'])->name('room.create');
Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
//Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

//Route::get('/room_management', function () {
//    return view('/admin/room/room_management');
//})->name('room_management');
Route::get('/add_room', function () {
    return view('/admin/room/add_room');
})->name('add_room');
Route::get('/room_config', function () {
    return view('/admin/room/room_config');
})->name('room_config');

// --------------| Room Category Routes |----------------------------------------

Route::get('/room-categories/create', [RoomCategoryController::class, 'create'])->name('room-categories.create');
Route::post('/room-categories', [RoomCategoryController::class, 'store'])->name('room-categories.store');
Route::put('/room-categories/{id}', [RoomCategoryController::class, 'update'])->name('room-categories.update');
Route::delete('/room-categories/{id}', [RoomCategoryController::class, 'destroy'])->name('room-categories.destroy');

// --------------| Room Facility Routes |----------------------------------------

Route::prefix('facilities')->group(function () {
//    Route::get('/', [FacilityController::class, 'index'])->name('facilities.index');
//    Route::get('/create', [FacilityController::class, 'create'])->name('facilities.create');
//    Route::get('/{id}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::post('/', [FacilityController::class, 'store'])->name('facilities.store');
    Route::put('/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    Route::post('/facilities/reorder', [FacilityController::class, 'reorder'])->name('facilities.reorder');
//    Route::delete('/facilities/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
});

// --------------| Room Management Routes |----------------------------------------

Route::middleware(['auth'])->group(function () {
    // Room listing page
    Route::get('/rooms', [RoomController::class, 'index'])->name('room_management');
    Route::post('/rooms/{room}/update-price', [RoomController::class, 'updatePrice'])->name('rooms.updatePrice');
    Route::post('/rooms/{room}/toggle-availability', [RoomController::class, 'toggleAvailability'])->name('rooms.toggleAvailability');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
    Route::get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('edit_room');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/room/{room}', [RoomController::class, 'destroy'])->name('room.destroy');
    Route::post('/rooms/reorder', [RoomController::class, 'reorder'])->name('rooms.reorder');
    Route::post('/rooms/update-availability', [RoomController::class, 'updateAvailability'])->name('rooms.updateAvailability');
    // routes/web.php
//    Route::get('/rooms/{room}/manage-gallery', [RoomController::class, 'manageGallery'])->name('rooms.manage_gallery');
    Route::prefix('rooms/{room}')->group(function () {
        Route::get('/manage-gallery', [RoomController::class, 'manageGallery'])
            ->name('rooms.manage_gallery');

        Route::post('/gallery/add', [RoomController::class, 'addImages'])
            ->name('room.gallery.add');

        Route::post('/gallery/add-image', [RoomController::class, 'addImage'])
            ->name('room.gallery.add_image');

        Route::patch('/gallery/{roomImage}/update', [RoomController::class, 'updateGalleryImage'])
            ->name('room.gallery.update');

//        Route::patch('/gallery/{roomImage}/toggle-featured', [RoomController::class, 'toggleFeatured'])
//            ->name('room.gallery.toggle_featured');

        Route::delete('/gallery/{roomImage}', [RoomController::class, 'deleteImage'])
            ->name('room.gallery.delete');
    });


//    Route::post('/rooms/{room}/upload-gallery', [RoomController::class, 'uploadGallery'])->name('rooms.upload_gallery');
//    Route::delete('/rooms/gallery/{image}', [RoomController::class, 'deleteGalleryImage'])->name('rooms.delete_gallery_image');
    Route::post('rooms/{room}/images/reorder', [RoomController::class, 'reorderImages'])->name('rooms.images.reorder');
    Route::post('/rooms/{room}/update-gallery', [RoomController::class, 'updateGallery'])->name('rooms.update_gallery');

    Route::delete('/rooms/images/{image}', [RoomController::class, 'deleteImage'])->name('rooms.delete_image');
});


// -----------------------------
// ROOMS routes
// -----------------------------
Route::prefix('rooms')->name('rooms.')->group(function () {
    // Room update sections
    Route::put('{room}/info', [RoomController::class, 'updateInfo'])->name('update.info');
//    Route::put('{room}/featured-image', [RoomController::class, 'updateFeaturedImage'])->name('update.featuredImage');
    Route::put('{room}/facilities', [RoomController::class, 'updateFacilities'])->name('update.facilities');

    // Gallery routes grouped by room
    Route::prefix('{room}/gallery')->name('gallery.')->group(function () {
        Route::get('/', [RoomController::class, 'editGallery'])->name('edit');
        Route::post('/', [RoomController::class, 'storeGallery'])->name('store');
    });
});

Route::get('/view-logs', function() {
    $logFile = storage_path('logs/laravel.log');

    if (!file_exists($logFile)) {
        return 'Log file not found';
    }

    $logs = file_get_contents($logFile);
    $lastLogs = collect(explode("\n", $logs))->reverse()->take(100)->reverse()->implode("\n");

    return '<pre>' . $lastLogs . '</pre>';
});

Route::get('/clear-cache', function() {
    try {
        // Clear all caches
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');

        return response()->json([
            'message' => 'All caches cleared successfully!',
            'details' => [
                'config' => 'cleared',
                'cache' => 'cleared',
                'route' => 'cleared',
                'view' => 'cleared',
                'optimize' => 'cleared'
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to clear cache',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/migrate-images', function() {
    $moved = 0;
    $failed = 0;

    try {
        // Create directory if not exists
        if (!file_exists(public_path('uploads/rooms'))) {
            mkdir(public_path('uploads/rooms'), 0755, true);
        }

        $images = \App\Models\RoomImage::all();

        foreach ($images as $image) {
            $oldPath = public_path('storage/' . $image->image_path);
            $newPath = public_path('uploads/' . $image->image_path);

            if (file_exists($oldPath)) {
                // Create subdirectories if needed
                $dir = dirname($newPath);
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }

                // Copy file
                if (copy($oldPath, $newPath)) {
                    $moved++;
                } else {
                    $failed++;
                }
            }
        }

        return "Migration complete! Moved: $moved, Failed: $failed";

    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/deep-search-images', function() {
    $targetImage = '68ea82a22261a.jpg';

    $searchPaths = [
        'storage/app/public/rooms' => storage_path('app/public/rooms'),
        'storage/app/public/room_images' => storage_path('app/public/room_images'),
        'public/storage/rooms' => public_path('storage/rooms'),
        'public/storage/room_images' => public_path('storage/room_images'),
        'public/uploads/rooms' => public_path('uploads/rooms'),
        'public_html/storage/rooms' => '/home/jupiterc/domains/shoreshotelng.com/public_html/storage/rooms',
        'public_html/uploads/rooms' => '/home/jupiterc/domains/shoreshotelng.com/public_html/uploads/rooms',
        'public_html/img/rooms' => '/home/jupiterc/domains/shoreshotelng.com/public_html/img/rooms',
    ];

    $allImages = [];
    foreach ($searchPaths as $label => $path) {
        if (file_exists($path)) {
            $files = scandir($path);
            $imageFiles = array_filter($files, function($file) {
                return preg_match('/\.(jpg|jpeg|png|webp)$/i', $file);
            });

            if (!empty($imageFiles)) {
                $allImages[$label] = [
                    'path' => $path,
                    'count' => count($imageFiles),
                    'files' => array_values($imageFiles),
                    'has_target' => in_array($targetImage, $imageFiles),
                ];
            }
        }
    }

    return response()->json([
        'searching_for' => $targetImage,
        'all_locations_with_images' => $allImages,
    ]);
});


require __DIR__.'/auth.php';
