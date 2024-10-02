<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Group;
use App\Models\SchoolClass;
use App\Models\SchoolHoliday;
use App\Models\SchoolSemster;
use App\Models\OfficialHoliday;
use App\Models\Trip;
class TripService
{
    /**
     * Get future trips for the given group.
     *
     * @param Group $group
     * @return array
     */


    public function getFutureWeeklyTrips(Group $group)
    {
        $currentDate = Carbon::now();
        $startOfWeek = $currentDate->copy()->startOfWeek(); // بداية الأسبوع الحالي
        $endOfWeek = $currentDate->copy()->endOfWeek(); // نهاية الأسبوع الحالي
        $schoolClasses = SchoolClass::where('school_id', $group->school_id)->get();
        $semesters = SchoolSemster::where('school_id', $group->school_id)
                                    ->where('status', 1) // الفصول الدراسية النشطة
                                    ->get();

         // الحصول على العطلات الخاصة بالمدرسة
        $schoolHolidays = SchoolHoliday::where('school_id', $group->school_id)->get();

         // الحصول على العطلات العامة
        $generalHolidays = OfficialHoliday::all();

        $futureWeeklyTrips = [];

        foreach ($schoolClasses as $schoolClass) {
            $entryTime = Carbon::createFromFormat('g:i a', $schoolClass->entry_time);
            $checkOutTime = Carbon::createFromFormat('g:i a', $schoolClass->check_out);

            foreach ($semesters as $semester) {
                if ($currentDate->between($semester->study_start, $semester->study_end) ||
                    $currentDate->between($semester->exam_start, $semester->exam_end)) {

                     // التحقق مما إذا كنا في فترة الامتحانات
                    $inExamPeriod = $currentDate->between($semester->exam_start, $semester->exam_end);

                     // إنشاء الرحلات لكل يوم مستقبلي في الأسبوع
                    $currentDay = $startOfWeek->copy();

                    while ($currentDay->lte($endOfWeek)) {
                        if ($currentDay->isFuture() ) { // التحقق مما إذا كان اليوم في المستقبل
                            $tripDate = $currentDay->copy()->toDateString();

                             // التحقق مما إذا كان اليوم عطلة
                            $isHoliday = $schoolHolidays->contains(function ($holiday) use ($tripDate) {
                                return $holiday->start_date <= $tripDate && $holiday->end_date >= $tripDate;
                            }) || $generalHolidays->contains(function ($holiday) use ($tripDate) {
                                return $holiday->date == $tripDate;
                            });

                            // التحقق مما إذا كان اليوم الحالي أو في المستقبل
                            $isToday = $currentDay->isToday();

                            // التحقق مما إذا كان اليوم هو الجمعة أو السبت
                            $isWeekend = $currentDay->isWeekend();

                            if (!$isHoliday && !$isWeekend) {
                                if ($isToday) {
                                    // إذا كان اليوم هو اليوم الحالي، نتحقق من أن الرحلات لم تنقض بعد
                                    $currentTime = Carbon::now()->format('H:i');
                                    if ($currentTime < $entryTime->format('H:i')) {
                                        // إضافة رحلة ذهاب إلى المدرسة اليوم
                                        $futureWeeklyTrips[] = [
                                            'trip_date' => $tripDate,
                                            'time'      => $entryTime->format('H:i'),
                                            'status'    => 'upcoming',
                                            'semester'  => $semester->semester,
                                            'in_exam_period' => $inExamPeriod,
                                            'trip_type' => 'to_school', // نوع الرحلة ذهاب
                                            'group_id'  => $group->id, // إضافة معرف المجموعة
                                            'school_class_id' => $schoolClass->id, // إضافة معرف الصف الدراسي
                                        ];
                                    }
                                    if ($currentTime < $checkOutTime->format('H:i')) {
                                        // إضافة رحلة العودة من المدرسة اليوم
                                        $futureWeeklyTrips[] = [
                                            'trip_date' => $tripDate,
                                            'time' => $checkOutTime->format('H:i'),
                                            'status' => 'upcoming',
                                            'semester' => $semester->semester,
                                            'in_exam_period' => $inExamPeriod,
                                            'trip_type' => 'from_school', // نوع الرحلة إياب
                                            'group_id'  => $group->id, // إضافة معرف المجموعة
                                            'school_class_id' => $schoolClass->id, // إضافة معرف الصف الدراسي
                                        ];
                                    }
                                } else {
                                    // إضافة رحلة ذهاب إلى المدرسة في الأيام المستقبلية
                                    $futureWeeklyTrips[] = [
                                        'trip_date' => $tripDate,
                                        'time'      => $entryTime->format('H:i'),
                                        'status'    => 'scheduled',
                                        'semester'  => $semester->semester,
                                        'in_exam_period' => $inExamPeriod,
                                        'trip_type' => 'to_school', // نوع الرحلة ذهاب
                                        'group_id'  => $group->id, // إضافة معرف المجموعة
                                        'school_class_id' => $schoolClass->id, // إضافة معرف الصف الدراسي
                                    ];

                                    // إضافة رحلة العودة من المدرسة في الأيام المستقبلية
                                    $futureWeeklyTrips[] = [
                                        'trip_date' => $tripDate,
                                        'time' => $checkOutTime->format('H:i'),
                                        'status' => 'scheduled',
                                        'semester' => $semester->semester,
                                        'in_exam_period' => $inExamPeriod,
                                        'trip_type' => 'from_school', // نوع الرحلة إياب
                                        'group_id'  => $group->id, // إضافة معرف المجموعة
                                        'school_class_id' => $schoolClass->id, // إضافة معرف الصف الدراسي
                                    ];
                                }
                            }
                        }

                        $currentDay->addDay();
                    }
                }
            }
        }

        return $futureWeeklyTrips;
    }

    public function storeTrip($group, $status)
    {

        try {
            // Validate the group and status
            if (!$group || !$status) {
                throw new \Exception('Invalid group or status');
            }

            // Validate the driver_id
            if (!$group->driver_id) {
                throw new \Exception('Invalid driver_id');
            }

            $trip = Trip::create([
                'group_id' => $group->id,
                'status' => $status,
                'driver_id' => $group->driver_id,
                'trip_date' => Carbon::now()->toDateString(),
                'time' => Carbon::now()->toTimeString(),
                'description' => 'Trip description', // Add a default description
                'trip_type' => 'morning', // Add a default trip type
                'in_exam_period' => false, // Add a default value for in_exam_period
                'school_class_id' => $group->school_class_id, // Add school_class_id
            ]);

        } catch (\Exception $e) {
            // Handle the exception
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return $trip;
    }
}
