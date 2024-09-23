<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
                    'message' => 'Unauthorized: Father not authenticated.'
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
                        'photo' => $child->photo ? url($child->photo) : null,
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
            $response = [
                'groups' => $groups->map(function ($group) {
                    return [
                        'id' => $group->id,
                        'group_name' => $group->name,
                        'children' => $group->children->select('id', 'name'),
                        'school' => $group->school->name,
                        'driver' => $group->driver->name,
                        'school_class' => $group->schoolClass ? $group->schoolClass->name : null,
                        'status' => $group->status,
                        'waypoints' => json_decode($group->waypoints, true),
                    ];
                })
            ];

            // Return the JSON response with groups
            return response()->json( $response , 200);

        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error('Failed to retrieve groups: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json([
                'message' => 'An error occurred while retrieving data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
