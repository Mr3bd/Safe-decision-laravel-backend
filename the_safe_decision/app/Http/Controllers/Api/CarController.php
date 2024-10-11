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
            'manu_year' => [
                'required',
                'integer',
                'min:2007', // The first car was invented around 1886
                'max:' . date('Y') // Maximum is the current year
            ],
        ]);

        // Get the authenticated user's institution ID
        $user = Auth::user();
        $institutionId = $user->institution_id;

        $institutionCar = InstitutionCar::create([
            'institution_id' => $institutionId,
            'model_id' => $request->car_model_id,
            'tagNumber' => $request->tag_number,
            'manu_year' => $request->manu_year,
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
            'manu_year' => [
                'required',
                'integer',
                'min:2007', // The first car was invented around 1886
                'max:' . date('Y') // Maximum is the current year
            ],
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
        $institutionCar->manu_year = $request->manu_year;
        $institutionCar->save(); // Save changes to the database

        return response()->json($institutionCar, 200);
    }


   public function getAvailableInstitutionCars(Request $request)
    {
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'rent_date' => 'required|date_format:Y-m-d H:i:s',
            'return_date' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Get rent_date and return_date from the request
        $rentDate = $request->input('rent_date');
        $returnDate = $request->input('return_date');

        if ($returnDate <= $rentDate) {
            return response()->json(['data' => []], 200); // Return empty data
        }

        // Assuming you have the user's institution ID stored in the authenticated user
        $user = $request->user();
        $institutionId = $user->institution_id;

        // Fetch institution cars with related institution and model data
        $institutionCars = InstitutionCar::with(['institution', 'model.manufacture'])
            ->where('institution_id', $user->institution_id) // Filter by user's institution
            ->whereDoesntHave('rentalContracts', function ($query) use ($rentDate, $returnDate) {
                $query->where(function ($subQuery) use ($rentDate, $returnDate) {
                    // Check for overlap conditions
                    $subQuery->where('rent_date', '<=', $returnDate) // Existing rent starts before requested return
                            ->where('return_date', '>=', $rentDate); // Existing return ends after requested rent
                })
                ->where('status_id', 1); // Exclude rental contracts with status_id equal to 1 (In Progress)
            })
            ->get(); // Retrieve all cars that are not rented within the given dates and meet the status condition

        // Return the response
        return response()->json([
            'data' => $institutionCars
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }



}
