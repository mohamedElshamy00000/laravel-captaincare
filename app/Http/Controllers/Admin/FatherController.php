<?php

namespace App\Http\Controllers\Admin;

use App\Models\Father;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionInvoice;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;


class FatherController extends Controller
{
    function index() {
        $fathers = Father::latest()->paginate(15);
        return view('admin.fathers.index', compact('fathers'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.fathers.create');
    }
    function show($id) {
        $father = Father::where('id', $id)->first();
        $invoices = SubscriptionInvoice::where('user_id', $father->id)->paginate(10);
        if ($father) {
            return view('admin.fathers.show', compact('father','invoices'));
        }
        flash()->addError('father fail!');
        return redirect()->route('admin.fathers.index');
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
            'state' => 'required|string|max:500',
            'city' => 'required|string|max:500',
            'phone' => 'required|string',
            'email' => 'required|email|unique:fathers,email|max:255',
            'Latitude' => 'nullable',
            'Longitude' => 'nullable',
            'password' => 'required'
        ]);
        // dd($request->all());
        // try {
            DB::beginTransaction();
            $father = new Father();

            $father->name = $request->name;
            $father->state = $request->state;
            $father->city = $request->city;
            $father->email = $request->email;
            $father->phone = $request->phone;
            $father->Latitude = $request->Latitude ?? '';
            $father->Longitude = $request->Longitude ?? '';
            $father->status = 1;
            $father->password = Hash::make($request->password);

            if ($father->save()) {
                DB::commit();
                flash()->addSuccess('father created successfully.');
                return redirect()->route('admin.fathers.index');
            }

            DB::commit();
            flash()->addError('father create fail!');
            return redirect()->route('admin.fathers.index');

            
        // } catch (\Exception $ex) {
        //     DB::rollback();
        //     flash()->addError('father create fail!');
        //     return redirect()->route('admin.fathers.index');
        // }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $father = Father::where('id', $id)->first();
        if ($father) {
            return view('admin.fathers.edit', compact('father'));
        }
        flash()->addError('father fail!');
        return redirect()->route('admin.fathers.index');
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
        // Find the father by ID
        $father = Father::find($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:500',
            'city' => 'required|string|max:500',
            'phone' => 'required|string',
            'email' => 'required|email|unique:fathers,email,' . $father->id,
            'Latitude' => 'nullable',
            'Longitude' => 'nullable',
        ]);
        // dd($request->all());
        try {
            DB::beginTransaction();
            if (!$father) {
                flash()->addError('father fail!');
                return redirect()->route('admin.fathers.index');
            }
            
            $father->name = $request->name;
            $father->state = $request->state;
            $father->city = $request->city;
            $father->email = $request->email;
            $father->phone = $request->phone;
            $father->Latitude = $request->Latitude ?? '';
            $father->Longitude = $request->Longitude ?? '';
            $father->status = 1;
            if ($father->password != null) {
                $father->password = Hash::make($request->password);
            }

            if ($father->save()) {
                DB::commit();
                flash()->addSuccess('father created successfully.');
                return redirect()->route('admin.fathers.index');
            }

            DB::commit();
            flash()->addError('father create fail!');
            return redirect()->route('admin.fathers.index');

            
        } catch (\Exception $ex) {
            DB::rollback();
            flash()->addError('father create fail!');
            return redirect()->route('admin.fathers.index');
        }
    }

    function addChild($id) {
        $father = Father::findOrFail($id);
        return view('admin.fathers.create-child', compact('father'));
    }
    public function banUnban($id, $status)
    {
        if (auth()->user()->hasRole('Admin')){
            $father = Father::findOrFail($id);
            $father->status = $status;
            if ($father->save()){
                flash()->addSuccess('father status updated successfully.');
                return redirect()->back();
            }
            flash()->addError('father status update fail!');
            return redirect()->back();
        }
        return redirect(Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

}
