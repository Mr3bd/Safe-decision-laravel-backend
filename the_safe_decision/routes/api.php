<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// For Sanctum
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\VehicleComparisonController;
use App\Http\Controllers\Api\CarController;

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

// Route to get all car manufactures (sorted)
Route::get('/car-manufactures', [CarController::class, 'getManufactures']);

// Route to get car models based on the selected manufacture (sorted)
Route::get('/car-models/{manufactureId}', [CarController::class, 'getModels']);

// Route to create an institution car
Route::middleware('auth:sanctum')->post('/institution-cars', [CarController::class, 'createInstitutionCar']);

