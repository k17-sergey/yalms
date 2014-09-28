<?php

namespace app\controllers\Api\Course;
use BaseController;
use Response;
use Yalms\Models\Courses\Course;

/**API-интерфейс на Course**/

class CourseController extends BaseController
{



    public function index()
    {
        $course = Course::all();
        return Response::json($course);
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
}