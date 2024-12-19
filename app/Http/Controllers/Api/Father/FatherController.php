<?php

namespace App\Http\Controllers\Api\Father;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Trip;
class FatherController extends Controller
{
    public function getFatherChildren(Request $request)
    {
        try {
            // Get the authenticated father using JWT
            $parent = auth()->guard('father')->user();

            // If no authenticated father is found
            if (!$parent) {
                return response()->json([
                    'status' => false,
                    'message' => 'غير مصرح لك بالوصول. الرجاء تسجيل الدخول'
                ], 401);
            }

            // Get the children associated with the father
            $children = $parent->children;

            // If the father has no children
            if ($children->isEmpty()) {
                return response()->json([
                    'message' => 'No children found for this father.'
                ], 404);
            }

            // Format the response data
            $response = [
                'children' => $children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'age' => $child->age,
                        'phone' => $child->phone,
                        'address' => $child->address,
                        'latitude' => $child->latitude,
                        'longitude' => $child->longitude,
                        'school_id' => $child->school_id,
                        'school_class_id' => $child->school_class_id,
                        'photo' => $child->photo ? asset('assets/files/children/' . $child->photo) : null,
                    ];
                })
            ];

            // Return the JSON response with children
            return response()->json( $response , 200);


        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error('Failed to retrieve children: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json([
                'message' => 'An error occurred while retrieving data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFatherGroups(Request $request)
    {
        try {
            // Get the authenticated father using JWT
            $parent = auth()->guard('father')->user();

            // If no authenticated father is found
            if (!$parent) {
                return response()->json([
                    'message' => 'Unauthorized: Father not authenticated.'
                ], 401);
            }

            // Get the children associated with the father
            $children = $parent->children;

            // If the father has no children
            if ($children->isEmpty()) {
                return response()->json([
                    'message' => 'No children found for You.'
                ], 404);
            }

            // Fetch the groups that contain these children
            $groups = Group::whereHas('children', function ($query) use ($children) {
                // Use 'children.id' to remove ambiguity
                $query->whereIn('children.id', $children->pluck('id'));
            })->with('school', 'driver', 'schoolClass', 'children')->get();

            // If no groups are found containing these children
            if ($groups->isEmpty()) {
                return response()->json([
                    'message' => 'No groups found containing these children.'
                ], 404);
            }

            // Format the response data
            return response()->json([
                'data' => [
                    'parameters' => $groups->map(function ($group) {
                        return [
                            'id' => $group->id,
                            'group_name' => $group->name,
                            'children_count' => $group->children->count(),
                            'status' => $group->status,
                            'waypoints' => json_decode($group->waypoints, true), // تحويل JSON للـ waypoints
                            'created_at' => $group->created_at->format('Y-m-d H:i:s'), // تنسيق تاريخ الإنشاء
                            'children' => $group->children->map(function ($child) {
                                return [
                                    'id' => $child->id,
                                    'name' => $child->name,
                                    'latitude' => $child->Latitude,
                                    'longitude' => $child->Longitude,
                                    'photo' => $child->photo ? asset('assets/files/children/' . $child->photo) : null,
                                ];
                            }),
                            'school' => [
                                'id' => $group->school->id,
                                'name' => $group->school->name,
                            ],
                            'driver' => [
                                'id' => $group->driver->id,
                                'name' => $group->driver->name,
                                'phone' => $group->driver->phone ?? null, // الهاتف إذا كان موجودًا
                            ],
                            'school_class' => $group->schoolClass ? [ // تحقق من وجود الـ SchoolClass
                                'id' => $group->schoolClass->id,
                                'name' => $group->schoolClass->name,
                            ] : null,
                        ];
                    }),
                ],
            ] , 200);

        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error('Failed to retrieve groups: ' . $e->getMessage(), [
                'father_id' => auth()->guard('father')->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    // dosnt work
    public function getFatherTrips(Request $request) {
        $parent = auth()->guard('father')->user();
        $trips = Trip::whereHas('group', function ($query) use ($parent) {
            $query->whereHas('children', function ($query) use ($parent) {
                $query->whereIn('children.id', $parent->children->pluck('id'));
            });
        })->get();

        return response()->json($trips);
    }

    public function getGroupDetails($groupId) {
        $group = Group::find($groupId);
        if (!$group) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }
        return response()->json([
            'data' => [
                'parameters' => [
                    'id' => $group->id,
                    'name' => $group->name,
                    'status' => $group->status,
                    'children_count' => $group->children->count(),
                    'waypoints' => json_decode($group->waypoints, true),
                ],
                'children' => $group->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'latitude' => $child->Latitude,
                        'longitude' => $child->Longitude,
                        'photo' => $child->photo ? asset('assets/files/children/' . $child->photo) : null,
                    ];
                }),
                'school' => [
                    'id' => $group->school->id,
                    'name' => $group->school->name,
                ],
                'driver' => [
                    'id' => $group->driver->id,
                    'name' => $group->driver->name,
                    'phone' => $group->driver->phone ?? null,
                    'car' => $group->driver->cars[0] ? [
                        'id' => $group->driver->cars[0]->id,
                        'model' => $group->driver->cars[0]->model,
                        'license' => $group->driver->cars[0]->license ? asset('assets/files/drivers/car/' . $group->driver->cars[0]->license) : null,
                        'color' => $group->driver->cars[0]->color,
                        'photo' => $group->driver->cars[0]->photo ? asset('assets/files/drivers/car/' . $group->driver->cars[0]->photo) : null,
                    ] : null,
                ],
                'school_class' => $group->schoolClass ? [
                    'id' => $group->schoolClass->id,
                    'name' => $group->schoolClass->name,
                ] : null,
            ]
        ]);
    }
}
