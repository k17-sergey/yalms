<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration
{

	/**
	 * Run the migrations.
	 *
	 */
	public function up()
	{
		Schema::create('users', function ($table) {
			$table->increments('id');
			$table->string('first_name', 32)->default('');
			$table->string('last_name', 32)->default('');
			$table->string('middle_name', 32)->default('');

			$table->string('email')->default('');
			$table->string('phone')->unique();
			$table->string('password');
			$table->string('remember_token', 100)->nullable();

			$table->boolean('enabled')->default('0');
			$table->timestamps();
		});

		Schema::create('user_student', function ($table) {
			$table->integer('user_id')->unsigned()->primary();
			$table->boolean('enabled')->default('0');
			$table->timestamps();
		});
		Schema::table('user_student', function ($table) {
			$table->foreign('user_id')->references('id')->on('users');
		});


		Schema::create('user_teacher', function ($table) {
			$table->integer('user_id')->unsigned()->primary();
			$table->boolean('enabled')->default('0');
			$table->timestamps();
		});
		Schema::table('user_teacher', function ($table) {
			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::create('user_admin', function ($table) {
			$table->integer('user_id')->unsigned()->primary();
			$table->boolean('enabled')->default('0');

			$table->timestamps();
		});
		Schema::table('user_admin', function ($table) {
			$table->foreign('user_id')->references('id')->on('users');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 */
	public function down()
	{
		Schema::table('user_student', function ($table) {
			$table->dropForeign('user_student_user_id_foreign');
		});
		Schema::table('user_teacher', function ($table) {
			$table->dropForeign('user_teacher_user_id_foreign');
		});
		Schema::table('user_admin', function ($table) {
			$table->dropForeign('user_admin_user_id_foreign');
		});


		Schema::drop('users');
		Schema::drop('user_student');
		Schema::drop('user_teacher');
		Schema::drop('user_admin');
	}

}
