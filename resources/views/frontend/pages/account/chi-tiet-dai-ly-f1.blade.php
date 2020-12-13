<?php
    $month = request()->month && request()->month !='' ? request()->month : now()->month;
    $year = request()->year && request()->year !='' ? request()->year : now()->year;
?>
	<link rel="stylesheet" type="text/css" title="" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" title="" href="{{ __BASE_URL__ }}/css/all.fontawesome.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> 
	<script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<style type="text/css" media="screen">
		#table1_paginate{
			float: right;
			margin-left: -30px
		}
		table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting:after {
		    content: "";
		}
		#table1_paginate .pagination a{
			border-radius: unset
		}
		#table1_wrapper{
			display: unset
		}
		.advanced-search-form select{
			font-size: 16px;
    		padding: 2px;
		}
		.advanced-search-form label{
			color: #000
		}
	</style>
	



	<main class="main-site products-site">

		<div class="main-container">

			<div class="container">

				<div class="products-box">
					<div class="products-content">
						<!-- <div class="content-header">
							<div class="advanced-search-block advanced-search-block-2">
								<form class="advanced-search-form">
									<div class="form-content">
										
										<div class="form-group">
											<input type="text" name="start_date" value="{{@$start_date}}" class="form-control search-start" readonly id="startDate" placeholder="{{ trans('message.tu_ngay') }}">
										</div>
										<div class="form-group">
											<input type="text" name="end_date" value="{{@$end_date}}" class="form-control search-input" readonly id="endDate" placeholder="{{ trans('message.den_ngay') }}">
										</div>
										<div class="form-group">
											<button class="btn search-btn">
												<span>{{ trans('message.tim_kiem') }}</span>
											</button>
										</div>
									</div>
								</form>
							</div>
						</div> -->

						<div class="table-content">
							<table border="1" class="products-table">
								<thead>
									<tr>
										<th>STT</th>
										<th>{{ trans('message.ma_don_hang') }}</th>
										<th>{{ trans('message.thanh_tien') }}</th>
										<th>{{ trans('message.ngay_mua') }}</th>
										<th>{{ trans('message.trang_thai') }}</th>
										<th>{{ trans('message.chi_tiet') }}</th>
									</tr>
								</thead>
								<tbody>
									@if(count($orders) == 0)
										<tr>
											<td colspan="6" rowspan="" headers="">Không có đơn hàng nào</td>
										</tr>
									@endif
									@foreach($orders as $k => $item)
									<tr>
										<td>
											<span>{{$k+1}}</span>
										</td>
										<td>
											<a href="#" title="Mã đơn">
												<span>{{$item->mavd}}</span>
											</a>
										</td>
										<td>
											<div class="product-prices">
												<span class="price">{!! number_format(@$item->tongtien, 0, '.', '.')!!} đ</span>
											</div>
										</td>
										<td>
											<span>{{format_datetime($item->ngay_giaodich,'d-m-Y')}}</span>
										</td>
										<td class="status">
											<span>{{ app()->getLocale() == 'vi' ? $item->name_status : $item->nameen_status }}</span>
										</td>
										<td>
											<a href="#" class="code-orders-dt show-order-detal" data-id="{{$item->id}}">
												<i class="far fa-eye icon"></i>
											</a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>

						<!-- <div class="table-footer">
							<div class="product-total">
								<label>Tổng:</label>
								<span>600.000 vnđ</span>
							</div>
						</div> -->
					</div>
				</div>
				
				
				
			</div>				

		</div>
		<input type="hidden" name="" id="url_chitiet_luong" value="{{route('home.doanh-thu')}}">
	</main> 

	<div id="classModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true" style="background: radial-gradient(#787878, transparent)">
	   <div class="modal-dialog modal-lg" style="left: unset;width: 1000px">
	      <div class="modal-content" style="margin-top: 150px">
	         <div class="modal-header" style="padding: unset">
	         	<h4 class="text-center" style="width: 100%;text-align: center">
	         		Chi tiết đơn hàng
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="font-size: 30px;margin-right: 10px">
	            ×
	            </button>
	         	</h4>
	         </div>
	         <div class="modal-body">
	            
	         </div>
	         
	      </div>
	   </div>
	</div>
	<input type="hidden" name="" id="url_chitiet_daily" value="{{route('home.chi-tiet-daily',['id'=>$id])}}">
	<input type="hidden" name="" id="get_url_web" value="{{url('/')}}">
	<script type="text/javascript">
        $('select').change(function () {
            var url = $('#url_chitiet_daily').val();
            var month = $('#monthSelector').val();
            var year = $('.get-year').val();
            var url_browse = $('#get_url_path').val();
            $('.daily-detail-content').html('<img src="'+url_browse+'/public/images/loader.gif'+'">');
            $.ajax({
                url: url+'?year='+year+'&month='+month,
                type:'GET',
                
                success: function(data) {
                    $('.daily-detail-content').html(data);
                }
            });
        });
        $('.products-table .code-orders-dt').click(function(e){
	        e.preventDefault();
			$('#classModal').modal('show');
			
	        var id_order = $(this).data('id');
	        var url_browse = $('#get_url_web').val();
	        $('.modal-body').html('<img src="'+url_browse+'/public/images/loader.gif'+'">');
	        console.log(url_browse);
	        $.ajax({
	            url: url_browse+'/chi-tiet-don-hang/'+id_order,
	            type:'GET',
	            success: function(data) {
	                $('.modal-body').html(data);
	            }
	        });
	        
	    });
    </script>
