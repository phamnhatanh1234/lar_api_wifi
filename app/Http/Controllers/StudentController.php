<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
class StudentController extends Controller {


	public function __construct(){
			$this->middleware('jwt.auth');
		}

		private function transformCollection($roles){
			return array_map([$this, 'transform'], $roles->toArray());
		}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$item = Student::all();
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
		if(!$request->masv or !$request->name or !$request->quequan or !$request->phong or !$request->nha or !$request->sdt){
			return Response::json([
					'error' => [
							'message' => 'Phai cung cap day du'
					]
			], 422);
		}

		$item = new Student;
		$item->masv = $request->masv;
		$item->name = $request->name;
		$item->quequan = $request->quequan;
		$item->phong = $request->phong;
		$item->nha = $request->nha;
		$item->sdt = $request->sdt;
		$item->save();
		return Response::json([
			 'message' => 'Thông tin sinh viên được thêm thành công',
				'data' => $item
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
		$detail = Student::where('id', $id)->get();
		$billbystudent = DB::table('bills')
				->where('idstudent', '=', $id)
				->where('refund',0)
				->join('userwifis', 'userwifis.username' , '=' , 'userwifiname')
				->join('priceusertypes', 'priceusertypes.id', '=', 'idpricetype')
				->join('usertypes', 'idusertype' ,'=','usertypes.id')
				->join('devices', 'usertypes.iddevice', '=', 'devices.id')
				->select('bills.*', 'userwifis.password as userwifi_password', 'usertypes.name as usertype_name','priceusertypes.price as price', 'devices.name as device_name')
				->get();

		$analysis = DB::table('bills')
						->where('idstudent', '=', $id)
						->where('refund',0)
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
	public function update($id, Request $request)
	{
		if(!$request->masv or !$request->name or !$request->quequan or !$request->phong or !$request->nha or !$request->sdt){
			return Response::json([
					'error' => [
							'message' => 'Phai cung cap day du'
					]
			], 422);
		}

		$item =  Student::findorFail($id);
		$item->masv = $request->masv;
		$item->name = $request->name;
		$item->quequan = $request->quequan;
		$item->phong = $request->phong;
		$item->nha = $request->nha;
		$item->sdt = $request->sdt;
		$item->save();
		return Response::json([
			 'message' => 'Thông tin sinh viên được cập nhật thành công',
				'data' => $item
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check = DB::table('bills')
						->where('idstudent', '=', $id)->get();
		if(empty($check)){
			$item = Student::destroy($id);
			return Response::json([
				 'message' => 'xóa thành công'
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
