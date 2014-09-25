<?php

use Illuminate\Database\Migrations\Migration;

class FixIssues extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('users', function ($table) {

			$table->dropColumn('first_name');
			$table->dropColumn('last_name');
			$table->dropColumn('middle_name');
			$table->dropColumn('email');

			$table->string('first_name', 32)->default('');
			$table->string('last_name', 32)->default('');
			$table->string('middle_name', 32)->default('');
			$table->string('email')->default('');
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
		Schema::create('users', function ($table) {

			$table->dropColumn('first_name');
			$table->dropColumn('last_name');
			$table->dropColumn('middle_name');
			$table->dropColumn('email');

			$table->string('first_name', 32);
			$table->string('last_name', 32);
			$table->string('middle_name', 32);
			$table->string('email')->nullable()->unique();
		});

	}

}
