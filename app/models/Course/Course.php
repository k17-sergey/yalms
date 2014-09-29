<?php




namespace Yalms\Models\Courses;


/**
 * Class Course
 *
 * @property integer        $id
 * @property string         $name
 * @method static Course find($id)
 * @method static Course delete()
 * @method static Course save()
 * @method static Course all()
 * @method static Course findOrFail($id)
 *
 */
class Course extends \Eloquent {

	protected $fillable = ['name'];
	protected $guarded = array('id');

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