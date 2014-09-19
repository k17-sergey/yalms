<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCourseLessonExamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('course_lesson_exams', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('course_lesson_id')->unsigned();
			$table->foreign('course_lesson_id')->references('id')->on('course_lessons');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('course_lesson_exams');
	}

}
