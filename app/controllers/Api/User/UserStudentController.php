<?php
namespace app\controllers\Api\User;

use Response;
use Yalms\Component\User\UserComponent;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\User;
use Input;

class UserStudentController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//Количество строк на странице по умолчанию
		$perPage = 30;
		if (Input::has('per_page')) {
			$perPage = Input::get('per_page');
		}

		$student = UserStudent::whereEnabled(1)->with(array(
					'user' => function ($query) {
							$query->whereEnabled(true);
						}
				)
			)->paginate($perPage);

		return Response::json($student);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Response::json(
			array('Status' => 403, 'Message' => 'Forbidden'),
			403
		);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return Response::json(
			array('Status' => 403, 'Message' => 'Forbidden'),
			403
		);
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
		$student = UserStudent::with('user')->find($id, array('user_id', 'enabled'));
		$user = User::whereEnabled(true)->find($id);

		if (empty($student->user_id) || empty($user->id)) {
			return Response::json(
				array('Status' => 404, 'Message' => 'Not Found'),
				404
			);
		}


		return Response::json(['student' => $student]);
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
		$userComponent = new UserComponent(Input::all());
		$result = $userComponent->updateStudent($id);

		if ($result) {
			return $this->show($id);
		}

		return Response::json(array(
				'result' => false,
				'message' => $userComponent->message
			),
			$userComponent->status
		);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 *
	 * @return Response
	 */
	public function destroy()
	{
		return Response::json(
			array('Status' => 403, 'Message' => 'Forbidden'),
			403
		);
	}


}
