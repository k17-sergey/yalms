<?php

namespace Yalms\Tests\Api;


use TestCase;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;

class UserDeleteTest extends TestCase
{

	private $userId;

	public function setUp()
	{

		parent::setUp();

		$user = User::wherePhone('79100000000')->first();
		$user->admin->enabled = 1;
		$user->teacher->enabled = 1;
		$user->student->enabled = 1;
		$user->push();
		$this->userId = $user->id;

	}

	/**
	 * Удаление пользователя и очистка его профилей, по REST запросу
	 */
	public function testUserDelete()
	{
		$this->assertEquals(1, UserAdmin::find($this->userId)->enabled);
		$this->assertEquals(1, UserStudent::find($this->userId)->enabled);
		$this->assertEquals(1, UserTeacher::find($this->userId)->enabled);

		$this->call('DELETE',
			"api/v1/user/{$this->userId}"
		);

		$this->assertTrue(empty(User::find($this->userId)));

		$this->assertEquals(0, UserAdmin::find($this->userId)->enabled);
		$this->assertEquals(0, UserStudent::find($this->userId)->enabled);
		$this->assertEquals(0, UserTeacher::find($this->userId)->enabled);
	}


}