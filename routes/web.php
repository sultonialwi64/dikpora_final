<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Redirect after login (dashboard for authenticated users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes for users and admins
Route::middleware(['auth', 'isAdmin'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin-specific booking routes
    Route::get('/admin/bookings', [BookingController::class, 'index'])->name('admin.bookings.index'); // View all bookings
    Route::post('/admin/bookings/{id}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve'); // Approve booking
    Route::post('/admin/bookings/{id}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject'); // Reject booking
    Route::post('/addUsers', [RegisteredUserController::class,'saveUser'])->name('addUsers');

    Route::get('/admin-jadwal', [BookingController::class, 'jadwalAdmin'])->name('jadwal.admin');
    Route::get('/admin-notifikasi', [BookingController::class, 'getNotifAdmin'])->name('notifikasi.admin');
    Route::post('/update-booking-status/{id}', [BookingController::class, 'updateStatus']);
    Route::get('/master-ruang', [RoomController::class, 'index'])->name('admin.master'); // View rooms
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Menyimpan user baru
    Route::get('/history', [BookingController::class, 'getHistory'])->name('history');
    Route::get('/admin-kalender', [CalendarController::class, 'admin'])->name('calendar.admin');
    Route::get('/api/admin-get-agenda', [CalendarController::class, 'getAgendaAdmin'])->name('calendar.getAgendaAdmin');
    Route::get('/api/admin-get-all-agenda', [CalendarController::class, 'getAllAgendaAdmin'])->name('calendar.getAllAgendaAdmin');
    Route::get('/download-history', [BookingController::class, 'downloadHistory'])->name('download.history');
});

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // User-specific booking routes
    Route::post('/check-availability', [RoomController::class, 'checkAvailability'])->name('checkAvailability');
    Route::post('/book', [BookingController::class, 'store'])->name('booking.store'); // Submit booking request
    Route::get('/jadwal', [BookingController::class, 'jadwal'])->name('jadwal');
    Route::get('/pembatalan', [BookingController::class, 'pembatalan'])->name('pembatalan');
    Route::delete('/bookings/{id}/pembatalan', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/pengajuan', [BookingController::class, 'getPengajuan'])->name('pengajuan');
    Route::get('/notifikasi', [BookingController::class, 'getNotif'])->name('notifikasi');
    Route::get('/kalender', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/api/get-agenda', [CalendarController::class, 'getAgenda'])->name('calendar.getAgenda');
    Route::get('/api/get-all-agenda', [CalendarController::class, 'getAllAgenda'])->name('calendar.getAllAgenda');
});

require __DIR__.'/auth.php';
