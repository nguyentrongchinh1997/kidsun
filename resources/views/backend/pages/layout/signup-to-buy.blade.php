@extends('backend.layouts.app')
@section('controller','Trang')
@section('controller_route',route('pages.list'))
@section('action','Danh sách')
@section('content')
	<div class="content">
		<div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
               	@include('flash::message')
               	<form action="{{ route('pages.build.post') }}" method="POST">
					{{ csrf_field() }}
					<input name="type" value="{{ $data->type }}" type="hidden">
					<input name="lang" value="{{ $data->lang }}" type="hidden">

	               	<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Trang</label>
								<input type="text" class="form-control" value="{{ $data->name_page }}" disabled="">
				 				
								@if (\Route::has($data->route))
									<h5>
										<a href="{{ route($data->route) }}" target="_blank">
					                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>
					                        Link: {{ route($data->route) }}
					                    </a>
									</h5>
				                @endif
							</div>
							
						</div>
					</div>
					<?php if(!empty($data->content)){
						$content = json_decode($data->content);
					} ?>
					<div class="nav-tabs-custom">
				        <ul class="nav nav-tabs">
				            <li class="active">
				            	<a href="#seo" data-toggle="tab" aria-expanded="true">Cấu hình trang</a>
				            </li>
				        </ul>
				    </div>
				    <div class="tab-content">
			            <div class="tab-pane active" id="seo">
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label for="">Tiêu đề trang</label>
										<input type="text" name="meta_title" class="form-control" value="{{ @$data->meta_title }}">
									</div>
									<div class="form-group">
										<label for="">Nội dung trang</label>
										<textarea name="meta_description" 
										class="form-control content" rows="5">{!! @$data->meta_description !!}</textarea>
									</div>
									
								</div>
							</div>
			            </div>
			           <button type="submit" class="btn btn-primary">Lưu lại</button>
			        </div>
				</form>
			</div>
		</div>
	</div>
@stop