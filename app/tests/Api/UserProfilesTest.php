<?php

namespace Yalms\Tests\Api;


use TestCase;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;

class UserProfilesTest extends TestCase
{

	private $userId;

	public function setUp()
	{

		parent::setUp();

		$user = User::wherePhone('79100000000')->first();
		$user->teacher->enabled = 0;
		$user->student->enabled = 0;
		$user->push();
		$this->userId = $user->id;

	}

	/**
	 * Изменение профиля "студент" по REST запросу
	 */
	public function testUserStudentUpdate()
	{
		$this->assertEquals(0, UserStudent::find($this->userId)->enabled);

		$this->call('PUT',
			"api/v1/student/{$this->userId}",
			['enabled' => 1]
		);

		$this->assertEquals(1, UserStudent::find($this->userId)->enabled);
	}

	/**
	 * Изменение профиля "учитель" по REST запросу
	 */
	public function testUserTeacherUpdate()
	{
		$this->assertEquals(0, UserTeacher::find($this->userId)->enabled);

		$this->call('PUT',
			"api/v1/teacher/{$this->userId}",
			['enabled' => 1]
		);
		$this->assertEquals(1, UserTeacher::find($this->userId)->enabled);

	}

	/**
	 * Запрещенные запросы на создание и удаление профилей пользователя
	 */
	public function testProhibitedStudentCreateCall()
	{
		$this->call('GET',
			"api/v1/student/create"
		);
		$this->assertResponseStatus(403);
	}

	public function testProhibitedStudentStoreCall()
	{
		$this->call('POST',
			"api/v1/student",
			['enabled' => 0]
		);
		$this->assertResponseStatus(403);
	}

	public function testProhibitedStudentDeleteCall()
	{
		$this->call('DELETE',
			"api/v1/student/{$this->userId}"
		);
		$this->assertResponseStatus(403);
	}

	public function testProhibitedTeacherCreateCall()
	{
		$this->call('GET',
			"api/v1/teacher/create"
		);
		$this->assertResponseStatus(403);
	}

	public function testProhibitedTeacherStoreCall()
	{
		$this->call('POST',
			"api/v1/teacher",
			['enabled' => 0]
		);
		$this->assertResponseStatus(403);
	}

	public function testProhibitedTeacherDeleteCall()
	{
		$this->call('DELETE',
			"api/v1/teacher/{$this->userId}"
		);
		$this->assertResponseStatus(403);
	}

}