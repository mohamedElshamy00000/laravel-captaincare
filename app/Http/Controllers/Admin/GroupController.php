<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Child;
use App\Models\Group;
use App\Models\Driver;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Models\GroupChildren;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    function index() {
        return view('admin.groups.index');
    }

    function show($id) {
        $group = Group::where('id', $id)->first();
        $children = Child::where('school_id', $group->school->id)->with('fathers')->get();
        if ($group) {
            $myDriver = $group->driver;
            return view('admin.groups.show', compact('group','myDriver','children'));
        } else{
            flash()->addError('fail!');
            return redirect()->back();

        }
    }

    function showAllGroups() {
        $groups = Group::with('school', 'driver', 'children')->get();
        if ($groups) {
            return view('admin.groups.all-groups', compact('groups'));
        } else{
            flash()->addError('fail!');
            return redirect()->back();

        }
    }

    public function getGroups()
    {
        // $data = GroupChildren::where('group_id', $groupId)->with('student')->orderBy('id', 'desc')->get();
        $data = Group::with('children','driver','school')->orderBy('id', 'desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return '<a href="'.route('admin.groups.show',$row->id).'" >'.$row->name.'</a>' ?? 'null';
            })
            ->addColumn('description', function ($row) {
                return $row->description ?? 'null';
            })
            ->addColumn('driver', function ($row) {
                return $row->driver->name ?? 'null';
            })
            ->addColumn('school', function ($row) {
                return $row->school->name ?? 'null';
            })
            ->addColumn('date', function ($row) {
                return $row->created_at->format('d-m-y');
            })
            ->addColumn('status', function ($row) {
                if($row->status == 1){
                    $badge = '<span class="badge badge-pill badge-soft-success font-size-12">Active</span>';
                }
                else {
                    $badge = '<span class="badge badge-pill badge-soft-danger font-size-12">blocked</span>';
                }
                return $badge;

            })
            ->addColumn('action', function($row){

                if($row->status == 1){
                    $btn = '<a href="'.route("admin.groups.banUnban", ["id" => $row->id, "status" => 0]).'" class="badge bg-danger">UnActive</a>';
                }
                else {
                    $btn = '<a href="'.route("admin.groups.banUnban", ["id" => $row->id, "status" => 1]).'" class="badge bg-info">Active</a>';
                }
                return $btn;
            })
            ->rawColumns(['name','description','driver','school','date','status','action'])
            ->make(true);
    }
    public function getGroupDetails($groupId)
    {
        // $data = GroupChildren::where('group_id', $groupId)->with('student')->orderBy('id', 'desc')->get();
        $data = Group::where('id', $groupId)->with('children')->orderBy('id', 'desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->children ?? 'null';
            })
            ->addColumn('age', function ($row) {
                return $row->children->age ?? 'null';
            })
            ->addColumn('status', function ($row) {
                return $row->status ?? 'null';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-y');
            })
            ->make(true);
    }

    function createGroup() {
        // abort_if(Gate::denies('group_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $drivers  = Driver::all();
        $myDriver = Driver::first();
        return view('admin.groups.create', compact('drivers','myDriver'));
    }

    public function getSchools(Request $request)
    {
        $schools = School::where('status', 1)->get();
        return response()->json($schools);
    }

    public function getChildren(Request $request)
    {
        $schoolId = $request->get('school_id');
        $children = Child::where('school_id', $schoolId)->get();
        return response()->json($children);
    }

    public function getClasses(Request $request)
    {
        $schoolId = $request->get('school_id');
        $classe = SchoolClass::where('school_id', $schoolId)->get();
        return response()->json($classe);
    }

    // function create($id) {
    //     abort_if(Gate::denies('group_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    //     $school = School::where('id', $id)->first();
    //     $schoolClasses = $school->classes;
    //     // dd($school->classes);

    //     $childrens     = Child::where('school_id', $school->id)->where('status', 1)->get();
    //     $drivers       = Driver::all();
    //     $myDriver = Driver::first();
    //     return view('admin.schools.create-group', compact('childrens','drivers','school','schoolClasses','myDriver'));
    // }
    function store(Request $request) {

        // return $request->all();
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'driver' => 'required',
            'childrens' => 'required',
            'school_id' => 'required',
        ]);

        // try {
        //     DB::beginTransaction();

            $group = new Group();

            $group->name = $request->name;
            $group->description = $request->description;
            $group->driver_id = $request->driver;
            $group->school_id = $request->school_id;
            $group->waypoints = $request->waypoints ?: null; // تعديل هنا
            if ($group->save()) {
                foreach ($request->childrens as $childId) {
                    $group->children()->attach($childId);
                }

                // DB::commit();
                flash()->addSuccess('Group created successfully.');
                return redirect()->route('admin.groups.index');
            }

            // DB::commit();

            flash()->addError('Group create fail!');
            return redirect()->route('admin.groups.index');


        // } catch (\Exception $ex) {
        //     DB::rollback();
        //     flash()->addError('Group create fail!');
        //     return redirect()->back();
        // }

    }

    function addChild(Request $request ,$group_id) {
        // dd($request->input('child'));
        if ($group_id){
            $group = Group::findOrFail($group_id);
            $childIds = $request->input('child');
            if ($group){
                $group->children()->syncWithoutDetaching($childIds);
                flash()->addSuccess('child added successfully.');
                return redirect()->back();
            }
            flash()->addError('fail!');
            return redirect()->back();
        }
    }

    function deleteChildFromGroup(Request $request) {
        $group = Group::find($request->input('group_id'));
        $childId = $request->input('child_id');

        if ($group) {
            // Detach children from the group
            $group->children()->detach($childId);
            flash()->addSuccess('child added successfully.');
            return redirect()->back();
        }
        flash()->addError('fail!');
        return redirect()->back();

    }
    public function banUnban($id, $status)
    {
        if (auth()->user()->hasRole('Admin')){
            $Group = Group::findOrFail($id);
            $Group->status = $status;
            if ($Group->save()){
                flash()->addSuccess('Group status updated successfully.');
                return redirect()->back();
            }
            flash()->addError('Group status update fail!');
            return redirect()->back();
        }
        return redirect(Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

}
