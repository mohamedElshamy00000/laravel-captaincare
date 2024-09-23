@extends('layouts.backend')
@section('title')
    Plans
@endsection

@section('content')
<div class="col-12">
    <div class="card mb-4">
      <h5 class="card-header">Create Plan</h5>
      <div class="card-body">
        <form action="{{ route('admin.subscription.update.plan', $plan->id) }}" method="POST">
          @csrf
          <div class="row">
              <div class="mb-3 col-6">
                  <label for="fname" class="form-label">Name</label>
                  <input type="text" class="form-control" required name="name" id="fname" value="{{ $plan->name }}">
                  @error('name')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
              <div class="mb-3 col-6">
                  <label for="fPrice" class="form-label">Price</label>
                  <div class="input-group input-group-merge">
                      <span class="input-group-text text-success">EGP</span>
                      <input type="number" class="form-control" required name="price" value="{{ $plan->price }}" aria-label="price">
                      
                  </div>
                  @error('price')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
          </div>
          <div class="mb-3 col-12">
              <label for="fDescription" class="form-label">Description</label>
              <textarea class="form-control" id="fDescription" required name="description" rows="3">{!!  $plan->description  !!}</textarea>
              @error('description')
                <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          <div class="mb-3 col-4">
            <label for="defaultSelect" class="form-label">Duration</label>
            <select id="defaultSelect" name="duration" required class="form-select">
              <option value="30"  @if($plan->duration == '30') selected  @endif>30 Days</option>
              <option value="90"  @if($plan->duration == '90') selected  @endif>3 month</option>
              <option value="365"  @if($plan->duration == '365') selected  @endif>1 year</option>
            </select>
            @error('duration')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <div class="row targetDiv" id="div1">
              <div class="col-md-12">
                @if ($plan->metadata != null)
                    @foreach ($plan->metadata as $key => $value)
                    <div id="group2" class="fvrduplicate">
                    <div class="row entry">
                        <!-- Field Start --> 
                        <div class="mb-3 col-md-11 mb-0">
                            <div class="input-group">
                            <span class="input-group-text">meta data</span>
                            <input type="text" aria-label="key" name="key[]" value="{{ $key }}" class="form-control">
                            <input type="text" aria-label="value"  name="value[]" value="{{ $value }}" class="form-control">
                            </div>
                        </div>
                        @if ($loop->first)
                        <div class="mb-3 col-md-1 mb-0">
                            <button type="button" class="btn btn-label-success waves-effect btn-add w-100">
                            <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        @else 
                        <div class="mb-3 col-md-1 mb-0">
                            <button type="button" class="btn btn-label-success waves-effect w-100 btn-remove btn-label-danger">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    @endforeach
                  @endif
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