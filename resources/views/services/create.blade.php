@extends('layouts.app')

@section('page-title', __('Services Create') )

@push('stylesheets')
<link rel="stylesheet" href="{{ asset("/css/dropify.min.css") }}">
@endpush

@section('page-toolbar')
<div class="row mb-3 align-items-center">
    <div class="col">
       <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
          <li class="breadcrumb-item"><a class="text-secondary" href="{{ route("services.index") }}">{{ __('Services') }}</a></li>
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
                <h4><a href="{{ route("services.index") }}" class="fa fa-arrow-circle-left me-2" title="{{ __('Create Service') }}"></a>{{ __('Create Service') }}</h4>
                <div class="dropdown morphing scale-left">
                    <a href="javascript:void(0);" class="card-fullscreen" data-bs-toggle="tooltip" title="{{ __("Full Screen") }}"><i class="icon-size-fullscreen"></i></a>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(["route" => ["services.store"], "method" => "POST", "id" => "submit-form", "enctype" => "multipart/form-data"]) !!}
                @if(Auth::user()->parent_id === 0 && Auth::user()->isAdmin())
                    <div class="row mb-4">
                        <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Service Branch') }} *</label>
                        <div class="col-xl-8 col-sm-9">
                            {!! Form::select('service_branch', $branches, null, ["class" => "form-control form-control-lg", "placeholder" => __("Service Branch")]) !!}
                        </div>
                    </div>
                @endif
                <div class="row mb-4">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Service Code') }} *</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::text('service_code', null, ["class" => "form-control form-control-lg", "id" => "service_code", "placeholder" => __('Service Code')]) !!}
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Service Name') }} *</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::text('service_name', null, ["class" => "form-control form-control-lg", "id" => "service_name", "placeholder" => __('Service Name')]) !!}
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Service Amount') }} *</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::number('service_amount', null, ["class" => "form-control form-control-lg", "id" => "inputmask_currency", "inputmode" => "text", "placeholder" => __('Service Amount')]) !!}
                        <div class="form-text">{{ __("Currency Symbol") }}:
                            <code>{{ Auth::user()->getDefaultCurrency() }}</code></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Service Image') }} *</label>
                    <div class="col-sm-8">
                        {!! Form::file('service_image', ["class" => "dropify"]) !!}
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Service Description') }}</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::textarea('service_description', null, ["class" => "form-control form-control-lg", "id" => "service_description", "rows" => 4]) !!}
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
<script src="{{ asset("/bundles/inputmask.bundle.js") }}"></script>
<script>
    Inputmask("{{ Auth::user()->getDefaultCurrency() }} 999,999,999", { "numericInput": true, "autoUnmask": true, }).mask("#inputmask_currency");
</script>
<script>
    $(function() {
        $('.dropify').dropify();
        $('#dropify-event').dropify();
    });

</script>
<script>
    $('#inputmask_currency').on("change keyup paste", function() {
        $('#inputmask_currency').val($(this).val());
    });

    $('button[type="submit"]').on("click", function(e) {
        e.preventDefault();
        var myForm = document.getElementById('submit-form');
        $('button[type="submit"]').prop("disabled", true);
        myForm.submit();
    });
</script>
@endpush
