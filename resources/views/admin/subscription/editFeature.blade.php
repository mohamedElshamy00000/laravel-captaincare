@extends('layouts.backend')
@section('title')
    Plans Features
@endsection

@section('content')


<div class="col-12">
    <div class="card mb-4">
      <h5 class="card-header">Edit Feature</h5>
      <div class="card-body">
        <form action="{{ route('admin.subscription.update.feature', $feature->id) }}" method="POST">
          @csrf
          <div class="row">
              <div class="mb-3 col-6">
                  <label for="fname" class="form-label">Name</label>
                  <input type="text" class="form-control" required name="name" id="fname" value="{{ $feature->name }}">
                  @error('name')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
              <div class="mb-3 col-6">
                  <label for="fcode" class="form-label">code</label>
                  <div class="input-group">
                      <input type="text" class="form-control" required name="code" value="{{ $feature->code }}" aria-label="code">
                  </div>
                  @error('code')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
          </div>
          <div class="mb-3 col-12">
              <label for="fDescription" class="form-label">Description</label>
              <textarea class="form-control" id="fDescription" required name="description" rows="3">{!! $feature->description  !!}</textarea>
              @error('description')
                <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          <div class="row">
            <div class="mb-3 col-4">
              <label for="defaultSelect" class="form-label">type</label>
              <select id="defaultSelect" name="type" required class="form-select">
                <option value="limit" @if($feature->type == 'limit') selected  @endif>limit</option>
                <option value="feature" @if($feature->type == 'feature') selected  @endif>feature</option>
              </select>
              @error('type')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-8" style="display: none" id="Featurelimit">
                <label for="flimit" class="form-label">limit - For unlimited feature, the limit field will be set to any negative value</label>
                <div class="input-group input-group">
                    <span class="input-group-text fw-bold">limit</span>
                    <input type="number" class="form-control" name="limit" @if($feature->type == 'limit') value="{{ $feature->limit }}" @endif aria-label="limit">
                </div>
                @error('limit')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
          </div>
          <div class="mb-3">
            <div class="row targetDiv" id="div1">
              <div class="col-md-12">
                @foreach ($feature->metadata as $key => $value)
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
    if($('#defaultSelect').find(":selected").val() == 'limit') {
        $('#Featurelimit').show();
    } else {
        $('#Featurelimit').fadeOut();
    }
    $("#defaultSelect").change(function() {
        console.log($('#defaultSelect').find(":selected").val());
        if($('#defaultSelect').find(":selected").val() == 'limit') {
            $('#Featurelimit').show();
        } else {
            $('#Featurelimit').fadeOut();
        }
    });

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