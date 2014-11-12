<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function () {
	return View::make('hello');
});


Route::resource('student', 'StudentController');
Route::resource('teacher', 'TeacherController');
Route::resource('course', 'CourseController');

Route::group(array('prefix' => 'api/v1'), function () {

	\App::error(function (\Exception $exception, $code) {
		if ($code >= 500) {
			Log::error($exception);
		}

		return Response::json(array(
				//или 'done'  => false,
				'come_true' => false,
				'message'   => $exception->getMessage(),
				'errors'    => array()
			),
			$code
		);
	});

	Route::resource('user', 'app\controllers\Api\User\UserController');
	Route::resource('teacher', 'app\controllers\Api\User\UserTeacherController');
	Route::resource('student', 'app\controllers\Api\User\UserStudentController');

	Route::resource('course', 'app\controllers\Api\Course\CourseController');
});
