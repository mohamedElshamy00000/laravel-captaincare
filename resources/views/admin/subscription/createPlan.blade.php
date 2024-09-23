@extends('layouts.backend')
@section('title')
    Plans
@endsection

@section('content')

<div class="col-12">
    <div class="card mb-4">
      <h5 class="card-header">Create Plan</h5>
      <div class="card-body">
        <form action="{{ route('admin.subscription.store.plan') }}" method="POST">
          @csrf
          <div class="row">
              <div class="mb-3 col-6">
                  <label for="fname" class="form-label">Name</label>
                  <input type="text" class="form-control" required name="name" id="fname" placeholder="name">
                  @error('name')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
              <div class="mb-3 col-6">
                  <label for="fPrice" class="form-label">Price</label>
                  <div class="input-group input-group-merge">
                      <span class="input-group-text text-success">EGP</span>
                      <input type="number" class="form-control" required name="price" placeholder="100" aria-label="price">
                      
                  </div>
                  @error('price')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
          </div>
          <div class="mb-3 col-12">
              <label for="fDescription" class="form-label">Description</label>
              <textarea class="form-control" id="fDescription" required name="description" rows="3"></textarea>
              @error('description')
                <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          <div class="mb-3 col-4">
            <label for="defaultSelect" class="form-label">Duration</label>
            <select id="defaultSelect" name="duration" required class="form-select">
              <option value="30">30 Days</option>
              <option value="90">3 month</option>
              <option value="365">1 year</option>
            </select>
            @error('duration')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <div class="row targetDiv" id="div1">
              <div class="col-md-12">
                <div id="group2" class="fvrduplicate">
                  <div class="row entry">
                    <!-- Field Start --> 
                    <div class="mb-3 col-md-11 mb-0">
                      <div class="input-group">
                        <span class="input-group-text">meta data</span>
                        <input type="text" aria-label="key" name="key[]" placeholder="key" class="form-control">
                        <input type="text" aria-label="value"  name="value[]" placeholder="value" class="form-control">
                      </div>
                    </div>
                    <div class="mb-3 col-md-1 mb-0">
                      <button type="button" class="btn btn-label-success waves-effect btn-add w-100">
                        <i class="fa fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">Save</button>
          </div>
        </form>
      </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

$(function() {
    $(document).on('click', '.btn-add', function(e) {
        e.preventDefault();
        var controlForm = $(this).closest('.fvrduplicate'),
            currentEntry = $(this).parents('.entry:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
        newEntry.find('input').val('');
        controlForm.find('.entry:not(:first) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-label-danger')
            .html('<i class="fa fa-minus" aria-hidden="true"></i>');
    }).on('click', '.btn-remove', function(e) {
        $(this).closest('.entry').remove();
        return false;
    });
});
</script>
@endsection