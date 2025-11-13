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
use App\Http\Controllers\EmailController;

use Webklex\PHPIMAP\ClientManager;



//Route::get('/', function () {
//    return view('index.index');
//});
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/aboutUs', [HomeController::class, 'about'])->name('aboutUs');

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

//

//Route::get('/getlodged', function () {
//    return view('getlodged');
//})->name('getlodged');

Route::get('/getRooms', function () {
    return view('getRooms');
})->name('getRooms');

Route::get('/getApartments', function () {
    return view('getApartments');
})->name('getApartments');


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

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Booking Email Routes
    Route::post('/bookings/{id}/send-email', [BookingController::class, 'sendEmail'])->name('bookings.send-email');

    Route::post('/bookings/{id}/send-whatsapp', [BookingController::class, 'sendWhatsApp'])->name('bookings.send-whatsapp');

    Route::post('/bookings/{id}/send-confirmation', [BookingController::class, 'sendConfirmation'])->name('bookings.send-confirmation');

    Route::post('/bookings/{id}/send-reminder', [BookingController::class, 'sendReminder'])->name('bookings.send-reminder');

});

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



// -----------------------------
// Email routes
// -----------------------------
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // ============================================
    // EMAIL ROUTES
    // ============================================

    // Inbox - View emails by folder
    Route::get('/email/inbox/{folder?}', [EmailController::class, 'inbox'])
        ->name('email.inbox');

    // View single email
    Route::get('/email/{uid}', [EmailController::class, 'show'])
        ->name('email.show');

    // Compose new email
    Route::match(['get', 'post'], '/email/compose', [EmailController::class, 'compose'])
        ->name('email.compose');

    // Email actions
    Route::post('/email/{uid}/reply', [EmailController::class, 'reply'])
        ->name('email.reply');

    Route::post('/email/{uid}/forward', [EmailController::class, 'forward'])
        ->name('email.forward');

    Route::post('/email/{uid}/toggle-flag', [EmailController::class, 'toggleFlag'])
        ->name('email.toggle-flag');

    Route::delete('/email/{uid}', [EmailController::class, 'delete'])
        ->name('email.delete');

    // Download attachment
    Route::get('/email/{uid}/attachment/{attachmentId}', [EmailController::class, 'downloadAttachment'])
        ->name('email.attachment');

    Route::post('/email/save-draft', [EmailController::class, 'saveDraft'])
        ->name('email.save-draft');

    Route::get('/email/draft/{uid}', [EmailController::class, 'loadDraft'])
        ->name('email.load-draft');

    Route::delete('/email/draft/{uid}', [EmailController::class, 'deleteDraft'])
        ->name('email.delete-draft');

    Route::post('/email/{uid}/mark-spam', [EmailController::class, 'markAsSpam'])
        ->name('email.mark-spam');
    Route::post('/email/{uid}/not-spam', [EmailController::class, 'notSpam'])
        ->name('email.not-spam');



    // ============================================
    // DIRECTADMIN EMAIL ROUTES
    // ============================================

    Route::post('/email/switch-account', [EmailController::class, 'switchAccount'])
        ->name('email.switch-account');

    Route::get('/email/test-directadmin', [EmailController::class, 'testDirectAdminConnection'])
        ->name('email.test-directadmin');

    Route::post('/email/clear-cache', [EmailController::class, 'clearEmailCache'])
        ->name('email.clear-cache');

    Route::get('/email/account/{email}', [EmailController::class, 'switchAndView'])
        ->name('email.switch-and-view');

    // ============================================
    // BOOKING EMAIL ROUTES
    // ============================================

    Route::post('/bookings/{id}/send-email', [BookingController::class, 'sendEmail'])
        ->name('bookings.send-email');

    Route::post('/bookings/{id}/send-whatsapp', [BookingController::class, 'sendWhatsApp'])
        ->name('bookings.send-whatsapp');

    Route::post('/bookings/{id}/send-confirmation', [BookingController::class, 'sendConfirmation'])
        ->name('bookings.send-confirmation');

    Route::post('/bookings/{id}/send-reminder', [BookingController::class, 'sendReminder'])
        ->name('bookings.send-reminder');
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

// In routes/web.php - TEMPORARY FOR TESTING
Route::get('/test-email', function() {
    $html = '<html><head><meta charset="UTF-8"></head><body style="font-family: Arial, sans-serif;"><h1 style="color: red; font-size: 32px;">TEST HTML EMAIL</h1><p style="font-size: 18px;">This should be <strong>bold</strong> and <em>italic</em></p><div style="background: #e9f7ef; padding: 20px; margin: 20px 0; border-left: 4px solid #28a745;"><h2 style="color: #28a745;">Styled Box</h2><p>If you see this styled, HTML is working!</p></div></body></html>';

    // For Laravel 9+ (no SwiftMailer)
    Mail::html($html, function($message) {
        $message->to('book_hotel@shoreshotelng.com')
            ->subject('Test HTML Email - ' . now());
    });

    return 'Test email sent! Check your inbox at book_hotel@shoreshotelng.com';
});

Route::get('/update-display-names', function () {
    try {
        $output = [];

        // Update shores_hotel display name only
        $hotel = App\Models\EmailAccount::where('email', 'book_hotel@shoreshotelng.com')->first();
        if ($hotel) {
            $hotel->display_name = 'Shores Hotel';
            $hotel->save();
            $output[] = "✓ Updated display name to 'Shores Hotel' for book_hotel@shoreshotelng.com";
        } else {
            $output[] = "✗ book_hotel account not found";
        }

        // Update shores_apartment display name only
        $apartment = App\Models\EmailAccount::where('email', 'book_apartment@shoreshotelng.com')->first();
        if ($apartment) {
            $apartment->display_name = 'Shores Apartment';
            $apartment->save();
            $output[] = "✓ Updated display name to 'Shores Apartment' for book_apartment@shoreshotelng.com";
        } else {
            $output[] = "✗ book_apartment account not found";
        }

        // Verify changes
        $output[] = "<br><strong>Current email accounts:</strong>";
        $accounts = App\Models\EmailAccount::all();
        foreach ($accounts as $acc) {
            $output[] = $acc->email . " - " . $acc->display_name;
        }

        return implode("<br>", $output);

    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-email-direct', function () {
    try {
        // Force log driver for testing
        config(['mail.default' => 'log']);

        $booking = \App\Models\Booking::latest()->first();

        if (!$booking) {
            return "No bookings found. Please create a booking first.";
        }

        $room = \App\Models\Room::find($booking->room_id);

        \Log::info("=== DIRECT EMAIL TEST START ===");

        // Determine sender email based on room type
        if ($room->room_type == 0) {
            $senderEmail = 'book_hotel@shoreshotelng.com';
            $senderName = 'Shores Hotel';
        } else {
            $senderEmail = 'book_apartment@shoreshotelng.com';
            $senderName = 'Shores Apartment';
        }

        \Log::info("Sending emails for booking ID: {$booking->id}");
        \Log::info("Customer: {$booking->customer_email}");
        \Log::info("Sender: {$senderEmail} ({$senderName})");

        // Send customer email
        \Mail::to($booking->customer_email)
            ->send(new \App\Mail\BookingRequestMail($booking, $senderEmail, $senderName, $room));

        \Log::info("Customer email sent successfully");

        // Calculate nights for report
        $nights = \Carbon\Carbon::parse($booking->check_out)->diffInDays(\Carbon\Carbon::parse($booking->check_in));

        // Send report email
        \Mail::send('emails.booking-report', [
            'booking' => $booking,
            'room' => $room,
            'senderName' => $senderName,
            'nights' => abs($nights)
        ], function ($message) use ($senderEmail, $senderName, $booking) {
            $message->to($senderEmail)
                ->from($senderEmail, $senderName)
                ->subject("New Booking Request - {$senderName} - {$booking->customer_name}")
                ->replyTo($booking->customer_email, $booking->customer_name);
        });

        \Log::info("Report email sent successfully");
        \Log::info("=== DIRECT EMAIL TEST COMPLETED ===");

        return "Email test completed for booking ID: " . $booking->id . ". Check storage/logs/laravel.log for email content.";

    } catch (\Exception $e) {
        \Log::error('Direct test error: ' . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
});

// Add to routes/web.php - FOR LOCAL DEVELOPMENT ONLY
Route::get('/preview-booking-email/{bookingId}', function($bookingId) {
    $booking = \App\Models\Booking::with('room.category')->findOrFail($bookingId);
    $room = $booking->room;

    $nights = abs(\Carbon\Carbon::parse($booking->check_out)->diffInDays(\Carbon\Carbon::parse($booking->check_in)));

    $senderName = $room->room_type == 0 ? 'Shores Hotel' : 'Shores Apartment';

    // Return the view directly to your browser
    return view('emails.booking-report', [
        'booking' => $booking,
        'room' => $room,
        'senderName' => $senderName,
        'nights' => $nights,
    ]);
})->name('preview.booking.email');

// Preview the customer confirmation email
Route::get('/preview-booking-confirmation/{bookingId}', function($bookingId) {
    $booking = \App\Models\Booking::with('room.category')->findOrFail($bookingId);
    $room = $booking->room;

    $senderEmail = $room->room_type == 0 ? 'book_hotel@shoreshotelng.com' : 'book_apartment@shoreshotelng.com';
    $senderName = $room->room_type == 0 ? 'Shores Hotel' : 'Shores Apartment';

    return view('emails.booking-request', [
        'booking' => $booking,
        'senderEmail' => $senderEmail,
        'senderName' => $senderName,
        'room' => $room,
    ]);
})->name('preview.booking.confirmation');

require __DIR__.'/auth.php';
