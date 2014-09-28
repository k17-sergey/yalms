<?php

namespace Yalms\Models\Courses;

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
		return $this->belongsToMany('UserStudent');
	}

	public function teacher()
	{
		return $this->belongsTo('UserTeacher');
	}
}