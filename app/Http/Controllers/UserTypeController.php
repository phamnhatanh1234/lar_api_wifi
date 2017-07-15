<?php namespace App\Http\Controllers;

use App\UserType;
use App\Device;
use App\PriceUserType;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use DateTime;
use DateTimeZone;
class UserTypeController extends Controller {


		public function __construct(){
			$this->middleware('jwt.auth');
		}

		private function transformCollection($item){
	    return array_map([$this, 'transform'], $item->toArray());
		}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
			$item = DB::table('usertypes')
					->join('priceusertypes', 'priceusertypes.idusertype', '=', 'usertypes.id')
					->whereRaw('priceusertypes.dateupdate = usertypes.dateupdateprice')
					->select('usertypes.*', 'priceusertypes.id as idprice', 'priceusertypes.price as price')
	         ->get();
    	return Response::json([
			'data' => $item
    	], 200);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if($request->idadmin != 2){
			return Response::json([
					'error' => [
							'message' => 'Admin mới có quyền hạn này'
					]
			], 422);
		}
		if(!$request->name or (!$request->ngay and !$request->thang) or !$request->price or !$request->iddevice ){
			return Response::json([
					'error' => [
							'message' => 'Phải cung cấp đầy đủ thông tin'
					]
			], 422);
		}
		if($request->ngay and $request->thang){
			return Response::json([
					'error' => [
							'message' => 'Chỉ được phép nhập hoặc ngày hoặc tháng'
					]
			], 422);
		}
		if($request->ngay>=30){
			return Response::json([
					'error' => [
							'message' => 'Không thể nhập ngày lớn hơn hoặc bằng 30'
					]
			], 422);
		}
		// `name`, `duration`, `iddevice`, `dateupdateprice`,
		// 'Price', 'DateUpdate', 'IdUserType'
		$item = new UserType;
		$item->name = $request->name;
		if($request->ngay){
			$item->duration = $request->ngay;
		}
		if($request->thang){
			$item->duration = $request->thang*30;
		}
		$item->iddevice =  $request->iddevice;
		$now = new DateTime();
		$now->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
		$item->dateupdateprice = $now->format('Y-m-d H:i:s');    // MySQL datetime format
		$item->save();

		$price = new PriceUserType;
		$price->price =  $request->price;
		$price->dateupdate = $now->format('Y-m-d H:i:s');    // MySQL datetime format
		$price->idusertype = $item->id;
		$price->save();


		$result = DB::table('usertypes')
				->join('priceusertypes', 'priceusertypes.idusertype', '=', 'usertypes.id')
				->whereRaw('priceusertypes.dateupdate = usertypes.dateupdateprice')
				->where('usertypes.id', $item->id )
				->select('usertypes.*', 'priceusertypes.id as idprice', 'priceusertypes.price as price')
				 ->get();



		return Response::json([
			 'message' => 'Thông tin loại tài khoản wifi được thêm thành công',
				'data' => $result
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$item = DB::table('usertypes')
				->join('priceusertypes', 'priceusertypes.idusertype', '=', 'usertypes.id')
				//->join('userwifis',  'priceusertypes.id', '=', 'userwifis.idpricetype')
				->where('usertypes.id', '=', $id)
				->whereRaw('priceusertypes.dateupdate = usertypes.dateupdateprice')
				->select('usertypes.*', 'priceusertypes.id as idprice', 'price')
				 ->get();

		return Response::json([
		'data' => $item
		], 200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		if($request->idadmin != 2){
			return Response::json([
					'error' => [
							'message' => 'Admin mới có quyền hạn này'
					]
			], 422);
		}
		if(!$request->name or (!$request->ngay and !$request->thang) or !$request->price or !$request->iddevice ){
			return Response::json([
					'error' => [
							'message' => 'Phải cung cấp đầy đủ thông tin'
					]
			], 422);
		}
		if($request->ngay and $request->thang){
			return Response::json([
					'error' => [
							'message' => 'Chỉ được phép nhập hoặc ngày hoặc tháng'
					]
			], 422);
		}
		if($request->ngay>=30){
			return Response::json([
					'error' => [
							'message' => 'Không thể nhập ngày lớn hơn hoặc bằng 30'
					]
			], 422);
		}

		$item = UserType::findorFail($id);;
		$item->name = $request->name;
		if($request->ngay){
			$item->duration = $request->ngay;
		}
		if($request->thang){
			$item->duration = $request->thang*30;
		}
		$item->iddevice =  $request->iddevice;



		//Update Price
		$check = DB::table('usertypes')
				->join('priceusertypes', 'priceusertypes.idusertype', '=', 'usertypes.id')
				->where('usertypes.id', '=', $id)
				->whereRaw('priceusertypes.dateupdate = usertypes.dateupdateprice')
				->select('price')
				 ->first();
		if($request->price != $check->price){
			$now = new DateTime();
			$now->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
			$item->dateupdateprice = $now->format('Y-m-d H:i:s');    // MySQL datetime format


			$price = new PriceUserType;
			$price->price =  $request->price;
			$price->dateupdate = $now->format('Y-m-d H:i:s');    // MySQL datetime format
			$price->idusertype = $item->id;
			$price->save();
		}
 			$item->save();
		$result = DB::table('usertypes')
				->join('priceusertypes', 'priceusertypes.idusertype', '=', 'usertypes.id')
				->whereRaw('priceusertypes.dateupdate = usertypes.dateupdateprice')
				->where('usertypes.id', $id)
				->select('usertypes.*', 'priceusertypes.id as idprice', 'priceusertypes.price as price')
				 ->get();
		return Response::json([
		 		'message' => 'Thông tin loại tài khoản wifi được cập nhật thành công',
		 		'data' => $result
		 ]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function destroy($id, Request $request)
	{
		if($request->idadmin != 2){
			return Response::json([
					'error' => [
							'message' => 'Admin mới có quyền hạn này'
					]
			], 422);
		}
		$check = DB::table('usertypes')
						->join('priceusertypes','priceusertypes.idusertype', '=', 'usertypes.id')
						->where('usertypes.id', '=', $id)
						->join('userwifis','priceusertypes.id', '=', 'userwifis.idpricetype')
						->select('userwifis.*')
						->get();
		if(empty($check)){
			$check = DB::table('priceusertypes')
							->where('idusertype', '=', $id)->delete();
			$delete = DB::table('usertypes')
							->where('id', '=', $id)->delete();
			return Response::json([
				 'message' => 'xóa thành công',
			 ]);
		}else{
			return Response::json([
				'error' => [
					 'message' => 'tài khoản này đã từng lập hóa đơn, không thể xóa',
			 ]
		]);
		}
	}

}
