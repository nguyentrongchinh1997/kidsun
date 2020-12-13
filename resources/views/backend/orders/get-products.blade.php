<style type="text/css" media="screen">
	.dataTables_empty{
		text-align: center
	}
</style>
<table border="1" class="products-table" id="table1">
	<thead>
	    <tr>
	        <th style="text-align: center">STT</th>
	        <th style="text-align: center">Sản phẩm</th>
	        <th style="text-align: center">Giá</th>
	        <th style="text-align: center"></th>
	    </tr>
	</thead>
	<tbody>
		@foreach($products as $k =>$item)
		<tr class="parent-tr">
			<td style="text-align: center">
				{{$k+1}}
			</td>
			<td>
				<a href="#" title="Sản phẩm" class="product-link">
					<div class="text-center">
						<img src="{{url('/')}}/{{$item->image}}" style="max-width: 90px; max-height: 90px; width: 100%; height: 100%">
					</div>
					<div class="text-center" style="padding-top: 5px">
						<span class="product-name">{{$item->name}}</span>
					</div>
					<input type="hidden" class="id_product" value="{{$item->id}}">
				</a>
			</td>
			<td>
				<div class="product-prices">
					<span class="price">{!! number_format(@$item->price, 0, '.', '.')!!} đ</span>
					<input class="price-hidden" type="hidden" value="{{@$item->price}}">
				</div>
			</td>
			
			<td class="status">
				<button class="chon-san-pham btn btn-sm btn-success">Thêm</button>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<script type="text/javascript">
	$('#table1').DataTable( {      

             // "searching": false,

             "pageLength": 100000,

             "paging": false, 

             "info": false,

             "ordering": false,      

             "lengthChange":false ,
             language: {
                "sProcessing": "Đang xử lý...",
                "sLengthMenu": "Xem _MENU_ mục",
                "sZeroRecords": "Không tìm thấy sản phẩm nào",
                "sInfo": "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
                "sInfoEmpty": "Đang xem 0 đến 0 trong tổng số 0 mục",
                "sInfoFiltered": "(được lọc từ _MAX_ mục)",
                "sInfoPostFix": "",
                "sSearch": "Tìm:",
                "sUrl": "",
            },
            initComplete : function() {
		        $("#table1_filter").detach().appendTo('.search-css');
		    }

        } );
</script>