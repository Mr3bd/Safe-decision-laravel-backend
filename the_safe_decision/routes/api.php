<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// For Sanctum
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\VehicleComparisonController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
});
Route::post('/register-institution', [InstitutionController::class, 'register'])->withoutMiddleware('auth');
Route::post('/verify-otp', [InstitutionController::class, 'verifyOtp']);
Route::get('/institution-types', [InstitutionController::class, 'getInstitutionTypes']);
Route::post('/compare', [VehicleComparisonController::class, 'compareImages']);




