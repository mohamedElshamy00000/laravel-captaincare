@extends('layouts.backend')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-sm-flex flex-wrap">
            <h4 class="card-title">Invoices</h4>
            <div class="ms-auto">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.subscription.invoice.create') }}">Create New</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="card-datatable table-responsive">
            <table class="invoice-list-table table border-top dataTable no-footer dtr-column" id="invoices-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Plan</th>
                        <th>User</th>
                        <th>Child</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Transaction ID</th>
                        <th>Payment Way</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.subscription.getInvoicesData") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'plan_name', name: 'plan_name' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'child_name', name: 'child_name' },
                    { data: 'amount', name: 'amount' },
                    { data: 'due_date', name: 'due_date' },
                    { data: 'status', name: 'status' },
                    { data: 'transaction_id', name: 'transaction_id' },
                    { data: 'payment_way', name: 'payment_way' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection
