<?php

class Course extends \Eloquent {

	protected $fillable = [];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'courses';

	public function lessons()
	{
		return $this->hasMany('Lesson');
	}

	public function students()
	{
		return $this->belongsToMany('Student');
	}

	public function teacher()
	{
		return $this->belongsTo('Teacher');
	}
}