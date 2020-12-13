<?php 

    $status = request()->status ? request()->status : '';

    $month = request()->month && request()->month !='' ? request()->month : now()->month;

    $year = request()->year && request()->year !='' ? request()->year : now()->year;

    $tab = request()->tab && request()->tab !='' ? request()->tab : 'no';

?>

@extends('backend.layouts.app')

@section('controller','Bảng lương')

@section('action','Danh sách')

@section('controller_route', route('orders.index'))

@section('content')

    <div class="content">

        <div class="clearfix"></div>

        <div class="box box-primary">

            <div class="box-body">

                @include('flash::message')

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                       <li data-value="no" class="@if($tab=='no') active @endif">
                               <a href="#activity" data-toggle="tab" aria-expanded="true">Danh sách chưa tính lương</a>
                        </li>
                        <li data-value="yes" class="@if($tab=='yes') active @endif">
                            <a href="#activity1" data-toggle="tab" aria-expanded="true">Danh sách đã tính lương</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div class="row">
                        <div class="col-sm-12">
                                <div class="advanced-search-block advanced-search-block-2" style="padding-right: 15px;margin-left: 20px;display: inline-flex">
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
                                    <form action="{{route('orders.export-bang-luong')}}" method="POST" accept-charset="utf-8">
                                        @csrf
                                        <input type="hidden" name="year" value="{{$year}}">
                                        <input type="hidden" name="month" value="{{$month}}">
                                        <input type="hidden" name="key_luong" value="no">
                                        <button type="submit" class="btn btn-primary" style="margin-left: 50px">Xuất excel</button>
                                    </form>
                                </div> 
                            <div class="tab-content" style="margin-bottom: 30px">
                                <div class="tab-pane active" id="activity">
                                    <div class="clear-fix">
                                        <h3 style="background: #fe7676;padding: 5px 10px;border-radius: 3px;text-align: center">Danh sách chưa tính lương
                                        </h3>
                                        <form>
                                            <input type="hidden" id="url_bang_luong" value="{{route('orders.bang-luong')}}">

                                            <table id="table1" class="table table-bordered table-striped">

                                            <thead>

                                                <tr>

                                                    <th>STT</th>

                                                    <th>Họ và tên</th>

                                                    <th>ID</th>

                                                    <th>Số điện thoại</th>

                                                    <th>Tổng lương</th>

                                                    <th>Trạng thái</th>

                                                    <th class="text-center">Hành động</th>

                                                    

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach ($luong as $item)

                                                <tr class="hoan-thanh">

                                                    

                                                    <td>{{ $loop->index +1 }}</td>

                                                    <td>{{ $item->full_name }}</td>

                                                    <td>

                                                       {{$item->user_name}}

                                                    </td>

                                                    <td>

                                                       {{$item->phone}}

                                                    </td>

                                                    <td>{{number_format($item->doanh_Thu($item->id,$year,$month), 0, '.', '.')}}đ</td>

                                                    

                                                    <td>

                                                        Đang chờ

                                                    </td>

                                                    

                                                    <td class="text-center">

                                                        <a href="{{route('orders.chi-tiet-luong',['id' => $item->id ])}}?year={{$year}}&month={{$month}}" class="btn-destroy label label-primary">

                                                            <i class="fa fa-pencil fa-fw"></i> Xác nhận

                                                        </a>

                                                    </td>

                                                </tr>

                                                @endforeach

                                            </tbody>

                                            </table>

                                        </form>

                                    </div>
                                </div>
                                 <div class="tab-pane" id="activity1">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3 style="background: #aafe76;padding: 5px 10px;border-radius: 3px;text-align: center">Đã hoàn thành
                                            </h3>
                                            <form>
                                                <table id="table2" class="table table-bordered table-striped">

                                                    <thead>

                                                        <tr>

                                                            <th>STT</th>

                                                            <th>Họ và tên</th>

                                                            <th>ID</th>

                                                            <th>Số điện thoại</th>

                                                            <th>Tổng lương</th>

                                                            <th>Trạng thái</th>

                                                            <th class="text-center">Hành động</th>

                                                            

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        @foreach ($bangluong as $item)

                                                        <tr class="hoan-thanh">

                                                            

                                                            <td>{{ $loop->index +1 }}</td>

                                                            <td>{{ $item->full_name }}</td>

                                                            <td>

                                                               {{$item->user_name}}

                                                            </td>

                                                            <td>

                                                               {{$item->phone}}

                                                            </td>

                                                            <td>{{number_format($item->money+$item->bu_tru, 0, '.', '.')}}đ</td>

                                                            

                                                            <td>

                                                                Hoàn thành

                                                            </td>

                                                            

                                                            <td class="text-center">

                                                                <a href="{{route('orders.chi-tiet-luong',['id' => $item->id ])}}?year={{$year}}&month={{$month}}" class="btn-destroy label label-primary">

                                                                    <i class="fa fa-pencil fa-fw"></i> Xác nhận

                                                                </a>

                                                            </td>

                                                        </tr>

                                                        @endforeach

                                                    </tbody>

                                                </table>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- <div style="font-size: 18px">

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

                </div> -->

                </br>

                @include('flash::message')

                

               



                <div>

                    

                </div>

           </div>

        </div>

    </div>

    @section('scripts')

    

    <script type="text/javascript">
        $("ul.nav-tabs").on('click', 'li' ,function() {
            var value = $(this).data('value');
            $('input[name="key_luong"]').val(value);
        /* Your function code goes here. */

        });
        $(document).ready(function() {

            $('#table1,#table2').DataTable( {      

                 // "searching": false,

                 "paging": true, 

                 "info": false,         

                 "lengthChange":false ,

                 language: {

                    "sProcessing": "Đang xử lý...",

                    "sLengthMenu": "Xem _MENU_ mục",

                    "sZeroRecords": "Không tìm thấy dòng nào phù hợp",

                    "sInfo": "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",

                    "sInfoEmpty": "Đang xem 0 đến 0 trong tổng số 0 mục",

                    "sInfoFiltered": "(được lọc từ _MAX_ mục)",

                    "sInfoPostFix": "",

                    "sSearch": "Tìm:",

                    "sUrl": "",

                    "oPaginate": {

                        "sFirst": "Đầu",

                        "sPrevious": "Trước",

                        "sNext": "Tiếp",

                        "sLast": "Cuối"

                    }

                }

               

            } );

        });

        $('select').change(function () {

            var url = $('#url_bang_luong').val();

            var month = $(this).val();

            var year = $('.get-year').val();

            // var month = $('.get-month').val();

            var tab = $('input[name="key_luong"]').val();

            window.location.href = url+'?year='+year+'&month='+month+'&tab='+tab;

        });

    </script>

    @endsection

@stop