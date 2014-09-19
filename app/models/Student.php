<?php

class Student extends \Eloquent {
	protected $fillable = [];

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