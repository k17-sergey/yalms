<?php

use Illuminate\Database\Migrations\Migration;

class ChangeStudents extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('students', function ($table) {

			$table->string('email')->nullable()->unique();
			$table->string('password');
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
		Schema::table('students', function ($table) {

			$table->dropColumn('email');
			$table->dropColumn('password');
			$table->dropTimestamps();
		});
	}

}
