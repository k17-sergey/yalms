<?php

use Illuminate\Database\Migrations\Migration;

class ChangeStTchToUsers extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::drop('students');
		Schema::drop('teachers');

		Schema::create('users', function ($table) {
			$table->increments('id');

			$table->string('email')->nullable()->unique();
			$table->string('phone_number')->nullable()->unique();
			$table->string('password');

			$table->string('first_name', 32);
			$table->string('last_name', 32);
			$table->string('middle_name', 32);

			$table->timestamps();
		});

		Schema::create('user_student', function ($table) {
			$table->increments('id');

			$table->integer('user_id');
			$table->boolean('enabled');

			$table->timestamps();
		});

		Schema::create('user_admin', function ($table) {
			$table->increments('id');

			$table->integer('user_id');
			$table->boolean('enabled');

			$table->timestamps();
		});

		Schema::create('user_teacher', function ($table) {
			$table->increments('id');

			$table->integer('user_id');
			$table->boolean('enabled');

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
		//
		Schema::create('students', function ($table) {
			$table->increments('id');

			//Имя
			$table->string('first_name', 32);
			//Фамилия
			$table->string('last_name', 32);
			//Отчество
			$table->string('middle_name', 32);
			$table->boolean('male');

			$table->timestamps();
		});

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

			$table->timestamps();
		});

		Schema::drop('users');
	}
}
