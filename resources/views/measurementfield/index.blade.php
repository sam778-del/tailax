@extends('layouts.app')

@section('page-title', __('Measurement Fields List') )

@section('page-toolbar')
    <div class="row mb-3 align-items-center">
        <div class="col">
            <ol class="breadcrumb bg-transparent mb-0">
                <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Measurement Fields') }}</li>
            </ol>
        </div>
    </div>
    <!-- .row end -->
@endsection

@section('page-content')
    <div class="col-12">
        <div class="card-body border-bottom">
            <div class="row align-items-center">
                {{-- Other Widget --}}
                <div class="col ml-n2">
                </div>
                {{-- End of other widget --}}
                @can('Create Measurement Field')
                    <div class="col-auto d-none d-md-inline-block">
                        <a href="{{ route("measurement_fields.create") }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            {{ __('Create Measurement Field') }}
                        </a>
                    </div>
                @endcan
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="table_list" class="table align-middle mb-0 card-table" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{ __('Measurement Name') }}</th>
                        <th class="text-center">{{ __('Value Type') }}</th>
                        <th class="text-center">{{ __('Created By') }}</th>
                        <th class="text-center">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showOptions" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body custom_scroll p-lg-4">
                    <div class="mb-4">
                        <h4>{{ __('Update Options')  }}</h4>
                    </div>
                    <div class="row item-row" id="optionsHtml">
                    </div>
                    {!! Form::open(["route" => null, "method" => "PATCH", "id" => "submit-form", "enctype" => "multipart/form-data"]) !!}
                    <div class="row item-row">
                        <div class="col-6">
                            <label class="form-label text-primary">{{ __('Options Name') }}</label>
                            <input type="text" name="option_name[]" class="form-control form-control-lg" placeholder="{{ __('Options Name') }}">
                        </div>
                        <div class="col-3">
                            <label class="form-label text-primary">{{ __('Image') }}</label>
                            <div class="col-md-12 col-sm-12">
                                <input type="file" class="form-control form-control-lg" name="image[]" id="file-input1">
                                <label for="file-input1" class="shadow text-muted"></label>
                            </div>
                        </div>
                        <div class="col-3">
                            <button href="javascript:void(0);" type="button"  onclick="addButton();" class="btn btn-link btn-lg color-400"><i class="fa fa-plus-circle"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-lg btn-primary" type="submit">{{ __('Save') }}</button>
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
        $(document).ready(function() {
            var table = $('#table_list')
                .addClass( 'nowrap' )
                .dataTable( {
                    responsive: true,
                    ordering: false,
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('measurement.field.datatables') }}',
                    columns: [
                        { data: 'measurement_name', name: 'measurement_name' },
                        { data: 'value_type', name: 'value_type', searchable: false, orderable: false },
                        {data: 'created_by', created_by: 'created_by'},
                        { data: 'action', searchable: false, orderable: false }
                    ],
                    language : {
                        processing: '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                    },
                });
        });

        function deleteAction(url)
        {
            Swal.fire({
                title: '{{ __("Do you want to save the changes?") }}',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: '{{ __("Continue") }}',
                denyButtonText: `{{ __("Cancel") }}`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{!! csrf_token() !!}"
                        },
                        success: function(data) {
                            var oTable = $('#table_list').dataTable();
                            oTable.fnDraw(false);
                            if(data.status == true){
                                toastr.success("{{__('Success') }}", data.msg, 'success');
                            }else{
                                toastr.error("{{__('Error') }}", data.msg, 'error');
                            }
                        },
                        error: function(error) {
                            Swal.fire('{{ __("Action Cannot be completed") }}', '', 'error')
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire('{{ __("Changes Cancelled") }}', '', 'info')
                }
            })
        }

        function addButton()
        {
            $(document).ready(function() {
                $(".item-row:last").after('<div class="row item-row">                        <div class="col-6">                            <label class="form-label text-primary">{{ __('Options Name') }}</label>                            <input type="text" name="option_name[]" class="form-control form-control-lg" placeholder="{{ __('Options Name') }}">                        </div>                        <div class="col-3">                            <label class="form-label text-primary">{{ __('Image') }}</label>                            <div class="col-md-12 col-sm-12">                                <div class="image-input avatar xxl rounded-4" style="background-image: url({{ asset('images/company-logo.png') }})">                                    <div class="avatar-wrapper rounded-4" style="background-image: url({{ asset('images/company-logo.png') }})"></div>                                    <div class="file-input">                                        <input type="file" class="form-control" name="image[]" id="file-input">                                        <label for="file-input" class="fa fa-pencil shadow text-muted"></label>                                    </div>                                </div>                            </div>                        </div>                        <div class="col-3">                            <button href="javascript:void(0);" type="button"  onclick="addButton();" class="btn btn-link btn-lg color-400"><i class="fa fa-plus-circle"></i></button>                            <button href="javascript:void(0);" type="button" onclick="removeButton(this)" class="btn btn-link btn-lg color-400"><i class="fa fa-minus-circle"></i></button>                        </div>                    </div>'); //add input box
            });
        }

        function removeButton(ele)
        {
            $(document).ready(function() {
                $(ele).parents('.item-row').remove();
            })
        }

        function showOptions(action_url, ele)
        {
            $('#submit-form').attr('action', ele);
            $.ajax({
                url: action_url,
                type: "GET",
                cache: false,
                data: null,
                dataType: 'json',
                timeout: 2000,
                success: function(resp) {
                    if(resp.status == true)
                    {
                        $('#optionsHtml').html(resp.msg);
                        $('#showOptions').modal("toggle");
                    }else{
                        toastr.error("{{__('Error') }}", resp.msg, 'error');
                    }
                }
            });
        }
    </script>
@endpush
