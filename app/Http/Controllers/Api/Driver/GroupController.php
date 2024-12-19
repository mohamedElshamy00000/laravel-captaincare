<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
class GroupController extends Controller
{

    // get all groups
    public function getAllGroups()
    {
        try {
            // Get the authenticated father using JWT
            $driver = auth()->guard('driver')->user();

            // If no authenticated father is found
            if (!$driver) {
                return response()->json([
                    'message' => 'Unauthorized: Father not authenticated.'
                ], 401);
            }

            // Fetch the groups that contain these children
            $groups = Group::whereHas('children')->with('school', 'schoolClass', 'children')->get();

            // If no groups are found containing these children
            if ($groups->isEmpty()) {
                return response()->json([
                    'message' => 'No groups found containing these children.'
                ], 404);
            }

            // Format the response data
            return response()->json([
                'data' => $groups->map(function ($group) {
                    return [
                        'parameters' => [
                            'id' => $group->id,
                            'group_name' => $group->name,
                            'children_count' => $group->children->count(),
                            'status' => $group->status,
                            'created_at' => $group->created_at->format('Y M d h A'),
                        ],
                        'waypoints' => json_decode($group->waypoints, true),
                        'children' => $group->children->map(function ($child) {
                            return [
                                'id' => $child->id,
                                'name' => $child->name,
                                'phone' => $child->phone,
                                'photo' => $child->photo ? asset('assets/files/children/' . $child->photo) : null,
                            ];
                        }),
                        'school' => [
                            'id' => $group->school->id,
                            'name' => $group->school->name,
                        ],
                        'school_class' => $group->schoolClass ? [ // تحقق من وجود الـ SchoolClass
                            'id' => $group->schoolClass->id,
                            'name' => $group->schoolClass->name,
                        ] : null,
                    ];
                }),

            ] , 200);

        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error('Failed to retrieve groups: ' . $e->getMessage(), [
                'driver_id' => auth()->guard('driver')->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    // get Group Details
    public function getGroupDetails($group_id)
    {
        // Get the authenticated driver
        $driver = auth()->guard('driver')->user();

        if (!$driver) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve groups associated with the authenticated driver
        $group = Group::where('id', $group_id)
            ->with('school', 'schoolClass', 'children') // Include necessary relations
            ->first();

        // Check if any groups were found
        if (!$group) {
            return response()->json(['message' => 'No group found'], 404);
        }

        return response()->json([
            'data' => [
                'parameters' => [
                    'id' => $group->id,
                    'name' => $group->name,
                    'children_count' => $group->children->count(),
                    'status' => $group->status,
                    'created_at' => $group->created_at->format('Y-m-d H:i:s'),
                ],
                'waypoints' => json_decode($group->waypoints, true),
                'children' => $group->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'phone' => $child->phone,
                        'latitude' => $child->Latitude,
                        'longitude' => $child->Longitude,
                        'photo' => $child->photo ? asset('assets/files/children/' . $child->photo) : null,
                    ];
                }),
                'school' => [
                    'id' => $group->school->id,
                    'name' => $group->school->name,
                ],
                'school_class' => $group->schoolClass ? [ // تحقق من وجود الـ SchoolClass
                    'id' => $group->schoolClass->id,
                    'name' => $group->schoolClass->name,
                ] : null,
            ]
        ] , 200);
    }

}
