<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function getUsers(Request $request)
    {
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'page_size' => 'integer|min:1',
            'page_index' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Get the role IDs (in this case [1,2,3,4])
        $roleIds = [1, 2, 3, 4];

        // Get page index and size from the request (default: page 1, size 10)
        $pageSize = $request->input('page_size', 10);  // Default to 10 items per page
        $pageIndex = $request->input('page_index', 1); // Default to page 1

        // Query users where role_id is in the specified list, order by created_at descending, and paginate
        $users = User::with(['role', 'status', 'institution'])
            ->whereIn('role_id', $roleIds)
            ->orderBy('created_at', 'desc') // Order by created_at in descending order
            ->paginate($pageSize, ['*'], 'page', $pageIndex);


        // Return the paginated data as a JSON response
        return response()->json([
            'data' => $users->items(), // Get the items for the current page
            'total' => $users->total(), // Total number of records
            'current_page' => $users->currentPage(), // Current page
            'last_page' => $users->lastPage(), // Last page
            'per_page' => $users->perPage(), // Items per page
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function getUserRoles()
    {
        $roleIds = [1, 2, 3, 4];

        $roles = UserRole::whereIn('id', $roleIds)
                          ->orderBy('role_name')
                          ->get();
  
        return response()->json([
            'data' => $roles
        ], 200, [], JSON_UNESCAPED_UNICODE);

    }


     // Register new user
    public function addSystemUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|regex:/^\+962[7][0-9]{8}$/',
            'role_id' => 'required|integer|exists:user_roles,id',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'role_id'=> $request->role_id,
            'status_id'=> 1


        ]);

        return response()->json(['message' => 'User added successfully!'], 201);
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
        $user = User::find($id);

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