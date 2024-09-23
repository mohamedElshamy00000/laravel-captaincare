@extends('layouts.backend') <!-- Assuming you have a Vuexy-based layout -->

@section('content')
<!-- Begin: Content-->
<div class="content-body">
    <!-- Display success message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display error message -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <section class="invoice-add-wrapper">
        <div class="row invoice-add">
            <!-- Invoice Add Left starts -->
            <div class="col-xl-9 col-md-8 col-12">
                <div class="card invoice-preview-card">
                    <div class="card-body">
                        <form action="{{ route('admin.subscription.invoice.store') }}" method="POST">
                            @csrf

                            <!-- Invoice Header -->
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <h4 class="invoice-title">Create Invoice</h4>
                                </div>
                            </div>

                            <!-- Client (Father) and Child Selection -->
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label for="father_id" class="form-label">Select Father</label>

                                    <select id="father_id" name="father_id" class="form-control">
                                        <option value="" disabled>Select Father</option>
                                        @foreach($fathers as $father)
                                            <option value="{{ $father->id }}" {{ old('father_id') == $father->id ? 'selected' : '' }}>
                                                {{ $father->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('father_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label for="child_id" class="form-label">Select Child</label>
                                    <select name="child_id" id="child_id" class="form-control" required>
                                        <option value="" disabled selected>Select Child</option>
                                        <!-- Children options will be dynamically populated -->
                                    </select>
                                    @error('child_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Rest of the Form -->
                            <!-- Plan, Amount, Payment Details, etc. -->
                            <div class="row mt-2">
                                <div class="col-md-6 col-12">
                                    <label for="plan_id" class="form-label">Select Plan</label>
                                    <select name="plan_id" id="plan_id" class="form-control" required>
                                        <option value="" disabled selected>Select Plan</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('plan_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" id="amount" name="amount" class="form-control" placeholder="Enter Amount" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-2">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Invoice Add Right starts -->
            <div class="col-xl-3 col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-2">Invoice Summary</h6>
                        <p><strong>Father:</strong> Select a father to see details</p>
                        <p><strong>Child:</strong> Select a child to see details</p>
                        <p><strong>Amount:</strong> Will be calculated based on the selected plan</p>
                    </div>
                </div>
            </div>
            <!-- Invoice Add Right ends -->
        </div>
    </section>
</div>
<!-- End: Content-->
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function populateChildren(fatherId) {
        if (fatherId) {
            var url = "{{ route('admin.father.get.childrens', ':fatherId') }}";
            url = url.replace(':fatherId', fatherId);

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#child_id').empty();
                    $('#child_id').append('<option value="" disabled selected>Select Child</option>');
                    $.each(data, function(key, value) {
                        $('#child_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        } else {
            $('#child_id').empty();
            $('#child_id').append('<option value="" disabled selected>Select Child</option>');
        }
    }

    // On change event
    $('#father_id').on('change', function() {
        var fatherId = $(this).val();
        populateChildren(fatherId);
    });

    // Trigger on page load if a father is already selected
    var selectedFatherId = $('#father_id').val();
    if (selectedFatherId) {
        populateChildren(selectedFatherId);
    }
});
</script>

@endsection
