@extends('layouts.backend')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
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

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <i class="bx bx-bar-chart-alt-2 h1"></i>
                    </div>
                    <div class="flex-grow-1 align-self-center">
                        <div class="text-muted">
                            <p class="mb-2">{{ $group->name }}</p>
                            <h5 class="mb-1">{{ $group->description }}</h5>
                            <p class="mb-0">
                                @if($group->status == 1)
                                    <span class="badge badge-pill badge-soft-success font-size-12">Active</span>
                                @else
                                    <span class="badge badge-pill badge-soft-danger font-size-12">blocked</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 align-self-center">
                <div class="text-lg-center mt-4 mt-lg-0">
                    <div class="row">
                        <div class="col-4">
                            <div>
                                <p class="text-muted text-truncate mb-2"><i class="mdi mdi-account-group h5"></i> children</p>
                                <h5 class="mb-0">{{ $group->children->count() }}</h5>
                            </div>
                        </div>
                        <div class="col-4">
                            <div>
                                <p class="text-muted text-truncate mb-2"><i class="mdi mdi-office-building h5"></i> School</p>
                                <h5 class="mb-0">{{ $group->school->name }}</h5>
                            </div>
                        </div>
                        <div class="col-4">
                            <div>
                                <p class="text-muted text-truncate mb-2"><i class="mdi mdi-car h5"></i> Driver</p>
                                <h5 class="mb-0">{{  $group->driver->name }}</h5>
                                <p class="text-muted mb-0 small">{{  $group->driver->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 d-none d-lg-block">
                <div class="clearfix mt-4 mt-lg-0">
                    <div class="dropdown float-end">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bxs-cog align-middle me-1"></i> Setting
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <button disabled class="dropdown-item" href="#">Edit</button>
                            @if ($group->status == 1)
                            <a class="dropdown-item" href="{{ route('admin.groups.banUnban', ["id" => $group->id, "status" => 0]) }}">disable</a>
                            @else
                            <a class="dropdown-item" href="{{ route('admin.groups.banUnban', ["id" => $group->id, "status" => 1]) }}">active</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
</div>
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex flex-wrap">
            <h5 class="font-size-16 me-3 mt-3 mb-0">Groups Children</h5>

            <div class="ms-auto  me-3 mt-3 mb-0">
                <form action="{{ route('admin.groups.add.child', $group->id) }}" method="post">
                    @csrf
                    <div class="input-group input-group-sm">
                        <select class="js-example-basic-single form-select form-select-sm" name="child[]" multiple required>
                        </select>
                        <button type="submit" class="btn btn-dark">Add to Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="">
    
            <div class="table-responsive">
                <table class="table align-middle table-nowrap table-hover mb-0">
                    <thead>
                        <tr>
                          <th scope="col"><i class="dripicons-user-id font-size-20 align-middle text-primary me-2"></i> Name</th>
                          <th scope="col"><i class="dripicons-archive font-size-20 align-middle text-primary me-2"></i> Class</th>
                          <th scope="col"><i class="mdi mdi-account-child-outline font-size-20 align-middle text-primary me-2"></i> Parents</th>
                          <th scope="col"><i class="mdi mdi-sort-numeric-ascending-variant font-size-20 align-middle text-primary me-2"></i> Age</th>
                          <th scope="col"><i class="mdi mdi-dots-horizontal font-size-20 align-middle text-primary"></i></th>
                        </tr>
                      </thead>
                    <tbody>

                        @foreach ($group->children as $child)
                        <tr>
                            <td>
                                <a href="javascript: void(0);" class="text-dark fw-medium d-flex">
                                    <div>
                                        <h6 class="mb-0">{{ $child->name }}</h6>
                                        <p class="text-muted mb-0">{{ $child->phone }}</p>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="javascript: void(0);" class="text-dark fw-medium">
                                    {{ $child->school->name }} <i class="mdi mdi-arrow-horizontal-lock"></i> {{ $child->classe->name }}
                                </a>
                            </td>
                            <td>
                                <div class="text-dark fw-medium d-flex">
                                    
                                    <div>
                                        <h6 class=" mb-0">{{ $child->fathers->first()->name }}</h6>
                                        <p class="text-muted mb-0">{{ $child->fathers->first()->phone }}</p>    
                                    </div>
                                </div>
                            
                            </td>
                            <td>{{ $child->age }} Year's</td>
                            <td>
                                <form id="removeChildrenForm" action="{{ route('admin.groups.delete.child') }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="child_id" value="{{ $child->id }}">
                                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                                    <button class="btn" style="line-height: 10px;" type="submit"><i class="dripicons-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                       
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<div class="card">
    <div class="card-body">
        <div class="">
            <div id="map" style="height: 400px;"></div>
        </div>
    </div>
</div>


@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>
{{--<!-- Leaflet Control Geocoder plugin -->--}}
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>


<!-- Leaflet Routing Machine JavaScript -->
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script src="{{ asset('backend/assets/js/map.js') }}"></script>
<script>

$(document).ready(function () {

    var group = @json($group);
    var driver = group.driver;
    var school = group.school;
    var children = group.children;
    // Call the Map function
    initializeMap(driver, children, school);

});


$('.js-example-basic-single').select2({
    placeholder: "Select children",
    minimumInputLength: 1,
    multiple: true,
});

// Fetch and populate children options via AJAX
$.ajax({
    url: "{{ route('admin.getChildren') }}",
    data: { school_id: {{ $group->school->id }} },
    success: function(data) {
        var newOptions = data.map(function(child) {
            return {
                id: child.id,
                text: child.name + ' - ' + child.phone
            };
        });

        // Clear existing options and update with new ones
        $(".js-example-basic-single").empty().select2({
            data: newOptions,
            placeholder: "Select children",
            minimumInputLength: 1,
            allowClear: true,
            multiple: true,
            closeOnSelect: false,
            tags: false,
            dropdownAutoWidth: true,
            width: "100%",

            templateResult: function(child) {
                return child.text;
            },
            templateSelection: function(child) {
                return child.text;
            }
        });

        // Reattach event handler for when children are selected or unselected
        $(".js-example-basic-single").off("select2:select select2:unselect").on("select2:select select2:unselect", function(e) {
            var selectedChildren = $(this).select2("data");
            console.log(selectedChildren); // Handle selected children
        });
    }
});


</script>
</body>
@endsection
