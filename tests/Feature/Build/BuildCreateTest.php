<?php

namespace Tests\Feature\Build;

use App\Models\Build;
use Tests\TestCase;

class BuildCreateTest extends TestCase {
	public function testCreateAsGuest() {
		$response = $this->post('/api/builds/');
		$response->assertForbidden();
	}

	public function testValidator() {
		$this->loginAsTester();

		$response = $this->postJson('/api/builds/', []);
		$response->assertStatus(422);

		$data = json_decode($response->getContent(), true);
		$this->assertTrue($data['message'] === 'The given data was invalid.');
	}

	public function testSimpleCreate() {
		$this->loginAsTester();

		$response = $this->post('/api/builds/', [
			'title' => 'My Title',
			'description' => '',
			'author' => 'Author',
			'timePerRun' => '123',
			'expPerRun' => '456',
			'afkAble' => true,
			'hardcore' => true,
			'rifted' => true,
			'gameModeID' => 2,
			'difficultyID' => 2,
			'buildStatus' => Build::STATUS_PUBLIC,
			'mapID' => 1,
			'towers' => [
				[

				]
			],
			'heroStats' => [],
		]);
		$response->assertStatus(422);
		echo $response->getContent();
	}
}