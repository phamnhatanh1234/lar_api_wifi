<?php namespace App\Http\Controllers;
use App\Role;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RolesController extends Controller {



	public function __construct(){
			$this->middleware('jwt.auth');
		}

		private function transformCollection($roles){
	    return array_map([$this, 'transform'], $roles->toArray());
		}

		private function transform($role){
		    return [
		       'id' => $role['id'],
		       'name' => $role['name']
		     ];
		}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$item = Role::all();
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

		$item = Role::where('id', $id)->get();
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
