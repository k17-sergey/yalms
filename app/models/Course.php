<?php

/**
 * Class Course
 *
 * @property integer        $id
 * @property string         $name
 * @method static Course find($id)
 * @method static Course paginate()
 * @method static Course delete()
 * @method static Course save()
 * @method static Course all()
 */

class Course extends \Eloquent {

	protected $fillable = ['Name'];

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