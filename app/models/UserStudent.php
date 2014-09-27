<?php


/**
 * Class UserStudent
 *
 * @property integer $user_id
 * @property boolean $enabled
 *
 */
class UserStudent extends Eloquent
{

	protected $table = 'user_student';

	/**
	 * Используется отношение один к одному с таблицей users.
	 * Первичный ключ, он же ещё и внешний ключ с уникальными значениями
	 */
	protected $primaryKey = 'user_id';


}
