<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// For Sanctum
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\VehicleComparisonController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\RentalContractController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\AddressController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/get-data-by-token', [AuthController::class, 'getDataByToken']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
});
Route::middleware(['auth:sanctum', 'checkrole:1'])->get('/get-users', [UserController::class, 'getUsers']);
Route::get('/get-user-roles', [UserController::class, 'getUserRoles']);
Route::middleware(['auth:sanctum', 'checkrole:1'])->post('/add-system-user', [UserController::class, 'addSystemUser']);
Route::middleware(['auth:sanctum', 'checkrole:1'])->put('/update-user/{id}', [UserController::class, 'updateUser']);

Route::post('/register-institution', [InstitutionController::class, 'register'])->withoutMiddleware('auth');
Route::post('/verify-otp', [InstitutionController::class, 'verifyOtp']);
Route::get('/institution-types', [InstitutionController::class, 'getInstitutionTypes']);

Route::middleware(['auth:sanctum', 'checkrole:1,2,3,4'])->get('/get-institutions', [InstitutionController::class, 'getInstitutions']);
Route::middleware(['auth:sanctum', 'checkrole:1,2,4'])->post('/add-institution', [InstitutionController::class, 'addInstitution']);

Route::middleware(['auth:sanctum', 'checkrole:1,2,4'])->post('/update-institution/{id}', [InstitutionController::class, 'updateInstitution']);

Route::post('/compare', [VehicleComparisonController::class, 'compareImages']);

// Route to get all car manufactures (sorted)
Route::get('/car-manufactures', [CarController::class, 'getManufactures']);

// Route to get car models based on the selected manufacture (sorted)
Route::get('/car-models/{manufactureId}', [CarController::class, 'getModels']);

// Route to create an institution car
Route::middleware('auth:sanctum')->post('/institution-cars', [CarController::class, 'createInstitutionCar']);
Route::middleware('auth:sanctum')->get('/institution-cars', [CarController::class, 'getInstitutionCars']);
Route::middleware('auth:sanctum')->post('/update-institution-cars/{id}', [CarController::class, 'updateInstitutionCar']);




Route::get('/vehicle-features', [CarController::class, 'getVehicleFeatures']);


Route::middleware('auth:sanctum')->get('/countries', [AddressController::class, 'getCountries']);
Route::middleware('auth:sanctum')->get('/cities', [AddressController::class, 'getCities']);
Route::middleware('auth:sanctum')->get('/cities-by-country/{countryId}', [AddressController::class, 'getCitiesByCountry']);


Route::middleware('auth:sanctum')->post('/create-car-rentalcontract', [RentalContractController::class, 'store']);

Route::middleware(['auth:sanctum', 'checkrole:1,2,3,4'])->get('/get-tenants', [TenantController::class, 'getTenants']);


Route::middleware(['auth:sanctum', 'checkrole:1,2,4'])->post('/add-or-update-tenant', [TenantController::class, 'addOrUpdateTenant']);
