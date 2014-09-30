<?php

namespace app\controllers\Api\Course;
use BaseController;
use Illuminate\Support\Facades\Input;
use Response;
use Yalms\Models\Courses\Course;


/**API-интерфейс на Course**/

class CourseController extends BaseController
{


    public function index()
        /**
         Получение списка объектов.
         Пример запроса
         $.ajax({
         url: "/api/v1/course/"
         });
         */
    {


        $courses = Course::get(array('name','id'));
        return Response::json($courses);
    }

    public function store()
        /**
        Обновление объекта.
        Пример запроса
        $.ajax({
        url: "/api/v1/course",
        method :"POST",
        data: {"name": "foo"}
        });
        */
    {

        $course = new Course();
        $course -> name = Input::get('name');
        $course->save();

        $status = 'Course ' . $course->name . ' been successful created';

        //Респонз о удачном событии
        return Response::json($status);


    }

    public function show($id)
        /**
        Получение конкретного объекта.
        Пример запроса
        $.ajax({
        url: "/api/v1/course/1"
        });
        */
    {

        $course = Course::find($id,array('name'));
        return Response::json($course);
    }


    public function update($id)

        /**
        Обновление объекта.
        Пример запроса
        $.ajax({
        url: "/api/v1/course/7",
        method :"PUT",
        data: {"name": "bar"}
        });
        */

    {
        $course = Course::find($id);
        $course -> name = Input::get('name');
        $course->save();

        $status = 'Course ' . $course->name . ' been successful update';


        return Response::json($status);
    }

    public function destroy($id)

        /**
        Удаление объекта.
        Пример запроса
        $.ajax({
        url: "/api/v1/course/7",
        method :"DELETE",
        });
         */

    {


        $course = Course::find($id);
        $course ->delete();

        return Response::json(array('status','Course'));
    }
}