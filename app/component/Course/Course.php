<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 9/29/14
 * Time: 1:10 AM
 */

namespace Yalms\Component\Course;

use \Response;
use Yalms\Models\Courses\Course;



/**Service component for CourseController(REST&API implementations)**/
class CourseComponent
{



	static function  index()
	{
		return Course::get(array('name','id'));
	}
} 