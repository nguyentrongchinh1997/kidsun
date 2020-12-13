@extends('frontend.master')
@section('main')
	<?php if(!empty($dataSeo)){
		$content = $dataSeo->meta_description;
	} ?>
	<style type="text/css" media="screen">
		.art-products{
			padding-left: 55px;
		}
		.art-products p{
			color: #000;
			font-size: 16px
		}
		.art-products span{
			font-size: unset !important;
		}
	</style>
	<main class="main-site products-site">

		<div class="main-container">

			<div class="container">
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
						@include('frontend.pages.product.side-nav-left')
					</div>

					<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
						<article class="art-products">
							{!! @$content !!}
						</article>
					</div>
				</div>
			</div>				

		</div>
		<input type="hidden" name="" id="url_chitiet_luong" value="{{route('home.doanh-thu')}}">
	</main>
@stop