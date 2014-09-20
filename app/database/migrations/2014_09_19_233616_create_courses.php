<?php

use Illuminate\Database\Migrations\Migration;

class CreateCourses extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('courses', function ($table) {
			$table->increments('id');

			//Название
			$table->string('Name', 50);
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
		Schema::drop('courses');
	}

}
