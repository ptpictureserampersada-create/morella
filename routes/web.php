<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/destinasi', [PageController::class, 'destinations'])->name('destinations');
Route::get('/tiket', [PageController::class, 'tickets'])->name('tickets');
Route::get('/budaya', [PageController::class, 'culture'])->name('culture');
Route::get('/umkm', [PageController::class, 'umkm'])->name('umkm');
Route::get('/peta', [PageController::class, 'map'])->name('map');
Route::get('/media-sosial', [PageController::class, 'social'])->name('social');
Route::get('/agenda', [PageController::class, 'events'])->name('events');
Route::get('/berita', [PageController::class, 'news'])->name('news');
Route::get('/galeri', [PageController::class, 'gallery'])->name('gallery');
Route::get('/program', [PageController::class, 'program'])->name('program');
Route::get('/admin', [PageController::class, 'admin'])->name('admin');

// Admin auth
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');

// Tiket (AJAX)
Route::post('/tiket/pesan', [ActionController::class, 'bookTicket'])->name('tickets.book');
Route::post('/tiket/konfirmasi', [ActionController::class, 'confirmTicketPayment'])->name('tickets.confirm');
Route::post('/tiket/checkin', [ActionController::class, 'checkInTicket'])->name('tickets.checkin');

// Semua aksi admin wajib sudah login.
Route::middleware('admin.auth')->group(function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Admin CRUD
    Route::post('/admin/{entity}/buat', [ActionController::class, 'createEntity'])->name('admin.create');
    Route::post('/admin/{entity}/{id}/perbarui', [ActionController::class, 'updateEntity'])->name('admin.update');
    Route::post('/admin/{entity}/{id}/hapus', [ActionController::class, 'deleteEntity'])->name('admin.delete');
    Route::post('/admin/tiket/{id}/status', [ActionController::class, 'setTicketStatus'])->name('admin.ticketStatus');

    // Admin pengaturan
    Route::post('/admin/pengaturan/pembayaran', [ActionController::class, 'updatePaymentSettings'])->name('admin.payment');
    Route::post('/admin/pengaturan/slider', [ActionController::class, 'updateHeroSliders'])->name('admin.sliders');
    Route::post('/admin/pengaturan/kontak', [ActionController::class, 'updateContactInfo'])->name('admin.contact');
    Route::post('/admin/pengaturan/program-text', [ActionController::class, 'updateProgramText'])->name('admin.programText');
    Route::post('/admin/pengaturan/hero', [ActionController::class, 'updateHeroText'])->name('admin.heroText');
    Route::post('/admin/reset', [ActionController::class, 'reset'])->name('admin.reset');
});
