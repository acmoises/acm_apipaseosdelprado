<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BitacoraApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\DepartureApiController;
use App\Http\Controllers\Api\EntryApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\ResidentApiController;
use App\Http\Controllers\Api\RosterApiController;
use App\Http\Controllers\Api\SpentApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - ACM Paseos del Prado
|--------------------------------------------------------------------------
*/

// Public Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected API Routes
Route::middleware(['auth:sanctum', 'check.payment'])->group(function () {

    // User session
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Management & Permissions
    Route::apiResource('users', UserApiController::class);

    // Dashboard metrics
    Route::get('/dashboard/metrics', [DashboardApiController::class, 'metrics']);

    // Residents
    Route::get('/residents/card/{card_id}', [ResidentApiController::class, 'getByCardId']);
    Route::apiResource('residents', ResidentApiController::class);

    // Access Control (Entradas / Salidas)
    Route::get('/entries', [EntryApiController::class, 'index']);
    Route::post('/entries', [EntryApiController::class, 'store']);

    Route::get('/departures', [DepartureApiController::class, 'index']);
    Route::post('/departures', [DepartureApiController::class, 'store']);

    // Payments
    Route::get('/payments/services', [PaymentApiController::class, 'services']);
    Route::get('/payments/by-resident', [PaymentApiController::class, 'getPaymentsByResident']);
    Route::get('/payments/cancelled', [PaymentApiController::class, 'cancelledList']);
    Route::get('/payments/{id}/pdf', [PaymentApiController::class, 'generatePdf']);
    Route::post('/payments/cancel', [PaymentApiController::class, 'cancel']);
    Route::apiResource('payments', PaymentApiController::class);

    // Spents (Gastos)
    Route::apiResource('spents', SpentApiController::class);

    // Rosters (Nómina)
    Route::apiResource('rosters', RosterApiController::class);

    // Bitácoras
    Route::apiResource('bitacoras', BitacoraApiController::class);

    // Boletos
    Route::post('/boletos/generate', [\App\Http\Controllers\Api\BoletoApiController::class, 'generate']);

    // Pagos del Sistema (Software Renta)
    Route::get('/system-payments', [\App\Http\Controllers\Api\SystemPaymentController::class, 'index']);
    Route::post('/system-payments', [\App\Http\Controllers\Api\SystemPaymentController::class, 'store']);

});
