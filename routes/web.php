<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SertifikatTteController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/fungsionaris', 'fungsio')->name('fungsionaris');
    Route::get('/program-kerja', 'proker')->name('proker');
});

Route::controller(AspirasiController::class)->group(function () {
    Route::get('/aspirasi', 'index')->name('aspirasi.index');
    Route::post('/aspirasi', 'store')->name('aspirasi.store');
});

Route::controller(DownloadController::class)->group(function () {
    Route::get('/admin/notulensi-monev-download/{id}', 'downloadNotulensi')->name('notulensi.download');
});

Route::controller(SignatureController::class)->group(function () {
    Route::get('/signature/create', 'create')->name('signature.create');
    Route::post('/signature/create', 'store')->name('signature.store');
    Route::get('/signature/search', 'search')->name('signature.search');
    Route::post('/signature/search', 'find')->name('signature.find');
    Route::get('/signature/download', 'downloadFromPath')->name('signature.download-from-path');
    Route::get('/signature/{unique_link}', 'show')->name('signature.show');
    Route::get('/admin/tanda-tangan-elektronik/download-qr/{id}', 'downloadQRCode')->name('signature.download-qr');
    Route::get('/admin/tanda-tangan-elektronik/download-pdf/{id}', 'downloadPDF')->name('signature.download-pdf');
});

Route::controller(SertifikatTteController::class)->group(function () {
    Route::get('/sertifikat/{unique_link}', 'show')->name('sertifikat.show');
});

// LINK RAHASIA UNTUK MIGRASI DATABASE (HAPUS SETELAH SELESAI!)
Route::get('/rahasia-migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return '<h1 style="color:green; text-align:center; margin-top:50px;">✅ SUKSES! Tabel Sertifikat TTE Berhasil Dibuat di Server Hostinger!</h1><p style="text-align:center;">Silakan kembali ke panel Admin, error merahnya sudah hilang.</p>';
});