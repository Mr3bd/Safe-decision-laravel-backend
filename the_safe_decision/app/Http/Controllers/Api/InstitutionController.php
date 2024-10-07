<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterInstitutionRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\PendingInstitution;
use App\Models\PendingUser;
use App\Models\InstitutionType;
use Illuminate\Support\Facades\Storage;


class InstitutionController extends Controller
{

    public function register(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'orgName' => 'required|string|min:3',
            'orgNumber' => 'required|string|digits:8|unique:institutions,institution_number',
            'number' => 'required|string|regex:/^\+962[7][0-9]{8}$/',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
            'orgType'=> 'required|exists:institution_types,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create the pending institution
        $pendingInstitution = PendingInstitution::create([
            'name' => $request->orgName,
            'institution_number' => $request->orgNumber,
            'institution_type_id'=> $request->orgType
        ]);

        // Create the pending user
        $otp = Str::random(6); // Generate a random 6-digit OTP

        $pendingUser = PendingUser::create([
            'name' => $request->name,
            'phone_number' => $request->number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'institution_id' => $pendingInstitution->id,
            'otp' => $otp,
            'role_id'=> 10,
            'status_id'=> 2
        ]);

        // Send the OTP email
        Mail::to($pendingUser->email)->send(new SendOtpMail($otp));

        return response()->json(['message' => 'Registration successful! Please check your email for the OTP.'], 200);
    }




   public function verifyOtp(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Find the latest pending user with the provided email
        $pendingUser = PendingUser::where('email', $request->email)
                                    ->orderBy('created_at', 'desc') // Order by created_at to get the latest
                                    ->first();

        // Check if the pending user exists and if the OTP matches
        if (!$pendingUser || $pendingUser->otp !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP or email'], 401);
        }

        // Create the institution
        $institution = Institution::create([
            'name' => $pendingUser->institution->name,
            'institution_number' => $pendingUser->institution->institution_number,
            'institution_type_id'=> $pendingUser->institution->institution_type_id
        ]);

        // Create the user
        $user = User::create([
            'name' => $pendingUser->name,
            'phone_number' => $pendingUser->phone_number,
            'email' => $pendingUser->email,
            'password' => $pendingUser->password,
            'institution_id' => $institution->id,
            'role_id' => $pendingUser->role_id,
            'status_id' => $pendingUser->status_id,
        ]);

        // Delete the pending records
        $pendingUser->delete();
        $pendingInstitution = PendingInstitution::find($pendingUser->institution_id);
        if ($pendingInstitution) {
            $pendingInstitution->delete();
        }

        return response()->json(['message' => 'Registration completed successfully!'], 200);
    }


    public function getInstitutionTypes()
    {
        // Fetch all institution types from the database
        $institutionTypes = InstitutionType::all();

        // Return a success response with the data
         return response()->json([
            'data' => $institutionTypes
        ], 200, [], JSON_UNESCAPED_UNICODE);

  
    }


    public function getUsers(Request $request)
    {
        // Get the role IDs (in this case [1,2,3,4])
        $roleIds = [1, 2, 3, 4];

        // Get page index and size from the request (default: page 1, size 10)
        $pageSize = $request->input('page_size', 10);  // Default to 10 items per page
        $pageIndex = $request->input('page_index', 1); // Default to page 1

        // Query users where role_id is in the specified list
        $usersQuery = User::whereIn('role_id', $roleIds);

        // Paginate the query based on the page size and page index
        $users = $usersQuery->paginate($pageSize, ['*'], 'page', $pageIndex);

        // Return the paginated data as a JSON response

        return response()->json([
            'data' => $users
        ], 200, [], JSON_UNESCAPED_UNICODE);
        
    }


    public function getInstitutions(Request $request)
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

        $institutions = Institution::with(['institutionType'])
            ->orderBy('created_at', 'desc') // Order by created_at in descending order
            ->paginate($pageSize, ['*'], 'page', $pageIndex);


        // Return the paginated data as a JSON response
        return response()->json([
            'data' => $institutions->items(), // Get the items for the current page
            'total' => $institutions->total(), // Total number of records
            'current_page' => $institutions->currentPage(), // Current page
            'last_page' => $institutions->lastPage(), // Last page
            'per_page' => $institutions->perPage(), // Items per page
        ], 200, [], JSON_UNESCAPED_UNICODE);
    
    }



   public function addInstitution(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|min:3',
            'institution_number' => 'required|string|digits:8|unique:institutions,institution_number',
            'institution_type_id' => 'required|exists:institution_types,id',
            'emergency_number' => 'required|string|regex:/^\+962[7][0-9]{8}$/',
            'address_ar' => 'required|string|max:255',
            'address_en' => 'required|string|max:255',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image1Url = null;
        if ($request->hasFile('logo_image')) {
            $folder = 'InstitutionsLogo';
            $image1Path = $request->file('logo_image')->store($folder, 'spaces');
            $image1Url = Storage::disk('spaces')->url($image1Path);
        }

        // Create the institution
        $inst = Institution::create([
            'name' => $request->name,
            'institution_number' => $request->institution_number,
            'institution_type_id' => $request->institution_type_id,
            'emergency_number' => $request->emergency_number,
            'address_ar' => $request->address_ar,
            'address_en' => $request->address_en,
            'logo_image' => $image1Url,
        ]);

        return response()->json(['message' => 'Institution added successfully!'], 201);
    }


    public function updateInstitution(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'institution_number' => 'required|string|digits:8|unique:institutions,institution_number,' . $id,
            'institution_type_id' => 'required|exists:institution_types,id',
            'emergency_number' => 'required|string|regex:/^\+962[7][0-9]{8}$/',
            'address_ar' => 'required|string|max:255',
            'address_en' => 'required|string|max:255',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'isActive' => 'required|boolean',
        ]);

        $institution = Institution::findOrFail($id);

        if ($request->hasFile('logo_image')) {
            $folder = 'InstitutionsLogo';
            $imagePath = $request->file('logo_image')->store($folder, 'spaces');
            $imageUrl = Storage::disk('spaces')->url($imagePath);
            $institution->logo_image = $imageUrl;
        }

        $institution->update($request->except(['logo_image', '_method']));
        return response()->json(['message' => 'Institution updated successfully!'], 200);
    }



}