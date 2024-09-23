@extends('layouts.backend')

@section('styles')
<link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-end">
                        <div class="input-group input-group-sm">
                            <a href="{{ route('admin.groups.create') }}" class="btn btn-primary waves-effect waves-light btn-sm">Create new Group <i class="mdi mdi-chevron-right ms-1"></i></a>
                        </div>
                    </div>
                    <h4 class="card-title mb-4">Groups</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0" id="groups-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>description</th>
                                <th>driver</th>
                                <th>school</th>
                                <th>date</th>
                                <th>status</th>
                                <th>action</th>

                            </tr>
                        </thead>
                        
                    </table>
    
                </div>

            </div>
            
        </div>
    </div>
</div>

@endsection

@section('scripts')
{{-- get.all.group --}}
<script type="text/javascript">
    $(function () {
        var table = $('#groups-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.get.all.group') }}",
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'driver', name: 'driver'},
                {data: 'school', name: 'school'},
                {data: 'date', name: 'date'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    
        $('#search-button').on('click', function() {
            table.draw();
        });
    
        $(document).on('click', '.edit', function () {
            var id = $(this).data('id');
            // Add your logic to open an edit modal and populate it with data for the record with ID 'id'
        });
    
        $(document).on('click', '.delete', function () {
            var id = $(this).data('id');
            if (confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: "/groups/" + id,
                    type: 'DELETE',
                    success: function(result) {
                        table.draw();
                    }
                });
            }
        });
    });
    </script>
@endsection