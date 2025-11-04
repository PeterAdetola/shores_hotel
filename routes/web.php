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
















// -----------------------------
// Debugging routes
// -----------------------------

Route::get('/test-email-accounts', function() {
    $service = new \App\Services\DirectAdminEmailService();

    // Get accounts from DirectAdmin
    $daAccounts = $service->getEmailAccounts();

    // Get accounts from database
    $dbAccounts = \App\Models\EmailAccount::all();

    // Merged accounts (what controller will show)
    $controller = new \App\Http\Controllers\EmailController();
    $mergedAccounts = $controller->getEmailAccounts();

    return [
        'directadmin_accounts' => $daAccounts,
        'database_accounts' => $dbAccounts->map(function($acc) {
            return [
                'email' => $acc->email,
                'display_name' => $acc->display_name,
                'is_active' => $acc->is_active,
                'is_default' => $acc->is_default,
            ];
        }),
        'merged_accounts' => $mergedAccounts,
    ];
});

Route::get('/test-email-delivery/{bookingId?}', [App\Http\Controllers\BookingController::class, 'testEmailDelivery']);

Route::get('/test-email-delivery', function () {
    try {
        // Use log driver to see email content
        config(['mail.driver' => 'log']);

        $booking = \App\Models\Booking::latest()->first();
        if (!$booking) {
            return "No bookings found";
        }

        $room = \App\Models\Room::find($booking->room_id);

        \Log::info("=== TEST EMAIL DELIVERY ===");

        // Call the exact same method used in booking
        app(\App\Http\Controllers\BookingController::class)
            ->sendBookingRequestEmail($booking, $room);

        \Log::info("=== END TEST ===");

        return "Check storage/logs/laravel.log for email content";

    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-mailable', function () {
    try {
        $booking = \App\Models\Booking::latest()->first();
        if (!$booking) {
            return "No bookings found";
        }

        $room = \App\Models\Room::find($booking->room_id);

        \Log::info("Testing Mailable with booking ID: " . $booking->id);

        // Test the Mailable directly
        $mailable = new \App\Mail\BookingRequestMail($booking, 'shores_hotel@shoreshotelng.com', 'Shores Hotel');

        \Mail::to('peteradetola@gmail.com')->send($mailable);

        \Log::info("Mailable test completed");
        return "Mailable test sent for booking ID: " . $booking->id;

    } catch (\Exception $e) {
        \Log::error('Mailable test error: ' . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-simple-email', function () {
    try {
        \Log::info("Testing simple email");

        Mail::send([], [], function ($message) {
            $message->to('peteradetola@gmail.com')
                ->subject('Simple Test Email')
                ->text('This is a simple test email from Laravel.');
        });

        \Log::info("Simple email sent");
        return "Simple email sent - check logs";
    } catch (\Exception $e) {
        \Log::error('Simple email error: ' . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-booking-email/{bookingId}', [App\Http\Controllers\BookingController::class, 'testBookingEmail']);

Route::get('/test-directadmin', function() {
    $daUrl = config('directadmin.url');
    $daUsername = config('directadmin.username');
    $daPassword = config('directadmin.password');
    $domain = config('directadmin.domain');

    try {
        $response = \Illuminate\Support\Facades\Http::withBasicAuth($daUsername, $daPassword)
            ->withoutVerifying() // Add this if you have SSL issues
            ->timeout(30)
            ->get($daUrl . '/CMD_API_POP', [
                'action' => 'list',
                'domain' => $domain
            ]);

        if ($response->successful()) {
            $body = $response->body();
            parse_str($body, $data);

            return [
                'success' => true,
                'status' => $response->status(),
                'parsed_data' => $data,
                'raw_response' => $body,
            ];
        }

        return [
            'success' => false,
            'status' => $response->status(),
            'body' => $response->body(),
            'error' => 'Request failed'
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
})->name('test.directadmin');

// Add this to your routes/web.php
Route::get('/test-email', function () {
    try {
        \Mail::raw('Test email content', function ($message) {
            $message->to('peteradetola@gmail.com')
                ->subject('Test Email from Laravel');
        });

        return 'Test email sent! Check your inbox.';
    } catch (\Exception $e) {
        return 'Email error: ' . $e->getMessage();
    }
});

Route::get('/email/debug-folders', function() {
    try {
        $cm = new \Webklex\PHPIMAP\ClientManager();
        $client = $cm->make([
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => false,
            'username' => 'hello@shoreshotelng.com',
            'password' => 'hello@shoresEmailLogin',
            'protocol' => 'imap',
            'authentication' => null,
        ]);

        $client->connect();

        $folders = $client->getFolders();

        echo "<h2>Available Folders:</h2>";
        foreach ($folders as $folder) {
            echo "Name: " . $folder->name . "<br>";
            echo "Full Name: " . $folder->full_name . "<br>";
            echo "Path: " . $folder->path . "<br>";
            echo "Delimiter: " . $folder->delimiter . "<br>";
            echo "<hr>";
        }

    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
    }
})->name('debug.folders');

Route::get('/debug-uid-lookup/{uid}', function ($uid) {
    try {
        $cm = new \Webklex\PHPIMAP\ClientManager();
        $client = $cm->make([
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => 'hello@shoreshotelng.com',
            'password' => 'hello@shoresEmailLogin',
            'protocol' => 'imap',
        ]);

        $client->connect();
        $folder = $client->getFolder('INBOX');

        // Test all possible UID lookup methods
        $methods = [
            'query()->uid()->first()' => function() use ($folder, $uid) {
                $result = $folder->query()->uid($uid)->get();
                return $result->first();
            },

            'messages()->uid()->first()' => function() use ($folder, $uid) {
                $result = $folder->messages()->uid($uid)->get();
                return $result->first();
            },

            'query()->get() + filter by UID' => function() use ($folder, $uid) {
                $messages = $folder->query()->get();
                return $messages->where('uid', $uid)->first();
            },

            'messages()->all()->get() + filter by UID' => function() use ($folder, $uid) {
                $messages = $folder->messages()->all()->get();
                return $messages->where('uid', $uid)->first();
            },

            'messages()->all()->get() + manual filter' => function() use ($folder, $uid) {
                $messages = $folder->messages()->all()->get();
                foreach ($messages as $message) {
                    if ($message->getUid() == $uid) {
                        return $message;
                    }
                }
                return null;
            },

            'query()->where()->first()' => function() use ($folder, $uid) {
                if (method_exists($folder->query(), 'where')) {
                    return $folder->query()->where('UID', $uid)->first();
                }
                return null;
            },
        ];

        $results = [];
        foreach ($methods as $methodName => $method) {
            try {
                $message = $method();
                if ($message) {
                    $subjectAttr = $message->getSubject();
                    $results[$methodName] = [
                        'success' => true,
                        'uid' => $message->getUid(),
                        'subject' => $subjectAttr ? $subjectAttr->toString() : 'No subject',
                    ];
                } else {
                    $results[$methodName] = ['success' => false, 'message' => 'No message found'];
                }
            } catch (\Exception $e) {
                $results[$methodName] = ['success' => false, 'error' => $e->getMessage()];
            }
        }

        return response()->json($results);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/test-show-method/{uid}', function ($uid) {
    try {
        $cm = new \Webklex\PHPIMAP\ClientManager();
        $client = $cm->make([
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => 'hello@shoreshotelng.com',
            'password' => 'hello@shoresEmailLogin',
            'protocol' => 'imap',
        ]);

        $client->connect();
        $folder = $client->getFolder('INBOX');

        // Test different methods to get message by UID
        $methods = [
            'getByUid()->first()' => $folder->messages()->getByUid($uid)->first(),
            'query()->whereUid($uid)->first()' => $folder->query()->whereUid($uid)->first(),
        ];

        $results = [];
        foreach ($methods as $methodName => $message) {
            if ($message) {
                $subjectAttr = $message->getSubject();
                $results[$methodName] = [
                    'success' => true,
                    'subject' => $subjectAttr ? $subjectAttr->toString() : 'No subject',
                    'uid' => $message->getUid(),
                ];
            } else {
                $results[$methodName] = ['success' => false];
            }
        }

        return response()->json($results);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug-inbox', function () {
    try {
        $cm = new \Webklex\PHPIMAP\ClientManager();
        $client = $cm->make([
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => 'hello@shoreshotelng.com',
            'password' => 'hello@shoresEmailLogin',
            'protocol' => 'imap',
        ]);

        $client->connect();
        $folder = $client->getFolder('INBOX');

        // Test the exact same method that works in test-email-display
        $messages = $folder->messages()->all()->get();

        $debugInfo = [
            'folder_name' => $folder->name,
            'folder_path' => $folder->path,
            'messages_count_method' => $messages->count(),
            'messages_class' => get_class($messages),
        ];

        $emails = [];
        foreach ($messages as $index => $message) {
            try {
                $subjectAttr = $message->getSubject();
                $subject = $subjectAttr ? $subjectAttr->toString() : '(No Subject)';

                $from = $message->getFrom();
                $fromAddress = !empty($from) && isset($from[0]) ? $from[0]->mail : 'Unknown';
                $fromName = !empty($from) && isset($from[0]) ? ($from[0]->personal ?? $fromAddress) : 'Unknown';

                $dateAttr = $message->getDate();
                $dateString = $dateAttr ? $dateAttr->toString() : 'No date';

                $emails[] = [
                    'uid' => $message->getUid(),
                    'subject' => $subject,
                    'from_name' => $fromName,
                    'from' => $fromAddress,
                    'date' => $dateString,
                    'has_attachments' => $message->hasAttachments(),
                    'is_seen' => $message->hasFlag('\\Seen') ? 'Yes' : 'No',
                    'is_flagged' => $message->hasFlag('\\Flagged') ? 'Yes' : 'No',
                ];
            } catch (\Exception $e) {
                $emails[] = ['error' => $e->getMessage()];
            }
        }

        return response()->json([
            'debug_info' => $debugInfo,
            'emails' => $emails,
            'emails_count' => count($emails),
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/test-email-fetch', function () {
    try {
        $cm = new \Webklex\PHPIMAP\ClientManager();
        $client = $cm->make([
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => 'hello@shoreshotelng.com',
            'password' => 'hello@shoresEmailLogin',
            'protocol' => 'imap',
        ]);

        $client->connect();
        $folder = $client->getFolder('INBOX');

        // Debug folder information
        $folderInfo = [
            'name' => $folder->name,
            'path' => $folder->path,
            'full_name' => $folder->full_name,
            'message_count' => $folder->messages()->count(),
            'recent_count' => $folder->messages()->recent()->count(),
            'unread_count' => $folder->messages()->unseen()->count(),
        ];

        // Get messages with different queries
        $allMessages = $folder->messages()->all()->get();
        $recentMessages = $folder->messages()->recent()->get();
        $unseenMessages = $folder->messages()->unseen()->get();
        $limitedMessages = $folder->messages()->limit(10)->get();

        $messageDetails = [];
        foreach ($limitedMessages as $index => $message) {
            try {
                $messageDetails[] = [
                    'index' => $index,
                    'uid' => $message->getUid(),
                    'message_id' => $message->getMessageId(),
                    'subject' => $message->getSubject(),
                    'from' => $message->getFrom()[0]->mail ?? 'Unknown',
                    'from_name' => $message->getFrom()[0]->personal ?? 'Unknown',
                    'date' => $message->getDate()->format('Y-m-d H:i:s'),
                    'flags' => $message->getFlags(),
                    'has_attachments' => $message->hasAttachments(),
                    'has_text_body' => $message->hasTextBody(),
                    'has_html_body' => $message->hasHTMLBody(),
                ];
            } catch (\Exception $e) {
                $messageDetails[] = [
                    'index' => $index,
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'folder_info' => $folderInfo,
            'message_counts' => [
                'all' => $allMessages->count(),
                'recent' => $recentMessages->count(),
                'unseen' => $unseenMessages->count(),
                'limited' => $limitedMessages->count(),
            ],
            'messages' => $messageDetails,
        ]);

    } catch (\Exception $e) {
        \Log::error("Email fetch debug failed: " . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::get('/test-fixed-inbox', function () {
    try {
        $cm = new \Webklex\PHPIMAP\ClientManager();
        $client = $cm->make([
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => 'hello@shoreshotelng.com',
            'password' => 'hello@shoresEmailLogin',
            'protocol' => 'imap',
        ]);

        $client->connect();
        $folder = $client->getFolder('INBOX');
        $messages = $folder->messages()->all()->get();

        $emails = [];
        foreach ($messages as $message) {
            try {
                $subjectAttr = $message->getSubject();
                $subject = $subjectAttr ? $subjectAttr->toString() : '(No Subject)';

                $from = $message->getFrom();
                $fromAddress = !empty($from) && isset($from[0]) ? $from[0]->mail : 'Unknown';
                $fromName = !empty($from) && isset($from[0]) ? ($from[0]->personal ?? $fromAddress) : 'Unknown';

                $dateAttr = $message->getDate();
                $dateString = $dateAttr ? $dateAttr->toString() : 'No date';

                // Safe flag checking
                $isSeen = false;
                $isFlagged = false;
                try {
                    if (method_exists($message, 'getFlags')) {
                        $flags = $message->getFlags();
                        if (is_array($flags)) {
                            $isSeen = in_array('\\Seen', $flags);
                            $isFlagged = in_array('\\Flagged', $flags);
                        }
                    }
                } catch (\Exception $e) {
                    // Ignore flag errors
                }

                $emails[] = [
                    'uid' => $message->getUid(),
                    'subject' => $subject,
                    'from_name' => $fromName,
                    'from' => $fromAddress,
                    'date' => $dateString,
                    'has_attachments' => $message->hasAttachments(),
                    'is_seen' => $isSeen,
                    'is_flagged' => $isFlagged,
                    'preview' => 'Test preview',
                ];
            } catch (\Exception $e) {
                $emails[] = ['error' => $e->getMessage()];
            }
        }

        return response()->json([
            'status' => 'success',
            'emails_count' => count($emails),
            'emails' => $emails,
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/test-inbox-view', function () {
    // Simulate the data that should come from the controller
    $emails = [
        [
            'uid' => 1,
            'subject' => 'Test Email 1',
            'from_name' => 'Test Sender',
            'from' => 'test@example.com',
            'date' => '2024-01-01 10:00:00',
            'has_attachments' => false,
            'is_seen' => true,
            'is_flagged' => false,
            'preview' => 'This is a test email preview...'
        ],
        [
            'uid' => 2,
            'subject' => 'Test Email 2',
            'from_name' => 'Another Sender',
            'from' => 'another@example.com',
            'date' => '2024-01-01 11:00:00',
            'has_attachments' => true,
            'is_seen' => false,
            'is_flagged' => true,
            'preview' => 'Another test email with attachments...'
        ]
    ];

    $folder = 'INBOX';

    return view('admin.email.inbox', compact('emails', 'folder'));
});

Route::get('/test-folder-structure', function () {
    try {
        $cm = new \Webklex\PHPIMAP\ClientManager();
        $client = $cm->make([
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => 'hello@shoreshotelng.com',
            'password' => 'hello@shoresEmailLogin',
            'protocol' => 'imap',
        ]);

        $client->connect();

        $folders = $client->getFolders();
        $folderDetails = [];

        foreach ($folders as $folder) {
            try {
                $folderDetails[] = [
                    'name' => $folder->name, // Use property, not method
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'delimiter' => $folder->delimiter,
                    'message_count' => $folder->messages()->count(),
                ];
            } catch (\Exception $e) {
                $folderDetails[] = [
                    'name' => $folder->name,
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json($folderDetails);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/test-imap-folders', function () {
    try {
        $cm = new \Webklex\PHPIMAP\ClientManager();
        $client = $cm->account('default');
        $client->connect();

        $folders = $client->getFolders();

        $folderDetails = [];
        foreach ($folders as $folder) {
            $folderDetails[] = [
                'name' => $folder->name,
                'path' => $folder->path,
                'full_name' => $folder->full_name,
                'has_children' => $folder->hasChildren(),
            ];
        }

        return response()->json([
            'status' => 'success',
            'total_folders' => count($folders),
            'folders' => $folderDetails
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/test-network', function () {
    $host = 'mail.jupitercorporateservices.com';
    $port = 993;

    $connection = @fsockopen($host, $port, $errno, $errstr, 10);

    if ($connection) {
        fclose($connection);
        return response()->json([
            'status' => 'success',
            'message' => "Can connect to $host:$port"
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => "Cannot connect to $host:$port - $errstr ($errno)"
        ]);
    }
});

Route::get('/test-imap-dns', function () {
    $domain = 'shoreshotelng.com';

    // Check MX records
    $mxRecords = [];
    getmxrr($domain, $mxRecords);

    // Check common IMAP hosts
    $commonHosts = [
        'mail.' . $domain,
        'imap.' . $domain,
        'email.' . $domain,
        'webmail.' . $domain,
        'mail.jupitercorporateservices.com'
    ];

    $results = [];

    foreach ($commonHosts as $host) {
        $ip = gethostbyname($host);
        $results[$host] = $ip === $host ? 'Not found' : $ip;
    }

    return response()->json([
        'mx_records' => $mxRecords,
        'host_lookups' => $results
    ]);
});

// routes/web.php
Route::get('/test-imap', function () {
    $testConfigs = [
        [
            'name' => 'Current SSL Config',
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true
        ],
        [
            'name' => 'TLS Config',
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 993,
            'encryption' => 'tls',
            'validate_cert' => true
        ],
        [
            'name' => 'No Encryption',
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 143,
            'encryption' => false,
            'validate_cert' => false
        ],
        [
            'name' => 'Alternative Port with TLS',
            'host' => 'mail.jupitercorporateservices.com',
            'port' => 587,
            'encryption' => 'tls',
            'validate_cert' => true
        ]
    ];

    $results = [];

    foreach ($testConfigs as $config) {
        try {
            \Log::info("Testing config: " . $config['name']);

            $cm = new \Webklex\PHPIMAP\ClientManager();

            // Create a temporary config
            $tempConfig = [
                'host' => $config['host'],
                'port' => $config['port'],
                'encryption' => $config['encryption'],
                'validate_cert' => $config['validate_cert'],
                'username' => 'hello@shoreshotelng.com',
                'password' => 'hello@shoresEmailLogin',
                'protocol' => 'imap'
            ];

            $client = $cm->make($tempConfig);
            $client->connect();

            $folders = $client->getFolders();

            $results[$config['name']] = [
                'status' => 'success',
                'folders' => count($folders),
                'message' => 'Connection successful'
            ];

            \Log::info("SUCCESS: " . $config['name']);

        } catch (\Exception $e) {
            $results[$config['name']] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            \Log::error("FAILED: " . $config['name'] . " - " . $e->getMessage());
        }
    }

    return response()->json($results);
});
// routes/web.php
//Route::get('/test-email', function () {
//    $folder = 'INBOX';
//    $emails = [
//        [
//            'uid' => 1,
//            'subject' => 'TEST EMAIL - THIS SHOULD SHOW',
//            'from_name' => 'Test System',
//            'from' => 'test@system.com',
//            'date' => now(),
//            'has_attachments' => false,
//            'is_seen' => false,
//            'is_flagged' => false,
//            'preview' => 'If you can see this, the view is working!'
//        ]
//    ];

//    return view('admin.email.inbox', compact('emails', 'folder'));
//});

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
