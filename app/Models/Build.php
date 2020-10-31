<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Build extends Model {
	use HasFactory;

	/** @var int public build status (everyone can view the build) */
	public const STATUS_PUBLIC = 1;

	/** @var int unlisted build status (build ist not listed, but everyone can view the build) */
	public const STATUS_UNLISTED = 2;

	/** @var int private build status (only creator can view the build) */
	public const STATUS_PRIVATE = 3;

	/** @inheritdoc */
	protected $table = 'build';

	/** @inheritdoc */
	protected $perPage = 21;

	/** @inheritdoc */
	protected $primaryKey = 'ID';

	/** @inheritdoc */
	public $timestamps = false;

	/** @inheritdoc */
	protected $fillable = [
		'author',
		'title',
		'mapID',
		'difficultyID',
		'description',
		'date',
		'steamID',
		'buildStatus',
		'gameModeID',
		'hardcore',
		'afkAble',
		// 'views',
		// 'likes',
		// 'comments',
		'timePerRun',
		'expPerRun',
		// 'isDeleted'
	];

	public $validSortFields = ['author', 'likes', 'mapID', 'name', 'views', 'date', 'difficultyID', 'gamemodeID'];

	/**
	 * @return HasOne
	 */
	public function map() {
		return $this->hasOne(Map::class, 'id', 'mapID');
	}

	/**
	 * @return HasOne
	 */
	public function difficulty() {
		return $this->hasOne(Difficulty::class, 'id', 'difficultyID');
	}

	/**
	 * sort the query by specific sort fields
	 *
	 * @param Builder $query
	 * @param string  $column
	 * @param string  $direction
	 */
	public function scopeSort(Builder $query, $column = 'date', $direction = 'asc') {
		if ( $column === null && $direction === null ) {
			$column = 'date';
			$direction = 'desc';
		}

		if ( $column === null ) {
			$column = 'date';
		}
		if ( $direction === null ) {
			$direction = 'asc';
		}

		if ( in_array($column, $this->validSortFields) ) {
			$query->orderBy($column, $direction);
		}
	}

	/**
	 * add all columns there are relevant for the list page
	 *
	 * @param Builder $query
	 *
	 * @return Builder
	 */
	public function scopeListSelect(Builder $query) {
		return $query->addSelect([
			$this->table.'.ID',
			$this->table.'.author',
			$this->table.'.title',
			$this->table.'.date',
			// $this->table . '.mapID',
			$this->table.'.difficultyID',
			$this->table.'.buildStatus',
			$this->table.'.gameModeID',
			$this->table.'.hardcore',
			$this->table.'.afkAble',
			$this->table.'.timePerRun',
			$this->table.'.expPerRun',
			$this->table.'.views',
			$this->table.'.likes',
			$this->table.'.comments',
		]);
	}

	/**
	 * search specific builds
	 *
	 * @param Builder $query
	 * @param array   $searchParameters
	 */
	public function scopeSearch(Builder $query, array $searchParameters) {
		$searchParameters = array_merge([
			'isDeleted'  => 0,
			'title'      => null,
			'author'     => null,
			'map'        => null,
			'difficulty' => null,
			'gameMode'   => null,
		], $searchParameters);

		$where = [
			['isDeleted', '=', 0],
		];

		if ( $searchParameters['title'] ) {
			$where[] = ['title', 'like', '%'.$searchParameters['title'].'%'];
		}
		if ( $searchParameters['author'] ) {
			$where[] = ['author', 'like', '%'.$searchParameters['author'].'%'];
		}

		if ( $searchParameters['map'] ) {
			/** @var Map $map */
			$map = Map::where('name', $searchParameters['map'])->first();
			if ( $map !== null ) {
				$where[] = ['mapID', '=', $map->ID];
			}
		}
		if ( $searchParameters['gameMode'] ) {
			/** @var GameMode $gameMode */
			$gameMode = GameMode::where('name', $searchParameters['gameMode'])->first();
			if ( $gameMode !== null ) {
				$where[] = ['gameModeID', '=', $gameMode->ID];
			}
		}
		if ( $searchParameters['difficulty'] ) {
			/** @var Difficulty $difficulty */
			$difficulty = Difficulty::where('name', $searchParameters['difficulty'])->first();
			if ( $difficulty !== null ) {
				$where[] = ['difficultyID', '=', $difficulty->ID];
			}
		}

		$query->where($where)->where(function ($query) {
			$query->where('buildStatus', '=', self::STATUS_PUBLIC);
			if ( auth()->id() ) {
				$query->orWhere('steamID', '=', auth()->id());
			}
		});
	}

	/**
	 * add the map name to the build
	 *
	 * @param Builder $query
	 */
	public function scopeWithMapName(Builder $query) {
		$query->leftJoin('map', 'map.ID', '=', 'build.mapID')
		      ->addSelect('map.name as mapName');
	}

	/**
	 * add the difficulty name to the build
	 *
	 * @param Builder $query
	 */
	public function scopeWithDifficultyName(Builder $query) {
		$query->leftJoin('difficulty', 'difficulty.ID', '=', 'build.difficultyID')
		      ->addSelect('difficulty.name as difficultyName');
	}

	/**
	 * add the game mode name to the build
	 *
	 * @param Builder $query
	 */
	public function scopeWithGameModeName(Builder $query) {
		$query->leftJoin('game_mode', 'game_mode.ID', '=', 'build.gameModeID')
		      ->addSelect('game_mode.name as gameModeName');
	}
}