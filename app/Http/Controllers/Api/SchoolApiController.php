<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolApiController extends Controller
{
    /**
     * Get all active schools.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveSchools()
    {
        try {
            $schools = School::where('status', 1)->get();

            if ($schools->isEmpty()) {
                return response()->json([
                    'message' => 'No active schools found.',
                ], 404);
            }

            return response()->json([
                'message' => 'Active schools retrieved successfully.',
                'data' => $schools,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve active schools: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while retrieving schools.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all active classes by school ID.
     *
     * @param int $school_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassesBySchool($school_id)
    {
        try {
            $school = School::find($school_id);

            if (!$school) {
                return response()->json([
                    'message' => 'School not found.',
                ], 404);
            }

            $classes = SchoolClass::where('school_id', $school_id)->get();

            if ($classes->isEmpty()) {
                return response()->json([
                    'message' => 'No active classes found for this school.',
                ], 404);
            }

            return response()->json([
                'message' => 'Active classes retrieved successfully.',
                'data' => $classes,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve active classes: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while retrieving classes.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
