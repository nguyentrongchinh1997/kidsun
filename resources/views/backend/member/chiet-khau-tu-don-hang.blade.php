<div class="tab-pane" id="chiet-khau" >
    <table class="products-table dataTable no-footer">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Người nhận</th>
                <th class="text-center">ID</th>
                <th class="text-center">Chiết khấu(%)</th>
                <th class="text-center">Thành tiền</th>
                <th class="text-center">Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            @foreach($log as $k => $item)
            <tr>
                <td>{{$k+1}}</td>
                <td>{{$item->name_nguoinhan}}</td>
                <td>{{$item->user_name}}</td>
                <td>{{$item->phan_tram}}%</td>
                <td>{{number_format($item->money, 0, '.', '.')}}đ</td>
                <td>{{$item->name_status}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>