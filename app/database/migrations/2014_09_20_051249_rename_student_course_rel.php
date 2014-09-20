<?php

use Illuminate\Database\Migrations\Migration;

class RenameStudentCourseRel extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		//
		Schema::rename('student_course_rel', 'student_course');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::rename('student_course', 'student_course_rel');
	}

}
