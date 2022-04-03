@extends('layouts.app')

@section('page-title', __('Customers List') )

@section('page-toolbar')
<div class="row mb-3 align-items-center">
    <div class="col">
       <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('Customers') }}</li>
       </ol>
    </div>
</div>
 <!-- .row end -->
@endsection

@section('page-content')
<div class="col-12">
    <div class="row-title  card-body border-bottom">
        <div class="col col-3">
            @if(Auth::user()->isAdmin() && ! Auth::user()->isUser())
                {!! Form::select('service_branch', $branches, null, ["class" => "form-select", "id" => "searchBranch"]) !!}
            @endif
        </div>
        <div class="btn-group" role="group">
            @can('Create Service')
                <a href="{{ route("customers.create") }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    {{ __('Create Customer') }}
                </a>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="table_list" class="table align-middle mb-0 card-table" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Customer Name') }}</th>
                        <th class="text-center">{{ __('Customer Image') }}</th>
                        <th>{{ __('Customer Email') }}</th>
                        <th class="text-center">{{ __('Customer Branch') }}</th>
                        <th class="text-center">{{ __('Customer Measurement') }}</th>
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
            ajax: {
                url: '{{ route('customers.datatables') }}',
                data: function(d) {
                    d.service_branch = $('#searchBranch').val()
                }
            },
            columns: [
                { data: 'name', code: 'name' },
                { data: 'image', image: 'image' },
                { data: 'email', name: 'email' },
                { data: 'branch_name', amount: 'branch_name' },
                { data: 'measurement', searchable: false, orderable: false },
                { data: 'action', searchable: false, orderable: false }
            ],
            language : {
                processing: '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
            },
        });
    });

    $("#searchBranch").change(function() {
        var oTable = $('#table_list').dataTable();
        oTable.fnDraw(false);
    });

    function makeDefault(url)
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
                    method: "PATCH",
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
