@extends('layouts.backend')
@section('title')
    main settings
@endsection

@section('content')

    <div class="app-ecommerce">
    {{-- clear.cach --}}
        <form class="form-repeater" action="{{ route('admin.main.setting.update') }}" method="POST">
            <!-- Add Product -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">

                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1 mt-3">Edit website settings</h4>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">save changes</button>
                </div>

            </div>

            <div class="row">

                @csrf
                <!-- First column-->
                <div class="col-12 col-lg-8">
                    <!-- Product Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">website information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="">Name</label>
                                <input type="text" class="form-control" id="" value="{{ $settings->name }}" name="name" >
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="">phone</label>
                                    <input type="text" class="form-control" value="{{ $settings->phone }}" name="phone" >
                                </div>
                                <div class="col">
                                    <label class="form-label" for="">email</label>
                                    <input type="text" class="form-control" value="{{ $settings->email }}" name="email" >
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="">address ar</label>
                                    <input type="text" class="form-control" value="{{ $settings->address_ar }}" name="address_ar" >
                                </div>
                                <div class="col">
                                    <label class="form-label" for="">address en</label>
                                    <input type="text" class="form-control" value="{{ $settings->address_en }}" name="address_en" >
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label" for="">footer Quote ar</label>
                                    <input type="text" class="form-control" value="{{ $settings->footerQuote_ar }}" name="footerQuote_ar" >
                                </div>
                                <div class="col">
                                    <label class="form-label" for="">footer Quote en</label>
                                    <input type="text" class="form-control" value="{{ $settings->footerQuote_en }}" name="footerQuote_en" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Social media links</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="">twitter</label>
                                <input type="text" class="form-control" value="{{ $settings->twitter }}" name="twitter" >
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="">facebook</label>
                                <input type="text" class="form-control" value="{{ $settings->facebook }}" name="facebook" >
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="">linked in</label>
                                    <input type="text" class="form-control" value="{{ $settings->linkedin }}" name="linkedin" >
                                </div>
                                <div class="col">
                                    <label class="form-label" for="">youtube</label>
                                    <input type="text" class="form-control" value="{{ $settings->youtube }}" name="youtube" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Information -->

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">MAIL Configration</h5>
                        </div>
                        <div class="card-body">

                            <div data-repeater-list="group-a">
                                <div data-repeater-item="">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label " for="form-repeater-1-2">mail Driver</label>
                                            <input type="text" id="form-repeater-1-2" name="mail_driver" class="form-control" value="{{ $settings->mail_driver }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label " for="form-repeater-1-2">mail Host</label>
                                            <input type="text" id="form-repeater-1-2" name="mail_host" class="form-control" value="{{ $settings->mail_host }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label " for="form-repeater-1-2">mail Port</label>
                                            <input type="text" id="form-repeater-1-2" name="mail_port" class="form-control" value="{{ $settings->mail_port }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label " for="form-repeater-1-2">mail username</label>
                                            <input type="text" id="form-repeater-1-2" name="mail_username" class="form-control" value="{{ $settings->mail_username }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label " for="form-repeater-1-2">mail password</label>
                                            <input type="text" id="form-repeater-1-2" name="mail_password" class="form-control" value="{{ $settings->mail_password }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label " for="form-repeater-1-2">mail encryption</label>
                                            <input type="text" id="form-repeater-1-2" name="mail_encryption" class="form-control" value="{{ $settings->mail_encryption }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label " for="form-repeater-1-2">From Addesss(Email)</label>
                                            <input type="text" id="form-repeater-1-2" name="mail_from_Addesss" class="form-control" value="{{ $settings->mail_from_Addesss }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label " for="form-repeater-1-2">From name</label>
                                            <input type="text" id="form-repeater-1-2" name="mail_from_name" class="form-control" value="{{ $settings->mail_from_name }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Paymob API</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="PAYMOB_API_KEY">PAYMOB_API_KEY</label>
                                <input type="text" class="form-control" id="PAYMOB_API_KEY" value="{{ $settings->PAYMOB_API_KEY }}" name="PAYMOB_API_KEY">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="PAYMOB_CLIENT_ID">PAYMOB_CLIENT_ID</label>
                                <input type="text" class="form-control" id="PAYMOB_CLIENT_ID" value="{{ $settings->PAYMOB_CLIENT_ID }}" name="PAYMOB_CLIENT_ID">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="PAYMOB_IFRAME_ID">PAYMOB_IFRAME_ID</label>
                                <input type="text" class="form-control" id="PAYMOB_IFRAME_ID" value="{{ $settings->PAYMOB_IFRAME_ID }}" name="PAYMOB_IFRAME_ID">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="PAYMOB_HMAC">PAYMOB_HMAC</label>
                                <input type="text" class="form-control" id="PAYMOB_HMAC" value="{{ $settings->PAYMOB_HMAC }}" name="PAYMOB_HMAC">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="PAYMOB_CURRENCY">PAYMOB_CURRENCY</label>
                                <input type="text" class="form-control" id="PAYMOB_CURRENCY" value="{{ $settings->PAYMOB_CURRENCY }}" name="PAYMOB_CURRENCY">
                            </div>
                        </div>
                    </div>

                    {{-- <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Percentages</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="percentage_transport">Percentage Transport</label>
                                <input type="text" class="form-control" id="percentage_transport" value="{{ $settings->percentage_transport }}" name="percentage_transport">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="clearance_percentage">Percentage Clearance</label>
                                <input type="text" class="form-control" id="clearance_percentage" value="{{ $settings->clearance_percentage }}" name="clearance_percentage">
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">GAS</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="gas_value">GAS Value</label>
                                <input type="text" class="form-control" id="gas_value" value="{{ $settings->gas_value }}" name="gas_value">
                            </div>
                        </div>
                    </div> --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">S3 Storage</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="Access_KEY">Access KEY</label>
                                <input type="text" class="form-control" id="Access_KEY" value="{{ $settings->s3_access_key }}" name="s3_access_key">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="s3_secret_key">Secret KEY</label>
                                <input type="text" class="form-control" id="s3_secret_key" value="{{ $settings->s3_secret_key }}" name="s3_secret_key">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="s3_sefault_key">Default Region</label>
                                <input type="text" class="form-control" id="s3_sefault_key" value="{{ $settings->s3_sefault_key }}" name="s3_sefault_key">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="s3_bucket">Bucket</label>
                                <input type="text" class="form-control" id="s3_bucket" value="{{ $settings->s3_bucket }}" name="s3_bucket">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-content-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">save changes</button>
                </div>

            </div>
        </form>
        {{-- <a href="{{ route('clear.cach') }}" class="btn btn-primary mt-3 waves-effect waves-light">clear cash</a> --}}
    </div>

@endsection

@push('script')

@endpush
