<?php


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class CourseController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$paginate = Course::paginate(1);

		return [
			'items'  => $paginate->getItems(),
			'paging' => [
				'count' => $paginate->count(),
				'last'  => $paginate->getLastPage(),
				'per'   => $paginate->getPerPage(),
				'total' => $paginate->getTotal(),
			],
		];
	}




	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return new Course;

	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $course = new Course;
        $course ->name = $courseName = Input::get('name');
        $course ->save();

        return Response::json(array(
            'status' => 'Course ' .$courseName. ' been successful created',
            'http'=>200));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$course =  Course::find($id);
        return Response::json(array(
                'course' => $course,
                'status'=>200)
        );
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
       return Course::find($id);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function update($id)
	{
        $course = Course::find($id);
        $course ->name = $courseName = Input::get('name');
        $course ->save();

        return Response::json(array(
            'status' => 'Course ' .$courseName. ' been successful updated',
            'http'=>200));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$courseName =  Course::find($id)->name;
        Course::delete($id);
        return Response::json(array(
                'status' => 'Course ' .$courseName. ' been successful removed',
                'http'=>200)
        );
	}


}
