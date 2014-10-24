<?php
namespace app\controllers\Api\User;

use Response;
use Yalms\Models\Users\User;
use Yalms\Component\User\UserComponent;
use Input;

class UserController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json(
		// Параметр означает условие запроса enabled=='1'
			UserComponent::showUsers('1')
		);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		return Response::json(array(
			'edit_fields'     => array(
				'last_name'             => 'Фамилия',
				'first_name'            => 'Имя',
				'middle_name'           => 'Отчество',
				'email'                 => 'Электронная почта',
				'phone'                 => 'Номер телефона',
				'password'              => 'Пароль',
				'password_confirmation' => 'Подтверждение пароля'
			),
			'required_fields' => array(
				'first_name',
				'phone',
				'password',
				'password_confirmation'
			)
		));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$userComp = new UserComponent(Input::all());
		$result = $userComp->storeNewUser();

		if ($result) {
			$user = User::find(
				$userComp->user->id,
				array('id', 'first_name', 'middle_name', 'last_name', 'email', 'phone')
			);

			return Response::json($user, 201);
		}

		return Response::json(array(
				'result'  => false,
				'message' => $userComp->message
			)
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
		$user = User::findOrFail($id, array('id', 'first_name', 'middle_name', 'last_name', 'email', 'phone'));

		$fields = array(
			'last_name'             => 'Фамилия',
			'first_name'            => 'Имя',
			'middle_name'           => 'Отчество',
			'email'                 => 'Электронная почта',
			'password'              => 'Пароль',
			'password_confirmation' => 'Подтверждение пароля'
		);

		return Response::json(array(
				'user'        => $user,
				'edit_fields' => $fields
			)
		);
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
		$userComponent = new UserComponent(\Input::all());
		$result = $userComponent->update($id);

		if ($userComponent->status == 404) {
			return Response::json($userComponent->message, 404);
		}
		if ($result) {
			return $this->show($id);
		}

		return Response::json(array(
				'result' => false,
				'errors' => $userComponent->message
			)
		);
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
		$userComponent = new UserComponent();

		return Response::json(array(
				'result'  => $userComponent->destroy($id),
				'message' => $userComponent->message
			)
		);
	}


}
