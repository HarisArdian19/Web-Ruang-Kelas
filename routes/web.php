<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

// 1. Landing & Portal Chooser
Route::get('/', [ScheduleController::class, 'landing'])->name('portal.landing');

// 2. Terpisah: Portal Dosen
Route::get('/dosen', [ScheduleController::class, 'dosenPortal'])->name('portal.dosen');

// 3. Terpisah: Portal Admin Gedung
Route::get('/admin', [ScheduleController::class, 'adminPortal'])->name('portal.admin');

// 4. API Endpoints
Route::prefix('api')->group(function () {
    Route::get('/matrix', [ScheduleController::class, 'getMatrixData'])->name('api.matrix');
    Route::post('/conflict/check', [ScheduleController::class, 'checkConflict'])->name('api.conflict.check');
    Route::post('/reschedule/submit', [ScheduleController::class, 'submitReschedule'])->name('api.reschedule.submit');
    
    // Fitur Khusus Dosen: Pembatalan Kelas & Email Notifikasi H-1
    Route::post('/dosen/class/{id}/cancel', [ScheduleController::class, 'cancelClass'])->name('api.dosen.cancel');
    Route::post('/dosen/trigger-h1-reminders', [ScheduleController::class, 'triggerH1Reminders'])->name('api.dosen.reminders');
    Route::get('/dosen/emails', [ScheduleController::class, 'getEmails'])->name('api.dosen.emails');

    // Fitur Khusus Admin: Verifikasi & Kontrol Fasilitas
    Route::post('/admin/reschedule/{id}/approve', [ScheduleController::class, 'approveRequest'])->name('api.admin.approve');
    Route::post('/admin/reschedule/{id}/reject', [ScheduleController::class, 'rejectRequest'])->name('api.admin.reject');
    Route::post('/admin/reschedule/{id}/reroute', [ScheduleController::class, 'rerouteRequest'])->name('api.admin.reroute');
    Route::post('/admin/room/{id}/maintenance', [ScheduleController::class, 'toggleMaintenance'])->name('api.admin.maintenance');
});
