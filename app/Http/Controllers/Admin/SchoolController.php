<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Child;
use App\Models\Group;
use App\Models\School;
use Illuminate\Http\Request;
use App\Models\GroupChildren;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class SchoolController extends Controller
{
    function index() {
        $schools = School::latest()->paginate(15);
        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        // abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.schools.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string',
            'email' => 'required|email|unique:schools,email|max:255',
            'Latitude'      => 'required',
            'Longitude'     => 'required',
        ]);

        $school = new School();

        $school->name = $request->name;
        $school->address = $request->address;
        $school->email = $request->email;
        $school->phone_number = $request->phone;
        $school->Latitude = $request->Latitude;
        $school->Longitude = $request->Longitude;

        if ($school->save()) {
            flash()->addSuccess('School created successfully.');
            return redirect()->route('admin.schools.index');
        }
        flash()->addError('School create fail!');
        return redirect()->route('admin.schools.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $school = School::where('id', $id)->first();
        $groups = Group::where('school_id', $school->id)->with('children')->orderBy('id', 'desc')->paginate(10);

        if($school){
            return view('admin.schools.show', compact('school','groups'));
        }else{
            flash()->addError('School fail!');
            return redirect()->route('admin.schools.index');
        }
    }


    public function getGroups($id){

        $data = Group::where('id', $id)->orderBy('id', 'desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('name', function($row){
                if ($row->name == null) {
                    return 'null';
                } else {
                    return $row->name;
                }
            })
            ->addColumn('description', function($row){
                if ($row->description == null) {
                    return 'null';
                } else {
                    return $row->description;
                }
            })
            ->addColumn('date', function($row){
                return $row->created_at->format('d-m-y');
            })

            ->addColumn('action', function($row){

                if ($row->status == 0 ) {
                    $actionBtn = '<a href="'. '#' . '" class="edit btn btn-outline-info waves-effect btn-sm">'. __('general.Pay Now') .'</a> ';
                    return $actionBtn;
                } else {
                    
                }
            })
            ->addColumn('status', function($row){
                if ($row->status == 0) {
                    return '<span class="badge badge-pill badge-soft-danger font-size-11">' .  __('general.pending') . '</span>';
                } elseif($row->status == 1){
                    return '<span class="badge badge-pill badge-soft-success font-size-11">' .  __('general.accepted'). '</span>';
                }
                
            })
            ->addColumn('details', function ($row) {
                return '<a href="#" class="details-control" data-id="' . $row->id . '">Details</a>';
            })
            ->rawColumns(['name', 'description', 'date', 'action','status','details'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $school = School::where("id",$id)->first();
        return view('admin.schools.edit', compact('school'));

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
        // تحقق من صحة البيانات المدخلة
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:schools,email,' . $id . '|max:255',
            'Latitude' => 'required',
            'Longitude' => 'required',
        ]);
    
        // جلب بيانات المدرسة الموجودة
        $school = School::findOrFail($id);
    
        // تحديث بيانات المدرسة
        $school->name = $request->name;
        $school->address = $request->address;
        $school->email = $request->email;
        $school->phone_number = $request->phone_number;
        $school->Latitude = $request->Latitude;
        $school->Longitude = $request->Longitude;
    
        // حفظ التعديلات
        if ($school->save()) {
            // عرض رسالة نجاح
            flash()->addSuccess('School updated successfully.');
            return redirect()->route('admin.schools.index');
        }
    
        // عرض رسالة فشل
        flash()->addError('School update failed!');
        return redirect()->route('admin.schools.index');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function banUnban($id, $status)
    {
        if (auth()->user()->hasRole('Admin')){
            $school = School::findOrFail($id);
            $school->status = $status;
            if ($school->save()){
                flash()->addSuccess('School status updated successfully.');
                return redirect()->back();
            }
            flash()->addError('School status update fail!');
            return redirect()->back();
        }
        return redirect(Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

}
