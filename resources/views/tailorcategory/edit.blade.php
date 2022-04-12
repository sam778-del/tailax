@extends('layouts.app')

@section('page-title', __('Tailor Categories Edit') )

@section('page-toolbar')
    <div class="row mb-3 align-items-center">
        <div class="col">
            <ol class="breadcrumb bg-transparent mb-0">
                <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a class="text-secondary" href="{{ route("tailor_categories.index") }}">{{ __('Tailor Categories') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
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
                    <h4><a href="{{ route("tailor_categories.index") }}" class="fa fa-arrow-circle-left me-2" title="{{ __('Edit Tailor Categories') }}"></a>{{ __('Edit Tailor Categories') }}</h4>
                    <div class="dropdown morphing scale-left">
                        <a href="javascript:void(0);" class="card-fullscreen" data-bs-toggle="tooltip" title="{{ __("Full Screen") }}"><i class="icon-size-fullscreen"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(["route" => ["tailor_categories.update", $tailor_category->id], "method" => "PATCH", "id" => "submit-form"]) !!}
                    <div class="row mb-4">
                        <label class="col-xl-2 col-sm-3 col-form-label">{{ __('Category Name') }} *</label>
                        <div class="col-xl-8 col-sm-9">
                            {!! Form::text('name', $tailor_category->name, ["class" => "form-control form-control-lg", "placeholder" => __('Category Name')]) !!}
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-xl-2 col-sm-3 col-form-label"></label>
                        <div class="col-sm-8">
                            <button class="btn btn-lg bg-secondary text-light text-uppercase" type="submit">{{ __('Edit') }}</button>
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
        $('button[type="submit"]').on("click", function(e) {
            e.preventDefault();
            var myForm = document.getElementById('submit-form');
            $('button[type="submit"]').prop("disabled", true);
            myForm.submit();
        });
    </script>
@endpush
