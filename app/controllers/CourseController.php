<?php


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Yalms\Models\Courses\Course;

class CourseController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $courses = Course::all();
        //Вполне возможна ситуация по которой мы пришли в этот контроллер
        //после редиректа от функции удаления.
        //Тогда у нас есть некое статусное сообщение($message),
        //которое необходимо отрисовать на странице.
        $message = Session::get('message');
        if (isset($message))
            //Сообщение таки есть.Надо его показать пользователю
            return View::make('pages.course.index', compact('message', 'courses'));

        return View::make('pages.course.index')->with('courses', $courses);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        //Форма создания нового курса
        return View::make('pages.course.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return View
     */
    public function store()
    {
        $course = new Course;
        $course->name = Input::get('name');
        $course->save();
        $id = $course->id;
        $message = 'Course ' . $course->name . ' been successful created';

        //Отсылка к странице новосозданомого курсу

        return Redirect::action('CourseController@show', array($id))->with('message', $message);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Redirect
     */
    public function show($id)
    {

        $course = Course::find($id);
        //Вполне возможна ситуация по которой мы пришли в этот контроллер
        //после редиректа от функции создания или обновления.
        $message = Session::get('message');
        if (isset($message))
            //Сообщение таки есть.Надо его показать пользователю
            return View::make('pages.course.show', compact('message', 'course'));
        else
            return View::make('pages.course.show')->with('course', $course);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $url = URL::route('course.update', ['id' => $id]);
        $courseName = Course::find($id)->name;
        return View::make('pages.course.edit', compact('courseName', 'url'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Redirect
     */
    public function update($id)
    {
        $course = Course::find($id);
        $course->name = $courseName = Input::get('name');
        $course->save();

        $message = 'Course ' . $course->name . ' been successful update';

        //Покажем что мы там обновили
        return Redirect::action('CourseController@show', array($id))->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Redirect
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        $courseName = $course->name;

        $course->delete();

        $message = 'Course ' . $courseName . ' been successful removed';
        //Отправим на заглавную страницу всех курсов
        return Redirect::action('CourseController@index')->with('message', $message);
    }
}
