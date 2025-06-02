<?php

use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\LeaveTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/leave-types', [LeaveTypeController::class, 'index']);  // get all leave types
    Route::post('/leave-applications', [LeaveApplicationController::class, 'store']);   // apply for leave
    Route::get('/leave-applications', [LeaveApplicationController::class, 'index']);    // get current user leave application
    Route::put('/leave-applications/{id}/status', [LeaveApplicationController::class, 'updateStatus']); // approve/reject leave
    Route::get('/leave-balances', [LeaveBalanceController::class, 'index']);    // get leave balances

});
