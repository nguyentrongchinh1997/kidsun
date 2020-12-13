<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DataTables;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\Order;
use App\Models\Rank;
use App\Models\Log_profits;
use App\Models\Order_detail;
use App\Models\Quyenloi;
use App\Models\Products;
use App\Models\BangLuong;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function fields()
    {
        return [
            'name' => 'required',
            'name_en' => 'required',
            'image' => 'required',
            'price' => 'required',
        ];
    }
    


    protected function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm không được bỏ trống.',
            'name_en.required' => 'Tên sản phẩm bằng tiếng anh không được bỏ trống.',
            'image.required' => 'Bạn chưa chọn hình ảnh cho sản phẩm.',
            'price.required' => 'Giá sản phẩm không được bỏ trống.',
        ];
    }

    
    protected function messages_edit($name)
    {
        return [
            'name.required' => $name.' không được bỏ trống.',
            'name.unique' => $name.' đã tồn tại.',
        ];
    }
    protected function module(){
        return [
            'name' => 'Đơn hàng',
            'module' => 'orders',
            'table' =>[
                
                'mavd' => [
                    'title' => 'Mã đơn hàng', 
                    'with' => '',
                ],

                
                'tongtien' => [
                    'title' => 'Thành tiền', 
                    'with' => '',
                ],
                'ngay_giaodich' => [
                    'title' => 'Ngày mua', 
                    'with' => '',
                ],
                'id_status' => [
                    'title' => 'Trạng thái', 
                    'with' => '',
                ],
            ]
        ];
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            if(!$request->month){
                $month = date('m');
            }else{
                $month = $request->month;
            }
            if(!$request->year){
                $year = date('yy');
            }else{
                $year = $request->year;
            }

            $status = request()->status ? request()->status : 1;
            $list_order = Order::select('orders.*','status.name as name_status','member.full_name','member.user_name')
            ->join('status','status.id','=','orders.id_status')
            ->join('member','member.id','=','orders.id_member')
            ->where(function($q) use ($status,$year,$month) {
                $start_format = $year.'-'.$month.'-01';
                   
                $end_format = $year.'-'.$month.'-31';

                $q->where('orders.id_status',$status);

                $q->whereBetween('orders.ngay_giaodich', [$start_format, $end_format]);
                
            })
            ->orderBy('orders.id', 'desc')->get();

            
            return Datatables::of($list_order)
                ->addColumn('checkbox', function ($data) {
                    return '<input type="checkbox" name="chkItem[]" value="' . $data->id . '">';
                })->addColumn('full_name', function ($data) {
                    return $data->full_name;
                })->addColumn('user_name', function ($data) {
                    return $data->user_name;
                })->addColumn('tongtien', function ($data) {
                    return number_format($data->tongtien, 0, '.', '.'). ' VNĐ';
                })->addColumn('ngay_giaodich', function ($data) {
                    return format_datetime($data->ngay_giaodich,'d-m-Y');
                })->addColumn('id_status', function ($data) {
                    if ($data->id_status == 1) {
                        $status = ' <span class="label label-primary">'.$data->name_status.'</span>';
                    } elseif($data->id_status == 2) {
                        $status = ' <span class="label label-success">'.$data->name_status.'</span>';
                    }else{
                        $status = ' <span class="label label-danger">'.$data->name_status.'</span>';
                    }
                    
                    return $status;
                })->addColumn('action', function ($data) {
                    return '<a href="' . route('orders.edit', ['id' => $data->id ]) . '" title="Xem">
                            <i class="fa fa-pencil fa-fw"></i> Xem
                        </a> &nbsp; &nbsp; &nbsp;
                            <a href="javascript:;" class="btn-destroy" 
                            data-href="' . route('orders.destroy', $data->id) . '"
                            data-toggle="modal" data-target="#confim">
                            <i class="fa fa-trash-o fa-fw"></i> Xóa</a>
                        ';
                })->rawColumns(['checkbox','full_name','user_name','tongtien', 'ngay_giaodich','id_status','action'])
                ->addIndexColumn()
                ->make(true);
        }
        $data['module'] = $this->module();
        return view("backend.{$this->module()['module']}.list", $data);
    }
    public function create(Request $request)
    {   
        $order = Order::findOrFail($request->id);
        if($order){
            $order->update(['id_status'=>$request->status]);
        }
        return redirect()->route('orders.edit', ['id' => $request->id ]);
    }

    public function update_Bangluong($id_daily,$time){
        $luong = BangLuong::where(['id_daily' => $id_daily,'luong_thang'=>$time])->first();
        if($luong){
            $luong->update(['status'=>0]);
        }
    }

    public function create_profits($id_donhang,$id_nguoinhan,$name_nguoinhan,$money,$id_status,$phan_tram,$ngay_nhan){
        $log_profits = new Log_profits;
        $log_profits->id_donhang = $id_donhang;
        $log_profits->id_nguoinhan = $id_nguoinhan;
        $log_profits->name_nguoinhan = $name_nguoinhan;
        $log_profits->money = $money;
        $log_profits->id_status = $id_status;
        $log_profits->phan_tram = $phan_tram;
        $log_profits->ngay_nhan = $ngay_nhan;
        $log_profits->save();
    }

    public function tinh_Hoa_Hong($order,$request){
        if($request->status == 2){

            $id_member = $order->id_member;

            $member = Member::find($id_member);

            $mentor = Member::where('user_name',$member->mentor)->first();

            $quyenloi = Quyenloi::first();
            
            $hhds_dlbl = $quyenloi->hhds_dlbl;
            $hhm_dlbl = $quyenloi->hhm_dlbl;
            $hhds_dlpp = $quyenloi->hhds_dlpp;
            $hhtk_dlbl = $quyenloi->hhtk_dlbl;
            $hhtk_dlpp = $quyenloi->hhtk_dlpp;
            $order_success = Order::where(
                [
                    'id_member' => $id_member,
                    'id_status' => 2
                ]
            )->get();

            /* Lấy số tiền của cấp đại lý */
            $rank = Rank::all();
            $tien_dlbl = 0;
            $tien_dlpp = 0;
            foreach ($rank as $val) {
                if($val->key==1){
                    $tien_dlbl = $val->total;
                }
                if($val->key==2){
                    $tien_dlpp = $val->total;
                }
            }

            $capdo = $member->code;

            $tongtien_truoc = 0;
            foreach ($order_success as $value) {
                $tongtien_truoc+=$value->tongtien;
            }
            $tien_donhang_hientai = $order->tongtien;

            $tongtien = $tien_donhang_hientai+$tongtien_truoc;

            $timestamp = strtotime($order->ngay_giaodich);

            $time_bangluong = date ('Y-m', $timestamp);
            
            if($capdo =='CTV'){

                /*  Tiền cộng cho chính đại lý đó khi nạp hơn 6tr  */
                if($tongtien >= $tien_dlbl && $tongtien < $tien_dlpp){
                    
                    $member->code = 'DLBL';
                    $member->save();
                    if($tongtien > $tien_dlbl){
                        $sodu = $tien_donhang_hientai-($tien_dlbl-$tongtien_truoc);

                        $this->create_profits($order->id, $member->id, $member->full_name, $sodu*$hhds_dlbl/100, 4, $hhds_dlbl, $order->ngay_giaodich);
                        
                        $this->update_Bangluong($member->id,$time_bangluong);
                    }
                }
               
                if($tongtien >= $tien_dlpp){
                    $member->code = 'DLPP';
                    $member->save();
                    
                    $this->create_profits($order->id, $member->id, $member->full_name, ($tien_dlpp-$tien_dlbl)*$hhds_dlbl/100, 4, $hhds_dlbl, $order->ngay_giaodich);

                    if($tongtien > $tien_dlpp){
                        $sodu = $tongtien-$tien_dlpp;

                        $this->create_profits($order->id, $member->id, $member->full_name, $sodu*$hhds_dlpp/100,6,$hhds_dlpp, $order->ngay_giaodich);
                    }

                    $this->update_Bangluong($member->id,$time_bangluong);
                }


                /*  Tiền cộng cho đại lý giới thiệu  */
                // $mentor = Member::where('id',$member->mentor)->first();
                if($mentor){
                    if($mentor->code == 'DLBL' || $mentor->code == 'DLPP'){

                        if($mentor->code == 'DLBL'){

                            $log_profits = new Log_profits;
                            $log_profits->id_donhang = $order->id;
                            $log_profits->id_nguoinhan = $mentor->id;
                            $log_profits->name_nguoinhan = $mentor->full_name;
                            $log_profits->id_capduoi = $member->id;
                            $log_profits->name_capduoi = $member->full_name;
                            $log_profits->id_status = 5;
                            $log_profits->phan_tram = $hhm_dlbl;
                            $log_profits->ngay_nhan = $order->ngay_giaodich;
                            if($tongtien <= $tien_dlbl){
                                $log_profits->money = $tien_donhang_hientai*$hhm_dlbl/100;
                            }else{
                                $log_profits->money = ($tien_dlbl-$tongtien_truoc)*$hhm_dlbl/100;
                            }
                            $log_profits->save();

                            $this->update_Bangluong($mentor->id,$time_bangluong);

                        }else{

                            if($tongtien <= $tien_dlbl){
                                $log_profits = new Log_profits;
                                $log_profits->id_donhang = $order->id;
                                $log_profits->id_nguoinhan = $mentor->id;
                                $log_profits->name_nguoinhan = $mentor->full_name;
                                $log_profits->id_capduoi = $member->id;
                                $log_profits->name_capduoi = $member->full_name;
                                $log_profits->id_status = 5;
                                $log_profits->phan_tram = $hhds_dlpp;
                                $log_profits->ngay_nhan = $order->ngay_giaodich;
                                $log_profits->money = $tien_donhang_hientai*$hhds_dlpp/100;
                                $log_profits->save();
                            }elseif($tongtien > $tien_dlbl && $tongtien <= $tien_dlpp){

                                $this->create_profits($order->id, $mentor->id, $mentor->full_name, ($tien_dlbl-$tongtien_truoc)*$hhds_dlpp/100, 5, $hhds_dlpp, $order->ngay_giaodich);
                                /*  Tiền cộng cho dlpp khi ctv trở thành dlbl  */
                                $sd = $tongtien-$tien_dlbl;

                                $this->create_profits($order->id, $mentor->id, $mentor->full_name, $sd*$hhm_dlbl/100, 5, $hhm_dlbl, $order->ngay_giaodich);
                            }else{

                                $this->create_profits($order->id, $mentor->id, $mentor->full_name, ($tien_dlbl-$tongtien_truoc)*$hhds_dlpp/100, 5, $hhds_dlpp, $order->ngay_giaodich);

                                $this->create_profits($order->id, $mentor->id, $mentor->full_name, ($tien_dlpp-$tien_dlbl)*$hhtk_dlbl/100, 5, $hhtk_dlbl, $order->ngay_giaodich);
                            }

                            $this->update_Bangluong($mentor->id,$time_bangluong);
                        }
                    }
                }

            }elseif($capdo =='DLBL')
            {
                
                /*  Tiền cộng cho chính đại lý đó khi trở thành DLBL  */
                if($tongtien < $tien_dlpp)
                {
                    
                    $this->create_profits($order->id, $member->id, $member->full_name, $tien_donhang_hientai*$hhds_dlbl/100, 6, $hhds_dlbl, $order->ngay_giaodich);

                    $this->update_Bangluong($member->id,$time_bangluong);

                    if($mentor){
                        if($mentor->code=='DLPP')
                        {

                            $this->create_profits($order->id, $mentor->id, $mentor->full_name, $tien_donhang_hientai*$hhtk_dlbl/100, 5, $hhtk_dlbl, $order->ngay_giaodich);

                            $this->update_Bangluong($mentor->id,$time_bangluong);

                        }
                    }

                    
                }else
                {
                    if($tongtien >= $tien_dlpp)
                    {
                        $member->code = 'DLPP';
                        $member->save();
                    }
                    $sd = $tongtien-$tien_dlpp;

                    $this->create_profits($order->id, $member->id, $member->full_name, $sd*$hhds_dlpp/100, 6, $hhds_dlpp, $order->ngay_giaodich);

                    $this->create_profits($order->id, $member->id, $member->full_name, ($tien_dlpp - $tongtien_truoc)*$hhds_dlbl/100, 6, $hhds_dlbl, $order->ngay_giaodich);

                    $this->update_Bangluong($member->id,$time_bangluong);

                    if($mentor){
                        if($mentor->code=='DLPP')
                        {

                            $this->create_profits($order->id, $mentor->id, $mentor->full_name, ($tien_dlpp - $tongtien_truoc)*$hhtk_dlbl/100, 6, $hhtk_dlbl, $order->ngay_giaodich);

                            $this->update_Bangluong($mentor->id,$time_bangluong);
                        }
                    }
                }
            }elseif($capdo =='DLPP')
            {

                $this->create_profits($order->id, $member->id, $member->full_name, $tien_donhang_hientai*$hhds_dlpp/100, 6, $hhds_dlpp, $order->ngay_giaodich);

                $this->update_Bangluong($member->id,$time_bangluong);
            }
        }
    }

    public function xacNhanDonHang(Request $request){
        // dd($request->all());
        $order = Order::find($request->id);
        

        $this->tinh_Hoa_Hong($order,$request);

        $order->update(['id_status'=>$request->status]);

        
        if($request->status == 2){
            flash('Đã xác nhận đơn hàng hoàn thành.')->success();
        }else{
            flash('Đã xác nhận hủy đơn hàng.')->error();
        }
        return redirect()->route('orders.edit', ['id' => $request->id ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {   

        $data['order'] = Order::select('orders.*','orders.id as order_id','status.name as name_status','member.full_name as full_name','member.*')
            ->where('orders.id',$id)      
            ->join('status','status.id','=','orders.id_status')
            ->join('member','member.id','=','orders.id_member')
            ->first();

        $data['order_details'] = Order_detail::select('order_detail.*','products.name as product_name','products.name_en as product_name_en','products.image as image')
        ->join('products','products.id','order_detail.product_id')
        ->where('order_id',$id)
        ->where(function($q) use ($request) {
            if($request->startdate !=''){
                $start_format = Carbon::parse($request->startdate);
                $start_format->format('Y-m-d');
                $end_format = Carbon::parse($request->enddate);
                $end_format->format('Y-m-d');
                $q->whereBetween('order_detail.created_at', [$start_format, $end_format]);
            }           
        })->orderBy('order_detail.created_at', 'desc')
        ->get();

        $log = Log_profits::select('log_profits.*','status.name as name_status','member.user_name','member.code as capbac')
        ->where('id_donhang',$id)
        ->join('status','status.id','=','log_profits.id_status')
        ->join('member','member.id','=','log_profits.id_nguoinhan')
        ->get();
        //dd($data['order_details']);
        $data['log'] = $log;
        $data['module'] = $this->module();
        return view("backend.{$this->module()['module']}.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $order_curent = Order::find($id);
            $id_member = $order_curent->id_member;
            $timestamp = strtotime($order_curent->ngay_giaodich);
            $time_bangluong = date ('Y-m', $timestamp);
            
            $log_order = Log_profits::where('id_donhang',$id)->get();

            if(count($log_order) > 0){

                $log_new_id = $log_order->pluck('id_nguoinhan')->toArray();

                $log_time = $log_order[0]->ngay_nhan;

                $time_format = strtotime($log_time);

                $time_month = date ('Y-m', $time_format);

                $time_month_01 = $time_month.'-01';

                $time_month_02 = $time_month.'-31';

                Log_profits::where('id_donhang',$id)->delete();

                Order::destroy($id);

                Order_detail::where('order_id',$id)->delete();

                foreach ($log_new_id as $value) {
                    
                    $bangluong = BangLuong::where('luong_thang',$time_month)->where('id_daily',$value)->first();

                    if($bangluong){
                        $log_member_id = Log_profits::where('id_nguoinhan',$value)
                        ->where('active',1)
                        ->whereDate('ngay_nhan','<=',$time_month_02)
                        ->whereDate('ngay_nhan','>=',$time_month_01)
                        ->get()->sum('money');
                        $bangluong->update(['money'=>$log_member_id]);
                    }

                }
            }else{
                Order::destroy($id);

                Order_detail::where('order_id',$id)->delete();
            }
            $member = Member::find($id_member);

            $rank = Rank::all();
            $tien_dlbl = 0;
            $tien_dlpp = 0;

            foreach ($rank as $val) {
                if($val->key==1){
                    $tien_dlbl = $val->total;
                }
                if($val->key==2){
                    $tien_dlpp = $val->total;
                }
            }
            $order_success = Order::where(
                [
                    'id_member' => $id_member,
                    'id_status' => 2
                ]
            )->get();
            $tongtien = 0;
            foreach ($order_success as $value) {
                $tongtien+=$value->tongtien;
            }
            // dd($tien_dlbl);
            if($tongtien < $tien_dlbl){

                Member::find($id_member)->update(['code' => 'CTV']);
            }
            if($tongtien >= $tien_dlbl && $tongtien < $tien_dlpp){
                Member::find($id_member)->update(['code' => 'DLBL']);
            }

            if($tongtien >= $tien_dlpp){
                Member::find($id_member)->update(['code' => 'DLPP']);
            }

            flash('Xóa đơn hàng thành công.')->success();
            return back();
        } catch (Exception $e) {
            flash('Có lỗi xảy ra khi xóa đơn hàng.')->error();
            return back();
        }
        
    }

    public function doanhThu(Request $request){
        if (request()->ajax()) {

            $list_log = Log_profits::select('log_profits.*','status.name as name_status','orders.mavd as mavd','orders.id as mavd_id','orders.tongtien as tongtien','member.user_name as user_name')
            ->join('status','status.id','=','log_profits.id_status')
            ->join('orders','orders.id','=','log_profits.id_donhang')
            ->join('member','member.id','=','log_profits.id_nguoinhan')
            ->where(function($q) use ($request){
                if($request->start_date !=''){
                    $start_format = Carbon::parse($request->start_date);
                    $start_format->format('Y-m-d');
                    $end_format = Carbon::parse($request->end_date);
                    $end_format->format('Y-m-d');
                }else{
                    $month = date('m');
                    $year = date('yy');
                    $start_format = '01-'.$month.'-'.$year;
                    $end_format = '31-'.$month.'-'.$year;
                }
                
                $q->whereBetween('log_profits.ngay_nhan', [$start_format, $end_format]);
                
            })->orderBy('log_profits.id', 'desc')
            ->get();

            return Datatables::of($list_log)
                ->addColumn('checkbox', function ($data) {
                    return '';
                })->addColumn('name_nguoinhan', function ($data) {
                    return $data->name_nguoinhan;
                })->addColumn('user_name', function ($data) {
                    return $data->user_name;
                })->addColumn('mavd', function ($data) {
                    return '<a href="" class="products-table"><span class="code-orders" data-id="'.$data->mavd_id.'">'.$data->mavd.'</span></a>';
                })->addColumn('tongtien', function ($data) {
                    return  number_format($data->tongtien, 0, '.', '.').'đ';
                })->addColumn('phan_tram', function ($data) {
                    return $data->phan_tram.'%';
                })->addColumn('money', function ($data) {
                    return number_format($data->money, 0, '.', '.').'đ';
                })->addColumn('ngay_nhan', function ($data) {
                    return format_datetime($data->ngay_nhan,'d-m-Y');
                })->addColumn('name_status', function ($data) {
                    return $data->name_status;
                    
                })->rawColumns(['checkbox', 'name_nguoinhan','user_name', 'mavd','tongtien', 'ngay_nhan' , 'money', 'name_status'])
                ->addIndexColumn()
                ->make(true);

        }

        return view('backend.orders.doanh-thu');
    }

    public function bang_Luong(Request $request){
        $data = Member::all();
        // foreach ($member as $value) {
        //     echo $this->doanh_Thu($value->id).'</br>';
        // }
        return view('backend.orders.bang-luong',compact('data'));
    }

    public function chiTietDonHang($id){
        $order_details = Order_detail::select('order_detail.*','products.name as product_name','products.name_en as product_name_en')
        ->where('order_id',$id)
        ->join('products','products.id','order_detail.product_id')
        ->get();

        return view('frontend.pages.account.chi-tiet-don-hang',compact('order_details'));
    }


    public function createOrder(){
        
        $product = Products::where('status',1)->get();
        return view('backend.orders.form-tao-don-hang-thu-cong',compact('product'));
    }

    public function postTaoDonHangThuCong(Request $request){
        //dd($request->all());
        $fields = [
            'id_checked' => 'required',
            'user_name' => 'required',
        ];
        $message = [
                'id_checked.required' => 'Vui lòng chọn sản phẩm trước khi tạo đơn hàng',
                'user_name.required' => 'Vui lòng nhập tên user name cần tạo đơn hàng',
                
            ];

        // $validator->getMessageBag()->add('password', 'Password wrong');

        $input=$request->all();

        $validator = Validator::make($input, $fields,$message);

        if ($validator->passes()) {
            $username = Member::where('user_name',$request->user_name)->first();
            //dd($username);
            if($username){
            }else{
                $validator->errors()->add('user_name', 'User name không tồn tại');
                return response()->json(['error'=>$validator->errors()]);
            }
            $price_product = $request->price_product;
            $qty_product = $request->qty_product;
            $id_product = $request->id_checked;
            $time = Carbon::parse($request->startDate);
            $time->format('Y-m-d H:i:s');

            $array = [];
            $tongtien = 0;
            $array_order = [];

            foreach ($request->id_checked as $item) {
                $tongtien = $tongtien+$price_product[$item]*$qty_product[$item];
            }

            $order = new Order();
            $order->tongtien = $tongtien;
            $order->id_member = $username->id;
            $order->mavd = 'DH'.$username->id.Carbon::now()->format('dmYHis');
            $order->id_status = 1;
            $order->ngay_giaodich = $time;
            $order->save();

            foreach ($id_product as $item) {
                $order_detail = new Order_detail();
                $order_detail->order_id = $order->id;
                $order_detail->mavd = $order->mavd;
                $order_detail->product_id = $item;
                $order_detail->qty = $qty_product[$item];
                $order_detail->price = $price_product[$item];
                $order_detail->price_total = $qty_product[$item]*$price_product[$item];
                $order_detail->created_at = $time;
                $order_detail->save();
            }

            return response()->json([
                'toastr' => 'Tạo đơn hàng thành công',
                'status' => 1
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);
    }

    public function getProducts(){
        $products = Products::where('status',1)->get();
        return view('backend.orders.get-products',compact('products'));
    }
}
