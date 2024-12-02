<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Group;
use App\Models\Driver;
use App\Models\Father;
use App\Models\SchoolSemster;
use App\Models\SchoolClass;
use App\Models\OfficialHoliday;
use App\Models\SchoolHoliday;
use App\Services\TripService;
use App\Notifications\TripStartedNotification;
use App\Notifications\TripEndedNotification;
use App\Events\TripStarted;
use App\Events\TripEnded;
use App\Models\Trip;
use App\Models\Child;
use App\Notifications\ChildGotTheCarNotification;
use Illuminate\Support\Facades\Log;
use App\Events\ChildGotInCarEvent;


class TripController extends Controller
{
    protected $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    /**
     * Get planned trips for a driver.
     *
     * @param int $driverId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDriverTripss() // لا استخدمها حاليا )(قديمة)
    {
        $driver = auth()->guard('driver')->user();

        if (!$driver) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $groups = Group::where('driver_id', $driver->id)
            ->with('children', 'school', 'schoolClass')
            ->get();

        $currentDate = Carbon::now();
        $schoolIds = $groups->pluck('school.id')->unique();
        $semesters = SchoolSemster::whereIn('school_id', $schoolIds)->get();

        $plannedTrips = $groups->map(function ($group) use ($currentDate, $semesters) {
            $currentSemesters = $semesters->filter(function ($semester) use ($currentDate) {
                return $currentDate->between($semester->study_start, $semester->study_end) ||
                       $currentDate->between($semester->exam_start, $semester->exam_end);
            });

            $isInAnySemester = $currentSemesters->isNotEmpty();
            $inExamPeriod = $currentSemesters->contains(function ($semester) use ($currentDate) {
                return $currentDate->between($semester->exam_start, $semester->exam_end);
            });

            $futureTrips = $this->tripService->getFutureWeeklyTrips($group);

            return [
                'group_id' => $group->id,
                'group_name' => $group->name,
                'group_status' => $group->status,
                'school_name' => $group->school->name,
                'school_class' => $group->schoolClass ? $group->schoolClass->name : null,
                'children' => $group->children->map(function($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'latitude' => $child->Latitude,
                        'longitude' => $child->Longitude
                    ];
                }),
                'semester_info' => [
                    'current_semester' => $isInAnySemester ? $currentSemesters->pluck('semester')->implode(', ') : null,
                    'is_exam_period' => $inExamPeriod
                ],
                'trips' => $futureTrips
            ];
        });

        return response()->json($plannedTrips, 200);
    }

    /**
     * Get planned trips for a driver based on their assigned groups.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDriverTrips()
    {
        try {
            // Authenticate the driver using JWT
            $driver = auth()->guard('driver')->user();

            if (!$driver) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Retrieve groups associated with the driver
            $groups = Group::where('driver_id', $driver->id)
                ->with(['children', 'school', 'schoolClass'])
                ->get();

            if ($groups->isEmpty()) {
                return response()->json(['message' => 'No groups found for this driver.'], 404);
            }

            $currentDate = Carbon::now();
            $schoolIds = $groups->pluck('school.id')->unique();
            $semesters = SchoolSemster::whereIn('school_id', $schoolIds)->get();

            // Format the planned trips
            $plannedTrips = $groups->map(function ($group) use ($currentDate, $semesters) {
                // Determine applicable semesters for the current date
                $currentSemesters = $semesters->filter(function ($semester) use ($currentDate) {
                    return $this->isDateInSemester($currentDate, $semester);
                });

                // Check if any semester is applicable
                $isInAnySemester = $currentSemesters->isNotEmpty();
                $inExamPeriod = $currentSemesters->contains(function ($semester) use ($currentDate) {
                    return $this->isDateInExamPeriod($currentDate, $semester);
                });

                $futureTrips = $this->tripService->getFutureWeeklyTrips($group);

                return [
                    'data' => [
                        'group' => [
                            'id' => $group->id,
                            'name' => $group->name,
                            'status' => $group->status,
                            'waypoints' => json_decode($group->waypoints, true),
                        ],
                        'children' => $group->children->map(function ($child) {
                            return [
                                'id' => $child->id,
                                'name' => $child->name,
                                'phone' => $child->phone,
                                'latitude' => $child->Latitude,
                                'longitude' => $child->Longitude
                            ];
                        })->values(),
                        'school' => [
                            'name' => $group->school->name,
                            'class' => $group->schoolClass ? $group->schoolClass->name : null,
                        ],
                        'semester' => [
                            'name' => $isInAnySemester ? $currentSemesters->pluck('semester')->implode(', ') : null,
                            'in_exam_period' => $inExamPeriod
                        ],
                        'trips' => $futureTrips
                    ]
                ];
            });

            return response()->json($plannedTrips, 200);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve driver trips: ' . $e->getMessage(), [
                'driver_id' => $driver->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'An error occurred while retrieving trips.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Check if the current date is within the semester's study or exam period.
     *
     * @param \Carbon\Carbon $currentDate
     * @param $semester
     * @return bool
     */
    private function isDateInSemester($currentDate, $semester)
    {
        try {
            return $currentDate->between($semester->study_start, $semester->study_end) ||
                       $currentDate->between($semester->exam_start, $semester->exam_end);
            // return $currentDate->between(Carbon::parse($semester->study_start), Carbon::parse($semester->study_end)) || $currentDate->between(Carbon::parse($semester->exam_start), Carbon::parse($semester->exam_end));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if the current date is within the exam period of the semester.
     *
     * @param \Carbon\Carbon $currentDate
     * @param $semester
     * @return bool
     */
    private function isDateInExamPeriod($currentDate, $semester)
    {
        try {
            return $currentDate->between(Carbon::parse($semester->exam_start), Carbon::parse($semester->exam_end));
        } catch (\Exception $e) {
            return false;
        }
    }

    public function startTrip($group_id)
    {
        // Get the authenticated driver
        $driver = auth()->guard('driver')->user();

        if (!$driver) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve the group
        $group = Group::find($group_id);

        if (!$group) {
            return response()->json(['message' => 'No group found'], 404);
        }

        // Start the trip
        $trip = $this->tripService->storeTrip($group, 'started');

        // Notify parents
        foreach ($group->children as $child) {
            $parents = $child->fathers;
            foreach ($parents as $parent) {
                if ($parent) {
                    broadcast(new TripStarted($child));
                    $parent->notify(new TripStartedNotification($child));
                }
            }
        }

        return response()->json([
            'message' => 'Trip started successfully',
            'trip' => $trip,
        ], 200);
    }

    public function endTrip($group_id)
    {
        // Get the authenticated driver
        $driver = auth()->guard('driver')->user();

        if (!$driver) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // Retrieve the group
        $group = Group::find($group_id);
        if (!$group) {
            return response()->json(['message' => 'No group found'], 404);
        }

        // End the trip
        $trip = Trip::where('group_id', $group->id)->where('driver_id', $driver->id)->latest()->first();
        if ($trip) {
            $trip->status = 'completed';
            $trip->save();
        } else {
            return response()->json(['message' => 'No active trip found for the group'], 404);
        }

        // Notify parents
        foreach ($group->children as $child) {
            $parents = $child->fathers;
            foreach ($parents as $parent) {
                if ($parent) {
                    broadcast(new TripEnded($child));
                    $parent->notify(new TripEndedNotification($child));
                }
            }
        }

        return response()->json(['message' => 'Trip ended successfully', 'trip' => $trip], 200);
    }

    public function childGotInCar($child_id)
    {
        // Get the authenticated driver
        $driver = auth()->guard('driver')->user();

        if (!$driver) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve the child
        $child = Child::find($child_id);

        if (!$child) {
            return response()->json(['message' => 'No child found'], 404);
        }

        // Notify parent
        $parent = $child->fathers->first();
        if ($parent) {

            broadcast(new ChildGotInCarEvent($parent, $child));
            $parent->notify(new ChildGotTheCarNotification($child));

        }

        return response()->json([
            'message' => 'Notification sent successfully',
            'child_id' => $child->id,
            'child_name' => $child->name,
            'father_id' => $parent->id,
            'message' => 'طفلك ' . $child->name . ' قد وصل السيارة',
        ], 200);

    }
}
