@extends('layouts.backend')
@section('title')
    settings
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/quill/typography.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/quill/katex.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('content')

    <h4 class="py-3 mb-0">
      <span class="text-muted fw-light">setting /</span><span class="fw-medium"> Privacy Policy & Terms of Service</span>
    </h4>

    <div class="">
    
        <form class="form-repeater" action="{{ route('admin.setting.privacy.update') }}" method="POST">
        
            <div class="row">
                
                @csrf
                <!-- First column-->
                <div class="col-12">
                    <!-- Product Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Privacy Policy</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="snow-editor" class="form-label fs-5">content ar</label>
                                <div id="snow-editor" class="snow-editor">
                                    {!! $settings->privacy_ar !!}
                                </div>
                                <textarea id="textarea-editor" name="privacy_ar" style="display: none" class="w-100 p-4" rows="10">{!! $settings->privacy_ar !!}</textarea>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="snow2-editor" class="form-label fs-5">content en</label>
                                <div id="snow2-editor">
                                    {!! $settings->privacy_en !!}
                                </div>
                                <textarea id="textarea2-editor" name="privacy_en" style="display: none" class="w-100 p-4" rows="10">{!! $settings->privacy_en !!}</textarea>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                </div>
            
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">save changes</button>
                </div>
            
            </div>
        </form>
    </div>

    <div class="">
      
        <form class="form-repeater" action="{{ route('admin.setting.terms.update') }}" method="POST">
          
            <div class="row">
                  
                @csrf
                <!-- First column-->
                <div class="col-12">
                    <!-- Product Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Terms of Service</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="snow-terms-editor" class="form-label fs-5">content ar</label>
                                <div id="snow-terms-editor">
                                    {!! $settings->terms_ar !!}
                                </div>
                                <textarea id="textarea-terms-editor" name="terms_ar" style="display: none" class="w-100 p-4" rows="10">{!! $settings->terms_ar !!}</textarea>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="snow2-terms-editor" class="form-label fs-5">content en</label>
                                <div id="snow2-terms-editor">
                                    {!! $settings->terms_en !!}
                                </div>
                                <textarea id="textarea2-terms-editor" name="terms_en" style="display: none" class="w-100 p-4" rows="10">{!! $settings->terms_en !!}</textarea>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                </div>
            
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">save changes</button>
                </div>
            
            </div>
                
        </form>
    </div>

@endsection

@push('script')

@endpush

@section('script')

<!-- Vendors JS -->
<script src="{{ asset('backend/assets/vendor/libs/quill/katex.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/quill/quill.js') }}"></script>

<script>
    // text editor 1
    var editor_content;
    const snowEditor = new Quill('.snow-editor', {
        bounds: '.snow-editor',
        modules: {
            formula: true,
        },
        theme: 'snow'
    });
    
    snowEditor.on('text-change', function(delta, oldDelta, source) {
        editor_content = snowEditor.root.innerHTML;
        textareaeditor = $('#textarea-editor').html(editor_content);
    });

    // text editor 2
    var editor2_content;
    const snowEditor2 = new Quill('#snow2-editor', {
        bounds: '#snow-editor',
        modules: {
            formula: true,
        },
        theme: 'snow'
    });
    
    snowEditor2.on('text-change', function(delta, oldDelta, source) {
        editor2_content = snowEditor2.root.innerHTML;
        textareaeditor2 = $('#textarea2-editor').html(editor2_content);
    });

    // text editor 3
    var editor2_terms_content;
    const snowtermsEditor2 = new Quill('#snow2-terms-editor', {
        bounds: '#snow-editor',
        modules: {
            formula: true,
        },
        theme: 'snow'
    });
    
    snowtermsEditor2.on('text-change', function(delta, oldDelta, source) {
        editor2_terms_content = snowtermsEditor2.root.innerHTML;
        textareaeditorterms2 = $('#textarea2-terms-editor').html(editor2_terms_content);
    });

    // text editor 4
    var editor_terms_content;
    const snowtermsEditor = new Quill('#snow-terms-editor', {
        bounds: '#snow-editor',
        modules: {
            formula: true,
        },
        theme: 'snow'
    });
    
    snowtermsEditor.on('text-change', function(delta, oldDelta, source) {
        editor_terms_content = snowtermsEditor.root.innerHTML;
        textareaeditorterms2 = $('#textarea-terms-editor').html(editor_terms_content);
    });
</script>
@endsection