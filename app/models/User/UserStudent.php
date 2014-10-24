<?php

namespace Yalms\Models\Users;

use Eloquent;

/**
 * Class UserStudent
 *
 * @property integer $user_id
 * @property boolean $enabled
 *
 * @method static UserStudent first
 * @method static UserStudent find($id) first
 * @method static UserStudent whereEnabled($boolean)
 */
class UserStudent extends Eloquent
{

	protected $table = 'user_student';

	/**
	 * Используется отношение один к одному с таблицей users.
	 * Первичный ключ, он же ещё и внешний ключ с уникальными значениями
	 */
	protected $primaryKey = 'user_id';

	/**
	 * @return User
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
