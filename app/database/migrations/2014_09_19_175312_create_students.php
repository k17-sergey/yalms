<?php

use Illuminate\Database\Migrations\Migration;

class CreateStudents extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('students', function ($table) {
			$table->increments('id');

			//Имя
			$table->string('firstName', 32);
			//Фамилия
			$table->string('lastName', 32);
			//Отчество
			$table->string('middleName', 32);
			$table->boolean('male');
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
		Schema::drop('students');
	}

}
