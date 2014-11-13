<?php

namespace Yalms\Models\Users;

use Eloquent;
use Yalms\Models\Courses\Course;

/**
 * Class UserTeacher
 *
 * @property integer   $user_id
 * @property boolean   $enabled
 * @property Course[]  $courses
 *
 * @method static UserTeacher first
 * @method static UserTeacher find($id)
 * @method static UserTeacher findOrFail($id)
 * @method static UserTeacher whereEnabled($boolean)
 */
class UserTeacher extends Eloquent
{

	protected $table = 'user_teacher';

	/**
	 * Используется отношение один к одному с таблицей users.
	 * Первичный ключ, он же ещё и внешний ключ с уникальными значениями
	 */
	protected $primaryKey = 'user_id';

	public function courses()
	{
		return $this->hasMany(Course::class, 'user_teacher_id');
	}

	/**
	 * @return User
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}


}
