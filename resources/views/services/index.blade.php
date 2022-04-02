@extends('layouts.app')

@section('page-title', __('Services List') )

@section('page-toolbar')
<div class="row mb-3 align-items-center">
    <div class="col">
       <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a class="text-secondary" href="{{ url("/") }}">{{ __('Dashboard') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('Services') }}</li>
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
            @can('Create Service')
                <div class="col-auto d-none d-md-inline-block">
                    <a href="{{ route("services.create") }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        {{ __('Create Service') }}
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
                        <th>{{ __('Service Code') }}</th>
                        <th class="text-center">{{ __('Service Image') }}</th>
                        <th>{{ __('Service Name') }}</th>
                        <th>{{ __('Service Amount') }}</th>
                        <th>{{ __('Branch Name') }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Status') }}</th>
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
            ajax: '{{ route('services.datatables') }}',
            columns: [
                { data: 'code', code: 'code' },
                { data: 'image', image: 'image' },
                { data: 'name', name: 'name' },
                { data: 'amount', amount: 'amount' },
                {data: 'branch_name', branch_name: 'branch_name'},
                {data: 'created_by', created_by: 'created_by'},
                { data: 'description', description: 'description' },
                { data: 'status', status: 'status' },
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
