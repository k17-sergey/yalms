<?php

namespace Yalms\Tests\Api;


use DB;
use TestCase;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;

class UserTest extends TestCase
{

	public function setUp()
	{

		parent::setUp();

		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		UserAdmin::truncate();
		UserStudent::truncate();
		UserTeacher::truncate();
		User::truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

	}

	/**
	 * регистрируется новый человек
	 */
	public function testUserCreate()
	{
		$this->call('POST', 'api/v1/user', [
			'first_name' => 'Стас',
			'last_name'  => 'Михайлов',
			'phone'      => '79100000000',
			'password' => '123',
			'password_confirmation' => '123',
		]);
		$this->assertResponseStatus(201);

		$user = User::first();
		$this->assertEquals(1, $user->id);
		$this->assertEquals(1, $user->student->user_id);
		$this->assertEquals(1, $user->teacher->user_id);

	}

}