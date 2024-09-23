<?php

namespace App\Http\Controllers\Admin;

use App\Models\School;
use Illuminate\Http\Request;
use App\Models\SchoolSemster;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class SchoolSemsterController extends Controller
{
    public function index()
    {
        $schools = School::with('semesters')->paginate(15);
        $semesters = SchoolSemster::all();
        return view('admin.schools.semester.index', compact('semesters','schools'));

    }

    function createSemester($schoolId) {
        $school = School::where('id', $schoolId)->first();
        return view('admin.schools.semester.create' , compact('school'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'semester'      => 'required|string|max:255',
            'study_start'   => 'required|date',
            'study_end'     => 'required|date',
            'exam_start'    => 'required|date',
            'exam_end'      => 'required|date',
            'holiday_start' => 'required|date',
            'holiday_end'   => 'required|date',
            'school_id'     => 'required|string',
        ]);

        $schoolSemester = new SchoolSemster();

        $schoolSemester->semester = $request->semester;
        $schoolSemester->study_start = $request->study_start;
        $schoolSemester->study_end = $request->study_end;

        $schoolSemester->exam_start = $request->exam_start;
        $schoolSemester->exam_end = $request->exam_end;
        
        $schoolSemester->holiday_start = $request->holiday_start;
        $schoolSemester->holiday_end = $request->holiday_end;

        $schoolSemester->school_id  = $request->school_id;

        if ($schoolSemester->save()) {
            flash()->addSuccess('School Semester created successfully.');
            return redirect()->route('admin.schools.index');
        }
        flash()->addError('School Semester create fail!');
        return redirect()->route('admin.schools.index');

    }

    public function show($id)
    {
        $schoolSemester = SchoolSemster::find($id);
        if (!$schoolSemester) {
            return view('admin.schools.semester.edit', compact('schoolSemester'));
        }
        flash()->addError('School Semester create fail!');
        return redirect()->route('admin.schools.index');
    }

    public function edit($id)
    {
        $schoolSemester = SchoolSemster::find($id);
        if ($schoolSemester) {
            return view('admin.schools.semester.edit', compact('schoolSemester'));
        }
        flash()->addError('School Semester fail!');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'semester'      => 'required|string|max:255',
            'study_start'   => 'required|date',
            'study_end'     => 'required|date',
            'exam_start'    => 'required|date',
            'exam_end'      => 'required|date',
            'holiday_start' => 'required|date',
            'holiday_end'   => 'required|date',
        ]);

        $schoolSemester = SchoolSemster::find($id);

        $data['semester'] = $request->semester;
        $data['study_start'] = $request->study_start;
        $data['study_end'] = $request->study_end;
        $data['exam_start'] = $request->exam_start;
        $data['exam_end'] = $request->exam_end;
        $data['holiday_start'] = $request->holiday_start;
        $data['holiday_end'] = $request->holiday_end;
        $data['semester'] = $request->semester;

        if ($schoolSemester->update($data)) {
            flash()->addSuccess('School Semester Edit successfully.');
            return redirect()->back();
        }
        flash()->addError('School Semester Edit fail!');
        return redirect()->back();
    }

    public function destroySemester($id)
    {
        $schedule = SchoolSemster::find($id);
        if (!$schedule) {
            flash()->addError('School Semester Deleted fail!');
            return redirect()->back();
        }
        $schedule->delete();
        flash()->addSuccess('School Semester Deleted successfully.');
        return redirect()->back();
    }
}
