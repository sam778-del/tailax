@extends('layouts.app')

@section('page-title', __('Currencies Create') )

@section('page-toolbar')
<div class="row mb-3 align-items-center">
    <div class="col">
       <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
          <li class="breadcrumb-item"><a class="text-secondary" href="{{ route("currencies.index") }}">{{ __('Currencies') }}</a></li>
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
                <h4><a href="{{ route("currencies.index") }}" class="fa fa-arrow-circle-left me-2" title="{{ __('Create Currency') }}"></a>{{ __('Create Currency') }}</h4>
                <div class="dropdown morphing scale-left">
                    <a href="javascript:void(0);" class="card-fullscreen" data-bs-toggle="tooltip" title="{{ __("Full Screen") }}"><i class="icon-size-fullscreen"></i></a>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(["route" => ["currencies.store"], "method" => "POST", "id" => "submit-form"]) !!}
                <div class="row mb-4">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Currency Name') }} *</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::text('currency_name', null, ["class" => "form-control form-control-lg", "placeholder" => __('Currency Name')]) !!}
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Currency Symbol') }} *</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::text('currency_symbol', null, ["class" => "form-control form-control-lg", "placeholder" => __('Currency Symbol')]) !!}
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Exchange Rate') }} *</label>
                    <div class="col-xl-8 col-sm-9">
                        {!! Form::number('exchange_rate', null, ["class" => "form-control form-control-lg", "placeholder" => __('Exchange Rate')]) !!}
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
<script>
    $('button[type="submit"]').on("click", function() {
        $('button[type="submit"]').prop("disabled", true);
        $('#submit-form').submit();
    });
</script>
@endpush
