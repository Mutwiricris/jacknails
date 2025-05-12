<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route; // Duplicate import

// Public routes
Route::get('/', function () {
    return view('welcome');
}); // Note: This route is defined twice in the original code

// Booking system routes
Route::get('/book-an-appointment', [BookingController::class, 'book'])->name('booking.page');
Route::get('/selectDateTime', [BookingController::class, 'DateTime'])->name('booking.DateTime');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking-confirmation', [BookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/get-available-time-slots', [BookingController::class, 'getAvailableTimeSlots']);// Authentication routes
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Profile management routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include Laravel's default authentication routes
require __DIR__.'/auth.php';

// Admin routes (protected by auth middleware)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    
    // Bookings management
    Route::get('/admin/bookings', [DashboardController::class, 'bookings'])->name('admin.bookings');
    Route::get('/admin/bookings/{id}', [DashboardController::class, 'showBooking'])->name('admin.booking.show');
    Route::post('/admin/bookings/{id}/complete', [DashboardController::class, 'completeBooking'])->name('admin.booking.complete');

    Route::get('/admin/timeslots', [DashboardController::class, 'timeSlots'])->name('admin.timeslots');
    Route::post('/admin/timeslots', [DashboardController::class, 'storeTimeSlot'])->name('admin.timeslots.store');
    Route::delete('/admin/timeslots/{id}', [DashboardController::class, 'deleteTimeSlot'])->name('admin.timeslots.delete');
    // Route::post('/admin/timeslots/{id}/reactivate', [DashboardController::class, 'reactivateTimeSlot'])->name('admin.timeslot.reactivate'); // If implementing reactivate
	Route::get('/admin/bookings/calendar-events', [DashboardController::class, 'getCalendarEvents'])->name('admin.bookings.calendar-events');
    Route::get('/admin/reports', [DashboardController::class, 'reports'])->name('admin.reports');
});

// Redirect /admin to admin dashboard if authenticated
Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
})->middleware('auth');