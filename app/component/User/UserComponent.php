<?php
namespace Yalms\Component\User;

use Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;


class UserComponent
{

	/**
	 * @var null|User
	 */
	public $user = null;

	/**
	 * Принятые данные запроса
	 *
	 * @var array
	 */
	private $input = array();

	public function __construct($input = null)
	{
		$this->input = empty($input) ? array() : $input;
	}

	/**
	 * @var string|array Сообщение о результате выполненных операций
	 */
	public $message = '';

	/**
	 * Сообщения об ошибках при проверке данных
	 *
	 * @var array
	 */
	private $errorMessages = array(
		'required'   => 'Поле должно быть заполнено обязательно!',
		'unique'     => ':attribute с таким значением уже есть.',
		'email'      => 'Должен быть корректный адрес электронной почты.',
		'alpha_dash' => 'Должны быть только латинские символы, цифры, знаки подчёркивания (_) и дефисы (-).',
		'confirmed' => 'Пароли не совпадают.',
		'min'       => ':attribute должен быть не меньше :min символов'
	);

	/**
	 * Массив параметров запроса со значениями по умолчанию (название параметра => значение)
	 *
	 * @var array
	 */
	private $queryParameters = array(
		'per_page'  => 30,
		'sort'      => 'created',
		'direction' => 'desc',
		'state'     => 'enabled'
	);
	/**
	 * @return array  Массив параметров запроса с установками по умолчанию
	 */
	public function getQueryParameters()
	{
		return $this->queryParameters;
	}

	/**
	 * Правила проверки параметров запроса (для объекта Validator)
	 *
	 * @var array
	 */
	private $parameterRules = array(
		'page'      => 'integer|min:1',
		'per_page'  => 'integer|between:1,100',
		'sort'      => 'in:created,updated',
		'direction' => 'in:asc,desc',
		'state'     => 'in:enabled,disabled,all'
	);

	/**
	 * Проверка параметров запроса множественных данных (страницы, сортировка и пр.)
	 * и установка значений по умолчанию
	 *
	 * @return bool
	 */
	public function validateParameters()
	{
		$validator = Validator::make(
			$this->input,
			$this->parameterRules
		);
		if ($validator->fails()) {
			$this->setValidatorMessage($validator);

			return false;
		}

		foreach ($this->queryParameters as $parameter => $value) {
			if (!empty($this->input[$parameter])) {
				$this->queryParameters[$parameter] = $this->input[$parameter];
			}
		}

		return true;
	}

	/**
	 * Выдает список пользователей,
	 * с выборкой по полю enabled, либо всех пользователей.
	 *
	 * Параметры:
	 * state = enabled|disabled|all      выборка по полю "enabled", значение по умолчанию "enabled"
	 * sort = created|updated            Сортировка по полю  "created_at" или "updated_at", по умолчанию "created"
	 * direction = asc|desc              Направление сортировки, по умолчанию "desc"
	 *
	 * Для использования параметров запроса — предваритрельн вызвать "validateParameters()"
	 *
	 * Постранично. Параметры запроса страниц (не обязательные):
	 *      page — N страницы,
	 *      per_page — количество на странице.
	 *
	 * @return \Illuminate\Pagination\Paginator
	 */
	public function showUsers()
	{
		/**
		 * @var integer $per_page
		 * @var string  $sort
		 * @var string  $direction
		 * @var string  $state
		 */
		extract($this->queryParameters);
		$sort .= '_at';

		$users = null;
		if ($state == 'all') {
			$users = User::with('teacher', 'student', 'admin')->orderBy($sort, $direction)->paginate(
				$per_page,
				array('id', 'first_name', 'middle_name', 'last_name', 'created_at', 'updated_at')
				);
		} else {
			$state = ($state == 'enabled') ? '1' : '0';
			$users = User::with('teacher', 'student', 'admin')->whereEnabled($state)
				->orderBy($sort, $direction)->paginate(
					$per_page,
					array('id', 'first_name', 'middle_name', 'last_name', 'created_at', 'updated_at')
				);
		}

		return $users;
	}

	/**
	 * Сохранение принятых данных для нового пользователя
	 *
	 * @return bool
	 */
	public function storeNewUser()
	{
		$validator = Validator::make(
			$this->input,
			array(
				'phone'    => 'required|unique:users',
				'email'    => 'email',
				'password' => 'required|alpha_dash|min:8|confirmed'
			),
			$this->errorMessages
		);
		if ($validator->fails()) {
			$this->setValidatorMessage($validator);

			return false;
		}

		$this->user = new User;
		$this->user->phone = $this->input['phone'];
		$this->prepareToSave(array(
				'first_name',
				'middle_name',
				'last_name',
				'email',
				'password'
			)
		);

		$activeConnection = $this->user->getConnection();
		$activeConnection->beginTransaction();

		try {
			if ($this->saveNewUser()) {
				$activeConnection->commit();

				return true;
			}
			$activeConnection->rollBack();
		} catch (\Exception $error) {
			$this->message = $error->getMessage();
			$activeConnection->rollBack();
		}

		return false;
	}


	/**
	 *  Обновление данных пользователя, с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function update($id)
	{
		$this->user = User::find($id);
		if (empty($this->user->id)) {
			throw new NotFoundHttpException;
		}

		$validator = Validator::make(
			$this->input,
			array(
				'email'    => 'email',
				'password' => 'alpha_dash|min:8|confirmed'
			),
			$this->errorMessages
		);
		if ($validator->fails()) {
			$this->setValidatorMessage($validator);

			return false;
		}

		$areThereData = $this->prepareToSave(array(
				'first_name',
				'middle_name',
				'last_name',
				'email',
				'password'
			)
		);
		if (!$areThereData) {
			return false;
		}

		$result = $this->user->save();
		$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';

		return $result;
	}

	/**
	 * Удаление пользователя из БД.
	 *
	 * @param  int $id
	 *
	 * @return bool
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function destroy($id)
	{
		$this->user = User::find($id);
		if (empty($this->user->id)) {
			throw new NotFoundHttpException;
		}

		$activeConnection = $this->user->getConnection();
		$activeConnection->beginTransaction();

		try {
			if ($this->deleteUser()) {
				$activeConnection->commit();

				return true;
			}
			$activeConnection->rollBack();
		} catch (\Exception $error) {
			$this->message = $error->getMessage();
			$activeConnection->rollBack();
		}

		return false;
	}


	/**
	 * Заполнение принятых данных пользователя в модель БД
	 *
	 * @param array $fields
	 *
	 * @return bool
	 */
	private function prepareToSave($fields = array())
	{
		$areThereData = false;
		foreach ($fields as $field) {
			if (!empty($this->input[$field])) {
				$this->user->$field = $this->input[$field];
				$areThereData = true;
			}
		}
		if (!$areThereData) {
			$this->message = 'No required data.';
		}

		return $areThereData;
	}

	/**
	 * @return bool
	 */
	private function saveNewUser()
	{
		$isSaved = $this->user->save();

		$admin = new UserAdmin;
		$admin->user_id = $this->user->id;
		$isSaved &= $admin->save();

		$teacher = new UserTeacher;
		$teacher->user_id = $this->user->id;
		$isSaved &= $teacher->save();

		$student = new UserStudent;
		$student->user_id = $this->user->id;
		$isSaved &= $student->save();

		$this->message = $isSaved ? 'This user is saved' : 'This user is not saved';

		return $isSaved;
	}

	/**
	 * @return bool
	 */
	private function deleteUser()
	{
		$this->user->admin->enabled = false;
		$this->user->teacher->enabled = false;
		$this->user->student->enabled = false;
		$result = $this->user->push();
		$result &= $this->user->delete();
		$this->message = ($result) ? 'Data deleted successfully' : 'Failed to delete data';

		return $result;
	}




	//*********************************
	// Профайлы пользователя
	//*********************************

	/**
	 *  Обновление данных профиля пользователя "Admin", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function updateAdmin($id)
	{
		$admin = UserAdmin::find($id);
		if (empty($admin->user_id)) {
			throw new NotFoundHttpException;
		}
		if (!$this->validateProfile()) {
			return false;
		}

		$admin->enabled = $this->input['enabled'];
		$result = $admin->save();
		$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';

		return $result;
	}

	/**
	 *  Обновление данных профиля пользователя "студент", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function updateStudent($id)
	{
		$student = UserStudent::find($id);
		if (empty($student->user_id)) {
			throw new NotFoundHttpException;
		}
		if (!$this->validateProfile()) {
			return false;
		}

		$student->enabled = $this->input['enabled'];
		$result = $student->save();
		$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';

		return $result;
	}

	/**
	 *  Обновление данных профиля пользователя "учитель", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function updateTeacher($id)
	{
		$teacher = UserTeacher::find($id);
		if (empty($teacher->user_id)) {
			throw new NotFoundHttpException;
		}
		if (!$this->validateProfile()) {
			return false;
		}

		$teacher->enabled = $this->input['enabled'];
		$result = $teacher->save();
		$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';

		return $result;
	}

	private function validateProfile()
	{
		$validator = Validator::make(
			$this->input,
			array('enabled' => 'required|in:0,1'),
			array(
				'required' => 'Поле должно быть заполнено обязательно!',
				'in'       => 'Введено некорректное значение.'
			)
		);
		if ($validator->fails()) {
			$this->setValidatorMessage($validator);

			return false;
		}

		return true;
	}

	/**
	 * Ошибки валидатора записываются в сообщение
	 *
	 * @param object $validator
	 */
	private function setValidatorMessage($validator)
	{
		$this->message = array(
			'messages' => $validator->messages(),
			'failed'   => $validator->failed()
		);
	}


} 