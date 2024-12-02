<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\ChildMonthlyPrice;
use Carbon\Carbon;

class ChildrenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $children = Child::all();
        // return view('admin.children.index', compact('children'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.children.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'Latitude' => 'required|numeric',
            'Longitude' => 'required|numeric',
        ]);

        Child::create($validatedData);

        return redirect()->back()->with('success', 'Child data created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Child $child)
    {
        return view('admin.children.show', compact('child'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Child $child)
    {
        return view('admin.children.edit', compact('child'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Child $child)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'Latitude' => 'required|numeric',
            'Longitude' => 'required|numeric',
        ]);

        $child->update($validatedData);

        return redirect()->back()->with('success', 'Child data updated successfully');
    }

    public function storePrice(Request $request, Child $child)
    {
        $request->validate([
            'price' => 'required|numeric|min:0'
        ]);

        ChildMonthlyPrice::updateOrCreate(
            [
                'child_id' => $child->id,
            ],
            ['price' => $request->price]
        );

        return redirect()->back()
            ->with('success', 'Price added successfully for ' . $child->name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Child $child)
    {
        $child->delete();

        return redirect()->route('admin.children.index')->with('success', 'Child data deleted successfully');
    }
}
