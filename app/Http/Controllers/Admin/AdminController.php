<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $count['users'] = User::count();

        $count['groups'] = Group::count();
        $count['trips']  = Trip::count();
        return view('admin.index', compact('count'));
    }
}
