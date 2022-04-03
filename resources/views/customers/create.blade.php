@extends('layouts.app')

@section('page-title', __('Customers Create') )

@push('stylesheets')
<link rel="stylesheet" href="{{ asset("/css/dropify.min.css") }}">
@endpush

@section('page-toolbar')
<div class="row mb-3 align-items-center">
    <div class="col">
       <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
          <li class="breadcrumb-item"><a class="text-secondary" href="{{ route("customers.index") }}">{{ __('Customers') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
       </ol>
    </div>
</div>
 <!-- .row end -->
@endsection

@section('page-content')
<div class="row g-3 row-deck">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><a href="{{ route("customers.index") }}" class="fa fa-arrow-circle-left me-2" title="{{ __('Create Customer') }}"></a>{{ __('Create Customer') }}</h4>
                <div class="dropdown morphing scale-left">
                    <a href="javascript:void(0);" class="card-fullscreen" data-bs-toggle="tooltip" title="{{ __("Full Screen") }}"><i class="icon-size-fullscreen"></i></a>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(["route" => ["customers.store"], "method" => "POST", "id" => "submit-form", "enctype" => "multipart/form-data"]) !!}
                @if(Auth::user()->parent_id === 0 && Auth::user()->isAdmin())
                    <div class="row mb-4">
                        <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Customer Branch') }} *</label>
                        <div class="col-xl-8 col-sm-9">
                            {!! Form::select('customer_branch', $branches, null, ["class" => "form-control form-control-lg", "placeholder" => __("Customer Branch")]) !!}
                        </div>
                    </div>
                @endif
                <div class="row mb-4">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Customer Name') }} *</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::text('customer_name', null, ["class" => "form-control form-control-lg", "placeholder" => __('Customer Name')]) !!}
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Customer Email') }} *</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::email('customer_email', null, ["class" => "form-control form-control-lg", "placeholder" => __('Customer Email')]) !!}
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Phone Number') }} *</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::number('customer_phone_number', null, ["class" => "form-control form-control-lg", "placeholder" => __('Phone Number')]) !!}
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Customer Photo') }} </label>
                    <div class="col-sm-8">
                        {!! Form::file('photo', ["class" => "dropify"]) !!}
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Customer Address') }}</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::textarea('customer_address', null, ["class" => "form-control form-control-lg", "rows" => 4]) !!}
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Customer Description') }}</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::textarea('customer_description', null, ["class" => "form-control form-control-lg", "rows" => 4]) !!}
                    </div>
                </div>
                <div class="row">
                    <label class="col-xl-2 col-sm-3 col-form-label"></label>
                    <div class="col-sm-8">
                        <button class="btn btn-lg bg-secondary text-light text-uppercase" type="submit">{{ __('Create') }}</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset("/bundles/dropify.bundle.js") }}"></script>
<script>
    $(function() {
        $('.dropify').dropify();
        $('#dropify-event').dropify();
    });

</script>
<script>
    $('button[type="submit"]').on("click", function(e) {
        e.preventDefault();
        var myForm = document.getElementById('submit-form');
        $('button[type="submit"]').prop("disabled", true);
        myForm.submit();
    });
</script>
@endpush
