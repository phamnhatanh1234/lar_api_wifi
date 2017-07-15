<?php namespace App\Http\Controllers;

use App\Device;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DeviceController extends Controller {


	public function __construct(){
			$this->middleware('jwt.auth');
		}

		private function transformCollection($roles){
	    return array_map([$this, 'transform'], $roles->toArray());
		}

		private function transform($item){
		    return [
		       'id' => $item['id'],
		       'name' => $item['name']
		     ];
		}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$item = Device::all();
    return Response::json([
        'data' => $this->transformCollection($item)
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
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$item = Device::where('id', $id)->get();
		return Response::json([
				'data' => $this->transformCollection($item)
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
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
