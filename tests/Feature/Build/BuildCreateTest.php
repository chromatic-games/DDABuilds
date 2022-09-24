<?php

namespace Tests\Feature\Build;

use App\Models\Build;
use App\Models\Hero;
use App\Models\Tower;
use Tests\TestCase;

class BuildCreateTest extends TestCase
{
	public function testCreateAsGuest()
	{
		$response = $this->post('/api/builds/');
		$response->assertForbidden();
	}

	public function testValidator()
	{
		$this->loginAsTester();

		$response = $this->postJson('/api/builds/', []);
		$response->assertStatus(422);

		$data = json_decode($response->getContent(), true);
		$this->assertTrue($data['message'] === 'The given data was invalid.');
	}

	public function buildCreate(array $overrideData = []) : Build
	{
		/** @var Tower $tower */
		$tower = Tower::query()->where('isResizable', true)->inRandomOrder()->first();

		$heros = Hero::query()->where([['isHero', 1], ['isDisabled', 0]])->inRandomOrder()->limit(3)->get();

		$data = array_merge([
			'title' => 'My Title',
			'description' => '',
			'author' => $this->getTestUser()->name,
			'timePerRun' => '123',
			'expPerRun' => '456',
			'afkAble' => 1,
			'hardcore' => 1,
			'rifted' => 1,
			'gameModeID' => 2,
			'difficultyID' => 2,
			'buildStatus' => Build::STATUS_PUBLIC,
			'mapID' => 1,
			'towers' => [
				[
					'ID' => 2,
					'rotation' => 90,
					'size' => 0,
					'x' => 500,
					'y' => 500,
					'waveID' => 0,
				],
				[
					'ID' => $tower->ID,
					'rotation' => 90,
					'size' => $tower->maxUnitCost - 1,
					'x' => 400,
					'y' => 400,
					'waveID' => 1,
				],
			],
			'waves' => [
				'first_wave',
				'second_wave',
			],
			'heroStats' => [],
		], $overrideData);

		/** @var Hero $hero */
		foreach ( $heros as $hero ) {
			$data['heroStats'][$hero->ID] = [
				'hp' => 1,
				'rate' => 2,
				'damage' => 3,
				'range' => 4,
			];
		}

		$response = $this->post('/api/builds/', $data);
		$response->assertOk();

		$responseData = json_decode($response->getContent(), true);
		$buildID = $responseData['ID'];

		/** @var Build $build */
		$build = Build::query()->with(['gameMode', 'difficulty', 'map', 'waves', 'heroStats', 'likeValue', 'watchStatus',])->find($buildID);

		// check build data
		$this->assertSame($this->getTestUser()->ID, $build->steamID);

		foreach ( $data as $key => $value ) {
			if ( !is_array($value) ) {
				$this->assertSame($build->{$key}, $value);
			}
		}

		// check the waves
		foreach ( $data['waves'] as $key => $waveName ) {
			/** @var Build\BuildWave $buildWave */
			$buildWave = $build->waves()->get()->all()[$key];
			$this->assertSame($buildWave->name, $waveName);
			$tower = $buildWave->towers()->first();
			foreach ( $data['towers'][$key] as $towerKey => $value ) {
				if ( !in_array($towerKey, ['x', 'y', 'rotation', 'size', 'ID']) ) {
					continue;
				}

				if ( $towerKey === 'ID' ) {
					$towerKey = 'towerID';
				}
				elseif ( $towerKey === 'size' ) {
					$towerKey = 'overrideUnits';
				}

				$this->assertSame($tower->{$towerKey}, $value);
			}
		}

		$this->assertSame($build->heroStats()->count(), count($data['heroStats']), 'more hero stats available then given');

		/** @var Build\BuildHeroStats $heroStats */
		foreach ( $build->heroStats()->get() as $heroStats ) {
			$this->assertSame($data['heroStats'][$heroStats->heroID], [
				'hp' => $heroStats->hp,
				'rate' => $heroStats->rate,
				'damage' => $heroStats->damage,
				'range' => $heroStats->range,
			]);
		}

		// check if thumbnail was generated
		// TODO fix github pipeline, imagescale fails
		// $this->assertFileExists($build->getPublicThumbnailPath(), 'build thumbnail was not generated');

		return $build;
	}

	public function testCreate() : Build
	{
		$this->loginAsTester();
		$build = $this->buildCreate();
		$build->delete();

		// create a private build
		return $this->buildCreate([
			'afkAble' => 0,
			'hardcore' => 0,
			'rifted' => 0,
			'buildStatus' => Build::STATUS_PRIVATE,
		]);
	}

	/** @depends testCreate */
	public function testPermissions(Build $build) : Build
	{
		$response = $this->getJson('/api/builds/'.$build->ID);
		$response->assertForbidden();

		// login as sub tester, another users should not have access on private builds
		$this->loginAsSubTester();
		$response = $this->getJson('/api/builds/'.$build->ID);
		$response->assertForbidden();

		// login as creator, creator should have access
		$this->loginAsTester();
		$response = $this->getJson('/api/builds/'.$build->ID);
		$response->assertOk();

		return $build;
	}

	/** @depends testPermissions */
	public function testDelete(Build $build)
	{
		// test permission
		$response = $this->deleteJson('/api/builds/'.$build->ID);
		$response->assertForbidden();

		// test as non creator
		$this->loginAsSubTester();
		$response = $this->deleteJson('/api/builds/'.$build->ID);
		$response->assertForbidden();

		$this->loginAsTester();
		$response = $this->deleteJson('/api/builds/'.$build->ID);
		$response->assertNoContent();

		// check if it deleted
		$this->assertSame(1, Build::query()->find($build->ID)->isDeleted);
	}
}