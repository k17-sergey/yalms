<?php
namespace app\controllers\Api\User;

use Input;
use Response;
use app\controllers\Api\BaseApiController;
use Yalms\Models\Users\User;
use Yalms\Component\User\UserComponent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class UserController extends BaseApiController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$userComp = new UserComponent(Input::all());

		if (!$userComp->validateParameters()) {
			return $this->requestResult($userComp->message);
		}

		return Response::json(
			$userComp->showUsers()
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

		if ($userComp->storeNewUser()) {
			return $this->show($userComp->user->id, 201);
		}
		return $this->requestResult($userComp->message);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @param int  $statusHttp
	 *
	 * @return Response
	 */
	public function show($id, $statusHttp = 200)
	{
		try {
			$user = User::with('teacher', 'student', 'admin')
				->findOrFail($id, array('id', 'first_name', 'middle_name', 'last_name', 'email', 'phone'));
		} catch (\Exception $exc) {
			return $this->clientError();
		}

		return Response::json(['user' => $user], $statusHttp);
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
		try {
			$user = User::findOrFail($id, array('id', 'first_name', 'middle_name', 'last_name', 'email', 'phone'));
		} catch (\Exception $exc) {
			return $this->clientError();
		}

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

		try {
			$result = $userComponent->update($id);
		} catch (NotFoundHttpException $exc) {
			return $this->clientError();
		}

		if ($result) {
			return $this->show($id);
		}

		return $this->requestResult($userComponent->message, $result);
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
		try {
			$result = $userComponent->destroy($id);
			$message = $userComponent->message;
		} catch (NotFoundHttpException $exc) {
			return $this->clientError();
		} catch (\Exception $exc) {
			$result = false;
			$message = $exc->getMessage();
		}

		return $this->requestResult($message, $result);
	}


}
