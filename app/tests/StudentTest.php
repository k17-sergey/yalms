<?php

class StudentTest extends TestCase
{


	public function setUp()
	{

		parent::setUp();


		Student::truncate();

	}

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testSuccessCreateStudent()
	{

		Student::create([
			'first_name' => 'Стас',
			'last_name'  => 'Михайлов',
		]);

		$student = Student::first();

		$this->assertNotEmpty($student);
		$this->assertEquals('Стас', $student->first_name);

	}


	public function testRestGetStudentList()
	{
		Student::create([
			'first_name' => 'Стас',
			'last_name'  => 'Михайлов',
		]);

		$response = $this->call('GET', '/student');

		$this->assertInstanceOf(Student::class, $response->original['items'][0]);


	}

}
