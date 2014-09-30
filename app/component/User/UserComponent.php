<?php
namespace Yalms\Component\User;

use Input;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;


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

} 