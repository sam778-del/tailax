@extends('layouts.app')

@section('page-title', __('Product List') )

@section('page-toolbar')
    <div class="row mb-3 align-items-center">
        <div class="col">
            <ol class="breadcrumb bg-transparent mb-0">
                <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Product') }}</li>
            </ol>
        </div>
    </div>
    <!-- .row end -->
@endsection

@section('page-content')
    <div class="col-12">
        <div class="row-title  card-body border-bottom">
            <div class="row">
                <div class="col-12">
                    {!! Form::select('select_branch', $branches, null, ["class" => "form-select form-control", "id" => "selectBranch", "placeholder" => __("All Branches")]) !!}
                </div>
            </div>
            <div class="btn-group" role="group">
                @can('Create Tailor Product')
                    <div class="row">
                        <div class="col col-3">
                            <button type="button" class="btn btn-danger" disabled title="{{ __("Delete Product")  }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="col-3">
                            <a href="{{ route("tailor_products.create") }}" class="btn btn-warning" title="{{ __('Import Product')  }}">
                                <i class="bi bi-file-earmark-excel"></i>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route("tailor_products.create") }}" class="btn btn-primary" title="{{ __('Create Product')  }}">
                                <i class="bi bi-plus-lg"></i>
                                {{ __('Create') }}
                            </a>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="table_list" class="table align-middle mb-0 card-table" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="text-center">{!! Form::checkbox('select_all', 1, null) !!}</th>
                        <th>{{ __('Garment Name') }}</th>
                        <th>{{ __('Stiching Charges')  }}</th>
                        <th>{{ __('Category')  }}</th>
                        <th>{{ __('Process Name')  }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                    </thead>
                </table>
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
                    ajax: '{{ route('tailor.product.datatables') }}',
                    columns: [
                        { data: 'name', name: 'name' },
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
    </script>
@endpush
