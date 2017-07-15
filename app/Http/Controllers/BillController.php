<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Bill;
use App\Student;
use App\UserWifi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use DateTime;
use DateTimeZone;
class BillController extends Controller {



	public function __construct(){
			//$this->middleware('jwt.auth', ['except' => ['showBillFree']]);
		}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$bills = DB::table('bills')
				->join('students', 'idstudent', '=', 'students.id')
				->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
				->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
				->join('usertypes', 'idusertype' ,'=','usertypes.id')
				->join('devices', 'usertypes.iddevice', '=', 'devices.id')
				->select('bills.*', 'students.name as student_name', 'userwifis.password as userwifi_password', 'usertypes.name as usertype_name','priceusertypes.price as price', 'devices.name as device_name')
				->get();

		$analysis = DB::table('bills')
				->join('students', 'idstudent', '=', 'students.id')
				->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
				->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
				->select( DB::raw('SUM(price) as total_sales'), DB::raw('COUNT(idstudent) as count'))
				->get();
		return Response::json([
		    'bills' => $bills,
				'analysis' => $analysis
		], 200);
	}


	public function showBillFree()
	{

		$now = new DateTime();
		$updateUser =  DB::table('bills')
								->where('dateexpired', '<', $now)
								->update(['expired' => 1]);
		$userwifis = DB::table('userwifis')
				->orderBy('username', 'ASC')
				->orderBy('dateexpired', 'DESC')
				->groupby('username')
				->join('priceusertypes',  'priceusertypes.id', '=', 'userwifis.idpricetype')
				->join('usertypes',  'priceusertypes.idusertype', '=', 'usertypes.id')
				->join('bills', 'userwifis.username' , '=' , 'bills.userwifiname')
				->join('devices', 'usertypes.iddevice', '=', 'devices.id')
				->where('expired', 0)
				->distinct('username')
				->select('username','password','datebuy','dateexpired', 'managername', 'expired', 'priceusertypes.price as price','usertypes.id as usertypes_id', 'usertypes.name as usertype_name','devices.name as device_name','priceusertypes.price as price' )
				 ->get();


				$str = "/ip hotspot user \n";
				foreach ($userwifis as $item) {
					 $dateexpired = $item->dateexpired;
					 $datebuy = $item->datebuy;
						if($item->expired == 1){
							//$disable="yes";
							//$str = $str."disable \"".$item->userwifiname."\" \n";
						}else{
							$disable="no";
							$str = $str."add email=\"a@a.vn\" comment=\"".$dateexpired." ".$datebuy." ".$item->managername." ".$item->price." ".$item->device_name."\" disabled=".$disable." name=".$item->username." password=".$item->password." \n";
						}

				}
				return $str;
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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function search()
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
		//Validate
		if(!$request->idstudent or !$request->userwifiname or !$request->managername){
			return Response::json([
					'error' => [
							'message' => 'Phai cung cap day du'
					]
			], 422);
		}

		$checkStudent = Student::where('id', $request->idstudent)->first();
		if(empty($checkStudent)){
			return Response::json([
					'error' => [
							'message' => 'Sinh viên này không tồn tại'
					]
			], 422);
		}

		$checkManager = DB::table('managers')
			->join('roles',  'managers.idrole', '=', 'roles.id')
			->where('username', $request->managername)
			->select('managers.*', 'roles.name as role_name' )
			->first();
		if(empty($checkManager)){
			return Response::json([
					'error' => [
							'message' => 'Quản lý này không tồn tại'
					]
			], 422);
		}

		$checkUserWifis = DB::table('userwifis')
				->where('username', $request->userwifiname)
				 ->first();
		if(empty($checkUserWifis)){
			return Response::json([
					'error' => [
							'message' => 'Tài khoản này không tồn tại'
					]
			], 422);
		}



		//Create Bill
		$item = new Bill;
		$item->idstudent = $request->idstudent;
		$item->userwifiname = $request->userwifiname;
		$item->managername = $request->managername;
		$now = new DateTime();
		$now->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
		$item->datebuy = $now->format('Y-m-d H:i:s');
		$userwifis = DB::table('userwifis')
				->join('priceusertypes',  'priceusertypes.id', '=', 'userwifis.idpricetype')
				->join('usertypes',  'priceusertypes.idusertype', '=', 'usertypes.id')
				->where('username', $item->userwifiname)
				->select('usertypes.Duration as duration' )
				 ->first();


		// if($userwifis->used == 1){
		// 	return Response::json([
		// 			'error' => [
		// 					'message' => 'Tải khoản wifi đã được sử dụng'
		// 			]
		// 	], 422);
		// }
		//'datecanrefund', 'refund', 'daterefund'
		$duration = $userwifis->duration;
		$datecanrefund = new DateTime();
		$dateexpired = new DateTime();

		if ($duration%30!=0){

			$datecanrefund->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
			$datecanrefund->modify('+3 days');
			$dateexpired = $now->modify('+'.$duration.' days');
		}else{

			$datecanrefund->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
			$datecanrefund->modify('+7 days');
			$duration = $duration/30;
			$dateexpired = $now->modify('+'.$duration.' months');
		}
		$item->datecanrefund = $datecanrefund->format('Y-m-d H:i:s');
		$item->dateexpired = $dateexpired->format('Y-m-d H:i:s');
		$item->refund = 0;
		$item->expired = 0;
		//Check used for userwifis
		$item->save();
		// $updateUser =  DB::table('userwifis')->where('username', $item->userwifiname)->update(['used' => 1]);



		//Show result
		$bills = DB::table('bills')
				->where('bills.id','=', $item->id)
				->join('students', 'idstudent', '=', 'students.id')
				->join('managers', 'managername', '=', 'managers.username')
				->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
				->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
				->join('usertypes', 'idusertype' ,'=','usertypes.id')
				->join('devices', 'usertypes.iddevice', '=', 'devices.id')
				->select('bills.*', 'students.name as student_name', 'userwifis.password as userwifi_password', 'usertypes.name as usertype_name','priceusertypes.price as price', 'devices.name as device_name', 'managers.name as manager_name')
				->get();
		return Response::json([
			 'message' => 'Thông tin quản trị được thêm thành công',
			 'bills' => $bills
		]);

		//'idstudent', 'userwifiname', 'managername', 'datebuy', 'dateexpired', 'expired'

		//'/used'
	}




	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$bills = DB::table('bills')
				->where('bills.id','=', $id)
				->join('students', 'idstudent', '=', 'students.id')
				->join('managers', 'managername', '=', 'managers.username')
				->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
				->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
				->join('usertypes', 'idusertype' ,'=','usertypes.id')
				->join('devices', 'usertypes.iddevice', '=', 'devices.id')
				->select('bills.*', 'students.name as student_name', 'userwifis.password as userwifi_password', 'usertypes.name as usertype_name','priceusertypes.price as price', 'devices.name as device_name', 'managers.name as manager_name')
				->get();
			return Response::json([
				 'bills' => $bills,
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
			if($request->refund == "refund"){
					$now = new DateTime();
					$now->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
					$now->format('Y-m-d H:i:s');


					$bill =  DB::table('bills')
							->where('bills.id','=', $id)
							->select('datecanrefund', 'expired')
							->first();
				if($bill->expired == 0){
						if($bill->datecanrefund > $now->format('Y-m-d H:i:s')){
							$update =  DB::table('bills')
													->where('bills.id', '=', $id)
													->update(['expired' => 1, 'refund' => 1]);
							$result = DB::table('bills')
											->where('bills.id','=', $id)
											->join('students', 'idstudent', '=', 'students.id')
											->join('managers', 'managername', '=', 'managers.username')
										  ->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
										  ->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
											->join('usertypes', 'idusertype' ,'=','usertypes.id')
											->join('devices', 'usertypes.iddevice', '=', 'devices.id')
											->select('bills.*', 'students.name as student_name', 'userwifis.password as userwifi_password', 'usertypes.name as usertype_name','priceusertypes.price as price', 'devices.name as device_name', 'managers.name as manager_name')
											->get();
							return Response::json([
								 'message' => 'Hoàn trả thành công',
								 'result' => $result,
								 'refund' => 1,
							]);
						}else{
							$result = DB::table('bills')
											->where('bills.id','=', $id)
											->join('students', 'idstudent', '=', 'students.id')
											->join('managers', 'managername', '=', 'managers.username')
										  ->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
										  ->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
											->join('usertypes', 'idusertype' ,'=','usertypes.id')
											->join('devices', 'usertypes.iddevice', '=', 'devices.id')
											->select('bills.*', 'students.name as student_name', 'userwifis.password as userwifi_password', 'usertypes.name as usertype_name','priceusertypes.price as price', 'devices.name as device_name', 'managers.name as manager_name')
											->get();
							return Response::json([
								 'message' => 'Không thể hoàn trả',
								 'result' => $result,
								 'refund' => 0,
							]);
						}

				}else{
					$result = DB::table('bills')
									->where('bills.id','=', $id)
									->join('students', 'idstudent', '=', 'students.id')
									->join('managers', 'managername', '=', 'managers.username')
									->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
									->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
									->join('usertypes', 'idusertype' ,'=','usertypes.id')
									->join('devices', 'usertypes.iddevice', '=', 'devices.id')
									->select('bills.*', 'students.name as student_name', 'userwifis.password as userwifi_password', 'usertypes.name as usertype_name','priceusertypes.price as price', 'devices.name as device_name', 'managers.name as manager_name')
									->get();
					return Response::json([
						 'message' => 'Tải khoản đã hết hạn',
						 'result' => $result,
						 'refund' => 0,
					]);
				}
			}
			if($request->expired == "expired"){
				if($request->idadmin != 2){
					return Response::json([
							'error' => [
									'message' => 'Admin mới có quyền hạn này'
							]
					], 422);
				}
				$bill =  DB::table('bills')
						->where('bills.id','=', $id)
						->select('datecanrefund', 'expired')
						->first();
				if($bill->expired == 0){
					$update =  DB::table('bills')
											->where('bills.id', '=', $id)
											->update(['expired' => 1]);
					$result = DB::table('bills')
					->where('bills.id','=', $id)
					->join('students', 'idstudent', '=', 'students.id')
					->join('managers', 'managername', '=', 'managers.username')
					->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
					->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
					->join('usertypes', 'idusertype' ,'=','usertypes.id')
					->join('devices', 'usertypes.iddevice', '=', 'devices.id')
					->select('bills.*', 'students.name as student_name', 'userwifis.password as userwifi_password', 'usertypes.name as usertype_name','priceusertypes.price as price', 'devices.name as device_name', 'managers.name as manager_name')
					->get();
					return Response::json([
					'message' => 'Tải khoản đã hết hạn',
					'result' => $result,
					'expired' => 1,
					]);
				}
				else{
					return Response::json([
							'error' => [
									'message' => 'Tải khoản đã hết hạn trước đó'
							]
					], 422);
				}
		}
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
		$item = Bill::findorFail($id);
		$item->delete();
		return Response::json([
			 'message' => 'Xoá thành công'
		 ]);
	}

}
