<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OfficialHoliday;
use App\Models\School;
use App\Models\SchoolHoliday;

class HolidayController extends Controller
{

    function schoolHolidays($school) {
        $school = School::where('id', $school)->first();
        if ($school) {
            // $holidays = $school->holidays;
            $holidays = SchoolHoliday::where('school_id', $school->id)->paginate(30);

            return view('admin.holidays.schools.index', compact('school','holidays'));
        } else {
            flash()->addError('Whoops failed!');
            return redirect()->back();    
        }
    }
    
    function create($school)  {
        $school = School::where('id', $school)->first();
        if ($school) {
            return view('admin.holidays.schools.create', compact('school'));
        } else {
            flash()->addError('Whoops failed!');
            return redirect()->back();    
        }
    }

    public function getAllHolidays()
    {
        $officialHolidays = OfficialHoliday::all()->map(function($holiday) {
            return [
                'name' => $holiday->name,
                'date' => $holiday->date,
                'school' => null
            ];
        });

        $schoolHolidays = SchoolHoliday::with('school')->get()->map(function($holiday) {
            return [
                'name' => $holiday->name,
                'date' => $holiday->date,
                'school' => $holiday->school->name
            ];
        });

        $allHolidays = $officialHolidays->merge($schoolHolidays);

        return response()->json($allHolidays);
    }

    // Store a new school holiday
    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'date' => 'required|date'
        ]);

        $holiday = SchoolHoliday::create($request->all());

        flash()->addSaved('saved');
        return redirect()->route('admin.schools.show', $request->school_id ); 

        // return response()->json($holiday, 201);
    }

    function edit($id)  {
        $holiday = SchoolHoliday::where('id', $id)->first();
        if ($holiday) {
            return view('admin.holidays.schools.edit', compact('holiday'));
        } else {
            flash()->addError('Whoops failed!');
            return redirect()->back();    
        }
    }

    // Update an existing school holiday
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date'
        ]);

        $holiday = SchoolHoliday::findOrFail($id);
        $holiday->update($request->all());

        flash()->addUpdated('Updated Succesfuly!');
        return redirect()->back(); 

        // return response()->json($holiday);
    }
    
    // Delete a school holiday
    public function destroy($id)
    {
        $holiday = SchoolHoliday::findOrFail($id);
        $holiday->delete();

        flash()->addDeleted('Deleted Succesfuly');
        return redirect()->back(); 

        // return response()->json(null, 204);
    }

    function officialHolidays() {
        $holidays = OfficialHoliday::paginate(30);
        if ($holidays) {
            return view('admin.holidays.index', compact('holidays'));
        } else {
            flash()->addError('Whoops failed!');
            return redirect()->back();    
        }
    }
    
    function officialCreate()  {
        return view('admin.holidays.create');
    }

    // Store a new holiday
    public function officialStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date'
        ]);

        $holiday = OfficialHoliday::create($request->all());

        flash()->addSaved('saved');
        return redirect()->back(); 

        // return response()->json($holiday, 201);
    }

    function officialEdit($id)  {
        $holiday = OfficialHoliday::where('id', $id)->first();
        if ($holiday) {
            return view('admin.holidays.edit', compact('holiday'));
        } else {
            flash()->addError('Whoops failed!');
            return redirect()->back();    
        }
    }

    // Update an existing holiday
    public function officialUpdate(Request $request, $id)
    {   
        // dd($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date'
        ]);

        $holiday = OfficialHoliday::findOrFail($id);
        $holiday->update($request->all());

        flash()->addUpdated('Updated Succesfuly!');
        return redirect()->back(); 

        // return response()->json($holiday);
    }

    public function officialDestroy($id)
    {
        $holiday = OfficialHoliday::findOrFail($id);
        $holiday->delete();

        flash()->addDeleted('Deleted Succesfuly');
        return redirect()->back(); 

        // return response()->json(null, 204);
    }
}
