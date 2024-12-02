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
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Notification;
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // HTTP status code 422 for validation errors
        }

        // Validation passed, proceed with creating father and handling other logic

        try {

            // if ($request->hasFile('photo')) {
            //     $file = $request->file('photo');
            //     $filename = time() . '.' . $file->getClientOriginalExtension();
            //     $file->move(public_path("/assets/files/fathers/"), $filename);
            //     $photoPath = $filename;
            // }

            $father = Father::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'state' => $request->state ?? 'Unknown',
                'city' => $request->city ?? 'Unknown',
                'Latitude' => $request->Latitude ?? null,
                'Longitude' => $request->Longitude ?? null,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'photo' => $photoPath ?? null,
            ]);

            // Attach children to father
            $father->children()->sync($request->children);

            // Generate JWT token
            $token = JWTAuth::fromUser($father);

            $admin = User::role('admin')->first();
            Notification::send($admin, new NewUserNotification($father));

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

        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422); // Return validation errors
        }

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
            'photo' => $photoPath ?? null,
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
        try {
            $credentials = $request->only('email', 'password');
            if (!$token = Auth::guard('father')->attempt($credentials)) {
                return response()->json(['error' => 'بيانات الاعتماد غير صالحة'], 400);
            }

            return response()->json([
                'token' => $token
            ]);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    // Driver Login
    public function driverLogin(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = Auth::guard('driver')->attempt($credentials)) {
                return response()->json(['error' => 'بيانات الاعتماد غير صالحة'], 400);
            }

            return response()->json([
                'token' => $token
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json(compact('token'));
    }

    // Get Authenticated Father
    public function meFather()
    {
        try {
            $father = auth('father')->user()->load([
                'children.school',
                'children.groupChildren.group.driver',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Father data retrieved successfully',
                'data' => [
                    'id' => $father->id,
                    'name' => $father->name,
                    'phone' => $father->phone,
                    'email' => $father->email,
                    'status' => $father->status,
                    'location' => [
                        'latitude' => $father->Latitude,
                        'longitude' => $father->Longitude,
                    ],
                    'children' => $father->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'age' => $child->age,
                            'phone' => $child->phone,
                            'photo' => $child->photo ? asset('assets/files/children/' . $child->photo) : null,
                            'location' => [
                                'address' => $child->address,
                                'latitude' => $child->Latitude,
                                'longitude' => $child->Longitude,
                            ],
                            'school' => [
                                'id' => $child->school_id,
                                'name' => $child->school->name ?? null,
                                'class_id' => $child->school_class_id,
                            ],
                            'groups' => $child->groupChildren->map(function ($groupChild) {
                                $group = $groupChild->group;
                                return [
                                    'id' => $group->id,
                                    'name' => $group->name,
                                    'status' => $groupChild->status,
                                    'driver' => $group->driver ? [
                                        'id' => $group->driver->id,
                                        'name' => $group->driver->name,
                                        'phone' => $group->driver->phone,
                                    ] : null
                                ];
                            })
                        ];
                    })
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve father data',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    // Get Authenticated Driver
    public function meDriver()
    {
        try {
            $driver = auth('driver')->user();

            return response()->json([
                'data' => [
                    'id' => $driver->id,
                    'name' => $driver->name,
                    'phone' => $driver->phone,
                    'status' => $driver->status,
                    'photo' => $driver->photo ? url('assets/files/drivers/'. $driver->photo ) : null,
                    'location' => [
                        'latitude' => $driver->Latitude,
                        'longitude' => $driver->Longitude,
                    ],
                    'cars' => $driver->cars->map(function ($car) {
                        return [
                            'id' => $car->id,
                            'model' => $car->model,
                            'year' => date('Y', strtotime($car->make)),
                            'license' => $car->license ? url('assets/files/drivers/car'. $car->license ) : null,
                            'photo' => $car->photo ? url('assets/files/drivers/car'. $car->photo ) : null,
                        ];
                    })
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve father data',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function updateFather(Request $request) {
        $father = auth('father')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'phone' => 'string',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'Latitude' => 'nullable',
            'Longitude' => 'nullable',
            'email' => 'nullable|string|email|max:255|unique:fathers,email,'.$father->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            if ($request->hasFile('photo')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($father->photo != 'avatar.png') {
                    $oldPhotoPath = public_path("/assets/files/fathers/") . $father->photo;
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                $file = $request->file('photo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/assets/files/fathers/"), $filename);
                $father->photo = $filename;
            }

            $father->fill($request->only([
                'name', 'phone', 'state', 'city', 'Latitude', 'Longitude', 'email'
            ]));

            $father->save();

            return response()->json([
                'message' => 'تم تحديث البيانات بنجاح',
                'father' => $father
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل تحديث البيانات'], 500);
        }
    }

    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
            'current_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $father = auth('father')->user();

        if (!Hash::check($request->current_password, $father->password)) {
            return response()->json(['error' => 'كلمة المرور الحالية غير صحيحة'], 400);
        }

        try {
            $father->password = Hash::make($request->password);
            $father->save();

            return response()->json(['message' => 'تم تغيير كلمة المرور بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل تغيير كلمة المرور'], 500);
        }
    }

    public function updateDriver(Request $request) {
        $driver = auth('driver')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'phone' => 'string',
            'address' => 'nullable|string|max:255',
            'Latitude' => 'nullable',
            'Longitude' => 'nullable',
            'email' => 'nullable|string|email|max:255|unique:drivers,email,'.$driver->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            if ($request->hasFile('photo')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($driver->photo != 'avatar.png') {
                    $oldPhotoPath = public_path("/assets/files/drivers/") . $driver->photo;
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                $file = $request->file('photo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/assets/files/drivers/"), $filename);
                $driver->photo = $filename;
            }

            $driver->fill($request->only([
                'name', 'phone', 'address', 'Latitude', 'Longitude', 'email'
            ]));

            $driver->save();

            return response()->json([
                'message' => 'تم تحديث البيانات بنجاح',
                'driver' => $driver
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل تحديث البيانات'], 500);
        }
    }

    public function changePasswordDriver(Request $request) {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $driver = auth('driver')->user();

        if (!Hash::check($request->current_password, $driver->password)) {
            return response()->json(['error' => 'كلمة المرور الحالية غير صحيحة'], 400);
        }

        try {
            $driver->password = Hash::make($request->password);
            $driver->save();

            return response()->json(['message' => 'تم تغيير كلمة المرور بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل تغيير كلمة المرور'], 500);
        }
    }
}
