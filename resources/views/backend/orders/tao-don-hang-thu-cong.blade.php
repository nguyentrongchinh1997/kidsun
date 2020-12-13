<?php 
    $month = date('m');
    $year = date('yy');
    $start_format = '01-'.$month.'-'.$year;
    $start_date = request()->startdate ? request()->startdate : $start_format;
    $end_date = request()->enddate ? request()->enddate : '';
?>
@extends('backend.layouts.app')
@section('controller','Tạo đơn hàng thủ công')
@section('controller_route', route('orders.doanh-thu'))
@section('content')
	<div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('flash::message')
                <form action="{{route('orders.post-check-user')}}" method="POST">
                	@csrf
                	<div class="col-sm-6">
	                	<div class="form-group">
	                		<label for="">Nhập tên user name cần tạo đơn hàng</label>
			                <input type="text" class="form-control" value="{!! old('user_name') !!}" name="user_name">
			            </div>
		                <button class="btn btn-success" type="">Tạo đơn hàng</button>
                	</div>
                </form>
			</div>
        </div>
    </div>
@stop