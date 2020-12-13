<?php 
    $status = request()->status ? request()->status : 1;

    $month = request()->month && request()->month !='' ? request()->month : now()->month;

    $year = request()->year && request()->year !='' ? request()->year : now()->year;
?>
@extends('backend.layouts.app')
@section('controller', $module['name'] )
@section('controller_route', route($module['module'].'.index'))
@section('action', 'Danh sách')
@section('content')
<style type="text/css" media="screen">
    .dataTables_empty{
        text-align: center
    }
</style>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('flash::message')
                <div style="font-size: 18px;display: inline-flex">
                    <div id="orer-by-time">
                        
                        <label style="margin-right: 10px">Năm:

                            <select id="yearSelector" class="get-year">
                                
                                @for($i=2019;$i<=now()->year;$i++)
                                    <option value="{{$i}}" @if($year == $i) selected="selected" @endif>{{$i}}</option>
                                @endfor

                            </select>

                        </label>

                        <label>Tháng:

                            <select id="monthSelector" class="get-month">

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

                    <div id="orer-by-status" style="margin-left: 50px">
                        <label>Trạng thái:
                            <select name="status">
                                <option @if($status ==1) selected @endif value="1">Chờ duyệt</option>
                                <option @if($status ==2) selected @endif value="2">Thành công</option>
                                <option @if($status ==3) selected @endif value="3">Đã hủy</option>
                            </select>
                        </label>
                    </div>

                </div>

                </br>

                
                <form action="" method="POST">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <input type="hidden" id="url_orders" value="{{route('orders.index')}}">
                    <table id="table-ajax" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th></th>
                                <th>STT</th>
                                <th>Người mua</th>
                                <th>ID</th>
                                <th>Thành tiền</th>
                                <th>Ngày mua</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $('#table-ajax').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('orders.index') !!}',
                    type: 'GET',
                    data: function (d) {
                      // read start date from the element
                      
                       
                      d.month = $('.get-month').val();
                      // read end date from the element
                      d.year = $('.get-year').val();

                      d.status =  $('select[name="status"]').val();
                    }
                },
                // "paging": false,
                pageLength: 25,
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'DT_RowIndex',name: 'DT_RowIndex'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'tongtien', name: 'tongtien'},
                    {data: 'ngay_giaodich', name: 'ngay_giaodich'},
                    {data: 'id_status', name: 'id_status'},
                    {data: 'action', name: 'action'},

                ],
                'columnDefs': [{
                    'targets': [0, 1],
                    'orderable': false,
                    'searchable': false,
                }],
                language: {
                    "sProcessing": "Đang xử lý...",
                    "sLengthMenu": "Xem _MENU_ mục",
                    "sZeroRecords": "Không tìm thấy đơn hàng nào.",
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
            });
        });
        $('#orer-by-time select').change(function () {

            var url = $('#url_orders').val();

            var month = $(this).val();

            var year = $('.get-year').val();

            // var month = $('.get-month').val();

            var status = $('select[name="status"]').val();

            window.location.href = url+'?year='+year+'&month='+month+'&status='+status;

        });

        $('#orer-by-status select').change(function () {

            var url = $('#url_orders').val();

            var month = $('.get-month').val();

            var year = $('.get-year').val();

            // var month = $('.get-month').val();

            var status = $(this).val();

            window.location.href = url+'?year='+year+'&month='+month+'&status='+status;

        });
    </script>
@stop

