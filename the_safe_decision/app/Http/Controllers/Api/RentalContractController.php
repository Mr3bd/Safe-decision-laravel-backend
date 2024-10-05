<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant;
use App\Models\RentalContract;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RentalContractController extends Controller
{
    public function store(Request $request)
    {
        
        // Validate incoming request
        $validated = $request->validate([
            'nationalId' => 'required|string',
            'firstName' => 'required|string',
            'middleName' => 'nullable|string',
            'lastName' => 'required|string',
            'phoneNumber' => 'required|string',
            'whatsappNumber' => 'nullable|string',
            'cityId' => 'required|integer',
            'region' => 'required|string',
            'streetName' => 'required|string',
            'buildingNumber' => 'required|string',
            'nearestLocation' => 'required|string',
            'rentDate' => 'required|date',
            'returnDate' => 'required|date',
            'carId' => 'required|integer',
            'kmReading' => 'required|numeric',
            'driverLicenseImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'carFrontImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'carRearImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'carRightImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'carLeftImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        
        // Handle file uploads
        $fileUrls = [];
        foreach (['driverLicenseImage', 'carFrontImage', 'carRearImage', 'carRightImage', 'carLeftImage'] as $image) {
            $fileUrls[$image] = Storage::disk('spaces')->putFile('rental_contracts', $validated[$image]);
        }

        // Create a new tenant
        $tenant = Tenant::create([
            'national_id' => $validated['nationalId'],
            'first_name' => $validated['firstName'],
            'middle_name' => $validated['middleName'],
            'last_name' => $validated['lastName'],
            'phone_number' => $validated['phoneNumber'],
            'whatsapp_number' => $validated['whatsappNumber'],
            'city_id' => $validated['cityId'],
            'region' => $validated['region'],
            'street' => $validated['streetName'],
            'building_number' => $validated['buildingNumber'],
            'nearest_location' => $validated['nearestLocation'],
            'driver_license' => $fileUrls['driverLicenseImage'], // Save the driver's license URL
        ]);

        // Create a rental contract
        $contractId = Str::random(12); // Generate a unique 8-12 digit ID
        $user = Auth::user();
        $institutionId = $user->institution_id;

        RentalContract::create([
            'id' => $contractId,
            'institution_id' => $institutionId, // Assuming you're getting this from the authenticated user
            'tenant_id' => $tenant->id,
            'rent_date' => $validated['rentDate'],
            'return_date' => $validated['returnDate'],
            'car_id' => $validated['carId'],
            'status_id' => 1, // Hardcoded value
            'km_reading_before' => $validated['kmReading'],
            'front_image' => $fileUrls['carFrontImage'],
            'rear_image' => $fileUrls['carRearImage'],
            'right_side' => $fileUrls['carRightImage'],
            'left_side' => $fileUrls['carLeftImage'],
        ]);

        return response()->json(['message' => 'Rental contract created successfully.'], 200);
    }
}
