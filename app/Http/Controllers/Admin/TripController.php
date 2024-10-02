<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
class TripController extends Controller
{
    public function index()
    {
        return view('admin.trips.index');
    }

    // get all trips
    public function getTrips(Request $request)
    {
        $trips = Trip::with('driver','group')->get();
        // dd($trips);
        return datatables()->of($trips)
            ->addColumn('action', function ($trip) {
                return '<a href="'.'route'.'" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->editColumn('created_at', function ($trip) {
                return $trip->created_at->format('Y-m-d');
            })
            ->editColumn('trip_date', function ($trip) {
                return $trip->trip_date;
            })
            ->editColumn('description', function ($trip) {
                return $trip->description;
            })
            ->editColumn('driver_id', function ($trip) {
                return $trip->driver->name;
            })
            ->editColumn('status', function ($trip) {
                return $trip->status;
            })
            ->editColumn('trip_type', function ($trip) {
                return $trip->trip_type;
            })
            ->editColumn('group_id', function ($trip) {
                return $trip->group->name;
            })
            ->make(true);
    }
}
