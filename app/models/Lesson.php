<?php

class Lesson extends \Eloquent {

	protected $fillable = [];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'course_lessons';

	public function courses()
	{
		return $this->belongsTo('Course');
	}

	public function exams()
	{
		return $this->hasMany('Exam');
	}
}