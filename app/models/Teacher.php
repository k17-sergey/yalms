<?php

class Teacher extends \Eloquent {
	protected $fillable = [];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'teachers';

	public function courses()
	{
		return $this->hasMany('Course');
	}

}