<?php

namespace app\controllers\Api\Course;
use BaseController;
use Response;
use Yalms\Models\Courses\Course;


/**API-интерфейс на Course**/

class CourseController extends BaseController
{


	//GET-request for get list courses
    public function index()
    {
	    $courses = Course::get(array('name','id'));
        return Response::json($courses);
    }

    public function create()
    {

    }

    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update($id)
    {

    }

    public function destroy($id)
    {

    }
}