@extends('layouts.app')

@section('page-title', __('Dashboard') )

@section('page-toolbar')
<div class="row mb-3 align-items-center">
    <div class="col">
       <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a class="text-secondary" href="index.html">Home</a></li>
          <li class="breadcrumb-item"><a class="text-secondary" href="dashboard.html">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">My Dashboard</li>
       </ol>
    </div>
</div>
 <!-- .row end -->
<div class="row align-items-center">
    <div class="col">
       <h1 class="fs-5 color-900 mt-1 mb-0">{{ __('Welcome back') }}, {{ auth()->user()->getUserName() }}!</h1>
    </div>
</div>
 <!-- .row end -->
@endsection

@section('page-content')

@endsection
