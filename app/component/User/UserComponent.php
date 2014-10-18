<?php
namespace Yalms\Component\User;

use Input;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;
use DB;


class UserComponent
{

	/**
	 * @var null|User
	 */
	private $user = null;

	/**
	 * @var string Сообщение о результате выполненных операций
	 */
	public $message = '';

	/**
	 * Выдает список пользователей,
	 * с выборкой по полю enabled, либо всех пользователей.
	 * Постранично. Параметры запроса страниц (не обязательные):
	 *      page — N страницы,
	 *      per_page — количество на странице.
	 *
	 * @param string $controlEnabled Выборка пользователей по значению поля "enabled". Значения параметра: 1|0|all
	 *
	 * @return \Illuminate\Pagination\Paginator
	 */
	static public function showUsers($controlEnabled = 'all')
	{
		$perPage = 30; //Количество строк на странице по умолчанию
		if (Input::has('per_page')) {
			$perPage = Input::get('per_page');
		}

		$users = null;
		if ($controlEnabled == 'all') {
			$users = User::paginate($perPage, array('id', 'first_name', 'middle_name', 'last_name'));
		} elseif ($controlEnabled == '1' || $controlEnabled == '0') {
			$users = User::whereEnabled($controlEnabled)
				->paginate($perPage, array('id', 'first_name', 'middle_name', 'last_name'));
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
		$phone = trim(Input::get('phone'));
		if (empty($phone)) {
			$this->message = 'Important data has not been entered';

			return false;
		}

		$countPhones = User::wherePhone($phone)->count();
		if ($countPhones > 0) {
			$this->message = 'This user already exists';

			return false;
		}

		if (!Input::has('password')) {
			$this->message = 'Have not received a password';

			return false;
		}

		$this->user = new User;
		$this->user->phone = $phone;
		$this->prepareToSave(array(
				'first_name',
				'middle_name',
				'last_name',
				'email',
				'password'
			)
		);
		$this->user->save();

		$admin = new UserAdmin;
		$admin->user_id = $this->user->id;
		$admin->save();

		$teacher = new UserTeacher;
		$teacher->user_id = $this->user->id;
		$teacher->save();

		$student = new UserStudent;
		$student->user_id = $this->user->id;
		$student->save();

		$this->message = 'This user is saved';

		return true;
	}


	/**
	 *  Обновление данных пользователя, с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function update($id)
	{
		$this->user = User::find($id);
		if (empty($this->user->id)) {
			$this->message = 'User not found';

			return false;
		}

		$this->prepareToSave(array(
				'first_name',
				'middle_name',
				'last_name',
				'email',
				'password'
			)
		);
		$result = $this->user->save();
		$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';

		return $result;
	}


	/**
	 * Заполнение принятых данных пользователя в модель БД
	 *
	 * @param array $fields
	 */
	private function prepareToSave($fields = array())
	{
		foreach ($fields as $field) {
			if (Input::has($field)) {
				$this->user->$field = Input::get($field);
			}
		}
	}

	/**
	 * Удаление пользователя из БД.
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function destroy($id)
	{
		$this->user = User::find($id);
		if (empty($this->user->id)) {
			$this->message = 'User not found';

			return false;
		}

		$activeConnection = DB::connection();
		$activeConnection->beginTransaction();

		try {
			$isChanged = false;
			if (isset($this->user->admin->enabled) && $this->user->admin->enabled) {
				$this->user->admin->enabled = false;
				$isChanged = true;
			}
			if (isset($this->user->teacher->enabled) && $this->user->teacher->enabled) {
				$this->user->teacher->enabled = false;
				$isChanged = true;
			}
			if (isset($this->user->student->enabled) && $this->user->student->enabled) {
				$this->user->student->enabled = false;
				$isChanged = true;
			}
			if ($isChanged) {
				$this->user->push();
			}

			$result = $this->user->delete();
			$this->message = ($result) ? 'Data deleted successfully' : 'Failed to delete data';
			$activeConnection->commit();
		} catch (\Exception $error) {
			$result = false;
			$this->message = $error->getMessage();
			$activeConnection->rollBack();
		}

		return $result;
	}


	/**
	 *  Обновление данных профиля пользователя "Admin", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function updateAdmin($id)
	{
		$admin = UserAdmin::find($id);
		if (empty($admin->user_id)) {
			$this->message = 'User not found';

			return false;
		}

		if (Input::has('enabled')) {
			$admin->enabled = Input::get('enabled', '0');
			$result = $admin->save();
			$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';
		} else {
			$result = false;
			$this->message = 'No input data';
		}

		return $result;
	}

	/**
	 *  Обновление данных профиля пользователя "студент", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function updateStudent($id)
	{
		$student = UserStudent::find($id);
		if (empty($student->user_id)) {
			$this->message = 'User not found';

			return false;
		}

		if (Input::has('enabled')) {
			$student->enabled = Input::get('enabled', '0');
			$result = $student->save();
			$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';
		} else {
			$result = false;
			$this->message = 'No input data';
		}

		return $result;
	}

	/**
	 *  Обновление данных профиля пользователя "учитель", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function updateTeacher($id)
	{
		$teacher = UserTeacher::find($id);
		if (empty($teacher->user_id)) {
			$this->message = 'User not found';

			return false;
		}

		if (Input::has('enabled')) {
			$teacher->enabled = Input::get('enabled', '0');
			$result = $teacher->save();
			$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';
		} else {
			$result = false;
			$this->message = 'No input data';
		}

		return $result;
	}


} 