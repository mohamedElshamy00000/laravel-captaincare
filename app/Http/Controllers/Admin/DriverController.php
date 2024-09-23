<?php

namespace App\Http\Controllers\Admin;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class DriverController extends Controller
{
    function index() {
        $drivers = Driver::latest()->paginate(15);
        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.drivers.create');
    }
    function show($id) {
        $driver = Driver::where('id', $id)->first();
        if ($driver) {
            return view('admin.drivers.show', compact('driver'));
        }
        flash()->addError('driver fail!');
        return redirect()->route('admin.drivers.index');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'license' => 'required|string|max:500',
            'phone' => 'required|string',
            'photo' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'email' => 'required|email|unique:drivers,email|max:255',
            'address' => 'required|max:255',
            'Latitude' => 'nullable',
            'Longitude' => 'nullable',
            'password' => 'required'
        ]);
        // dd($request->all());
        try {
            DB::beginTransaction();
            $driver = new Driver();

            $driver->name = $request->name;
            $driver->license = $request->license;
            $driver->email = $request->email;
            $driver->phone = $request->phone;
            $driver->address = $request->address;
            $driver->Latitude = $request->Latitude ?? '';
            $driver->Longitude = $request->Longitude ?? '';
            $driver->status = 1;
            $driver->password = Hash::make($request->password);

            // Store the photo

            if ($request->file('photo')) {

                $file = $request->file('photo');
                $filename = $driver->license . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/assets/files/drivers/"), $filename);
                $driver->photo = $filename;

            }
            if ($driver->save()) {
                DB::commit();
                flash()->addSuccess('driver created successfully.');
                return redirect()->route('admin.drivers.index');
            }

            DB::commit();
            flash()->addError('driver create fail!');
            return redirect()->route('admin.drivers.index');

            
        } catch (\Exception $ex) {
            DB::rollback();
            flash()->addError('driver create fail!');
            return redirect()->route('admin.drivers.index');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $driver = Driver::where('id', $id)->first();
        if ($driver) {
            return view('admin.drivers.edit', compact('driver'));
        }
        flash()->addError('driver fail!');
        return redirect()->route('admin.drivers.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the driver by ID
        $driver = Driver::find($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'license' => 'required|string|max:500',
            'phone' => 'required|string',
            // 'email' => 'required|email|unique:drivers,email|max:255',
            'email' => 'required|email|unique:drivers,email,' . $driver->id,
            'address' => 'required|max:255',
            'Latitude' => 'nullable',
            'Longitude' => 'nullable',
        ]);
        // dd($request->all());
        try {
            DB::beginTransaction();
            if (!$driver) {
                flash()->addError('driver fail!');
                return redirect()->route('admin.drivers.index');
            }
            
            $driver->name = $request->name;
            $driver->license = $request->license;
            $driver->email = $request->email;
            $driver->phone = $request->phone;
            $driver->address = $request->address;
            $driver->Latitude = $request->Latitude ?? '';
            $driver->Longitude = $request->Longitude ?? '';
            
            // update the photo
            if ($request->file('photo')) {
                // Delete the old photo if it exists
                if ($driver->photo) {
                    $oldFilePath = public_path("/assets/files/drivers/") . $driver->photo;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }

                }
                
                // Save the new photo
                $file = $request->file('photo');
                $filename = $driver->license . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/assets/files/drivers/"), $filename);

                // Update the driver photo path in the database
                $driver->photo = $filename;
                $driver->save();

            }
            if ($driver->save()) {
                DB::commit();
                flash()->addSuccess('driver created successfully.');
                return redirect()->route('admin.drivers.index');
            }

            DB::commit();
            flash()->addError('driver create fail!');
            return redirect()->route('admin.drivers.index');

            
        } catch (\Exception $ex) {
            DB::rollback();
            flash()->addError('driver create fail!');
            return redirect()->route('admin.drivers.index');
        }
    }

    function addCar($id) {
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.cars.create', compact('driver'));
    }
    public function banUnban($id, $status)
    {
        if (auth()->user()->hasRole('Admin')){
            $driver = Driver::findOrFail($id);
            $driver->status = $status;
            if ($driver->save()){
                flash()->addSuccess('driver status updated successfully.');
                return redirect()->back();
            }
            flash()->addError('driver status update fail!');
            return redirect()->back();
        }
        return redirect(Response::HTTP_FORBIDDEN, '403 Forbidden');
    }
}
