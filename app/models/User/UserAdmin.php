<?php

namespace Yalms\Models\Users;

use Eloquent;

/**
 * Class UserAdmin
 *
 * @property integer $user_id
 * @property boolean $enabled
 *
 */
class UserAdmin extends Eloquent
{

	protected $table = 'user_admin';

	/**
	 * Используется отношение один к одному с таблицей users.
	 * Первичный ключ, он же ещё и внешний ключ с уникальными значениями
	 */
	protected $primaryKey = 'user_id';


}
