<?php namespace App\Http\Controllers;

use App\Manager;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
class ManagerController extends Controller {


	public function __construct(){
			$this->middleware('jwt.auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->idadmin != 2){
			return Response::json([
					'error' => [
							'message' => 'Admin mới có quyền hạn này'
					]
			], 422);
		}
		$item = DB::table('managers')
				->join('roles',  'managers.idrole', '=', 'roles.id')
				->select('managers.*', 'roles.name as role_name' )
				->orderby('idrole', 'desc')
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
		if(!$request->username or !$request->password or !$request->name or !$request->idrole ){
			return Response::json([
					'error' => [
							'message' => 'Phai cung cap day du'
					]
			], 422);
		}


		$item = new Manager;
		$item->username = $request->username;
		$item->password = bcrypt($request->password);
		$item->name = $request->name;
		$item->idrole = $request->idrole;
		$item->canuse = 1;
		$item->save();
		return Response::json([
			 'message' => 'Thông tin quản trị được thêm thành công',
				'data' => $item
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($username, Request $request)
	{
					if($request->idadmin != 2){
						return Response::json([
								'error' => [
										'message' => 'Admin mới có quyền hạn này'
								]
						], 422);
					}
					$detail = DB::table('managers')
					->join('roles',  'managers.idrole', '=', 'roles.id')
					->where('username', $username)
					->select('managers.*', 'roles.name as role_name' )
				 	->get();
					$billbystudent = DB::table('bills')
							->where('managername', '=', $username)
							->join('students', 'idstudent', '=', 'students.id')
							->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
							->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
							->join('usertypes', 'idusertype' ,'=','usertypes.id')
							->join('devices', 'usertypes.iddevice', '=', 'devices.id')
							->select('students.*','bills.*', 'userwifis.password as userwifi_password', 'usertypes.name as usertype_name','priceusertypes.price as price', 'devices.name as device_name')
							->get();

					$analysis = DB::table('bills')
									->where('managername', '=', $username)
									->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
									->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
									->select( DB::raw('SUM(price) as total_sales'), DB::raw('COUNT(idstudent) as count'))
									->get();
		return Response::json([
			'detail' => $detail,
			'billsbystudent' => $billbystudent,
			'analysis' => $analysis
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
	public function update($username, Request $request)
	{
		if(!$request->password  or !$request->name or !$request->idrole ){
			return Response::json([
					'error' => [
							'message' => 'Phai cung cap day du'
					]
			], 422);
		}

		$detail = DB::table('managers')
		->join('roles',  'managers.idrole', '=', 'roles.id')
		->where('username', $username)
		->select('managers.*', 'roles.name as role_name' )
		->first();
		if (Hash::check($request->password,$detail->password) == false){
			return Response::json([
					'error' => [
							'message' =>'Mật khẩu không đúng'
					]
			], 422);
		}

		$updateUser =  DB::table('managers')
								->where('username', $username)
								->update(['name' => $request->name, 'idrole' => $request->idrole]);
		// $item->username = $request->username;
		if($request->newpassword){
				$updateUser=  DB::table('managers')
										->where('username', $username)
										->update(['password' => bcrypt($request->newpassword)]);
		}
			//$item->password = bcrypt($request->password);
			//$item->name = $request->name;
			//$item->idrole = $request->idrole;
			//$item->save();
			$detail = DB::table('managers')
			->join('roles',  'managers.idrole', '=', 'roles.id')
			->where('username', $username)
			->select('managers.*', 'roles.name as role_name' )
			->get();


		//$item->canuse = 1;

		return Response::json([
			 'message' => 'Thông tin quản trị được cập nhật thành công',
				'data' => $detail
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($username,Request $request)
	{
		if($request->idadmin != 2){
			return Response::json([
					'error' => [
							'message' => 'Admin mới có quyền hạn này'
					]
			], 422);
		}

		$check = DB::table('bills')
						->where('managername', '=', $username)->get();
		if(empty($check)){
			$item = DB::table('managers')
			 				->where('username', '=', $username)
			 				->delete();
			return Response::json([
				 'complete' => 'xóa thành công'
			 ]);
		}else{
			$item = DB::table('managers')
						 ->where('username', '=', $username)
						 ->update(array('password' => 'nopassword','canuse' => 1));
			return Response::json([
				 'error' => 'tài khoản này đã từng lập hóa đơn, được chuyển về dạng lưu trữ',
				 'data' => $item
			 ]);
		}
	}

}
