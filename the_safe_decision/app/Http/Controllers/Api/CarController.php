<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CarManufacture;
use App\Models\CarModel;
use App\Models\InstitutionCar;
use App\Models\VehicleFeature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    // Get all car manufactures (sorted alphabetically)
    public function getManufactures()
    {
        $manufactures = CarManufacture::orderBy('name_en')->get();
        return response()->json([
            'data' => $manufactures
        ], 200, [], JSON_UNESCAPED_UNICODE);

    }

    // Get car models based on the selected manufacture (sorted alphabetically)
    public function getModels($manufactureId)
    {
        $models = CarModel::where('manufacturer_id', $manufactureId)
                          ->orderBy('name_en')
                          ->get();

        return response()->json(['data' => $models], 200, [], JSON_UNESCAPED_UNICODE);
    }

    // Create an institution car
    public function createInstitutionCar(Request $request)
    {
        $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'tag_number' => 'required|string|max:255',
        ]);

        // Get the authenticated user's institution ID
        $user = Auth::user();
        $institutionId = $user->institution_id;

        $institutionCar = InstitutionCar::create([
            'institution_id' => $institutionId,
            'model_id' => $request->car_model_id,
            'tagNumber' => $request->tag_number,
        ]);

        return response()->json($institutionCar, 200);
    }

    // Get all car manufactures (sorted alphabetically)
    public function getVehicleFeatures()
    {
        $manufactures = VehicleFeature::orderBy('name_en')->get();
        return response()->json([
            'data' => $manufactures
        ], 200, [], JSON_UNESCAPED_UNICODE);

    }

    public function getInstitutionCars(Request $request)
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

        // Assuming you have the user's institution ID stored in the auth user
        $user = $request->user();
        
        // Fetch institution cars with related institution and model data, applying pagination
        $institutionCars = InstitutionCar::with(['institution', 'model.manufacture'])
            ->where('institution_id', $user->institution_id) // Filter by user's institution
            ->paginate($pageSize, ['*'], 'page', $pageIndex);

        // Return the response
        return response()->json([
            'data' => $institutionCars
        ], 200, [], JSON_UNESCAPED_UNICODE);

    }


    // Update an institution car
    public function updateInstitutionCar(Request $request, $id)
    {
        // Validate incoming request
        $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'tag_number' => 'required|string|max:255',
        ]);

        // Get the authenticated user's institution ID
        $user = Auth::user();
        $institutionId = $user->institution_id;

        // Find the existing institution car by ID
        $institutionCar = InstitutionCar::where('id', $id)
            ->where('institution_id', $institutionId) // Ensure the car belongs to the user's institution
            ->first();

        // Check if the car exists
        if (!$institutionCar) {
            return response()->json(['message' => 'Car not found or does not belong to your institution.'], 404);
        }

        // Update the car's attributes
        $institutionCar->model_id = $request->car_model_id;
        $institutionCar->tagNumber = $request->tag_number;
        $institutionCar->save(); // Save changes to the database

        return response()->json($institutionCar, 200);
    }

}
