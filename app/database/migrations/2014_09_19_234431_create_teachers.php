<?php

use Illuminate\Database\Migrations\Migration;

class CreateTeachers extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('teachers', function ($table) {
			$table->increments('id');

			//Имя
			$table->string('firstName', 32);
			//Фамилия
			$table->string('lastName', 32);
			//Отчество
			$table->string('middleName', 32);
			$table->boolean('male');
			$table->string('specialization', 32);

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
		Schema::drop('teachers');
	}

}
