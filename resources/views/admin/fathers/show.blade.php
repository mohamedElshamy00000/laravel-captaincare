@extends('layouts.backend')
@section('styles')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JavaScript -->

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

@endsection
@section('content')


<div class="row">
    <div class="row">
        <!-- end col -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1 overflow-hidden">
                            <h2 class="text-truncate font-size-30">{{ $father->name }} </h2>
                            <p class="mb-0">
                                Status : 
                                @if ($father->status == 1)
                                    <span class="badge badge-pill badge-soft-success font-size-11">active</span>
                                @else
                                    <span class="badge badge-pill badge-soft-danger font-size-11">not active</span>
                                @endif
                            </p>
                        </div>
                    </div>
    
                    <h5 class="font-size-15 mt-4">Details :</h5>
    
                    <p class="text-muted"> <strong>State </strong> {{$father->state}} <strong> City </strong> {{  $father->city }}</p>
    
                    <div class="text-muted mt-4 mb-0 pb-0">
                        <p><i class="mdi mdi-chevron-right text-primary me-1"></i> {{ $father->email }}</p>
                        <p><i class="mdi mdi-chevron-right text-primary me-1"></i> {{ $father->phone }}</p>
                        
                            @if ($father->hasActiveSubscription())
                                @php
                                    $Subscription = $father->activeSubscription();
                                @endphp
                                <ul class="list-inline mb-0">
                                    <li><i class="mdi mdi-chevron-right text-primary me-1"></i> Subscription :</li>
                                    <li class="list-inline-item me-1">
                                        <span class="badge bg-success px-2 py-1">{{ $Subscription->plan->name }}</span>
                                    </li>
                                    <li class="list-inline-item me-1">
                                        <h5 class="font-size-14" data-toggle="tooltip" data-placement="top" title="Amount"><i class="bx bx-money me-1 text-muted"></i>{{ $Subscription->plan->price }} {{ $Subscription->plan->currency }}</h5>
                                    </li>
                                    <li class="list-inline-item">
                                        <h5 class="font-size-14" data-toggle="tooltip" data-placement="top" title="Due Date">/ {{ $Subscription->plan->duration }} Day</h5>
                                    </li>
                                </ul>
                            @else
                                Not Active Subscription
                            @endif
                        
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div id="map" style="height:245px" class="rounded"></div>
            </div>
        </div>
    </div>

    <!-- end col -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">children</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Age</th>
                                    <th scope="col">Class</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($father->children as $child)
                                    
                                <tr>
                                    <td>
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-dark">
                                                {{ substr($child->name, 0,2) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{ $child->name }}</a></h5>
                                        <p class="text-muted mb-0">{{ $child->school->name }}</p>
                                    </td>
                                    <td>{{ $child->phone }}</td>
                                    <td>
                                        {{ $child->age }}
                                    </td>
                                    <td>
                                        <div>
                                            <a href="javascript: void(0);" class="badge badge-soft-primary font-size-11 m-1">Class {{ $child->classe->name }}</a>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($child->status == 1)
                                        <span class="badge badge-pill badge-soft-success font-size-11">Active</span>
                                        @else
                                        <span class="badge badge-pill badge-soft-danger font-size-11">Not Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="list-inline font-size-20 contact-links mb-0">
                                            <li class="list-inline-item px-2">
                                                <a href="{{ route('admin.children.edit', $child->id) }}" title="Edit"><i class="bx bx-edit"></i></a>
                                            </li>
                                            <li class="list-inline-item px-2">
                                                <a href="{{ route('admin.children.show', $child->id) }}" title="Profile"><i class="bx bx-user-circle"></i></a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Invoices</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">transaction_id</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Refunded</th>
                                    <th scope="col">payment way</th>
                                    <th scope="col">discount</th>
                                    <th scope="col">comment</th>
                                    <th scope="col">plan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>
                                            {{ $invoice->transaction_id }}
                                        </td>
                                        <td>
                                            <h5 class="font-size-14 mb-1">{{ $invoice->amount }}</h5>
                                        </td>
                                        <td>
                                            {{ $invoice->due_date }}
                                        </td>
                                        
                                        <td>
                                            @if ($invoice->refunded == 1)
                                                <span class="badge badge-pill badge-soft-danger font-size-11">refunded</span>
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            {{ $invoice->payment_way }}
                                        </td>
                                        <td>
                                            {{ $invoice->discount ?? '--' }}
                                        </td>
                                        <td>
                                            {{ $invoice->comment }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.subscription.plans.details', $invoice->plan->id) }}">{{ $invoice->plan->name }}</a>
                                        </td>
                                        <td>
                                            @if ($invoice->status == 1)
                                                <span class="badge badge-pill badge-soft-success font-size-11">Paid</span>
                                            @else
                                                <span class="badge badge-pill badge-soft-danger font-size-11">Un Paid</span>
                                            @endif
                                        </td>
                                        <td>
                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                @if ($invoice->status == 0)
                                                    <li class="list-inline-item px-2">
                                                        <a href="{{ route('admin.children.edit', $invoice->id) }}" title="assign"><i class="bx bx-money"></i></a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $invoices->links('vendor.pagination.bootstrap-5') }}

                </div>
            </div>
        </div>
    </div>
    


</div>
<!-- end row -->

@endsection


@section('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>
{{--<!-- Leaflet Control Geocoder plugin -->--}}
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<!-- Leaflet Routing Machine JavaScript -->
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
    // Initialize Leaflet map
    var map = L.map('map').setView([{{ $father->Latitude }}, {{ $father->Longitude }}], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Initialize marker
    var marker = L.marker([{{ $father->Latitude }}, {{ $father->Longitude }}], {
        draggable: true // Make the marker draggable
    }).addTo(map);

</script>
@endsection