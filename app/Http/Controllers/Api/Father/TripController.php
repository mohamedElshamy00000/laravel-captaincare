<?php

namespace App\Http\Controllers\Api\Father;

use Carbon\Carbon;
use App\Models\Group;
use App\Models\Driver;
use App\Models\Father;
use App\Models\SchoolSemster;
use App\Models\SchoolClass;
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
use Illuminate\Support\Facades\Log;

class TripController extends Controller
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
                    // Ensure dates are valid Carbon instances
                    try {
                        $studyStart = Carbon::parse($semester->study_start);
                        $studyEnd = Carbon::parse($semester->study_end);
                        $examStart = Carbon::parse($semester->exam_start);
                        $examEnd = Carbon::parse($semester->exam_end);

                        return $currentDate->between($studyStart, $studyEnd) ||
                               $currentDate->between($examStart, $examEnd);
                    } catch (\Exception $e) {
                        return false;
                    }
                });

                // التحقق من وجود فصل دراسي مناسب
                $isInAnySemester = $currentSemesters->isNotEmpty();
                $schoolClass = $group->schoolClass;
                // dd($schoolClass);
                // التحقق من فترة الامتحانات بناءً على الفصول الدراسية الحالية
                $inExamPeriod = $currentSemesters->contains(function ($semester) use ($currentDate) {
                    try {
                        $examStart = Carbon::parse($semester->exam_start);
                        $examEnd = Carbon::parse($semester->exam_end);
                        return $currentDate->between($examStart, $examEnd);
                    } catch (\Exception $e) {
                        return false;
                    }
                });

                $futureTrips = $this->tripService->getFutureWeeklyTrips($group);
                // dd($futureTrips);

                $groupDetails = [
                    'data' => [
                        'group' => [
                            'id' => $group->id,
                            'name' => $group->name,
                            'status' => $group->status,
                            'waypoints' => json_decode($group->waypoints, true),
                        ],
                        'children' => $group->children->map(function($child) {
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
                            'class' => $schoolClass ? $schoolClass->name : null,
                        ],
                        'driver' => [
                            'id' => $group->driver->id,
                            'name' => $group->driver->name,
                            'photo' => $group->driver->photo,
                            'phone' => $group->driver->phone ?? null,
                        ],
                        'semester' => [
                            'name' => $isInAnySemester ? $currentSemesters->pluck('semester')->implode(', ') : null,
                            'in_exam_period' => $inExamPeriod
                        ],
                        'trips' => $futureTrips
                    ]
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

}
