<?php

namespace App\Http\Controllers\Api;

use App\Models\Child;
use App\Models\Group;
use App\Models\Driver;
use App\Models\Father;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\SubscriptionInvoice;

class AuthController extends Controller
{

    public function registerFather(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'Latitude' => 'nullable',
            'Longitude' => 'nullable',
            'email' => 'nullable|string|email|max:255|unique:fathers,email',
            'password' => 'required|string|min:6|confirmed',
            // 'children' => 'required|array',
            // 'children.*' => 'exists:children,id'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // HTTP status code 422 for validation errors
        }

        // Validation passed, proceed with creating father and handling other logic

        try {

            $father = Father::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'state' => $request->state ?? 'Unknown',
                'city' => $request->city ?? 'Unknown',
                'Latitude' => $request->Latitude,
                'Longitude' => $request->Longitude,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Attach children to father
            // $father->children()->sync($request->children);

            // Generate JWT token
            $token = JWTAuth::fromUser($father);

            return response()->json([
                'message' => 'Successfully registered',
                'token' => $token,
            ], 201); // HTTP status code 201 for resource creation success
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to register father'], 500); // HTTP status code 500 for server error
        }
    }

    public function registerFatherChildren(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'father_id' => 'required|exists:fathers,id',
            'children' => 'required|array',
            'children.*.name' => 'required|string',
            'children.*.age' => 'required|integer|min:0',
            'children.*.phone' => 'required|string',
            'children.*.address' => 'required|string',
            'children.*.Latitude' => 'required|string',
            'children.*.Longitude' => 'required|string',
            'children.*.school_id' => 'required|exists:schools,id',
            'children.*.school_class_id' => 'required|exists:school_classes,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // HTTP status code 422 for validation errors
        }
        // validated data
        $validatedData = $validator->validated();

        try {

            // Fetch the father and verify existence
            $father = Father::findOrFail($validatedData['father_id']);

            // Prepare an array to keep track of child IDs for the relationship
            $childrenIds = [];

            foreach ($validatedData['children'] as $childData) {
                // Check if the child already exists or create a new one
                $child = Child::firstOrCreate(
                    [
                        'name' => $childData['name'],
                        'photo' => 'avatar.png',
                        'status' => 0,
                        'age' => $childData['age'],
                        'phone' => $childData['phone'],
                        'address' => $childData['address'],
                        'Latitude' => $childData['Latitude'],
                        'Longitude' => $childData['Longitude'],
                        'school_id' => $childData['school_id'],
                        'school_class_id' => $childData['school_class_id'],
                    ]
                );

                // Attach the child to the father with the school and class context
                $father->children()->syncWithoutDetaching([$child->id]);

                $childrenIds[] = $child->id; // Track the child's ID
            }

            // Load the father's children for the response
            $father->load('children');

            return response()->json([
                'message' => 'Children successfully registered to the father with school and class.',
                'father' => $father,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to register children'], 500); // HTTP status code 500 for server error
        }
    }

    // Driver Registration
    public function registerDriver(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:drivers',
            'license' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'required|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Latitude' => 'nullable',
            'Longitude' => 'nullable',

        ]);

        // Store the photo

        if ($request->file('photo')) {

            $file = $request->file('photo');
            $filename = $request->license . '.' . $file->getClientOriginalExtension();
            $file->move(public_path("/assets/files/drivers/"), $filename);
            $photoPath = $filename;

        }

        $driver = Driver::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'license' => $request->license,
            'address' => $request->address,
            'Latitude' => $request->Latitude,
            'Longitude' => $request->Longitude,
            'status' => $request->status,
            'photo' => $photoPath,
            'password' => Hash::make($request->password),
        ]);

        // Generate JWT token
        $token = JWTAuth::fromUser($driver);

        return response()->json([
            'message' => 'Successfully registered',
            'token' => $token
        ], 201);

    }

    // Father Login
    public function fatherLogin(Request $request)
    {
        // dd($request->all());
        try {

            // Validate incoming request
            $credentials = $request->only('email', 'password');

            // Attempt to log the user in
            if (!$token = Auth::guard('father')->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 400);
            }

            // Retrieve the authenticated father and their children
            $father = Father::with('children')->find(auth('father')->id());
            $children = $father->children;

            // Fetch the groups that contain these children
            $groups = Group::whereHas('children', function ($query) use ($children) {
                // Use 'children.id' to remove ambiguity
                $query->whereIn('children.id', $children->pluck('id'));
            })->with('school', 'driver', 'schoolClass')->get();

            // Prepare child data with associated groups
            $childData = $children->map(function ($child) use ($groups) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'groups' => $groups->filter(function ($group) use ($child) {
                        return $group->children->contains('id', $child->id);
                    })->map(function ($group) {
                        return [
                            'id' => $group->id,
                            'name' => $group->name,
                        ];
                    }),
                ];
            })->toArray();

            // Return the JSON response with the token and father's details
            return response()->json([
                'token' => $token,
                'father' => [
                    'id' => $father->id,
                    'name' => $father->name,
                    'children' => $childData,
                ],
            ]);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }


    // public function fatherLogin(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     try {
    //         if (!$token = Auth::guard('father')->attempt($credentials)) {
    //             return response()->json(['error' => 'Invalid credentials'], 400);
    //         }
    //     } catch (JWTException $e) {
    //         return response()->json(['error' => 'Could not create token'], 500);
    //     }

    //     return response()->json(compact('token'));
    // }

    // Driver Login
    public function driverLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = Auth::guard('driver')->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json(compact('token'));
    }

    // Get Authenticated Father
    public function meFather()
    {
        $father = auth('father')->user();

        // Check if the father is authenticated
        if (!$father) {
            return response()->json(['message' => 'Unauthorized: Father not authenticated.'], 401);
        }

        // Load the father's children and their associated groups
        $children = $father->children;

        // Fetch the groups that contain these children
        $groups = Group::whereHas('children', function ($query) use ($children) {
            // Use 'children.id' to remove ambiguity
            $query->whereIn('children.id', $children->pluck('id'));
        })->with('school', 'driver', 'schoolClass')->get();

        // Fetch the invoices associated with these children
        $invoices = SubscriptionInvoice::whereIn('child_id', $children->pluck('id'))->get();

        // Prepare the response data
        $childData = $children->map(function ($child) use ($groups, $invoices) {
            // Get the invoices related to the current child
            $childInvoices = $invoices->filter(function ($invoice) use ($child) {
                return $invoice->child_id == $child->id;
            })->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'amount' => $invoice->amount,
                    'due_date' => $invoice->due_date,
                    'comment' => $invoice->plan->comment,
                    'status' => $invoice->status ? 'paid' : 'unpaid' ,
                ];
            });

            return [
                'id' => $child->id,
                'name' => $child->name,
                'age' => $child->age,
                'phone' => $child->phone,
                'address' => $child->address,
                'latitude' => $child->Latitude,
                'longitude' => $child->Longitude,
                'school_id' => $child->school_id,
                'school_class_id' => $child->school_class_id,
                'photo' => $child->photo, // Include the child's photo path
                'groups' => $groups->filter(function ($group) use ($child) {
                    return $group->children->contains('id', $child->id);
                })->map(function ($group) {
                    return [
                        'id' => $group->id,
                        'name' => $group->name,
                    ];
                }),
                'invoices' => $childInvoices, // Add invoices for the child
            ];
        })->toArray();

        // Return the JSON response with father's details, children with groups and invoices
        return response()->json([
            'id' => $father->id,
            'name' => $father->name,
            'phone' => $father->phone,
            'status' => $father->status,
            'latitude' => $father->Latitude,
            'longitude' => $father->Longitude,
            'children' => $childData,
        ]);
    }



    // Get Authenticated Driver
    public function meDriver()
    {
        return response()->json(auth('driver')->user());
    }

    // Father Logout
    public function logoutFather()
    {
        auth('father')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    // Driver Logout
    public function logoutDriver()
    {
        auth('driver')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
