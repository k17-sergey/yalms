<?php

namespace Yalms\Models\Courses;

class Exam extends \Eloquent {

	protected $fillable = [];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'course_lesson_exams';

	public function lesson()
	{
		return $this->belongsTo('Lesson');
	}
}