@extends('layouts.backend')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <h4 class="card-title mb-4">Trips</h4>
                </div>

                <!-- Advanced Search Form -->
                <div class="mb-4">
                    <form id="search-form" class="form-inline row mb-5">
                        <div class="form-group mr-2 col">
                            <label for="trip-date" class="mr-2">Trip Date</label>
                            <input type="date" class="form-control" id="trip-date" name="trip_date">
                        </div>
                        <div class="form-group mr-2 col">
                            <label for="driver" class="mr-2">Driver</label>
                            <input type="text" class="form-control" id="driver" name="driver">
                        </div>
                        <div class="form-group mr-2 col">
                            <label for="status" class="mr-2">Status</label>
                            <select class="form-control" id="" name="status">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group mr-2 col">
                            <label for="trip-type" class="mr-2">Trip Type</label>
                            <select class="form-control" id="trip-type" name="trip_type">
                                <option value="">All</option>
                                <option value="one-way">One Way</option>
                                <option value="round-trip">Round Trip</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary col mt-4">Search</button>
                    </form>
                </div>
                <!-- End Advanced Search Form -->

                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0" id="trips-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Trip Date</th>
                                <th>Trip Time</th>
                                <th>Description</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th>Trip Type</th>
                                <th>Group</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated here via DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#trips-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.trips.get') }}',
                data: function(d) {
                    d.trip_date = $('#trip-date').val();
                    d.driver = $('#driver').val();
                    d.status = $('#status').val();
                    d.trip_type = $('#trip-type').val();
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'trip_date', name: 'trip_date' },
                { data: 'time', name: 'time' },
                { data: 'description', name: 'description' },
                { data: 'driver_id', name: 'driver_id' },
                { data: 'status', name: 'status' },
                { data: 'trip_type', name: 'trip_type' },
                { data: 'group_id', name: 'group_id' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            table.draw();
        });
    });
</script>
@endsection
