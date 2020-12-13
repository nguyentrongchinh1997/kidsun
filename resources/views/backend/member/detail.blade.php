<?php
    $month = request()->month && request()->month !='' ? request()->month : now()->month;
    $year = request()->year && request()->year !='' ? request()->year : now()->year;
?>
@extends('backend.layouts.app')

@section('controller', 'Đại lý' )

@section('controller_route', route('member.index'))

@section('action', 'Thông tin chi tiết')

@section('content')

    <style type="text/css" media="screen">

        .form-group input{

            padding: 20px 10px;

            background-color: #ececff !important;

        }

        .products-table {

            width: 100%;

        }

        .products-table td, .products-table th {

            padding: 15px;

            text-align: center;

            color: #333;

            vertical-align: middle;

        }

        #activity4 input{
            max-width: 463px
        }

    </style>

    <div class="content">

        <div class="clearfix"></div>

        <div class="box box-primary">

            <div class="box-body">

                @include('flash::message')

                    <div class="nav-tabs-custom">

                        <ul class="nav nav-tabs">
                            <li class="@if(!session()->has('errors') && !session()->has('success')) active @endif">
                                   <a href="#activity" data-toggle="tab" aria-expanded="true">Doanh số của đại lý</a>
                            </li>
                            <li class="">
                                <a href="#activity1" data-toggle="tab" aria-expanded="true">Hoa hồng đại lý</a>
                            </li>
                            <li class="">
                                <a href="#activity2" data-toggle="tab" aria-expanded="true">Tiền nạp của đại lý</a>
                            </li>
                            <li class="">
                                <a href="#activity3" data-toggle="tab" data-tab="dsk" aria-expanded="true">Doanh số kênh</a>
                            </li>
                            <li class="@if(session()->get('errors') || session()->get('success')) active @endif">
                                <a href="#activity4" data-toggle="tab" data-tab="dsk" aria-expanded="true">Cập nhập mật khẩu đại lý</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="tab-content" style="display: flex;margin-bottom: 30px">
                                        <div>
                                            <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Xem thông tin chi tiết tài khoản</button>
                                        </div>
                                        <div class="advanced-search-block advanced-search-block-2" style="padding-right: 15px;margin-left: 20px">
                                            <form class="advanced-search-form" id="form-update-time">
                                                <div style="font-size: 18px;display: inline-flex;margin-left: 15px;padding-top: 3px">
                                                  <label style="margin-right: 10px">Năm:
                                                    <select id="yearSelector" class="get-year">
                                                        @for($i=2019;$i<=now()->year;$i++)
                                                            <option value="{{$i}}" @if($year == $i) selected="selected" @endif>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                  </label>
                                                  <label>Tháng:
                                                    <select id="monthSelector">
                                                        @for($i=01;$i<13;$i++)
                                                        @if($i < 10)
                                                        <option value="0{{$i}}" @if($month==$i) selected @endif>{{$i}}</option>
                                                        @else
                                                        <option value="{{$i}}" @if($month==$i) selected @endif>{{$i}}</option>
                                                        @endif
                                                      @endfor
                                                    </select>
                                                  </label>
                                                </div>
                                            </form>
                                        </div> 
                                    </div>

                                    <div id="demo" class="collapse">
                                        <div>
                                            <div class="col-sm-6 form-group">

                                                <label for="">Trạng thái</label></br>

                                                <span class="label label-success">@if(@$member->lock ==0) Đang hoạt động @else Bị khóa @endif</span>

                                            </div>

                                            <div class="col-sm-6" style="margin-bottom: 10px">

                                                <label for="">Link giới thiệu</label>

                                                <div style="position: relative">                                        

                                                    <span id="divClipboard-page" style="color: #ff00eb" class="form-control" name="" >{{url('/')}}?ma-gioi-thieu={{@$member->user_name}}</span>

                                                    <label style="position: absolute;top: 0px;right: 0px;background: #716c72;padding: 7px;cursor: pointer;color: #ffffff;" for="startDate" class="depotit">

                                                        <a href="" title="Copy" class="btn-copy" onclick="copyClipboard_Code('divClipboard-page')">Copy</a>

                                                    </label>

                                                </div>

                                            </div>

                                        </div>

                                        <!-- <div class="col-sm-6 form-group">

                                            <label for="">Cấp bậc đại lý</label>
                                            <select id="update-rank" class="form-control" style="background: #ececff">
                                                <option @if(@$member->code =='CTV') selected @endif value="CTV">Cộng tác viên</option>
                                                <option @if(@$member->code =='DLBL') selected @endif value="DLBL">Đại lý bán lẻ</option>
                                                <option @if(@$member->code =='DLPP') selected @endif value="DLPP">Đại lý phân phối</option>
                                            </select>

                                        </div> -->

                                        <div class="col-sm-6 form-group">

                                            <label for="">User name</label>

                                            <input value="{{@$member->user_name}}" readonly class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">

                                            <label for="">Họ tên chủ đại lý</label>

                                            <input value="{{@$member->full_name}}" readonly class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">

                                            <label for="">Email</label>

                                            <input value="{{@$member->email}}" readonly class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">

                                            <label for="">Số điện thoại</label>

                                            <input value="{{@$member->phone}}" readonly class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">

                                            <label for="">Địa chỉ</label>

                                            <input value="{{@$member->address}}" readonly class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">
                                            <label for="">Người giới thiệu mở tài khoản</label>

                                            <input value="{{@$member->mentor}}" readonly class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">

                                            <label for="">Số tài khoản ngân hàng</label>

                                            <input value="{{@$member->bank_account}}" class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">

                                            <label for="">Tên chủ tài khoản ngân hàng</label>

                                            <input value="{{@$member->bank_account_name}}" readonly class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">

                                            <label for="">Ngân hàng</label>

                                            <input value="{{@$member->bank_name}}" readonly class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">

                                            <label for="">Địa chỉ ngân hàng</label>

                                            <input value="{{@$member->bank_address}}" readonly class="form-control" name="">

                                        </div>

                                        <div class="col-sm-6 form-group">

                                            <label for="">Số chứng minh nhân dân(thẻ căn cước)</label>

                                            <input value="{{@$member->so_cmnd}}" readonly class="form-control" name="">

                                        </div>

                                    </div>
                                </div>
                                @if(request()->type=='f1')
                                <?php $id_mentor = App\Models\Member::where('user_name',$member->mentor)->first()->id; ?>
                                <div class="col-sm-12" style="text-align: right">
                                    <a href="{{route('member.detail',  ['id'=>$id_mentor])}}" title=""><button class="btn btn-primary">Quay lại</button></a>
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane @if(!session()->has('errors') && !session()->has('success')) active @endif" id="activity">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="table2" border="1" class="products-table">

                                            <thead style="background: #f26824">

                                                <tr>

                                                    <th>STT</th>

                                                    <th>Mã đơn hàng</th>

                                                    <th>Tổng tiền</th>

                                                    <th>Ngày mua</th>

                                                    <th>Trạng thái</th>

                                                    <th>Chi tiết</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php $tong = 0; ?>
                                                @if(count($orders) > 0)
                                                @foreach($orders as $k => $item)

                                                <tr>

                                                    <td>

                                                        <span>{{$k+1}}</span>

                                                    </td>

                                                    <td>

                                                        <a href="{{route('member.chiet-khau-tu-don-hang',['id'=>$item->id])}}" class="chiet-khau-don-hang" target="_blank">

                                                            <span>{{$item->mavd}}</span>

                                                        </a>

                                                    </td>

                                                    <td>

                                                        <div class="product-prices">

                                                            <span class="price">{!! number_format(@$item->tongtien, 0, '.', '.')!!} đ</span>

                                                        </div>

                                                    </td>

                                                    <td>

                                                        <span>{{format_datetime($item->created_at,'d-m-Y')}}</span>

                                                    </td>

                                                    <td class="status">

                                                        <span>{{$item->name_status}}</span>

                                                    </td>

                                                    <td>

                                                        <a href="#" class="code-orders show-order-detal" data-id="{{$item->id}}">

                                                            <i class="fa fa-eye fa-fw"></i>

                                                        </a>

                                                    </td>

                                                </tr>
                                                <?php $tong+=$item->tongtien; ?>
                                                @endforeach

                                                @endif

                                            </tbody>
                                            <div class="table-footer">
                                                <div class="product-total" style="font-size: 18px;">
                                                    <label>Tổng doanh thu:</label>
                                                    <span>{!! number_format($tong, 0, '.', '.')!!} đ</span>
                                                </div>
                                            </div>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="activity1">
                                <div class="row">
                                    <div class="col-sm-12">

                                        <table id="table-ajax" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Mã đơn hàng</th>
                                                    <th>Số tiền</th>
                                                    <th>Ngày nhận</th>
                                                    <th>Note</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $tong_hoahong = 0; ?>
                                                @foreach($list_log as $k => $item)
                                                <tr>
                                                    <td colspan="" rowspan="" headers="">{{$k+1}}</td>
                                                    <td colspan="" rowspan="" headers="">{{$item->mavd}}</td>
                                                    <td colspan="" rowspan="" headers="">{{number_format($item->money, 0, '.', '.')}} đ</td>
                                                    <td colspan="" rowspan="" headers="">{{format_datetime($item->ngay_nhan,'d-m-Y')}}</td>
                                                    <td colspan="" rowspan="" headers="">{{$item->name_status}}</td>

                                                </tr>
                                                <?php $tong_hoahong+=$item->money; ?>
                                                @endforeach
                                            </tbody>
                                            <div class="table-footer">
                                                <div class="product-total" style="font-size: 18px;">
                                                    <label>Tổng:</label>
                                                    <span>{!! number_format($tong_hoahong, 0, '.', '.')!!} đ</span>
                                                </div>
                                            </div>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="activity2">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="table1" class="table table-bordered table-striped">

                                            <thead style="background: #c0bcbc">

                                                <tr>

                                                    <th>Người gửi</th>

                                                    <th>Tên ngân hàng</th>

                                                    <th>Số tiền</th>

                                                    <th>Mã giao dịch</th>

                                                    <th>Trạng thái</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach(@$recharge as $item)

                                                <tr>

                                                    <td>{{$item->sender}}</td>

                                                    <td>{{$item->bankname}}</td>

                                                    <td>{!! number_format(@$item->amount_money, 0, '.', ',')!!} đ</td>

                                                    <td>{{$item->trading_code}}</td>

                                                    <td>

                                                        @if($item->id_status == 1)

                                                            <span class="label label-primary">Chờ duyệt</span>

                                                        @elseif($item->id_status == 2)

                                                            <span class="label label-success">Thành công</span>

                                                        @else

                                                            <span class="label label-danger">Đã hủy</span>

                                                        @endif

                                                    </td>

                                                </tr>

                                                @endforeach

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="activity3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" class="url_cap_duoi_1" value="{{$member->id}}">
                                        <div class="text-right back-up-member">
                                            
                                        </div>
                                        <table id="table-3" border="1" class="products-table">

                                            <thead>

                                                <tr>

                                                    <th>STT</th>

                                                    <th>Họ tên</th>

                                                    <th>ID</th>

                                                    <th>Số điện thoại</th>

                                                    <th>Ngày tham gia</th>

                                                    <th>Doanh thu</th>

                                                    <th></th>

                                                </tr>

                                            </thead>

                                            <tbody id="tbody-member-f1">
                                                @if(count($daily_f1) == 0)
                                                    <tr>
                                                        <td colspan="7" class="text-center">Không tìm thấy đại lý F1 nào</td>
                                                    </tr>
                                                @endif
                                                <?php $toal_money = 0; ?>

                                                @foreach($daily_f1 as $k => $item)

                                                <?php $dt = App\Http\Controllers\ManagerAccountController::tongtien_Donhang_Thanhcong_Daily($item);

                                                    $tong+=$dt;

                                                ?>

                                                <tr>

                                                    <td>

                                                        <span>{{$k+1}}</span>

                                                    </td>

                                                    

                                                    <td>

                                                        <span><a class="dai-ly-cap-duoi" href="{{route('member.dai-ly-cap-duoi',['id'=>$item->id])}}">{{$item->full_name}}</a></span>

                                                    </td>

                                                    <td>

                                                        <span><a class="dai-ly-cap-duoi" href="{{route('member.dai-ly-cap-duoi',['id'=>$item->id])}}">{{$item->user_name}}</a></span>

                                                    </td>

                                                    <td>

                                                        <span>{{$item->phone}}</span>

                                                    </td>

                                                    <td>

                                                        <span>{{format_datetime($item->created_at,'d/m/Y')}}</span>

                                                    </td>

                                                    <td class="price">

                                                        <span>{!! number_format(@$dt, 0, '.', '.')!!} đ</span>

                                                    </td>
                                                    <td>
                                                        <a href="{{route('backend.chi-tiet-daily',['id'=>$item->id])}}" class="chi-tiet-dai-ly-modal-f1" data-id="{{$item->id}}">
                                                            Xem
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $toal_money+=$dt; ?>
                                                @endforeach

                                            </tbody>
                                        </table>
                                       
                                    </div>

                                </div>
                                    <div class="table-footer">
                                        <div class="product-total" style="padding-top: 20px;font-size: 18px">
                                            <label>{{ trans('message.tong') }}:</label>
                                            <span class="total-money">{!! number_format($toal_money, 0, '.', '.')!!} đ</span>
                                        </div>
                                    </div>
                            </div>

                            <div class="tab-pane @if(session()->get('errors') || session()->get('success')) active @endif" id="activity4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form action="{{route('member.cap-nhap-mat-khau')}}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="">Nhập mật khẩu mới</label>
                                                <input class="form-control" type="password" name="password" value="{!! old('password') !!}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nhập lại mật khẩu mới</label>
                                                <input class="form-control" type="password" name="password_confirmation" value="{!! old('password_confirmation') !!}">
                                            </div>
                                            <input type="hidden" name="id_daily" value="{{@$member->id}}">
                                            <button type="submit" class="btn btn-primary">Lưu lại</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- </form> -->

            </div>

        </div>

    </div>
    <input type="hidden" value="{{url('/')}}" id="url-website" name="">
    <div class="art-popups art-popups-code-orders">

        <div class="popups-box">

            <div class="popups-content">

                <div class="popup-content active">

                <div class="title-box title-popup">

                    <h3 class="title"><span class="header-text">{{ trans('message.don_hang') }}</span></h3>

                </div>

                <div class="popup-content">

                    <div class="products-content">

                        <div class="table-content order-detail-content text-center">

                            

                        </div>

                    </div>

                </div>

                </div>

            </div>

        </div>

    </div>

    <div class="art-popups art-popups-code-orders-f1">
        <div class="popups-boxs">
            <div class="popups-content" style="padding: 15px 10px 25px 10px">
                <div class="popup-content active">
                <div class="title-box title-popup">
                    <h3 class="title text-center" style="margin-top: unset;margin-bottom: 15px">
                        <span>Đơn hàng của đại lý F1</span>
                        <span type="button" class="close close-popup-order"  style="font-size: 30px">
                    ×
                    </span>
                    </h3>
                    
                </div>
                <div class="popup-content">
                    <div class="products-content">
                        <div class="table-content daily-detail-content-f1 text-center">
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="" id="url_chitiet_daily" value="{{route('member.detail',['id'=>@$member->id])}}">
    <script type="text/javascript">
        function copyClipboard_Code($id) {

            event.preventDefault();

            var elm = document.getElementById($id);

            $('.btn-copy').html('Copied');

            setTimeout(function(){

             $('.btn-copy').html('Copy'); },

            1000);

            if(window.getSelection) {

                var selection = window.getSelection();

                var range = document.createRange();

                range.selectNodeContents(elm);

                selection.removeAllRanges();

                selection.addRange(range);

                document.execCommand("Copy");               

            }

        }
        $('#form-update-time select').change(function () {
            var url = $('#url_chitiet_daily').val();
            var month = $('#monthSelector').val();
            var year = $('.get-year').val();
            var url_browse = $('#get_url_path').val();
            
            window.location.href = url+'?year='+year+'&month='+month;
        });
        $('#filter_date').on('click',function(){
            var url = $(this).data('href');
            var startdate = $('#startDate').val();
            var enddate = $('#endDate').val();

            window.location.href = url+'?startdate='+startdate+'&enddate='+enddate;
        });

        $('.products-table .chiet-khau-don-hang').click(function(e){

            e.preventDefault();

            var url_browse = $('#url-website').val();

            var url = $(this).attr('href');
            $('.order-detail-content').html('<img src="'+url_browse+'/public/images/loader.gif'+'">');

            var hw = $(window).height();

            var hlg = $('.popup-content').height();

            var hpcs = parseInt(hlg) + 60;



            if (hpcs > hw) {

                $('.popups-box').css({'height': hw - 30, 'top': '0'});

            } else {

                $('.popups-box').css({'height': 'auto', 'top': 'auto'});

            }

            $('.header-text').html('Chiết khấu từ đơn hàng');

            $('.art-popups-code-orders').addClass('active');

            $.ajax({

                url: url,

                type:'GET',

                success: function(data) {

                    $('.order-detail-content').html(data);

                }

            });
        });

        $(document).on('click','.chi-tiet-dai-ly-modal-f1',function(e){
            e.preventDefault();
            var _this = $(this);
            var url = _this.attr('href');
            var url_browse = $('#url-website').val();
            $('.daily-detail-content-f1').html('<img src="'+url_browse+'/public/images/loader.gif'+'">');
            var hw = $(window).height();
            var hlg = $('.popup-content').height();
            var hpcs = parseInt(hlg) + 60;

            if (hpcs > hw) {
                $('.popups-box').css({'height': hw - 30, 'top': '0'});
            } else {
                $('.popups-box').css({'height': 'auto', 'top': 'auto'});
            }

            $('.art-popups-code-orders-f1').addClass('active');
            // $('#classModal-f1').modal('show');
            $.ajax({
                url: url,
                type:'GET',
                
                success: function(data) {
                    $('.daily-detail-content-f1').html(data);
                }
            });
        });
        $(document).on('click','.close-popup-order',function(e){
            $('.art-popups-code-orders-f1').removeClass('active');
        });

        function total_money(){
            var money = 0;
            $( ".total_money_f1" ).each(function( index ) {
                money+=parseFloat($(this).val());
            });
            var money_convert = parseInt(money).toLocaleString()+' đ';
            $('.total-money').html(money_convert);
        }
        $(document).on('click','.dai-ly-cap-duoi',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var before_id = $('.url_cap_duoi_1').val();
            var curent_id = $('.id_dai_ly_cd').val();

            $.ajax({
                url: url,

                type:'GET',
                
                success: function(data) {
                    $('#tbody-member-f1').html(data.html);
                    $('.back-up-member').html('<button style="margin-bottom: 5px" type="button" data-id="'+data.id_mentor+'" class="btn btn-sm btn-primary dai-ly-cap-duoi-back" href="'+data.url_back+'">Quay lại</button>');
                    total_money();
                }
            });
            
        });
        $(document).on('click','.dai-ly-cap-duoi-back',function(e){
            e.preventDefault();
            var value = $(this).data('id');

            var url = $(this).attr('href');

            var before_id = $('.url_cap_duoi_1').val();

            $.ajax({
                url: url,

                type:'GET',
                
                success: function(data) {
                    $('#tbody-member-f1').html(data.html);

                    total_money();
                    
                    if(value == before_id){
                        
                        $('.back-up-member').html('');
                        
                    }else{
                        $('.back-up-member').html('<button style="margin-bottom: 5px" type="button" data-id="'+data.id_mentor+'" class="btn btn-sm btn-primary dai-ly-cap-duoi-back" href="'+data.url_back+'">Quay lại</button>');
                    }
                }
            });
            
        });
        $(document).on('click','.nav-tabs > li > a',function(e){
            var tab = $(this).data('tab');
            if(tab=='dsk'){
                $('.advanced-search-block').css('display','none');
            }else{
                $('.advanced-search-block').css('display','');
            }
        });
    </script>
@stop