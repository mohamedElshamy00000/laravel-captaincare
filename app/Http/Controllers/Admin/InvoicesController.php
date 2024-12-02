<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionInvoice;
use Creatydev\Plans\Models\PlanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Father;
use App\Models\Child;

class InvoicesController extends Controller
{
    // List all invoices
    public function index()
    {
        $invoices = SubscriptionInvoice::with('plan', 'users')->get();
        return view('admin.invoices.index', compact('invoices'));
    }

    // Fetch invoices data for DataTables
    public function getInvoicesData(Request $request)
    {
        if ($request->ajax()) {
            $invoices = SubscriptionInvoice::with('plan', 'users')->select('subscription_invoices.*');

            return DataTables::of($invoices)
                ->addColumn('plan_name', function ($row) {
                    return $row->plan->name ?? 'N/A';
                })
                ->addColumn('user_name', function ($row) {
                    return $row->users->name ?? 'N/A';
                })
                ->addColumn('child_name', function ($row) {
                    return $row->child->name ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return "<span class='badge rounded bg-success'> Paid </span>";
                    } else {
                        return "<span class='badge rounded bg-danger'> unPaid </span>";
                    }
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.subscription.invoice.edit', $row->id);
                    $editButton = '<a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>';
                    return $editButton;
                })
                ->rawColumns(['action','status']) // Ensures HTML output is rendered
                ->make(true);
        }
    }

    // Show create invoice form
    public function create()
    {
        $plans = PlanModel::all();
        $fathers = Father::with('children')->get();
        return view('admin.invoices.create', compact('plans','fathers'));
    }

    // Controller Method
    public function getChildrenByFather($fatherId)
    {
        $father = Father::find($fatherId);

        if ($father) {
            $children = $father->children; // Use the relationship method to get children
            return response()->json($children);
        } else {
            return response()->json(['error' => 'Father not found'], 404);
        }
    }

    // Store new invoice
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'amount' => 'required|integer',
            'plan_id' => 'required|integer',
            'father_id' => 'required|exists:fathers,id', // Validate that user_id exists in the fathers table
            'child_id' => 'required|exists:children,id',
        ]);

        try {
            // Create the invoice
            $invoice = SubscriptionInvoice::create([
                'due_date' => now(),
                'amount' => $request->amount,
                'plan_id' => $request->plan_id,
                'user_id' => $request->father_id,
                'child_id' => $request->child_id
            ]);

            // Redirect with success message
            return redirect()->route('admin.subscription.invoices')->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


    public function edit($id)
    {
        $invoice = SubscriptionInvoice::with('child')->findOrFail($id);
        $fathers = Father::all(); // Fetch fathers for the select input
        $plans = PlanModel::all(); // Fetch plans for the select input
        $children = Child::all(); // Fetch all children for the multi-select input

        return view('admin.invoices.edit', compact('invoice', 'fathers', 'plans', 'children'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer',
            'plan_id' => 'required|integer',
            'user_id' => 'required|exists:fathers,id',
            'status' => 'required|integer',
            'due_date' => 'required|date',
            'child_id' => 'required|exists:children,id',
        ]);

        try {
            $invoice = SubscriptionInvoice::findOrFail($request->id);
            $invoice->update([
                'due_date' => $request->due_date ?? now(),
                'amount' => $request->amount,
                'plan_id' => $request->plan_id,
                'user_id' => $request->user_id,
                'child_id' => $request->child_id,
                'status' => $request->status
            ]);

            return redirect()->route('admin.subscription.invoices')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

}
