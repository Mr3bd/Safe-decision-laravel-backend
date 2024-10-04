<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CarManufacture;
use App\Models\CarModel;
use App\Models\InstitutionCar;
use Illuminate\Support\Facades\Auth;

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
}
