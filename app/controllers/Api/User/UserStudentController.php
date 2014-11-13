<?php
namespace app\controllers\Api\User;

use Input;
use Response;
use app\controllers\Api\BaseApiController;
use Yalms\Component\User\UserComponent;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\User;

class UserStudentController extends BaseApiController
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
		$userComponent = new UserComponent(Input::only(
			array('page', 'per_page', 'sort', 'direction')
		));
		$params = $userComponent->getParameters();

		$student = UserStudent::whereEnabled(1)->with(array(
					'user' => function ($query) {
							/** @var User $query */
							$query->whereEnabled(true);
						}
				)
		)->orderBy($params->sort, $params->direction)->paginate($params->per_page);

		return Response::json($student);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return $this->clientError(405);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return $this->clientError(405);
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
		User::whereEnabled(true)->findOrFail($id);
		$student = UserStudent::with('user')->find($id, array('user_id', 'enabled'));

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
		$user = User::whereEnabled(true)->findOrFail($id, array('id', 'first_name', 'middle_name', 'last_name'));

		return Response::json(array(
			'student' => array(
				'id'      => $id,
				'enabled' => UserStudent::find($id)->enabled,
				'user'    => $user
			),
			'edit_fields'     => array('enabled' => 'Назначить студентом'),
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

		if ($userComponent->updateStudent($id) == UserComponent::FAILED_VALIDATION) {
			return $this->responseError($userComponent->getMessage(), $userComponent->getErrors());
		}

		return $this->show($id);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 *
	 * @return Response
	 */
	public function destroy()
	{
		return $this->clientError(405);
	}


}
