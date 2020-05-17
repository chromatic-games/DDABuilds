<?php

namespace data\build;

use data\build\wave\BuildWaveAction;
use data\DatabaseObjectAction;
use data\heroClass\HeroClass;
use data\heroClass\HeroClassList;
use system\Core;

class BuildAction extends DatabaseObjectAction {
	public function create() {
		/** @var Build $build */
		$build = parent::create();

		// create waves
		$validWaves = [0];
		if ( !empty($this->parameters['waves']) ) {
			$i = 1;
			foreach ( $this->parameters['waves'] as $waveId => $waveName ) {
				$wave = new BuildWaveAction([], 'create', [
					'data' => [
						'name'     => $waveName,
						'fk_build' => $build->getObjectID(),
					],
				]);
				$wave->executeAction();
				$validWaves[$waveId] = $i++;
			}
		}

		// insert towers
		if ( !empty($this->parameters['towers']) ) {
			$statement = Core::getDB()->prepareStatement('INSERT INTO placed (fk_build, fk_tower, x, y, rotation, fk_buildwave, override_du) VALUES (?, ?, ?, ?, ?, ?, ?);');
			foreach ( $this->parameters['towers'] as $tower ) {
				if ( isset($validWaves[$tower['wave']]) ) {
					$statement->execute([
						$build->getObjectID(),
						$tower['towerID'],
						(int) $tower['x'],
						(int) $tower['y'],
						(int) $tower['rotation'],
						(int) $validWaves[$tower['wave']],
						0,
					]);
				}
			}
		}

		// save hero class stats
		if ( !empty($this->parameters['stats']) ) {
			$heroClasses = new HeroClassList();
			$heroClasses->readObjects();
			$heroClasses = $heroClasses->getObjects();
			$statement = Core::getDB()->prepareStatement('INSERT INTO build_stats (buildID, classID, hp, damage, rate, `range`) VALUES (?, ?, ?, ?, ?, ?)');

			/** @var HeroClass[] $heroClasses */
			foreach ( $this->parameters['stats'] as $key => $statsValue ) {
				if ( !isset($heroClasses[$key]) || !$heroClasses[$key]->isHero ) {
					continue;
				}

				$hp = isset($statsValue['hp']) ? (int) $statsValue['hp'] : 0;
				$rate = isset($statsValue['rate']) ? (int) $statsValue['rate'] : 0;
				$range = isset($statsValue['range']) ? (int) $statsValue['range'] : 0;
				$damage = isset($statsValue['damage']) ? (int) $statsValue['damage'] : 0;

				// skip empty stats
				if ( max($hp, $rate, $range, $damage) === 0 ) {
					continue;
				}

				$statement->execute([
					$build->getObjectID(),
					$key,
					$hp,
					$rate,
					$range,
					$damage,
				]);
			}
		}

		// image
		if ( !empty($this->parameters['image']) ) {
			$build->saveScreenshot($this->parameters['image']);
		}

		return $build;
	}

	/**
	 * todo
	 */
	public function validateSave() {
	}

	/**
	 * todo
	 */
	public function save() {
	}
}