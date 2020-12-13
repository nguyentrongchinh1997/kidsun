<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\MemberExport;
use App\Exports\BangluongExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Validator;
use DataTables;
use App\Models\Member;
use App\Models\Rank;
use App\Models\User;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Rechage;
use App\Models\Taikhoan_khachhang;
use App\Models\Log_profits;
use App\Models\BangLuong;
use Carbon\Carbon;
use Auth;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function module(){
        return [
            'name' => 'Bảng lương',
            'module' => 'orders',
            'table' =>[
                
                'link_aff' => [
                    'title' => 'Mã tài khoản', 
                    'with' => '',
                ],

                
                'full_name' => [
                    'title' => 'Họ và tên', 
                    'with' => '',
                ],
                'code' => [
                    'title' => 'Cấp bậc', 
                    'with' => '',
                ],
                'money' => [
                    'title' => 'Tổng lương', 
                    'with' => '',
                ],
                
            ]
        ];
    }
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $list_post = Member::where(function($q) use ($request) {
                if($request->start_date){
                    $start_format = Carbon::parse($request->start_date);
                    $start_format->format('Y-m-d');
                    $end_format = Carbon::parse($request->end_date);
                    $end_format->format('Y-m-d');
                    $q->whereBetween('member.created_at', [$start_format, $end_format]);
                }
                if($request->code !=''){
                    $q->where('code',$request->code);
                }
                
            })->orderBy('member.created_at', 'desc')->get();
            return Datatables::of($list_post)
                ->addColumn('checkbox', function ($data) {
                    return '<input type="checkbox" name="chkItem[]" value="' . $data->id . '">';
                })->addColumn('full_name', function ($data) {
                    return $data->full_name;
                })->addColumn('user_name', function ($data) {
                    return $data->user_name;
                })->addColumn('code', function ($data) {
                    return $data->code;
                })->addColumn('phone', function ($data) {
                    return $data->phone;
                })->addColumn('link_aff', function ($data) {
                    return $data->mentor;
                })->addColumn('action', function ($data) {
                    if($data->lock==0){
                        $khoa = '&nbsp; &nbsp; &nbsp;
                            <a href="' . route( 'member.lock', ['id'=>$data->id] ) . '" title="Khóa">
                                <i class="fa fa-unlock"></i> Khóa
                            </a>';
                    }else{
                        $khoa = '&nbsp; &nbsp; &nbsp;
                            <a href="' . route( 'member.unlock', ['id'=>$data->id] ) . '" title="Mở">
                                <i class="fa fa-unlock-alt"></i> Mở
                            </a>';
                    }
                    return ' <a href="javascript:;" class="btn-destroy" 
                            data-href="' . route('member.destroy', $data->id) . '"
                            data-toggle="modal" data-target="#confim">
                            <i class="fa fa-trash-o fa-fw"></i> Xóa</a>
                            '.$khoa;
                })->addColumn('lichsu', function ($data) {
                    return '<a href="'.route( 'member.detail',  ['id'=>$data->id] ).'" class="btn-destroy">
                                <i class="fa fa-eye"></i> Xem
                            </a>';
                })->rawColumns(['checkbox', 'full_name', 'user_name', 'link_aff', 'phone', 'code', 'action','lichsu'])
                ->addIndexColumn()
                ->make(true);
        }

        //$data = Member::all();
        return view('backend.member.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Member::destroy($id);
        flash('Xóa thành công.')->success();
        return back();
    }
    public function lockMember($id){
        Member::find($id)->update(['lock'=>1]);
        flash('Đã khóa thành công.')->success();
        return back();
    }
    public function unlocklockMember($id){
        Member::find($id)->update(['lock'=>0]);
        flash('Mở khóa thành công.')->success();
        return back();
    }
    public function filterDate(Request $request){
        $start_format = Carbon::parse($request->startdate);
        $start_format->format('Y-m-d');
        $end_format = Carbon::parse($request->enddate);
        $end_format->format('Y-m-d');
        $member = Member::where('status',1)->whereBetween('created_at', [$start_format, $end_format])->get();
        return $member;
    }
    public function rankMember(){
        $rank = Rank::all();
        return view('backend.member.rank',compact('rank'));
    }
    public function addRankMember(){
        $rank = Rank::all();
        return view('backend.member.add_rank',compact('rank'));
    }
    public function postAddRankMember(Request $request){
        //dd($request->all());
        $array = [
            [
                'id'=>$request->id_1,
                'name'=>1,          
                'money_from'=>$request->money_from1,
                'money_to'=>$request->money_to1,
                'deposit'=>$request->deposit1,
            ],
            [
                'id'=>$request->id_2,
                'name'=>2,
                'money_from'=>$request->money_from2,
                'money_to'=>$request->money_to2,
                'deposit'=>$request->deposit2,
            ],
            [
                'id'=>$request->id_3,
                'name'=>3,
                'money_from'=>$request->money_from3,
                'deposit'=>$request->deposit3,
            ],
            [
                'id'=>$request->id_4,
                'name'=>4,
                'money_from'=>$request->money_from4,
                'deposit'=>$request->deposit4,
            ],
            [
                'id'=>$request->id_5,
                'name'=>5,
                'money_from'=>$request->money_from5,
                'deposit'=>$request->deposit5,
            ]
        ];
            
        foreach ($array as $value) {
            Rank::find($value['id'])->update($value);
        }
        // dd($array);
        // Rank::insert($array);

        flash('Sửa thành công.')->success();
        return redirect()->route('member.rank');
    }

    public function member_Detail (Request $request, $id){
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

        $member = Member::find($id);

        $daily_f1 = Member::where('mentor',$member->user_name)->get();

        $recharge = Member::select('recharge.*','member.*','member.user_name as member_name','member.phone as member_phone','member.email as member_email','member.id as member_id')
        ->where('member.id',$id)
        ->where(function($q) use ($request,$year,$month) {
            $start_format = $year.'-'.$month.'-01';
               
            $end_format = $year.'-'.$month.'-31';

            $q->whereBetween('recharge.created_at', [$start_format, $end_format]);
            
        })
        ->join('recharge','recharge.member_id','=','member.id')
        ->get();

        $orders = Order::select('orders.*','status.name as name_status','status.name_en as nameen_status')->where('id_member',$id)
            ->where('orders.id_status',2)
            ->where(function($q) use ($request,$year,$month) {
                $start_format = $year.'-'.$month.'-01';
                   
                $end_format = $year.'-'.$month.'-31';

                $q->whereBetween('orders.ngay_giaodich', [$start_format, $end_format]);
                
            })
            ->join('status','status.id','=','orders.id_status')
            ->orderBy('orders.created_at', 'desc')->get();
            
        $list_log = Log_profits::select('log_profits.*','status.name as name_status','orders.mavd as mavd')
        ->join('status','status.id','=','log_profits.id_status')
        ->join('orders','orders.id','=','log_profits.id_donhang')
        ->where('log_profits.id_nguoinhan',$id)
        ->where(function($q) use ($request,$year,$month) {
            $start_format = $year.'-'.$month.'-01';
               
            $end_format = $year.'-'.$month.'-31';

            $q->whereBetween('log_profits.ngay_nhan', [$start_format, $end_format]);
            
        })->orderBy('log_profits.id', 'desc')
        ->get();

        return view('backend.member.detail',compact('orders','recharge','member','list_log','daily_f1'));
        
    }

    public function member_Xacnhan($id){
        $member = Member::find($id);
        $member->xac_nhan = 1;
        $member->save();

        flash('Xác nhận tài khoản thành công')->success();

        return redirect()->route('member.detail',['id'=>$id]);
    }

    public function chiTietDonHang($id){
        $order_details = Order_detail::select('order_detail.*','products.name as product_name','products.name_en as product_name_en')
        ->where('order_id',$id)
        ->join('products','products.id','order_detail.product_id')
        ->get();

        return view('frontend.pages.account.chi-tiet-don-hang',compact('order_details'));
    }

    public function bang_Luong(Request $request){
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
            
            $bangluong = BangLuong::select('bang_luong.money','bang_luong.bu_tru','member.*')
            ->where('bang_luong.luong_thang',$year.'-'.$month)
            ->where('bang_luong.status',1)
            ->join('member','member.id','=','bang_luong.id_daily')
            ->orderBy('bang_luong.updated_at', 'desc')
            ->get();
            
            $array_id = $bangluong->pluck('id')->toArray();
            $luong = Log_profits::select('member.id','member.*','member.code as code')
                // ->join('status','status.id','=','log_profits.id_status')
                ->join('member','member.id','=','log_profits.id_nguoinhan')
                ->where(function($q) use ($request,$month,$year,$array_id){

                    $q->whereNotIn('id_nguoinhan',$array_id);
                    
                    $start_format = $year.'-'.$month.'-01';
                   
                    $end_format = $year.'-'.$month.'-31';

                    $q->whereBetween('log_profits.ngay_nhan', [$start_format, $end_format]);
                   
                })->orderBy('log_profits.ngay_nhan', 'desc')->distinct()
                ->get();
        $data['module'] = $this->module();
        $data['luong'] = $luong;
        $data['bangluong'] = $bangluong;
        return view("backend.orders.bang-luong", $data);
        //return view('backend.orders.bang-luong',compact('data'));
    }

    public function chi_Tiet_Luong(Request $request, $id){
        //dd($request->all());
        if($request->year && $request->month){
            $month = $request->year.'-'.$request->month;
        }else{
            $month = Carbon::now()->format('Y-m');
        }

        $member_id = $id;

        $member_info = Member::find($member_id);
        $data = Log_profits::select('log_profits.*','status.name as name_status','orders.mavd as mavd')
        ->join('status','status.id','=','log_profits.id_status')
        ->join('orders','orders.id','=','log_profits.id_donhang')
        ->where(function($q) use ($member_id,$month){

            $q->where([
                'id_nguoinhan' => $member_id,
                // 'active' => 0
            ]);
           
            $start_format = $month.'-01';
           
            $end_format = $month.'-31';

            $q->whereBetween('log_profits.ngay_nhan', [$start_format, $end_format]);
           
        })->orderBy('log_profits.active', 'asc')
        ->orderBy('log_profits.ngay_nhan','desc')
        ->get();

        $luong_thang_da_tinh = BangLuong::where(['id_daily'=>$member_id,'luong_thang'=>$month])->first();

        //dd($luong_thang_da_tinh->bu_tru);

         return view('backend.member.chi-tiet-luong',compact('data','member_id','luong_thang_da_tinh','member_info'));
    }

    public function xac_Nhan_Luong(Request $request){
        // dd($request->all());
        $luong = BangLuong::where('luong_thang',$request->time_tinh_luong)->where('id_daily',$request->id_daily)->first();
        if($luong){
            $luong->money = $request->money;
            $luong->bu_tru = $request->bu_tru;
            $luong->noidung = $request->noi_dung;
            $luong->status = 1;
            $luong->updated_at = date('Y-m-d H:i:s');
            $luong->update();
        }else{
            $bangluong = new BangLuong();
            $bangluong->id_daily = $request->id_daily;
            $bangluong->luong_thang = date('yy').'-'.date('m');
            $bangluong->money = $request->money;
            $bangluong->bu_tru = $request->bu_tru;
            $bangluong->status = 1;
            $bangluong->noidung = $request->noi_dung;
            $bangluong->save();
        }

        Log_profits::whereIn('id',$request->checked_id)->update(['active'=>1]);


        flash('Đã tính lương thành công cho đại lý');
        return back();

    }

    public function chietKhauDonHang($id){
        $log = Log_profits::select('log_profits.*','status.name as name_status','member.user_name')
        ->where('id_donhang',$id)
        ->join('status','status.id','=','log_profits.id_status')
        ->join('member','member.id','=','log_profits.id_nguoinhan')
        ->get();
        return view('backend.member.chiet-khau-tu-don-hang',compact('log'));
    }

    public function update_Rank(Request $request,$id){
        $code = $request->code;
        $mess = array(
            'CTV'=>'Cộng tác viên',
            'DLBL'=>'Đại lý bán lẻ',
            'DLPP'=>'Đại lý phân phối'
        );
        $array = array('CTV','DLBL','DLPP');
        if($code=='' || !in_array($code,$array)){
            return 'Đã có lỗi xảy ra vui lòng thử lại';
        }
        
        Member::find($id)->update(['code'=>$code]);

        flash('Đã cập nhập cấp bậc của đại lý thành <strong>'.$mess[$code].'</strong>.')->success();

        return back();
    }

    public function export_member(Request $request){

        $member = Member::where(function($q) use ($request) {
            if($request->startdate){
                $start_format = Carbon::parse($request->startdate);
                $start_format->format('Y-m-d');
                $end_format = Carbon::parse($request->enddate);
                $end_format->format('Y-m-d');
                $q->whereBetween('member.created_at', [$start_format, $end_format]);
            }
            if($request->code !=''){
                $q->where('code',$request->code);
            }
            
        })->orderBy('member.created_at', 'desc')->get();

        $array_export = [];

        foreach ($member as $k => $item) {        
            $array = [
                $k+1,
                $item->full_name,
                $item->user_name,
                $item->code,
                $item->phone,
                $item->mentor
            ];
            array_push($array_export,$array);
            
        }
        $export = new MemberExport($array_export);
        return Excel::download($export, 'danh-sach-dai-ly.xlsx');
    }

    public function export_Bang_Luong(Request $request){
        $year = $request->year;
        $month = $request->month;
        $key = $request->key_luong;

        if($key=='yes'){
            $luong = BangLuong::select('bang_luong.money','member.*')
            ->where('bang_luong.luong_thang',$year.'-'.$month)
            ->where('bang_luong.status',1)
            ->join('member','member.id','=','bang_luong.id_daily')
            ->get();
        }else{
            $bangluong = BangLuong::select('bang_luong.money','member.*')
            ->where('bang_luong.luong_thang',$year.'-'.$month)
            ->where('bang_luong.status',1)
            ->join('member','member.id','=','bang_luong.id_daily')
            ->get();

            $array_id = $bangluong->pluck('id')->toArray();

            $luong = Log_profits::select('member.id','member.*','member.code as code','log_profits.money')
            // ->join('status','status.id','=','log_profits.id_status')
            ->join('member','member.id','=','log_profits.id_nguoinhan')
            ->where(function($q) use ($request,$month,$year,$array_id){

                $q->whereNotIn('id_nguoinhan',$array_id);
                
                $start_format = $year.'-'.$month.'-01';
               
                $end_format = $year.'-'.$month.'-31';

                $q->whereBetween('log_profits.ngay_nhan', [$start_format, $end_format]);
               
            })->orderBy('log_profits.ngay_nhan', 'desc')->distinct()
            ->get();
        }

        $array_export = [];

        foreach ($luong as $k => $item) {        
            $array = [
                $item->full_name,
                $item->user_name,
                $item->phone,
                number_format($item->money, 0, '.', '.').'đ',
                $item->code,
                $item->bank_account,
                $item->bank_account_name,
                $item->bank_name,
                $item->bank_address,
            ];
            array_push($array_export,$array);
            
        }

        $export = new BangluongExport($array_export);
        return Excel::download($export, 'danh-sach-luong.xlsx');
    }

    public function update_Password(Request $request){

        $member_id = $request->id_daily;

        $member = Member::findOrFail($member_id);
                   
        $message = [
            'password.required' => 'Mật khẩu không được để trống',
            'password.string' => 'Mật khẩu phải là chuỗi kí tự',
            'password.min' => 'Mật khẩu ít nhất phải 6 kí tự',
            'password.confirmed' => 'Nhập lại mật khẩu không khớp',
            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu'
            
        ];
        
        
        $fields = [
            'password' => [
                'required',
                'string',
                'min:6', 
                'confirmed',
            ],
            'password_confirmation' => 'required',
        ];

        $input = $request->all();
        
        $validator = Validator::make($input, [
            'password' => [
                'required',
                'string',
                'min:6', 
                'confirmed',
            ],
            'password_confirmation' => 'required',
        ],$message);

        if ($validator->passes()) {

            $password = Hash::make($request->password);

            $member->update(['password' => $password]);

            flash('Cập nhập mật khẩu thành công')->success();
            return back()
                ->with('success','Cập nhập mật khẩu thành công');
        }
        return back()
                ->withErrors($validator)
                ->withInput();
    }

    public function dai_Ly_Cap_Duoi($id){

        $member = Member::find($id);

        $mentor = Member::where('user_name',$member->mentor)->first();

        if($mentor){
            $id_mentor = $mentor->id;
            $url_back = route('member.dai-ly-cap-duoi',['id'=>$id_mentor]);

            $daily_f1 = Member::where('mentor',$member->user_name)->get();

            $view = view('backend.member.dai-ly-cap-duoi', compact('daily_f1','member','url_back','mentor'))->render();

            return response()->json(['html'=>$view,'url_back'=>$url_back,'id_mentor'=>$id_mentor]);
        }


        return view('backend.member.dai-ly-cap-duoi',compact('daily_f1','member','url_back'));
    }

}
