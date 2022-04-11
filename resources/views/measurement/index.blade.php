@extends('layouts.app')

@section('page-title', __('Currencies List') )

@section('page-toolbar')
<div class="row mb-3 align-items-center">
    <div class="col">
       <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('Currencies List') }}</li>
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
            @can('Create Branch')
                <div class="col-auto d-none d-md-inline-block">
                    <a href="{{ route("currencies.create") }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        {{ __('Create Currency') }}
                    </a>
                </div>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="currency_list" class="table align-middle mb-0 card-table" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Currency Name') }}</th>
                        <th>{{ __('Currency Symbol') }}</th>
                        <th class="text-center">{{ __('Exchange Rate') }}</th>
                        <th class="text-center">{{ __('Base Currency') }}</th>
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
        var table = $('#currency_list')
        .addClass( 'nowrap' )
        .dataTable( {
            responsive: true,
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('currencies.datatables') }}',
            columns: [
                { data: 'currecny_name', name: 'currecny_name' },
                { data: 'currency_symbol', name: 'currency_symbol' },
                { data: 'exchange_rate', name: 'exchange_rate' },
                { data: 'base_currency', name: 'base_currency', searchable: false, orderable: false },
                { data: 'action', searchable: false, orderable: false }
            ],
            language : {
                processing: '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
            },
        });
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
                        var oTable = $('#currency_list').dataTable();
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
                    method: "DELETE",
                    data: {
                        _token: "{!! csrf_token() !!}"
                    },
                    success: function(data) {
                        var oTable = $('#currency_list').dataTable();
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
