<?php

class TeacherTest extends TestCase
{


	public function setUp()
	{

		parent::setUp();


		Teacher::truncate();

	}

	public function testRestGetTeacherList()
	{
		$response = $this->call('GET', '/teacher');

		$this->assertEquals('Михаил', $response->original['items'][0]['first_name']);

	}

}
