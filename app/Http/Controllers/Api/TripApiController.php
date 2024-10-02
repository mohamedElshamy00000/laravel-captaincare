<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Group;
use App\Models\Driver;
use App\Models\Father;
use App\Models\SchoolSemster;
use App\Models\SchoolClass; // Ensure this model is imported
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OfficialHoliday;
use App\Models\SchoolHoliday;
use App\Services\TripService;
use App\Notifications\TripStartedNotification;
use App\Notifications\TripEndedNotification;
use App\Events\TripStarted;
use App\Events\TripEnded;
use App\Models\Trip;

class TripApiController extends Controller
{
    protected $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    /**
     * Get planned trips for a parent based on their children.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFatherTrips()
    {
        try {
            // الحصول على المستخدم المصادق باستخدام JWT
            $parent = auth()->guard('father')->user();
            // dd($parent);
            // $driver = auth()->guard('driver')->user();
            if (!$parent) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // الحصول على الأطفال المرتبطين بولي الأمر
            $children = $parent->children;

            if ($children->isEmpty()) {
                return response()->json([
                    'message' => 'No children found for this father.'
                ], 404);
            }

            $groups = Group::whereHas('children', function ($query) use ($children) {
                // Specify the table name for the `id` column
                $query->whereIn('children.id', $children->pluck('id'));
            })->with('school', 'driver', 'schoolClass', 'children')->get();

            if ($groups->isEmpty()) {
                return response()->json([
                    'message' => 'No groups found containing these children.'
                ], 404);
            }

            // الحصول على فصل المدرسة الحالي
            $currentDate = Carbon::now();
            $schoolIds = $groups->pluck('school.id')->unique();
            $semesters = SchoolSemster::whereIn('school_id', $schoolIds)->get();

            // تنسيق النتائج
            $plannedTrips = $groups->map(function ($group) use ($currentDate, $semesters) {
                // تحديد الفصول الدراسية التي تنطبق على التاريخ الحالي
                $currentSemesters = $semesters->filter(function ($semester) use ($currentDate) {
                    return $currentDate->between($semester->study_start, $semester->study_end) ||
                        $currentDate->between($semester->exam_start, $semester->exam_end);
                });

                // التحقق من وجود فصل دراسي مناسب
                $isInAnySemester = $currentSemesters->isNotEmpty();
                $schoolClass = $group->schoolClass;

                // التحقق من فترة الامتحانات بناءً على الفصول الدراسية الحالية
                $inExamPeriod = $currentSemesters->contains(function ($semester) use ($currentDate) {
                    return $currentDate->between($semester->exam_start, $semester->exam_end);
                });
                $futureTrips = $this->tripService->getFutureWeeklyTrips($group);
                // بناء تفاصيل الجروب
                $groupDetails = [
                    'group_name' => $group->name,
                    'children' => $group->children->select('id', 'name', 'Latitude', 'Longitude'),
                    'school' => $group->school->name,
                    'driver' => $group->driver->name,
                    'school_class' => $schoolClass ? $schoolClass->name : null,
                    'status' => $group->status,
                    'waypoints' => json_decode($group->waypoints, true),
                    'semester' => $isInAnySemester ? $currentSemesters->pluck('semester')->implode(', ') : null,
                    'in_exam_period' => $inExamPeriod,
                    'trips' => $futureTrips,
                ];

                return $groupDetails;
            });

            return response()->json($plannedTrips, 200);


        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving trips.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Get planned trips for a driver.
     *
     * @param int $driverId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDriverTrips($driverId)
    {

        $driver = auth()->guard('driver')->user();

        if (!$driver) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // جلب الجروبات التي يقودها الكابتن
        $groups = Group::where('driver_id', $driverId)->with('children', 'school', 'schoolClass')->get();

        // الحصول على فصل المدرسة الحالي
        $currentDate = Carbon::now();
        $schoolIds = $groups->pluck('school.id')->unique();
        $semesters = SchoolSemster::whereIn('school_id', $schoolIds)->get();

        // تنسيق النتائج
        $plannedTrips = $groups->map(function ($group) use ($currentDate, $semesters) {
            // تحديد الفصول الدراسية التي تنطبق على التاريخ الحالي
            $currentSemesters = $semesters->filter(function ($semester) use ($currentDate) {
                return $currentDate->between($semester->study_start, $semester->study_end) ||
                       $currentDate->between($semester->exam_start, $semester->exam_end);
            });

            // التحقق من وجود فصل دراسي مناسب
            $isInAnySemester = $currentSemesters->isNotEmpty();
            $schoolClass = $group->schoolClass;

            // التحقق من فترة الامتحانات بناءً على الفصول الدراسية الحالية
            $inExamPeriod = $currentSemesters->contains(function ($semester) use ($currentDate) {
                return $currentDate->between($semester->exam_start, $semester->exam_end);
            });
            $futureTrips = $this->tripService->getFutureWeeklyTrips($group);
            // بناء تفاصيل الجروب
            $groupDetails = [
                'group_name' => $group->name,
                'children' => $group->children->select('id', 'name', 'Latitude', 'Longitude'),
                'school' => $group->school->name,
                'driver' => $group->driver->name,
                'school_class' => $schoolClass ? $schoolClass->name : null,
                'status' => $group->status,
                'waypoints' => json_decode($group->waypoints, true),
                'semester' => $isInAnySemester ? $currentSemesters->pluck('semester')->implode(', ') : null,
                'in_exam_period' => $inExamPeriod,
                'trips' => $futureTrips,
            ];

            return $groupDetails;
        });


        return response()->json($plannedTrips, 200);
    }

    public function getGroupsForDriver($group_id)
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

        // Format the data as needed
        $groupData = [
            'group_name' => $group->name,
            'school' => $group->school->name,
            'school_class' => $group->schoolClass ? $group->schoolClass->name : null,
            'children' => $group->children->map(function ($child) {
                return [
                    'name' => $child->name,
                    'age' => $child->age,
                    'phone' => $child->phone,
                    'address' => $child->address,
                    'latitude' => $child->Latitude,
                    'longitude' => $child->Longitude,
                ];
            }),
            'status' => $group->status,
            'waypoints' => json_decode($group->waypoints, true),
        ];

        return response()->json($groupData, 200);
    }

    public function updateTripStatus($group_id, $status)
    {
        // dd($group_id, $status);
        // Get the authenticated driver
        $driver = auth()->guard('driver')->user();

        if (!$driver) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve the group associated with the authenticated driver
        $group = Group::where('id', $group_id)->first();

        if (!$group) {
            return response()->json(['message' => 'No group found'], 404);
        }

        // Store the trip before updating the status
        if ($status == 'started') {
            $trip = $this->tripService->storeTrip($group, $status);
        } else {
            
        }

        // Notify parents
        foreach ($group->children as $child) {
            $parent = $child->parent;
            if ($parent) {
                // Send notification to parent
                if ($status == 'started') {
                    event(new TripStarted($child));
                    $parent->notify(new TripStartedNotification($child));
                } elseif ($status == 'completed') {
                    event(new TripEnded($child));
                    $parent->notify(new TripEndedNotification($child));
                }
            }
        }

        return response()->json(['message' => 'Trip status updated successfully', 'trip' => $trip], 200);
    }
}
