<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{

    public function getTenants(Request $request)
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

        // Subquery to get the latest tenant per national_id
        $latestTenantsSubquery = Tenant::selectRaw('MAX(id) as id')
            ->groupBy('national_id');

        // Join the subquery with the tenants table to get the details
        $tenants = Tenant::with(['city'])
            ->whereIn('id', $latestTenantsSubquery)
            ->orderBy('created_at', 'desc') // Order by created_at in descending order
            ->paginate($pageSize, ['*'], 'page', $pageIndex);


        return response()->json([
                'data' => $tenants->items(), // Get the items for the current page
                'total' => $tenants->total(), // Total number of records
                'current_page' => $tenants->currentPage(), // Current page
                'last_page' => $tenants->lastPage(), // Last page
                'per_page' => $tenants->perPage(), // Items per page
            ], 200, [], JSON_UNESCAPED_UNICODE);
    }

   public function addOrUpdateTenant(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'national_id' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'city_id' => 'required|integer|exists:cities,id',
            'region' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'building_number' => 'required|string|max:50',
            'nearest_location' => 'nullable|string|max:255',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Retrieve all validated data
        $data = $validator->validated();

        // Check if a tenant with the given national_id exists
        $tenant = Tenant::where('national_id', $data['national_id'])->first();

        if ($tenant) {
            // Tenant exists, update the existing tenant's details
            $tenant->update($data);
            $message = 'Tenant updated successfully';
        } else {
            // Tenant does not exist, create a new tenant record
            $tenant = Tenant::create($data);
            $message = 'Tenant created successfully';
        }

        // Return the response with the tenant data and a message
        return response()->json([
            'message' => $message,
            'data' => $tenant
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function updateUser(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:15',
            'isActive' => 'required|boolean',
            'name' => 'required|string|max:255',
            'role_id' => 'required|integer|exists:user_roles,id'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Find the user by ID
        $user = Tenant::find($id);

        // If user not found, return error response
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Update user with validated data
        $user->phone_number = $request->phone_number;
        $user->isActive = $request->isActive;
        $user->name = $request->name;
        $user->role_id = $request->role_id;

        // Save the updated user
        $user->save();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }
}