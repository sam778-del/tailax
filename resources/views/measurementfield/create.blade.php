@extends('layouts.app')

@section('page-title', __('Measurement Fields Create') )

@section('page-toolbar')
    <div class="row mb-3 align-items-center">
        <div class="col">
            <ol class="breadcrumb bg-transparent mb-0">
                <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a class="text-secondary" href="{{ route("measurement_fields.index") }}">{{ __('Measurement Fields') }}</a></li>
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
                    <h4><a href="{{ route("measurement_fields.index") }}" class="fa fa-arrow-circle-left me-2" title="{{ __('Create Measurement Fields') }}"></a>{{ __('Create Measurement Fields') }}</h4>
                    <div class="dropdown morphing scale-left">
                        <a href="javascript:void(0);" class="card-fullscreen" data-bs-toggle="tooltip" title="{{ __("Full Screen") }}"><i class="icon-size-fullscreen"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(["route" => ["measurement_fields.store"], "method" => "POST", "id" => "submit-form", "enctype" => 'multipart/form-data']) !!}
                    <table class="table align-middle mb-0 card-table" cellspacing="0">
                        <div class="col-12">
                            <thead>
                                <div class="col-xl-3 col-sm-3">
                                    <th>{{ __('Measurement Name')  }}</th>
                                </div>
                                <div class="col-xl-5 col-sm-5">
                                    <th>{{ __('Value Type') }}</th>
                                </div>
                                <div class="col-xl-4 col-sm-4">
                                    <th class="text-center">Action</th>
                                </div>
                            </thead>
                        </div>
                        <div class="col-12">
                            <tbody>
                                @foreach ($types as $key => $item)
                                    <tr class="item-row">
                                        <td class="text-center">
                                            <div class="col-xl-12 col-sm-12">
                                                {!! Form::text('measurement_name[]', $item, ["class" => "form-control form-control-lg", "placeholder" => __('Measuremnt Name')]) !!}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="row">
                                                <div class="col-xl-12 col-sm-12">
                                                    {!! Form::select('value_type[]', $value_type, null, ["class" => 'form-select form-control-lg']) !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button href="javascript:void(0);" type="button"  onclick="addButton();" class="btn btn-link btn-lg color-400"><i class="fa fa-plus-circle"></i></button>
                                            <button href="javascript:void(0);" type="button" onclick="removeButton(this)" class="btn btn-link btn-lg color-400"><i class="fa fa-minus-circle"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </div>
                    </table>
                    {!! Form::close() !!}
                </div>
                <div class="card-footer">
                    <div class="row">
                        <label class="col-xl-2 col-sm-3 col-form-label"></label>
                        <div class="col-sm-12">
                            <button class="btn btn-lg bg-secondary text-light text-uppercase" type="submit">{{ __('Create') }}</button>
                        </div>
                    </div>
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

        function addButton()
        {
            $(document).ready(function() {
                $(".item-row:last").after('<tr><td class="text-center"><div class="col-xl-12 col-sm-12">{!! Form::text('measurement_name[]', null, ["class" => "form-control form-control-lg", "placeholder" => __('Measuremnt Name')]) !!}</div></td><td class="text-center"><div class="row"><div class="col-xl-12 col-sm-12">{!! Form::select('value_type[]', $value_type, null, ["class" => 'form-select form-control-lg']) !!}</div></div></td><td class="text-center"><button href="javascript:void(0);" onclick="addButton();" type="button" class="btn btn-link btn-lg color-400 addButton"><i class="fa fa-plus-circle"></i></button><button href="javascript:void(0);" type="button" onclick="removeButton(this)" class="btn btn-link btn-lg color-400"><i class="fa fa-minus-circle"></i></button></td></tr>'); //add input box
            });
        }

        function removeButton(ele)
        {
            $(document).ready(function() {
                $(ele).parents('tr').remove();
            })
        }
    </script>
@endpush
