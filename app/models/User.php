<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


/**
 * Class User
 *
 * @property integer     $id
 * @property string      $first_name
 * @property string      $middle_name
 * @property string      $last_name
 * @property string      $email
 * @property string      $phone
 * @property string      $password
 * @property string      $remember_token
 * @property boolean     $enabled
 *
 * @property UserStudent $student
 * @property UserTeacher $teacher
 * @property UserAdmin   $admin
 *
 */
class User extends Eloquent implements UserInterface, RemindableInterface
{
	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('id', 'password', 'remember_token', 'enabled');


	public function student()
	{
		return $this->hasOne(UserStudent::class);
	}

	public function teacher()
	{
		return $this->hasOne(UserTeacher::class);
	}

	public function admin()
	{
		return $this->hasOne(UserAdmin::class);
	}

}
