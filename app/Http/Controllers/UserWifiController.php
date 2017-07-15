<?php namespace App\Http\Controllers;
use App\UserWifi;

use App\PriceUserType;
use App\UserType;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserWifiController extends Controller {


	public function __construct(){
		$this->middleware('jwt.auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$item = DB::table('userwifis')
				->join('priceusertypes',  'priceusertypes.id', '=', 'userwifis.idpricetype')
				->join('usertypes',  'priceusertypes.idusertype', '=', 'usertypes.id')
				->select('userwifis.*', 'priceusertypes.price as price','usertypes.id as usertypes_id', 'usertypes.name as usertype_name' )
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
		if(!$request->username or !$request->password or !$request->idusertype){
			return Response::json([
					'error' => [
							'message' => 'Phai cung cap day du'
					]
			], 422);
		}

		$item = new UserWifi;
		$item->username = $request->username;
		$item->password = $request->password;
		$usertypes = DB::table('usertypes')
				->join('priceusertypes', 'priceusertypes.idusertype', '=', 'usertypes.id')
				->whereRaw('priceusertypes.dateupdate = usertypes.dateupdateprice')
				->where('usertypes.id', $request->idusertype)
				->select('usertypes.*', 'priceusertypes.id as idprice', 'priceusertypes.price as price')
				 ->first();
		$item->idpricetype = $usertypes->idprice;

		$item->save();


		$result = DB::table('userwifis')
				->join('priceusertypes',  'priceusertypes.id', '=', 'userwifis.idpricetype')
				->join('usertypes',  'priceusertypes.idusertype', '=', 'usertypes.id')
				->where('username', $item->username)
				->select('userwifis.*', 'priceusertypes.price as price','usertypes.id as usertypes_id', 'usertypes.name as usertype_name' )
				 ->get();
		return Response::json([
			 'message' => 'Thông tin tài khoản wifi được thêm thành công',
				'data' => $result
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($username)
	{
		$item = DB::table('userwifis')
				->join('priceusertypes',  'priceusertypes.id', '=', 'userwifis.idpricetype')
				->join('usertypes',  'priceusertypes.idusertype', '=', 'usertypes.id')
				->where('username', $username)
				->select('userwifis.*', 'priceusertypes.price as price','usertypes.id as usertypes_id', 'usertypes.name as usertype_name' )
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
	public function edit($username, Request $request)
	{


	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,$username)
	{
		if($request->idadmin != 2){
			return Response::json([
					'error' => [
							'message' => 'Admin mới có quyền hạn này'
					]
			], 422);
		}
		if(!$request->idusertype){
			 return Response::json([
					'error' => [
							 'message' => 'Phải cung cấp đầy đủ thông tin'
					 ]
			 ], 422);
	 }
		$item = DB::table('userwifis')
				->where('username', $username)
				->select('used')
				 ->first();
		// if(!$item->used == 1 ){
		//  			return Response::json([
		//  					'error' => [
		//  							'message' => 'Tài khoản này đã được sử dụng'
		//  					]
		//  			], 422);
		// }

		$usertypes = DB::table('usertypes')
				->join('priceusertypes', 'priceusertypes.idusertype', '=', 'usertypes.id')
				->whereRaw('priceusertypes.dateupdate = usertypes.dateupdateprice')
				->where('usertypes.id', $request->idusertype)
				->select('usertypes.*', 'priceusertypes.id as idprice', 'priceusertypes.price as price')
				 ->first();
		//$item->idpricetype = $usertypes->idprice;
		if(empty($usertypes)){
			return Response::json([
					'error' => [
							'message' => 'Loại tài khoản này có lỗi'
					]
			], 422);
		}

		$updateUser =  DB::table('userwifis')
								->where('username', $username)
								->update(['idpricetype' => $usertypes->idprice]);
		$result = DB::table('userwifis')
						->join('priceusertypes',  'priceusertypes.id', '=', 'userwifis.idpricetype')
						->join('usertypes',  'priceusertypes.idusertype', '=', 'usertypes.id')
						->where('username', $username)
						->select('userwifis.*', 'priceusertypes.price as price','usertypes.id as usertypes_id', 'usertypes.name as usertype_name' )
						->first();
		return Response::json([
				'message' => 'Thông tin tài khoản wifi được cập nhật thành công',
				'data' => $result
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($username, Request $request)
	{
		if($request->idadmin != 2){
			return Response::json([
					'error' => [
							'message' => 'Admin mới có quyền hạn này'
					]
			], 422);
		}
		$check = DB::table('bills')
						->where('userwifiname', $username)
						->get();

		if(empty($check)){
			$delete = DB::table('userwifis')
							->where('username', '=', $username)->delete();
			return Response::json([
				 'complete' => 'xóa thành công'
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
