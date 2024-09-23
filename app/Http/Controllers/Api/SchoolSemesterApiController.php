<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\SchoolSemster;
use App\Http\Controllers\Controller;

class SchoolSemesterApiController extends Controller
{
    public function index()
    {
        $schedules = SchoolSemster::all();
        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $schedule = SchoolSemster::create($request->all());
        return response()->json($schedule, 201);
    }

    public function show($id)
    {
        $schedule = SchoolSemster::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }
        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $schedule = SchoolSemster::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }
        $schedule->update($request->all());
        return response()->json($schedule);
    }

    public function destroy($id)
    {
        $schedule = SchoolSemster::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }
        $schedule->delete();
        return response()->json(['message' => 'Schedule deleted']);
    }
}
