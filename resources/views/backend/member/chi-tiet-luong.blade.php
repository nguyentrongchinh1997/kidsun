<?php 

    $status = request()->status ? request()->status : '';

    $month = request()->month && request()->month !='' ? request()->month : now()->month;

    $year = request()->year && request()->year !='' ? request()->year : now()->year;

?>

@extends('backend.layouts.app')

@section('controller','Tính lương')

@section('action','Danh sách')

@section('content')

    <style type="text/css" media="screen">

        table .hoan-thanh{

            background-color: #6bd9a6 !important

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

    </style>

    <div class="content">

        <div class="clearfix"></div>

        <div class="box box-primary">

            <div class="box-body">

                @include('flash::message')

                <!-- <div class="alert alert-danger">

                    <span><b> Chú ý:</b> Lương sau khi hoàn tất sẽ không chỉnh sửa được!</span>

                </div> -->

                <div class="row">

                    <div style="font-size: 18px;padding: 0px 15px;display: inline-flex;">

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

                    <a href="{{route('orders.bang-luong')}}" title="">

                        

                        <button class="btn btn-primary" style="float: right;margin-right: 20px" type="">Danh sách</button>

                    </a>

                    </br>

                </div>



                <form action="{{route('orders.xac-nhan-luong')}}" method="POST">

                @csrf

                <div class="row">

                    <input type="hidden" name="" id="url_chitiet_luong" value="{{route('orders.chi-tiet-luong',['id'=>@$member_info->id])}}">

                    <div style="padding: 0px 15px;margin-bottom: 20px;">

                        <h3 class="clearfix">{{@$member_info->full_name}}</h3>

                        <span>

                            {{@$member_info->link_aff}} - @if($member_info->code=='CTV') Cộng tác viên @elseif($member_info->code=='DLBL') Đại lý bán lẻ @elseif($member_info->code=='DLPP') Đại lý phân phối @endif

                        </span>

                    </div>

                    <div class="col-md-2 pr-1">

                      <div class="form-group">

                        <label>Lương đã tính</label>

                        <?php $luong_da_tinh = $data->where('active',1)->sum('money'); ?>

                        <input type="text" class="form-control" readonly="" disabled="" value="{{number_format($luong_da_tinh, 0, '.', '.')}}đ">

                      </div>

                    </div>

                    <div class="col-md-2 px-1">

                      <div class="form-group">

                        <label>Đang chờ</label>

                        <input type="text" class="form-control" readonly="" disabled="" value="{{number_format($data->where('active',0)->sum('money'), 0, '.', '.')}}đ">

                      </div>

                    </div>
                    <?php $bu_tru = isset($luong_thang_da_tinh->bu_tru) ? $luong_thang_da_tinh->bu_tru : 0; ?>
                    <div class="col-md-2 px-1">

                      <div class="form-group">

                        <label>Bù/trừ</label>

                        <input type="number" class="form-control" value="{{ $bu_tru }}" name="bu_tru">
                        <input type="hidden" name="time_tinh_luong" value="{{$year}}-{{$month}}">
                      </div>

                    </div>

                    <div class="col-md-2 px-1">
                      <div class="form-group">
                        <label>Lương thực nhận</label>
                        <input type="text" readonly="" class="form-control" value="{{number_format($luong_da_tinh+$bu_tru, 0, '.', '.')}}đ ">
                      </div>
                    </div>

                    <div class="col-md-4 pl-1">

                      <div class="form-group">

                        <label>Nội dung</label>

                        <input type="text" class="form-control" name="noi_dung">

                      </div>

                    </div>

                </div>

                <br>



                

               

                    <div class="form-group">

                        <div class="form-check">

                          <label class="form-check-label">

                            <!-- <input class="form-check-input" type="checkbox" value="" id="chkConfirm"> -->

                           

                            <button class="btn btn-primary"  onclick="return confirm('Bạn có chắc chắn xác nhận ?')" class="form-check-sign">Xác nhận lương</button>
                           <!--  @if($data->where('active',0)->sum('money') !=0)

                            <button class="btn btn-primary"  onclick="return confirm('Bạn có chắc chắn xác nhận ?')" class="form-check-sign">Xác nhận lương cho đại lý.</button>

                            @else

                            <button class="btn btn-primary"  onclick="return confirm('Bạn có chắc chắn xác nhận ?')" class="form-check-sign">Xác nhận lại lương cho dại lý.</button>

                            @endif -->
                            

                          </label>

                        </div>

                    </div>



                    <input type="hidden" name="id_daily" value="{{$member_id}}">

                    <input type="hidden" name="money" value="{{$data->sum('money')}}">

                    <table id="example1" class="table table-bordered table-striped">

                        <thead>

                            <tr>

                                <th>STT</th>

                                <th>Hoa hồng từ</th>

                                <th>ID</th>

                                <th>Mã đơn hàng</th>

                                <th>Số tiền</th>

                                <th>Ngày nhận</th>

                                <th>Trạng thái</th>

                                <th>Note</th>

                                

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($data as $item)

                            <tr @if($item->active == 1) class="hoan-thanh" @endif>

                                <input type="hidden" name="checked_id[]" value="{{$item->id}}">

                                <td>{{ $loop->index +1 }}</td>

                                <td>

                                   {{ $item->name_capduoi !='' ? $item->name_capduoi : 'Doanh số nhập' }}

                                </td>

                                <td>
                                    
                                   {!! $item->get_Member($item->id_capduoi,$member_info->user_name) !!}

                                </td>

                                <td><a href=""><span class="code-orders" data-id="{{$item->id_donhang}}">{{ $item->mavd }}</span></a></td>

                                <td>{{number_format($item->money, 0, '.', '.')}}đ</td>

                                <td>{{format_datetime($item->ngay_nhan,'d-m-Y')}}</td>

                                <td>

                                    @if($item->active == 0) Đang chờ @else Đã hoàn thành @endif

                                </td>

                                <td>{{ $item->name_status }}</td>

                                

                                <!-- <td>

                                    <a href="" class="btn-destroy">

                                        <i class="fa fa-unlock-alt"></i> Xem

                                    </a>

                                </td> -->

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </form>

           </div>

        </div>

    </div>

    <div class="art-popups art-popups-code-orders">

        <div class="popups-box">

            <div class="popups-content">

                <div class="popup-content active">

                <div class="title-box title-popup">

                    <h3 class="title text-center"><span class="header-text">{{ trans('message.don_hang') }}</span></h3>

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
    <input type="hidden" value="{{url('/')}}" id="url-website" name="">

    @section('scripts')

    <script type="text/javascript">

        $('select').change(function () {

            var url = $('#url_chitiet_luong').val();

            var month = $(this).val();

            var year = $('.get-year').val();

            // var month = $('.get-month').val();

            window.location.href = url+'?year='+year+'&month='+month;

        });

        $(document).on('click','.code-orders',function(e){


            e.preventDefault();

            var id_order = $(this).data('id');

            var url_browse = $('#url-website').val();

            $('.order-detail-content').html('<img src="'+url_browse+'/public/images/loader.gif'+'">');

            var hw = $(window).height();

            var hlg = $('.popup-content').height();

            var hpcs = parseInt(hlg) + 60;



            if (hpcs > hw) {

                $('.popups-box').css({'height': hw - 30, 'top': '0'});

            } else {

                $('.popups-box').css({'height': 'auto', 'top': 'auto'});

            }


            $('.header-text').html('Chi tiết đơn hàng');

            $('.art-popups-code-orders').addClass('active');

            $.ajax({

                url: url_browse+'/backend/chi-tiet-don-hang/'+id_order,

                type:'GET',

                success: function(data) {

                    $('.order-detail-content').html(data);

                }

            });

            

        });

    </script>

    @endsection

@stop