
@extends('layouts.backend')
@section('title')
    Plan
@endsection
@section('content')
<div class="row mb-4 g-4">
    <div class="col-md-12">
        <div class="card h-100">
            <div class="card-body row">
                <div class="col-7 border-shift border-end">
                    <div class="">
                        <h4 class="mb-2 text-nowrap">{{ $plan->name }}</h4>
                        <h6><span class="badge bg-success mb-2">price : {{ $plan->price }} EGP</span> / {{ $plan->duration }} Day</h6>
                        <p class="mb-2"> <span class="me-2">{!! $plan->description !!}</span></p>

                        <p class="mt-4 small text-uppercase text-muted">meta data</p>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                @foreach ($plan->metadata as $key => $value)
                                <li class="mb-2">
                                    <span class="fw-medium me-1">{{ $key }} :</span>
                                    <span>{{ $value }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 ">
                    @if($plan->subscriptions)
                        <h2 class="text-primary d-flex align-items-center gap-1 mb-2">

                            {{ $plan->subscriptions->count() }}
                            <p class="h5 mb-1"> : Total Subscription</p>

                        </h2>
                        <h2 class="text-primary d-flex align-items-center gap-1 mb-2">{{ $plan->subscriptions->where('starts_on', '<', Carbon\Carbon::now())->where('expires_on', '>', Carbon\Carbon::now())->count() }} <p class="h5 mb-1"> : Active</p></h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Users List Table -->
<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between">
        <h5 class="card-title mb-0">Plan Feature</h5>
        <a href="{{ route('admin.subscription.create.feature',$plan->id) }}" class="btn btn btn-outline-dark waves-effect btn-sm">Add Feature</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table border-top" id="planF">
            <thead>
                <tr>
                    <th>name</th>
                    <th>code</th>
                    <th>description</th>
                    <th>type</th>
                    <th>limit</th>
                    <th>action</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

<div class="card mt-4">
    <div class="card-header border-bottom d-flex justify-content-between">
        <h5 class="card-title mb-0">Plan Subscriptions</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table border-top" id="planS">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Pay status</th>
                    <th>Price</th>
                    <th>Is active</th>
                    <th>Starts</th>
                    <th>Expires</th>
                    <th>Cancelled</th>
                    <th>action</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

@endsection

@section('scripts')
<script>
// Datatable (jquery)
$(document).ready( function () {

    var planFeature = $('#planF');

    if (planFeature.length) {
        var dt_Feature = planFeature.DataTable({
        ajax: "{{ route('admin.subscription.getPlansFeature', $plan->id) }}", // JSON
        columns: [
            // columns according to JSON
            { data: 'name' },
            { data: 'code' },
            { data: 'description' },
            { data: 'type' },
            { data: 'limit' },
            { data: 'action' }
        ],

        order: [[1, 'desc']],

        });
    }

    var planSubscriptions = $('#planS');
    if (planSubscriptions.length) {
        var dt_Subscription = planSubscriptions.DataTable({

            ajax: "{{ route('admin.subscription.getPlanSubscriptions', $plan->id) }}", // JSON
            columns: [

                { data: 'user' },
                { data: 'payment_status' },
                { data: 'price'},
                { data: 'is_active' },
                { data: 'starts' },
                { data: 'expires' },
                { data: 'cancelled' },
                { data: 'action' },

            ],
            order: [[4, 'desc']],

        });
    }
    // Filter form control to default size
    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);
});
</script>
@endsection
