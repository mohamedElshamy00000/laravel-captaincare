@extends('layouts.backend')
@section('title')
    Plans
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-end">
                        <div class="input-group input-group-sm">
                            <a href="{{ route('admin.subscription.create.plan') }}" class="btn btn-primary waves-effect waves-light btn-sm">Create new <i class="mdi mdi-chevron-right ms-1"></i></a>
                        </div>
                    </div>
                    <h4 class="card-title mb-4">Plans</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0" id="plans">
                        <thead>
                            <tr>
                                <th>name</th>
                                <th>description</th>
                                <th>price</th>
                                <th>duration</th>
                                <th>date</th>
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

<script type="text/javascript">

    $(document).ready( function () {

        var table = $('#plans').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering : true,
            ajax: "{{ route('admin.subscription.getPlans') }}",
            columns: [
                // {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'price', name: 'price'},
                {data: 'duration', name: 'duration'},
                {data: 'created_at', name: 'created_at'},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                }
            ],
            dom: 'Bfrtip'
        });

    });

</script>
@endsection