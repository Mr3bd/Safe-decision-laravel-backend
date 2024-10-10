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
use App\Models\TenantCarRentReview;
use App\Models\CarContractBeforeVFeature;
use App\Models\CarContractAfterVFeature;
use App\Models\VehicleFeature;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;

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
            'fuelBeforeReading' => 'nullable|numeric|in:0,0.25,0.5,0.75,1',
            'driverLicenseImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'carFrontImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'carRearImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'carRightImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'carLeftImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'selectedFeatures' => 'required|array|min:1', // Ensure it's a non-empty array
            'selectedFeatures.*' => 'integer', // Each element must be an integer
            'contractPrice' => 'required|numeric|min:0', // Validation for price

        ]);

        // Handle file uploads
        $fileUrls = [];
        $folder = 'RentalContracts';

        foreach (['driverLicenseImage', 'carFrontImage', 'carRearImage', 'carRightImage', 'carLeftImage'] as $image) {
            $imagePath = $request->file($image)->store($folder, 'spaces');
            $fileUrls[$image] = Storage::disk('spaces')->url($imagePath);
        }

        // Check if the tenant already exists based on national_id
        $tenant = Tenant::where('national_id', $validated['nationalId'])->first();

        if ($tenant) {
            // Tenant exists, update the fields
            $tenant->update([
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
                'driver_license' => $fileUrls['driverLicenseImage'],
            ]);
        } else {
            // Tenant does not exist, create a new tenant
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
                'driver_license' => $fileUrls['driverLicenseImage'],
            ]);
        }

        // Create a rental contract
        $user = Auth::user();
        $institutionId = $user->institution_id;
        $fuelBeforeReading = $request['fuelBeforeReading'] ?? 0.5; // Defaults to 0.5 if not set

        $rentalContract = RentalContract::create([
            'institution_id' => $institutionId,
            'tenant_id' => $tenant->id,
            'rent_date' => $validated['rentDate'],
            'return_date' => $validated['returnDate'],
            'car_id' => $validated['carId'],
            'status_id' => 1, // Hardcoded value for "In Progress"
            'km_reading_before' => $validated['kmReading'],
            'price' => $validated['contractPrice'],
            'fuel_before_reading' => $fuelBeforeReading,
            'front_image' => $fileUrls['carFrontImage'],
            'rear_image' => $fileUrls['carRearImage'],
            'right_side' => $fileUrls['carRightImage'],
            'left_side' => $fileUrls['carLeftImage'],
        ]);

        // Insert each feature from the selectedFeatures list into the car_contract_before_vfeatures table
        foreach ($validated['selectedFeatures'] as $featureId) {
            CarContractBeforeVFeature::create([
                'feature_id' => $featureId,
                'contract_id' => $rentalContract->id
            ]);
        }

        return response()->json(['message' => 'Rental contract created successfully.'], 200);
    }


    public function getContracts(Request $request)
    {

        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'page_size' => 'integer|min:1',
            'page_index' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Get page index and size from the request (default: page 1, size 10)
        $pageSize = $request->input('page_size', 10);  // Default to 10 items per page
        $pageIndex = $request->input('page_index', 1); // Default to page 1


        // Get the authenticated user
        $user = Auth::user();


        $contracts = RentalContract::with(['tenant.city', 'car.model', 'status'])
            ->where('institution_id', $user->institution_id)
            ->orderBy('rent_date', 'desc') // Order by created_at in descending order
            ->paginate($pageSize, ['*'], 'page', $pageIndex);

        return response()->json([
                'data' => $contracts->items(), // Get the items for the current page
                'total' => $contracts->total(), // Total number of records
                'current_page' => $contracts->currentPage(), // Current page
                'last_page' => $contracts->lastPage(), // Last page
                'per_page' => $contracts->perPage(), // Items per page
            ], 200, [], JSON_UNESCAPED_UNICODE);
        // Return the paginated contracts
    }


     public function cancelContract(Request $request, $id)
     {

        // Find the user by ID
        $rentalContract = RentalContract::find($id);

        // If user not found, return error response
        if (!$rentalContract) {
            return response()->json([
                'success' => false,
                'message' => 'Contract not found',
            ], 404);
        }

        // Update user with validated data
        $rentalContract->status_id = 3;

        // Save the updated user
        $rentalContract->save();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Contract updated successfully',
            'data' => $rentalContract,
        ], 200);
    }


    public function completeRentalContract(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required|exists:car_rent_contracts,id',
            'km_reading_after' => 'required|numeric',
            'fuel_after_reading' => 'nullable|numeric|in:0,0.25,0.5,0.75,1',
            'vehicle_features' => 'nullable|array',
            'vehicle_features.*' => 'exists:vehicle_features,id',
            'appointments' => 'required|integer|min:1|max:5',
            'accidents' => 'required|integer|min:1|max:5',
            'violations' => 'required|integer|min:1|max:5',
            'financial' => 'required|integer|min:1|max:5',
            'cleanliness' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Retrieve the contract based on ID
            $contract = RentalContract::findOrFail($request->contract_id);
            $fuelAfterReading = $request['fuel_after_reading'] ?? 0.5; // Defaults to 0.5 if not set

            // Update the contract details
            $contract->km_reading_after = $request->km_reading_after;
            $contract->fuel_after_reading = $fuelAfterReading;
            $contract->status_id = 2; // Set status to 2 to mark as completed
            $contract->save();

            // Insert vehicle features (if provided)
            if ($request->has('vehicle_features') && is_array($request->vehicle_features)) {
                foreach ($request->vehicle_features as $featureId) {
                    CarContractAfterVFeature::create([
                        'contract_id' => $contract->id,
                        'feature_id' => $featureId,
                    ]);
                }
            }

            // Add tenant reviews
            TenantCarRentReview::create([
                'contract_id' => $contract->id,
                'tenant_id' => $contract->tenant_id, // Get tenant_id from the contract model
                'appointments' => $request->appointments,
                'accidents' => $request->accidents,
                'violations' => $request->violations,
                'financial' => $request->financial,
                'cleanliness' => $request->cleanliness,
            ]);

            return response()->json(['message' => 'Rental contract completed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function extendContract(Request $request, $id,)
     {

       // Define validation rules
        $validator = Validator::make($request->all(), [
            'returnDate' => 'required|date'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Find the user by ID
        $contract = RentalContract::find($id);

        // If user not found, return error response
        if (!$contract) {
            return response()->json([
                'success' => false,
                'message' => 'Contract not found',
            ], 404);
        }

        if ($request->returnDate <= $contract->rent_date) {
            return response()->json(['message' => 'INVALID_DATE'], 400); // Return empty data
        }

        // Update user with validated data
        $contract->return_date = $request->returnDate;

        // Save the updated user
        $contract->save();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Contract updated successfully',
            'data' => $contract,
        ], 200);
    }

    public function downloadContract($contract_id)
    {
        // Retrieve the institution based on the given institution_id
         $rentalContract = RentalContract::with([
            'institution',
            'tenant.city.country'
        ])->find($contract_id);

        if (!$rentalContract) {
            return response()->json(['message' => 'Rental not found.'], 404);
        }

        $html = view('contract_template', compact('rentalContract'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'Cairo',
        ]);

        $mpdf->WriteHTML($html);

        $fileName = 'contract_' . $contract_id . '.pdf';

        return response($mpdf->Output($fileName, 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Access-Control-Allow-Origin', '*') // Allow all origins (adjust as needed)
            ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

     public function getContractById($id)
    {
        try {
            // Fetch the contract with the specified ID and include the relationships
            $contract = RentalContract::with(['tenant.city', 'car.model', 'status'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $contract
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Contract not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }



    public function getContractFeatures($id)
    {
        try {
            // Fetch the contract with the specified ID including the features before and after
            $contract = RentalContract::with(['featuresBefore', 'featuresAfter'])->findOrFail($id);

            // Fetch all vehicle features
            $allFeatures = VehicleFeature::all(); // Get all features as full records

            // Prepare response
            $response = [
                'contract_id' => $contract->id,
                'features_before' => $contract->featuresBefore->pluck('id')->toArray(),
                'features_after' => $contract->featuresAfter->pluck('id')->toArray(),
                'all_features' => $allFeatures->map(function($feature) {
                    return [
                        'id' => $feature->id,
                        'name_en' => $feature->name_en,
                        'name_ar' => $feature->name_ar,
                    ];
                }),
            ];

            return response()->json([
                'success' => true,
                'data' => $response
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Contract not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

}
