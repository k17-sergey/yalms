<?php
namespace app\controllers\Api\User;

use Response;
use Yalms\Component\User\UserComponent;
use Yalms\Models\Users\UserTeacher;

class UserTeacherController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Response::json(
			array('Status' => 404, 'Message' => 'Not Found'),
			404
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
			array('Status' => 404, 'Message' => 'Not Found'),
			404
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
		$teacher = UserTeacher::find($id, array('user_id', 'enabled'));

		if (empty($teacher->user_id)) {
			return Response::json(
				array(),
				204 //No Content
			);
		}

		return Response::json(['enabled' => $teacher->enabled]);
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
		$userComponent = new UserComponent;

		return Response::json(array(
				'result'  => $userComponent->updateTeacher($id),
				'message' => $userComponent->message
			)
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
			array('Status' => 404, 'Message' => 'Not Found'),
			404
		);
	}


}
