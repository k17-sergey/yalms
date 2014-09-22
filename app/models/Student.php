<?php

/**
 * Class Student
 *
 * @property string $first_name
 *
 * @method static Student find($id)
 * @method static Student first()
 */
class Student extends \Eloquent {
	protected $fillable = ['first_name', 'last_name'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'students';

	public function courses()
	{
		return $this->belongsToMany('Course');
	}

}