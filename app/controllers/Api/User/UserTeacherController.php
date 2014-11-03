<?php
namespace app\controllers\Api\User;

use Input;
use Response;
use Validator;
use app\controllers\Api\BaseApiController;
use Yalms\Component\User\UserComponent;
use Yalms\Models\Users\UserTeacher;
use Yalms\Models\Users\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserTeacherController extends BaseApiController
{

	/**
	 * Display a listing of the resource.
	 *
	 * Параметры:
	 *      page — N страницы,
	 *      per_page — количество на странице.
	 *      sort = created|updated   Сортировка по полю  "created_at" или "updated_at", по умолчанию "created"
	 *      direction = asc|desc     Направление сортировки, по умолчанию "desc"
	 *
	 * @return Response
	 */
	public function index()
	{
		$validator = Validator::make(
			Input::all(),
			array(
				'page'      => 'integer|min:1',
				'per_page'  => 'integer|between:1,100',
				'sort'      => 'in:created,updated',
				'direction' => 'in:asc,desc',
			)
		);
		if ($validator->fails()) {
			return $this->requestResult(
				array(
					'messages' => $validator->messages(),
					'failed'   => $validator->failed()
				)
			);
		}

		//Количество строк на странице
		$perPage = Input::get('per_page', 30);
		//Сортировка по полю
		$sort = Input::get('sort', 'updated') . '_at';
		//Направление сортировки
		$direction = Input::get('direction', 'desc');

		$teacher = UserTeacher::whereEnabled(1)->with(array(
					'user' => function ($query) {
							/** @var User $query */
							$query->whereEnabled(true);
						}
				)
		)->orderBy($sort, $direction)->paginate($perPage);

		return Response::json($teacher);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return $this->clientError(403);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return $this->clientError(403);
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
		$teacher = UserTeacher::with('user')->find($id, array('user_id', 'enabled'));
		$user = User::whereEnabled(true)->find($id);

		if (empty($teacher->user_id) || empty($user->id)) {
			return $this->clientError();
		}

		return Response::json(['teacher' => $teacher]);
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
		$user = User::whereEnabled(true)->find($id, array('id', 'first_name', 'middle_name', 'last_name'));
		if (empty($user->id)) {
			return $this->clientError();
		}

		return Response::json(array(
			'teacher' => array(
				'id'      => $id,
				'enabled' => UserTeacher::find($id)->enabled,
				'user'    => $user
			),
			'edit_fields'     => array('enabled' => 'Назначить учителем'),
			'required_fields' => array('enabled')
		));
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
		try {
			$result = $userComponent->updateTeacher($id);
		} catch (NotFoundHttpException $exc) {
			return $this->clientError();
		}

		if ($result) {
			return $this->show($id);
		}

		return $this->requestResult($userComponent->message);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 *
	 * @return Response
	 */
	public function destroy()
	{
		return $this->clientError(403);
	}


}
