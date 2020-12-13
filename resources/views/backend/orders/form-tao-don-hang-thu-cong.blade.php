<?php 
    $start_date = date("d-m-Y");
?>
@extends('backend.layouts.app')
@section('controller','Tạo đơn hàng thủ công')
@section('action','Thêm mới')
@section('content')
<style type="text/css" media="screen">
    .products-table {
        width: 100%;
    }
    .products-table td, .products-table th {
        padding: 10px;
        text-align: center;
        color: #333;
    }
    .product-qty{
        padding: 4px;
        text-align: center;
        max-width: 100px;
    }
    .products-content-show::-webkit-scrollbar,.table-content::-webkit-scrollbar{
      width: 5px;
      background-color: #F5F5F5;
    }
    .products-content-show::-webkit-scrollbar-thumb,.table-content::-webkit-scrollbar-thumb {
        background-color: #000000;
    }
    .products-content-show::-webkit-scrollbar-track,.table-content::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 4px rgba(0,0,0,0.3);
      background-color: #F5F5F5;
    }
    .table-content{
        max-height: 600px;
        overflow: auto;
    }
    .disabled{
        pointer-events: none;
        cursor: pointer;
    }
    .product-image img{
        max-width: 100px; 
        max-height: 100px; 
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 5px
    }
    .content-form-order{
        max-width: 90%;
        margin: auto
    }
    /* The container */
    .container-checkbox {
        position: relative;
        padding-left: 25px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .container-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom checkbox */
    .container-checkbox .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        border: 1px solid;
        background-color: #ffffff;
    }

    /* On mouse-over, add a grey background color */
    .container-checkbox:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .container-checkbox input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .container-checkbox .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .container-checkbox input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .container-checkbox .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }


    /* The container */
    .container-radio {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default radio button */
    .container-radio input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom radio button */
    .container-radio .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
        border-radius: 50%;
    }

    /* On mouse-over, add a grey background color */
    .container-radio:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the radio button is checked, add a blue background */
    .container-radio input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the indicator (the dot/circle - hidden when not checked) */
    .container-radio .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the indicator (dot/circle) when checked */
    .container-radio input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the indicator (dot/circle) */
    .container-radio .checkmark:after {
        top: 9px;
        left: 9px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: white;
    }
</style>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('flash::message')
                <form action="{{route('orders.post-tao-don-hang-thu-cong')}}" id="post-tao-don-hang" method="POST">
                    @csrf
                    <div class="col-sm-8" style="margin-bottom: 20px;">
                        <div>
                            <div class="col-sm-6" style="padding-left: unset">
                                <div class='input-group date' id='datetimepicker1'>
                                    <label class="input-group-addon" for="startDate">
                                        Chọn ngày tạo
                                    </label>
                                    <input type='text' value="{{@$start_date}}" class="form-control" readonly id="startDate" name="startDate" placeholder="Từ ngày" />
                                    <label class="input-group-addon" for="startDate">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5" style="padding-left: unset">
                                <div class='input-group date' id='datetimepicker1'>
                                    <label class="input-group-addon" for="startDate">
                                        User name
                                    </label>
                                    <input type='text' value="{!! old('user_name') !!}" class="form-control" name="user_name" placeholder="Nhập user name cần tạo đơn hàng" />
                                    
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-success btn-create-order" type="">Tạo đơn hàng</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="status" value="2">
                    <input type="hidden" class="redirect-url-order" value="{{route('orders.index')}}">
                    <div class="content-form-order">
                        <div class="col-sm-12 product-total" style="padding-bottom: 10px;padding-top: 15px;font-size: 16px;text-align: right">

                            <label>Tổng tiền:</label>

                            <span class="total-cart">{{number_format(Cart::total(), 0, '.', '.')}} đ</span>

                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                        <article class="art-products">

                            <div class="products-box">

                                <div class="products-content">

                                    <div class="table-content">

                                        <table border="1" class="products-table">

                                            <thead>

                                                <tr>

                                                    <th>Hình ảnh sản phẩm</th>

                                                    <th>Tên sản phẩm</th>

                                                    <th>Giá bán</th>

                                                    <th>Số lượng</th>

                                                    <th>Thành tiền</th>

                                                    <th>Chọn</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach($product as $k => $item)
                                                    <tr class="parent-orders" style="font-size: 16px">

                                                        <td>

                                                            <div class="product-box">

                                                                <div class="product-image">
                                                                    <img src="{{url('/')}}/{{$item->image}}">
                                                                </div>

                                                                <!-- <div class="product-content">

                                                                    <h4 class="product-name">

                                                                        <a href="#" class="product-link">{{$item->name}}</a>
                                                                        <input type="hidden" class="product_price" name="price_product[{{$item->id}}]" value="{{$item->price}}">
                                                                    </h4>

                                                                </div>  --> 

                                                            </div>

                                                        </td>
                                                        <td>
                                                            <h4 class="product-name">
                                                                {{$item->name}}
                                                                <input type="hidden" class="product_price" name="price_product[{{$item->id}}]" value="{{$item->price}}">
                                                            </h4>
                                                        </td>

                                                        <td>

                                                            <div class="product-prices">

                                                                <span class="price">{{number_format($item->price, 0, '.', '.')}}đ</span>

                                                            </div>

                                                        </td>

                                                        <td>
                                                            <div class="qty">

                                                                <button class="btn icon-minus icon-minus-pre"><i class="fa fa-minus"></i></button>

                                                                <input class="product-qty" type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" min="1" name="qty_product[{{$item->id}}]"  value="1" readonly>

                                                                <button class="btn icon-minus icon-minus-next"><i class="fa fa-plus"></i></button>

                                                            </div>

                                                        </td>

                                                        <td>

                                                            <div class="product-prices">

                                                                <span class="price-total cartitem-price">{{number_format($item->price, 0, '.', '.')}}đ</span>
                                                                <input type="hidden" class="price-total-fm" value="{{$item->price}}">

                                                            </div>

                                                        </td>

                                                        <td>    

                                                            <label class="container-checkbox">

                                                                <input type="checkbox" class="product_checked" name="id_checked[]" value="{{$item->id}}">
                                                                <span class="checkmark"></span>

                                                            </label>

                                                        </td>

                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>
                                        
                                    </div>

                                    <!-- <div class="table-footer" style="display: inline-block;width: 100%;    margin-top: 10px">

                                        <div class="product-total" style="float: right">

                                            <label>Tổng tiền:</label>

                                            <span class="total-cart">{{number_format(Cart::total(), 0, '.', '.')}} đ</span>

                                        </div>

                                        <div style="float: left">
                                           
                                            <button class="btn btn-success btn-create-order" type="">Tạo đơn hàng</button>
                                        </div>

                                    </div> -->
                                </div>

                            </div>

                        </article>

                    </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header text-center">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Chọn sản phẩm cho đơn hàng</h4>
            </div>
            <div class="search-css" style="margin-right: 20px;margin-top: 10px">
                
            </div>
            <div class="modal-body products-content-show" style="max-height: 550px;overflow: auto;text-align: center">
              <!-- <a class="insidemodal">Some text in the modal.</a>
              
              <div class="abc" style="display:none">I am hidden unless clicked by an anchor</div> -->
            </div>
            
          </div>
          
        </div>
    </div>
    <script type="text/javascript">
        var bindDateRangeValidation = function (f, s, e) {

            if(!(f instanceof jQuery)){

                    console.log("Not passing a jQuery object");

            }

            var jqForm = f,

                startDateId = s,

                endDateId = e;

          

            var checkDateRange = function (startDate, endDate) {

                var isValid = (startDate != "" && endDate != "") ? startDate <= endDate : true;

                return isValid;

            }



            var bindValidator = function () {

                var bstpValidate = jqForm.data('bootstrapValidator');

                var validateFields = {

                    startDate: {

                        validators: {

                            notEmpty: { message: 'This field is required.' },

                            callback: {

                                message: 'Start Date must less than or equal to End Date.',

                                callback: function (startDate, validator, $field) {

                                    return checkDateRange(startDate, $('#' + endDateId).val())

                                }

                            }

                        }

                    },

                    endDate: {

                        validators: {

                            notEmpty: { message: 'This field is required.' },

                            callback: {

                                message: 'End Date must greater than or equal to Start Date.',

                                callback: function (endDate, validator, $field) {

                                    return checkDateRange($('#' + startDateId).val(), endDate);

                                }

                            }

                        }

                    },

                    customize: {

                        validators: {

                            customize: { message: 'customize.' }

                        }

                    }

                }

                if (!bstpValidate) {

                    jqForm.bootstrapValidator({

                        excluded: [':disabled'], 

                    })

                }

                jqForm.bootstrapValidator('addField', startDateId, validateFields.startDate);

                jqForm.bootstrapValidator('addField', endDateId, validateFields.endDate);

            };

            var hookValidatorEvt = function () {

                var dateBlur = function (e, bundleDateId, action) {

                    jqForm.bootstrapValidator('revalidateField', e.target.id);

                }

            }

            bindValidator();

            hookValidatorEvt();

        };

        $(function () {

            var sd = @if($start_date !='') '{{$start_date}}' @else new Date() @endif;

            var ed = new Date();

            $('#startDate').datetimepicker({ 

              pickTime: false, 

              format: "DD-MM-YYYY", 

              maxDate: ed 

            });


            bindDateRangeValidation($("#form"), 'startDate', 'endDate');

        });
    </script>
    <script type="text/javascript">

        function add_price_new_next(this_){
            qty_old=0;
            var parent = this_.parents('tr');
            var qty_old = parent.find('.product-qty').val();
            qty = parseFloat(qty_old)+1;
            parent.find('.product-qty').val(qty);
            var parent_tr = this_.parents('.parent-orders');
            var product_price = parent_tr.find('.product_price').val();
            if(typeof product_price != 'undefined'){
                price_new = qty*product_price;
                price_new_convert = parseInt(price_new).toLocaleString()+' đ';
                parent_tr.find('.price-total').html(price_new_convert);
                parent_tr.find('.price-total-fm').val(price_new);
            }
        }

        function add_price_new_pre(this_){
            var parent = this_.parents('tr');
            var qty_old = parent.find('.product-qty').val();
            if(qty_old > 1){
                qty_old = parseFloat(qty_old)-1;
            }
            parent.find('.product-qty').val(qty_old);
            var parent_tr = this_.parents('.parent-orders');
            var product_price = parent_tr.find('.product_price').val();
            if(typeof product_price != 'undefined'){
                price_new = qty_old*product_price;
                price_new_convert = parseInt(price_new).toLocaleString()+' đ';
                parent_tr.find('.price-total').html(price_new_convert);
                parent_tr.find('.price-total-fm').val(price_new);
            }
        }

        $(document).on('click','.icon-minus-next',function(e){
            e.preventDefault();
            var this_ = $(this);
            add_price_new_next(this_);
            total_money();
            
        });
        $(document).on('click','.icon-minus-pre',function(e){
            e.preventDefault();
            var this_ = $(this);
            add_price_new_pre(this_);
            total_money();
        });

        $(document).on('click','.btn-create-order',function(e){
            e.preventDefault();
            var _this = $(this);
            _this.addClass('disabled');
            var url = $('#post-tao-don-hang').attr('action');
            var formData = new FormData($('#post-tao-don-hang')[0]);
            var url_redirect = $('.redirect-url-order').val();
            $.ajax({
                url: url,
                type:'POST',
                contentType: false,
                processData: false,
                data: formData,
                success: function(data) {
                    _this.removeClass('disabled');
                    if(data.error){
                        var error_user = '';
                        var error_product = '';
                        if(data.error.user_name){
                            error_user = data.error.user_name;
                        }
                        if(data.error.id_checked){
                            error_product = data.error.id_checked;
                        }
                        toastr["error"](error_user+'<br>'+error_product, "Thông báo");
                    }else{
                        if(data.status ==1){
                            toastr["success"](data.toastr, "Thông báo");
                            setTimeout(function(){ window.location.href = url_redirect; }, 1500);
                        }
                    }
                   
                }
            });
        });

        $('#myModal').on('hidden.bs.modal', function () {
            $('.search-css').html('');
        });
        function total_money(){
            var total_price = 0;
            $('.product_checked:checked').each(function () {
                // var sThisVal = (this.checked ? $(this).val() : "");
                var total = $(this).parents('tr').find('.price-total-fm').val();
                total_price+=parseInt(total);
            });
            var price_total_convert = total_price.toLocaleString()+' đ';
            $('.total-cart').html(price_total_convert);
        }
        $('input[type=checkbox]').click(function() {
            if($(this).is(':checked')) {
                total_money();
            } else {
                total_money();            }
        });
    </script>
@stop