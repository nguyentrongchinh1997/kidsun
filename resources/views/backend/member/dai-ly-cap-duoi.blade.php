@if(count($daily_f1) == 0)
    <tr>
        <td colspan="7" class="text-center">Không tìm thấy đại lý F1 nào</td>
    </tr>
@endif

<?php $toal_money = 0;$tong=0; ?>

@foreach($daily_f1 as $k => $item)

<?php 

    $dt = App\Http\Controllers\ManagerAccountController::tongtien_Donhang_Thanhcong_Daily($item);

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
        <input type="hidden" value="{{@$dt}}" class="total_money_f1">

    </td>
    <td>
        <a href="{{route('backend.chi-tiet-daily',['id'=>$item->id])}}" class="chi-tiet-dai-ly-modal-f1" data-id="{{$item->id}}">
            Xem
        </a>
    </td>
</tr>
<?php $tong+=$dt; ?>

@endforeach
