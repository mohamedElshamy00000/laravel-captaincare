<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $school = School::where('id', $id)->first();
        return view('admin.schools.classes.create', compact('school'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'check_out'   => 'required|string',
            'entry_time'  => 'required|string',
            'school_id'   => 'required',
        ]);

        $schoolClass = new SchoolClass;

        $schoolClass->name = $request->name;
        $schoolClass->description = $request->description;
        $schoolClass->check_out = $request->check_out;
        $schoolClass->entry_time = $request->entry_time;
        $schoolClass->school_id = $request->school_id;
        if ($schoolClass->save()) {
            flash()->addSuccess('School Class created successfully.');
            return redirect()->back();
        }
        flash()->addError('School Class create fail!');
        return redirect()->route('admin.schools.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $class = SchoolClass::where('id', $id)->first();
        if ($class) {
            return view('admin.schools.classes.edit', compact('class'));
        }
        flash()->addError('fail!');
        return redirect()->back();

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());

        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'check_out'   => 'required|string',
            'entry_time'  => 'required|string',
            'id' => 'required'
        ]);
        // $schoolClass = new SchoolClass;
        $schoolClass = SchoolClass::where('id',$request->id)->first();

        // Update the school class object with validated data
        $schoolClass->name = $validatedData['name'];
        $schoolClass->description = $validatedData['description'];
        $schoolClass->check_out = $validatedData['check_out'];
        $schoolClass->entry_time = $validatedData['entry_time'];

        if ($schoolClass->save()) {
            flash()->addSuccess('School Class Updated successfully.');
            return redirect()->back();
        }
        flash()->addError('School Class Updated fail!');
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
