@extends('admin.master')

@section('content')
<div class="container-fluid">
    <div class="row">
    	@include('admin.dashboard.user_group')
    	@include('admin.dashboard.department')
    	@include('admin.dashboard.designation')
    	@include('admin.dashboard.division')
    	@include('admin.dashboard.district')
    	@include('admin.dashboard.upazila')
    	@include('admin.dashboard.branch')
    	@include('admin.dashboard.user')
        @include('admin.dashboard.service')
        @include('admin.dashboard.floor')
        @include('admin.dashboard.counter')
        @include('admin.dashboard.my_call_list_chart')
    </div>
</div>
@endsection
