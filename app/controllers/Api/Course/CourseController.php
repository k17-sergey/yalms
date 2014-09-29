<?php

namespace app\controllers\Api\Course;
use BaseController;
use Response;
use Yalms\Component\Course\CourseComponent;

/**API-интерфейс на Course**/

class CourseController extends BaseController
{


	//GET-request for get list courses
    public function index()
    {
	    $courses = CourseComponent::index();
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