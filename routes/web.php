<?php

use App\Http\Controllers\Admin\ControlPanelController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CacheClearController;
use App\Http\Controllers\CitibarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\GetLodgedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// ============================================================
// PUBLIC ROUTES
// ============================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/aboutUs', [HomeController::class, 'about'])->name('aboutUs');
Route::get('/citibar', [CitibarController::class, 'index'])->name('citibar');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/getlodged', [GetLodgedController::class, 'index'])->name('getlodged');
Route::get('/getlodged/filter', [GetLodgedController::class, 'filter'])->name('getlodged.filter');
Route::get('/chosen_lodge/{categorySlug}/{roomId}', [GetLodgedController::class, 'showRoom'])
    ->name('chosen_lodge')
    ->where(['roomId' => '[0-9]+']);


// ============================================================
// PUBLIC VIEW ROUTES
// ============================================================

Route::get('/getLodged', fn() => view('getLodged'))->name('getLodged');
Route::get('/getRooms', fn() => view('getRooms'))->name('getRooms');
Route::get('/getApartments', fn() => view('getApartments'))->name('getApartments');
Route::get('/confirmReservation', fn() => view('confirmReservation'))->name('confirmReservation');
Route::get('/bookedSuccessfully', fn() => view('bookedSuccessfully'))->name('bookedSuccessfully');


// ============================================================
// BOOKING ROUTES (public-facing)
// ============================================================

Route::post('/make_booking', [BookingController::class, 'storeRoomDetails'])->name('make.booking');
Route::post('/store_booking', [BookingController::class, 'storeBooking'])->name('store.booking');
Route::get('/all_bookings', [BookingController::class, 'getAllBookings'])->name('get.all_bookings');
Route::get('/processed_bookings', [BookingController::class, 'getProcessedBookings'])->name('get.processed_bookings');
Route::get('/unprocessed_bookings', [BookingController::class, 'getUnprocessedBookings'])->name('get.unprocessed_bookings');


// ============================================================
// ROOM CATEGORY ROUTES
// ============================================================

Route::get('/room-categories/create', [RoomCategoryController::class, 'create'])->name('room-categories.create');
Route::post('/room-categories', [RoomCategoryController::class, 'store'])->name('room-categories.store');
Route::put('/room-categories/{id}', [RoomCategoryController::class, 'update'])->name('room-categories.update');
Route::delete('/room-categories/{id}', [RoomCategoryController::class, 'destroy'])->name('room-categories.destroy');


// ============================================================
// FACILITY ROUTES
// ============================================================

Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
Route::put('/facilities/{id}', [FacilityController::class, 'update'])->name('facilities.update');
Route::delete('/facilities/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
Route::post('/facilities/reorder', [FacilityController::class, 'reorder'])->name('facilities.reorder');


// ============================================================
// LOG VIEWER ROUTES
// ============================================================

Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
Route::get('/logs/view/{filename?}', [LogController::class, 'view'])->name('logs.view');
Route::get('/logs/show/{filename?}', [LogController::class, 'show'])->name('logs.show');
Route::get('/logs/tail/{lines}/{filename?}', [LogController::class, 'tail'])->name('logs.tail');
Route::get('/logs/tail/{filename?}', [LogController::class, 'tail'])->name('logs.tail.default');
Route::delete('/logs/clear/{filename?}', [LogController::class, 'clear'])->name('logs.clear');
Route::get('/logs/download/{filename?}', [LogController::class, 'download'])->name('logs.download');

Route::get('/laravel-log', function (Request $request) {
    $token        = $request->get('token');
    $allowedToken = env('LOG_VIEWER_TOKEN', 'your-secret-log-token');
    if (app()->isLocal() || $token === $allowedToken) {
        return redirect()->route('logs.view', ['filename' => 'laravel.log', 'token' => $token]);
    }
    abort(403, 'Unauthorized');
});


// ============================================================
// CACHE CLEAR ROUTES
// ============================================================

Route::get('/clear-cache', [CacheClearController::class, 'clearAll'])->middleware('throttle:3,1');
Route::get('/admin/clear-cache/{token}', [CacheClearController::class, 'clearAll']);


// ============================================================
// AUTH PROFILE ROUTES
// ============================================================

Route::middleware('auth')->get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::middleware('auth')->patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::middleware('auth')->delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


// ============================================================
// DASHBOARD
// ============================================================

Route::get('/dashboard', fn() => view('admin.dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// ============================================================
// ADMIN - CONTROL PANEL
// ============================================================

Route::get('/admin/control-panel/log-files', [ControlPanelController::class, 'logFiles'])->name('admin.control-panel.log-files');
Route::get('/admin/control-panel/download-log', [ControlPanelController::class, 'downloadLog'])->name('admin.control-panel.download-log');
Route::get('/admin/control-panel/logs', [ControlPanelController::class, 'logs'])->name('admin.control-panel.logs');
Route::post('/admin/control-panel/execute', [ControlPanelController::class, 'execute'])->name('admin.control-panel.execute');
Route::post('/admin/control-panel/clear-log', [ControlPanelController::class, 'clearLog'])->name('admin.control-panel.clear-log');
Route::get('/admin/control-panel', [ControlPanelController::class, 'index'])->name('admin.control-panel');


// ============================================================
// ADMIN - ANNOUNCEMENTS
// ============================================================

// Static paths FIRST — before {id} wildcard routes
Route::middleware(['auth'])->get('/admin/announcements', [AnnouncementController::class, 'index'])->name('announcements');
Route::middleware(['auth'])->get('/admin/announcements/create', [AnnouncementController::class, 'create'])->name('announcement.create');
Route::middleware(['auth'])->post('/admin/announcements/store', [AnnouncementController::class, 'store'])->name('announcement.store');
Route::middleware(['auth'])->post('/admin/announcements/unpublish-all', [AnnouncementController::class, 'unpublishAll'])->name('announcements.unpublishAll');
Route::middleware(['auth'])->post('/admin/announcements/update-order', [AnnouncementController::class, 'updateOrder'])->name('announcements.updateOrder');

// Wildcard {id} routes LAST
Route::middleware(['auth'])->get('/admin/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('announcement.edit');
Route::middleware(['auth'])->put('/admin/announcements/{id}/update', [AnnouncementController::class, 'update'])->name('announcement.update');
Route::middleware(['auth'])->post('/admin/announcements/{id}/toggle-publish', [AnnouncementController::class, 'togglePublish'])->name('announcements.toggle-publish');
Route::middleware(['auth'])->delete('/admin/announcements/{id}/delete', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');


// ============================================================
// ADMIN - DISCOUNTS
// ============================================================

// Specific paths FIRST — before the general /discount index
Route::middleware(['auth'])->post('/admin/discount/apply-rooms', [DiscountController::class, 'applyToRooms'])->name('admin.discount.apply-rooms');
Route::middleware(['auth'])->post('/admin/discount/apply-apartments', [DiscountController::class, 'applyToApartments'])->name('admin.discount.apply-apartments');
Route::middleware(['auth'])->post('/admin/discount/apply-all', [DiscountController::class, 'applyToAll'])->name('admin.discount.apply-all');
Route::middleware(['auth'])->get('/admin/discount/remove-rooms', [DiscountController::class, 'removeFromRooms'])->name('admin.discount.remove-rooms');
Route::middleware(['auth'])->get('/admin/discount/remove-apartments', [DiscountController::class, 'removeFromApartments'])->name('admin.discount.remove-apartments');
Route::middleware(['auth'])->get('/admin/discount/remove-all', [DiscountController::class, 'removeFromAll'])->name('admin.discount.remove-all');
Route::middleware(['auth'])->get('/admin/discount', [DiscountController::class, 'index'])->name('admin.discount');


// ============================================================
// ADMIN - BOOKINGS
// ============================================================

Route::middleware(['auth'])->post('/admin/bookings/{id}/send-email', [BookingController::class, 'sendEmail'])->name('admin.bookings.send-email');
Route::middleware(['auth'])->post('/admin/bookings/{id}/send-whatsapp', [BookingController::class, 'sendWhatsApp'])->name('admin.bookings.send-whatsapp');
Route::middleware(['auth'])->post('/admin/bookings/{id}/send-confirmation', [BookingController::class, 'sendConfirmation'])->name('admin.bookings.send-confirmation');
Route::middleware(['auth'])->post('/admin/bookings/{id}/send-reminder', [BookingController::class, 'sendReminder'])->name('admin.bookings.send-reminder');


// ============================================================
// ADMIN - EMAIL
// ============================================================

// Static paths FIRST — before {uid} wildcard routes
Route::middleware(['auth'])->get('/admin/email/inbox/{folder?}', [EmailController::class, 'inbox'])->name('admin.email.inbox');
Route::middleware(['auth'])->match(['get', 'post'], '/admin/email/compose', [EmailController::class, 'compose'])->name('admin.email.compose');
Route::middleware(['auth'])->post('/admin/email/save-draft', [EmailController::class, 'saveDraft'])->name('admin.email.save-draft');
Route::middleware(['auth'])->get('/admin/email/draft/{uid}', [EmailController::class, 'loadDraft'])->name('admin.email.load-draft');
Route::middleware(['auth'])->delete('/admin/email/draft/{uid}', [EmailController::class, 'deleteDraft'])->name('admin.email.delete-draft');
Route::middleware(['auth'])->post('/admin/email/switch-account', [EmailController::class, 'switchAccount'])->name('admin.email.switch-account');
Route::middleware(['auth'])->get('/admin/email/test-directadmin', [EmailController::class, 'testDirectAdminConnection'])->name('admin.email.test-directadmin');
Route::middleware(['auth'])->post('/admin/email/clear-cache', [EmailController::class, 'clearEmailCache'])->name('admin.email.clear-cache');
Route::middleware(['auth'])->get('/admin/email/account/{email}', [EmailController::class, 'switchAndView'])->name('admin.email.switch-and-view');

// Wildcard {uid} routes LAST
Route::middleware(['auth'])->get('/admin/email/{uid}', [EmailController::class, 'show'])->name('admin.email.show');
Route::middleware(['auth'])->delete('/admin/email/{uid}', [EmailController::class, 'delete'])->name('admin.email.delete');
Route::middleware(['auth'])->get('/admin/email/{uid}/attachment/{attachmentId}', [EmailController::class, 'downloadAttachment'])->name('admin.email.attachment');
Route::middleware(['auth'])->post('/admin/email/{uid}/reply', [EmailController::class, 'reply'])->name('admin.email.reply');
Route::middleware(['auth'])->post('/admin/email/{uid}/forward', [EmailController::class, 'forward'])->name('admin.email.forward');
Route::middleware(['auth'])->post('/admin/email/{uid}/toggle-flag', [EmailController::class, 'toggleFlag'])->name('admin.email.toggle-flag');
Route::middleware(['auth'])->post('/admin/email/{uid}/mark-spam', [EmailController::class, 'markAsSpam'])->name('admin.email.mark-spam');
Route::middleware(['auth'])->post('/admin/email/{uid}/not-spam', [EmailController::class, 'notSpam'])->name('admin.email.not-spam');


// ============================================================
// ADMIN - CLEAR CACHE (auth-protected)
// ============================================================

Route::middleware(['auth'])->get('/admin/clear-cache', [CacheClearController::class, 'clearAll'])->name('admin.clear-cache');


// ============================================================
// ADMIN VIEW ROUTES
// ============================================================

Route::middleware(['auth'])->get('/test-page', fn() => view('/admin/test-page'))->name('test-page');
Route::middleware(['auth'])->get('/add_room', fn() => view('/admin/room/add_room'))->name('add_room');
Route::middleware(['auth'])->get('/room_config', fn() => view('/admin/room/room_config'))->name('room_config');


// ============================================================
// ROOM MANAGEMENT ROUTES
// ============================================================

// Static /rooms paths FIRST — before {room} wildcard routes
Route::middleware(['auth'])->get('/rooms', [RoomController::class, 'index'])->name('room_management');
Route::middleware(['auth'])->post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
Route::middleware(['auth'])->post('/rooms/reorder', [RoomController::class, 'reorder'])->name('rooms.reorder');
Route::middleware(['auth'])->post('/rooms/update-availability', [RoomController::class, 'updateAvailability'])->name('rooms.updateAvailability');
Route::middleware(['auth'])->delete('/rooms/images/{image}', [RoomController::class, 'deleteImage'])->name('rooms.delete_image');

// Singular /room — delete
Route::middleware(['auth'])->delete('/room/{room}', [RoomController::class, 'destroy'])->name('room.destroy');

// Wildcard {room} routes LAST
Route::middleware(['auth'])->get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('edit_room');
Route::middleware(['auth'])->get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
Route::middleware(['auth'])->put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
Route::middleware(['auth'])->post('/rooms/{room}/update-price', [RoomController::class, 'updatePrice'])->name('rooms.updatePrice');
Route::middleware(['auth'])->post('/rooms/{room}/toggle-availability', [RoomController::class, 'toggleAvailability'])->name('rooms.toggleAvailability');
Route::middleware(['auth'])->put('/rooms/{room}/info', [RoomController::class, 'updateInfo'])->name('rooms.update.info');
Route::middleware(['auth'])->put('/rooms/{room}/facilities', [RoomController::class, 'updateFacilities'])->name('rooms.update.facilities');
Route::middleware(['auth'])->post('/rooms/{room}/images/reorder', [RoomController::class, 'reorderImages'])->name('rooms.images.reorder');
Route::middleware(['auth'])->post('/rooms/{room}/update-gallery', [RoomController::class, 'updateGallery'])->name('rooms.update_gallery');

// Gallery management (nested under {room}) — static paths first
Route::middleware(['auth'])->get('/rooms/{room}/manage-gallery', [RoomController::class, 'manageGallery'])->name('rooms.manage_gallery');
Route::middleware(['auth'])->get('/rooms/{room}/gallery', [RoomController::class, 'editGallery'])->name('rooms.gallery.edit');
Route::middleware(['auth'])->post('/rooms/{room}/gallery', [RoomController::class, 'storeGallery'])->name('rooms.gallery.store');
Route::middleware(['auth'])->post('/rooms/{room}/gallery/add', [RoomController::class, 'addImages'])->name('room.gallery.add');
Route::middleware(['auth'])->post('/rooms/{room}/gallery/add-image', [RoomController::class, 'addImage'])->name('room.gallery.add_image');
Route::middleware(['auth'])->patch('/rooms/{room}/gallery/{roomImage}/update', [RoomController::class, 'updateGalleryImage'])->name('room.gallery.update');
Route::middleware(['auth'])->delete('/rooms/{room}/gallery/{roomImage}', [RoomController::class, 'deleteImage'])->name('room.gallery.delete');


// ============================================================
// DEV / DEBUG ROUTES — remove or guard before going to production
// ============================================================

Route::get('/db-test', function () {
    $user = \App\Models\User::first();
    return $user ? $user->email : 'No users';
});

Route::get('/debug-admin-prefix', function () {
    return "Admin prefix group is reachable!";
})->prefix('admin')->name('debug.admin.test');

Route::get('/preview-booking-email/{bookingId}', function ($bookingId) {
    $booking    = \App\Models\Booking::with('room.category')->findOrFail($bookingId);
    $room       = $booking->room;
    $nights     = abs(\Carbon\Carbon::parse($booking->check_out)->diffInDays(\Carbon\Carbon::parse($booking->check_in)));
    $senderName = $room->room_type == 0 ? 'Shores Hotel' : 'Shores Apartment';
    return view('emails.booking-report', compact('booking', 'room', 'senderName', 'nights'));
})->name('preview.booking.email');

Route::get('/preview-booking-confirmation/{bookingId}', function ($bookingId) {
    $booking     = \App\Models\Booking::with('room.category')->findOrFail($bookingId);
    $room        = $booking->room;
    $senderEmail = $room->room_type == 0 ? 'book_hotel@shoreshotelng.com' : 'book_apartment@shoreshotelng.com';
    $senderName  = $room->room_type == 0 ? 'Shores Hotel' : 'Shores Apartment';
    return view('emails.booking-request', compact('booking', 'senderEmail', 'senderName', 'room'));
})->name('preview.booking.confirmation');

Route::get('/test-email-fetch', function () {
    try {
        $service = new \App\Services\ImapEmailService();
        echo '<h2>Testing Email Fetch</h2>';
        echo '<h3>1. Testing Connection...</h3>';
        $result = $service->testConnection('default');
        echo '<pre>' . print_r($result, true) . '</pre>';
        echo '<h3>2. Testing Fetch Emails...</h3>';
        $emails = $service->fetchEmails('default', 'INBOX', 5);
        echo 'Found ' . $emails->count() . ' emails<br>';
        foreach ($emails as $email) {
            echo '- ' . $email->getSubject() . '<br>';
        }
        echo '<h3>✓ Test Complete</h3>';
    } catch (\Exception $e) {
        echo "<h3 style='color:red;'>✗ Error:</h3>";
        echo '<pre>' . $e->getMessage() . '</pre>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    }
});


// ============================================================
// AUTH ROUTES
// ============================================================

require __DIR__ . '/auth.php';
