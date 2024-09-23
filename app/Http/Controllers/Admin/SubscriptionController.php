<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Father;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Creatydev\Plans\Models\PlanModel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Creatydev\Plans\Models\PlanFeatureModel;
use Creatydev\Plans\Models\PlanSubscriptionModel;

class SubscriptionController extends Controller
{
    function index() {
        return view('admin.subscription.plans');
    }
    public function getPlans()
    {
        $data = PlanModel::orderBy('created_at', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                $actionBtn  = '<a href="' . route('admin.subscription.plans.details' ,$row->id) . '" class="text-primary fw-bold">'.$row->name.'</a> ';
                return $actionBtn;

            })
            ->addColumn('description', function($row){
                return substr($row->description, 0, 15) . '...';
            })
            ->addColumn('price', function($row){
                return $row->price . __('general.EGP');
            })
            ->addColumn('duration', function($row){
                return $row->duration .   __('general.days');
            })
            ->addColumn('created_at', function($row){
                return $row->created_at->format('d-m-Y');
            })

            ->addColumn('action', function($row){
                $actionBtnF = '<a href="' . route('admin.subscription.create.feature',$row->id) . '" class="btn btn btn-outline-dark waves-effect btn-sm">'. __('general.Add Feature') .'</a>';
                $actionBtnp = '<a href="' . route('admin.subscription.edit.plan',$row->id) . '" class="btn btn btn-outline-dark waves-effect btn-sm">'. __('general.edit') .'</a>';
                return $actionBtnF . ' ' . $actionBtnp;
            })
            ->rawColumns(['name','action','created_at','duration','price','description'])
            ->make(true);
    }
    public function createPlan()
    {
        return view('admin.subscription.createPlan');
    }
    public function storePlan(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name'         => 'required|string',
            'description'  => 'required|string',
            'price'        => 'required',
            'duration'     => 'required',
        ]);

        if ($validation->fails()) {

            return redirect()->back()->withErrors($validation)->withInput();
        }

        try {

            $metadata = [];
            foreach ($request->key as $index => $key) {
                $value = isset($request->value[$index]) ? $request->value[$index] : '';
                $metadata[$key] = $value;
            }

            // dd($metadata);
            $plan = PlanModel::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'currency' => 'EGP',
                'duration' => $request->duration, // in days
                'metadata' => $metadata ?? null,
            ]);

            return redirect()->back()->with([
                'message' =>  __('message.plan_added'),
                'alert' => 'success'
            ]);

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect()->back()->with([
                'message' =>  __('message.error_occurred'),
                'alert' => 'danger'
            ]);

        }

    }
    public function editPlan($id)
    {
        $plan = PlanModel::where('id', $id)->first();
        return view('admin.subscription.editPlan', compact('plan'));
    }
    public function updatePlan(Request $request, $id)
    {

        $validation = Validator::make($request->all(), [
            'name'         => 'required|string',
            'description'  => 'required|string',
            'price'        => 'required',
            'duration'     => 'required',
        ]);

        if ($validation->fails()) {

            return redirect()->back()->withErrors($validation)->withInput();
        }

        try {

            $metadata = [];
            foreach ($request->key as $index => $key) {
                $value = isset($request->value[$index]) ? $request->value[$index] : '';
                $metadata[$key] = $value;
            }

            // dd($metadata);
            $plan = PlanModel::where('id',$id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'currency' => 'EGP',
                'duration' => $request->duration, // in days
                'metadata' => $metadata,
            ]);

            return redirect()->back()->with([
                'message' =>  __('message.plan_updated'),
                'alert' => 'success'
            ]);

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect()->back()->with([
                'message' =>  __('message.error_occurred'),
                'alert' => 'danger'
            ]);

        }

    }

    public function getPlansDetails($id)
    {
        $user = auth()->user();
        // $Plan = PlanFeatureModel::where('id',$id)->first();
        $plan = PlanModel::where('id',$id)->first();
        return view('admin.subscription.planDetails', compact('plan'));

    }

    public function addFeaturetoPlan($id)
    {
        $plan = PlanModel::where('id',$id)->first();
        return view('admin.subscription.createFeature', compact('plan'));
    }
    public function storeFeature(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'         => 'required|string',
            'description'  => 'required|string',
            'code'         => 'required',
            'type'         => 'required',
            'limit'        => 'nullable',
        ]);

        if ($validation->fails()) {

            return redirect()->back()->withErrors($validation)->withInput();
        }

        try {
            $plan = PlanModel::where('id',$id)->first();
            $metadata = [];

            foreach ($request->key as $index => $key) {
                $value = isset($request->value[$index]) ? $request->value[$index] : '';
                $metadata[$key] = $value;
            }
            // dd($metadata);

            $plan->features()->saveMany([
                new PlanFeatureModel([
                    'name' => $request->name,
                    'description' => $request->description,
                    'code' => $request->code,
                    'type' => $request->type,
                    'limit' => $request->limit ?? null,
                    'metadata' => $metadata,
                ]),
            ]);

            return redirect()->back()->with([
                'message' => __('message.added_successfully'),
                'alert' => 'success'
            ]);

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect()->back()->with([
                'message' => __('message.error_occurred'),
                'alert' => 'danger'
            ]);

        }

    }
    public function editFeature($id)
    {
        $feature = PlanFeatureModel::where('id',$id)->first();
        return view('admin.subscription.editFeature', compact('feature'));
    }

    public function updateFeature(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'         => 'required|string',
            'description'  => 'required|string',
            'code'         => 'required',
            'type'         => 'required',
            'limit'        => 'nullable',
        ]);

        if ($validation->fails()) {

            return redirect()->back()->withErrors($validation)->withInput();
        }

        try {
            $feature = PlanFeatureModel::where('id',$id)->first();
            $metadata = [];

            foreach ($request->key as $index => $key) {
                $value = isset($request->value[$index]) ? $request->value[$index] : '';
                $metadata[$key] = $value;
            }
            // dd($metadata);

            $feature->update([
                'name' => $request->name,
                'description' => $request->description,
                'code' => $request->code,
                'type' => $request->type,
                'limit' => $request->limit ?? null,
                'metadata' => $metadata,
            ]);

            return redirect()->back()->with([
                'message' => __('message.edit_successfully'),
                'alert' => 'success'
            ]);

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect()->back()->with([
                'message' => __('message.error_occurred'),
                'alert' => 'danger'
            ]);

        }
    }
    public function getPlanFeature($id)
    {
        $data = PlanFeatureModel::where('plan_id',$id)->get();
        // dd($data);
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('name', function($row){
            $actionBtn  = '<a href="' . route('admin.subscription.plans.details' ,$row->id) . '" class="text-dark">'.$row->name.'</a> ';
            return $actionBtn;

        })
        ->addColumn('description', function($row){
            return $row->description;
        })
        ->addColumn('code', function($row){
            return $row->code;
        })
        ->addColumn('type', function($row){
            return $row->type;
        })
        ->addColumn('limit', function($row){
            return $row->limit;
        })
        // ->addColumn('metadata', function($row){
        //     return $row->metadata;
        // })
        ->addColumn('action', function($row){
            $actionBtnF = '<a href="' . route('admin.subscription.edit.feature',$row->id) . '" class="btn btn btn-outline-dark waves-effect btn-sm">'.__('general.edit').'</a>';
            return $actionBtnF;
        })
        ->rawColumns(['name','code','limit','type','description','action'])
        ->make(true);

    }
    public function getPlanSubscriptions($id)
    {

        $plan = PlanModel::where('id',$id)->first();
        $data = $plan->subscriptions ?? [];
        // dd($plan->subscriptions);
        // '. route('admin.users.details', $row->model->id) .'
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('user', function($row){
            $actionBtn  = '<div class="me-2">
            <div class="d-flex align-items-center">
              <a href=""><h6 class="mb-0 me-1">'. $row->model->name .'</h6></a>
            </div>
            <small class="text-muted">'.$row->model->email.'</small>
          </div>';

            return $actionBtn;

        })
        ->addColumn('payment_status', function($row){
            if ($row->is_paid == 1) {
                return '<span class="badge bg-success ms-auto ">'.__('general.paid').'</span>';
            } elseif($row->is_paid == 0){
                return '<span class="badge bg-info ms-auto ">'.__('general.unpaid').'</span>';
            }
        })
        ->addColumn('is_active', function($row){
            if ($row->isActive() == 1) {
                return '<span class="badge bg-success ms-auto ">'.__('general.Active').'</span>';
            } elseif($row->isActive() == 0){
                return '<span class="badge bg-danger ms-auto ">'. __('general.unActive').'</span>';
            }
        })
        ->addColumn('price', function($row){
            return $row->charging_price .' <sup>'. $row->charging_currency . '</sup>';
        })
        ->addColumn('starts', function($row){
            return $row->starts_on ? Carbon::parse($row->starts_on)->format('Y-m-d') : '';
        })
        ->addColumn('expires', function($row){
            return $row->expires_on ? Carbon::parse($row->expires_on)->format('Y-m-d') : '';
        })
        ->addColumn('cancelled', function($row){
            if ($row->cancelled_on) {
                return '<span class="badge bg-danger ms-auto ">'. Carbon::parse($row->cancelled_on)->format('Y-m-d') .'</span>';
            } else {
                return '';
            }
        })
        ->addColumn('action', function($row){
            $cancelBtn = '<a href="'. route('admin.subscription.cancel', $row->id) .'" class="btn btn btn-outline-danger waves-effect btn-sm">'. __('general.cancel').'</a>';
            $acticeBtn = '<a href="'. route('admin.subscription.active', $row->id) .'" class="btn btn btn-outline-success waves-effect btn-sm">'. __('general.Active').'</a>';

            $lastActiveSubscription = $row->model->lastActiveSubscription();
            // dd($row->user->hasActiveSubscription());
            if ($row->isCancelled()) {
                if ($row->isPendingCancellation()) {
                    return $acticeBtn;
                }
            } else {
                return $cancelBtn;
            }

            return '';
        })
        ->rawColumns(['user','payment_status','is_active','price','starts','expires','cancelled','action'])
        ->make(true);
    }

    public function cancelSubscriptions($id)
    {
        $subscription = PlanSubscriptionModel::where('id',$id)->first();
        if ($subscription->model->hasActiveSubscription()) {

            $subscription->update([
                'cancelled_on' => Carbon::now(),
                'is_recurring' => 0
            ]);
            return redirect()->back()->with([
                'message' => __('message.canceled_successfully'),
                'alert' => 'success'
            ]);
        } else {
            return redirect()->back()->with([
                'message' => __('message.error_occurred'),
                'alert' => 'danger'
            ]);
        }
    }

    public function activeSubscriptions($id)
    {
        $subscription = PlanSubscriptionModel::where('id',$id)->first();
        // dd($subscription->isPendingCancellation());
        if ($subscription->isCancelled()) {
            if ($subscription->isPendingCancellation()) {
                $subscription->update([
                    'cancelled_on' => null,
                    'is_recurring' => 1
                ]);
                return redirect()->back()->with([
                    'message' => __('message.package_activated'),
                    'alert' => 'success'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'message' =>  __('message.error_occurred'),
                'alert' => 'danger'
            ]);
        }
    }
}