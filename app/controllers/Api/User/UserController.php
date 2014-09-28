<?php
namespace app\controllers\Api\User;

use Input;
use Request;
use Response;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;

class UserController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$perPage = 30; //Количество строк на странице по умолчанию
		if (Input::has('per_page')) {
			$perPage = Input::get('per_page');
		}

		$users = User::whereEnabled('1')
			->paginate($perPage, array('id', 'first_name', 'middle_name', 'last_name'));

		return Response::json($users);
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
		$phone = trim(Request::get('phone'));
		if (empty($phone)) {
			return [
				'result'  => false,
				'message' => 'Data has not been entered'
			];
		}

		$countPhones = User::wherePhone($phone)->count();
		if ($countPhones > 0) {
			return [
				'result'  => false,
				'message' => 'This user already exists'
			];
		}

		$user = new User;
		$user->first_name = Input::get('first_name');
		$user->middle_name = Input::get('middle_name');
		$user->last_name = Input::get('last_name');
		$user->email = Input::get('email');
		$user->phone = Input::get('phone');
		$user->password = Input::get('password');

		$user->save();

		$admin = new UserAdmin;
		$admin->user_id = $user->id;
		$admin->save();

		$teacher = new UserTeacher;
		$teacher->user_id = $user->id;
		$teacher->save();

		$student = new UserStudent;
		$student->user_id = $user->id;
		$student->save();

		return [
			'result'  => true,
			'message' => 'This user is saved'
		];
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::findOrFail($id, array('id', 'first_name', 'middle_name', 'last_name', 'email', 'phone'));

		return Response::json($user);

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
