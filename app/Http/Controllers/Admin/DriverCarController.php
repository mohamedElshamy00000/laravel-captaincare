<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class DriverCarController extends Controller
{
    
    function store(Request $request)
    {
        // dd( $request->all());
        $validatedData = $request->validate([
            'make'    => 'required|string|max:255',
            'license' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'model'   => 'required|string',
            'photo'   => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // dd($request->all());
        try {
            DB::beginTransaction();
            $dCar = new Car();
            
            $dCar->make = $request->make;
            $dCar->license = $request->license;
            $dCar->model = $request->model;
            $dCar->photo = $request->photo;
            $dCar->driver_id = $request->id;
            $dCar->status = 1;

            // Store the photo
            if ($request->file('photo')) {

                $file = $request->file('photo');
                $filename = $dCar->model . now()  . 'photo' . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/assets/files/drivers/car"), $filename);
                $dCar->photo = $filename;

            }
            // Store the license
            if ($request->file('license')) {

                $file = $request->file('license');
                $licenseName = $dCar->model . now() . 'License' . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/assets/files/drivers/car"), $licenseName);
                $dCar->license = $licenseName;
            }
            if ($dCar->save()) {
                DB::commit();
                flash()->addSuccess('driver Car created successfully.');
                return redirect()->route('admin.drivers.index');
            }

            DB::commit();
            flash()->addError('driver Car create fail!');
            return redirect()->route('admin.drivers.index');

            
        } catch (\Exception $ex) {
            DB::rollback();
            flash()->addError('driver Car create fail!');
            return redirect()->route('admin.drivers.index');
        }
        
    }

    function edit($id) {
        $car = Car::where('id', $id)->first();
        $driver = $car->driver;
        return view('admin.drivers.cars.edit', compact('car', 'driver'));
    }
    function update(Request $request, $id)
    {
        // dd( $request->all());
        $validatedData = $request->validate([
            'make'    => 'required|string|max:255',
            'license' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'model'   => 'required|string',
            'photo'   => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // dd($request->all());
        try {
            DB::beginTransaction();
            $dCar = Car::where('id', $id)->first();
            if (!$dCar) {
                flash()->addError('Car fail!');
                return redirect()->route('admin.drivers.index');
            }
            $dCar->make = $request->make;
            $dCar->license = $request->license;
            $dCar->model = $request->model;
            $dCar->photo = $request->photo;
            $dCar->driver_id = $request->id;
            $dCar->status = $request->status;

            // update the photo
            if ($request->file('photo')) {
                // Delete the old photo if it exists
                if ($dCar->photo) {
                    $oldFilePath = public_path("/assets/files/drivers/car") . $dCar->photo;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }

                }
                
                // Save the new photo
                $file = $request->file('photo');
                $filename = $dCar->model . 'photo' . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/assets/files/drivers/car"), $filename);

                // Update the driver photo path in the database
                $dCar->photo = $filename;
                $dCar->save();

            }

            // update the license
            if ($request->file('license')) {
                // Delete the old photo if it exists
                if ($dCar->license) {
                    $oldFilePath = public_path("/assets/files/drivers/car") . $dCar->license;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }

                }
                
                // Save the new license
                $file = $request->file('license');
                $filename = $dCar->model . 'License' . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/assets/files/drivers/car"), $filename);

                // Update the driver license path in the database
                $dCar->license = $filename;
                $dCar->save();

            }

            if ($dCar->save()) {
                DB::commit();
                flash()->addSuccess('driver Car created successfully.');
                return redirect()->route('admin.drivers.index');
            }

            DB::commit();
            flash()->addError('driver Car create fail!');
            return redirect()->route('admin.drivers.index');

            
        } catch (\Exception $ex) {
            DB::rollback();
            flash()->addError('driver Car create fail!');
            return redirect()->route('admin.drivers.index');
        }
        
    }
}
