<?php

use Illuminate\Database\Migrations\Migration;

class CreateStudentCourseRel extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('student_course_rel', function ($table) {
			$table->increments('id');

			//Имя
			$table->integer('student_id');
			//Фамилия
			$table->integer('course_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('student_course_rel');
	}

}
