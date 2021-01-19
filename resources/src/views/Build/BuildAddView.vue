<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 text-center">
				<h3>Map: <strong>{{$t('map.' + map.name)}}</strong></h3>
			</div>
			<div class="col-md-5 text-center">
				<label for="buildName">Build Name:</label>
				<input id="buildName" class="form-control" maxlength="128" placeholder="Build Name" type="text" value="">
			</div>
			<div class="col-md-4 text-center">
				<label for="buildAuthor">Author:</label>
				<input id="buildAuthor" class="form-control" maxlength="20" placeholder="Author" type="text" value="derpierre65.de">
			</div>
			<div class="col-md-1 text-center">
				<h3>
					DU: <b><span id="currentDefenseUnits">0</span>/<span id="maxDefenseUnits">60</span></b>
				</h3>
			</div>
		</div>

		<div class="tab-content">
			<div class="row">
				<div class="col-lg-9">
					<img class="ddmap" :src="'/assets/images/map/' + map.name + '.png'">
				</div>
				<div class="col-lg-3">
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-header">
									<i v-b-tooltip="'click the image to enable or disable the type of towers'" class="fa fa-question-circle"></i> Disable Tower
								</div>
								<div class="card-body">
									<div class="card-text">
										<img v-for="hero in heros" :key="hero.ID" v-b-tooltip="$t('hero.' + hero.name)" :src="'/assets/images/hero/' + hero.name + '.png'" class="disableTowerCheckbox" :class="{disabled: disabledHeros.includes(hero.ID)}" @click="toggleHeroClass(hero.ID)" />
									</div>
								</div>
							</div>
						</div>
						<div v-for="hero in heros" :key="hero.ID" :class="{'col-sm-6': hero.towers.length < 8, 'col-sm-12': hero.towers.length >= 8}">
							<div class="card">
								<div class="card-header">{{$t('hero.' + hero.name)}}</div>
								<div class="card-body">
									<div v-for="tower of hero.towers" :key="tower.ID" class="tower-container dummy">
										<img v-b-tooltip="$t('tower.' + tower.name)" class="tower" :src="'/assets/images/tower/' + tower.name + '.png'" />
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="card">
								<div class="card-header">Details</div>
								<div class="card-body">
									<div class="form-group">
										Required Attributes:
										<table class="table">
											<thead>
											<tr>
												<td class="text-right" colspan="2">Fortify</td>
												<td class="text-right">Power</td>
												<td class="text-right">Range</td>
												<td class="text-right">Def. Rate</td>
											</tr>
											</thead>
											<tbody>
											<tr v-for="hero in heroList" :key="hero.ID">
												<td><img v-b-tooltip="$t('hero.' + hero.name)" :alt="$t('hero.' + hero.name)" :src="'/assets/images/hero/' + hero.name + '.png'" class="heroAttribute" /></td>
												<td>
													<input class="form-control" size="5" value="0" />
												</td>
												<td>
													<input class="form-control" size="5" value="0" />
												</td>
												<td>
													<input class="form-control" size="5" value="0" />
												</td>
												<td>
													<input class="form-control" size="5" value="0" />
												</td>
											</tr>
											</tbody>
										</table>
									</div>

									<!-- build status -->
									<div class="form-group">
										<label for="buildStatus">Build Status:</label>
										<select id="buildStatus" v-model="build.buildStatus" class="form-control">
											<option value="1">Public</option>
											<option value="2">Unlisted</option>
											<option value="3">Private</option>
										</select>
									</div>

									<!-- difficulty -->
									<div class="form-group">
										<label for="difficulty">Difficulty:</label>
										<select id="difficulty" v-model="build.difficultyID" :class="'difficulty-' + build.difficultyID" class="form-control">
											<option v-for="difficulty in difficulties" :class="'difficulty-' + difficulty.ID"
												:value="difficulty.ID">{{$t('difficulty.' + difficulty.name)}}
											</option>
										</select>
									</div>
									<div class="form-group">
										<div v-for="gameMode in gameModes" class="form-check form-check-inline">
											<input :id="'buildGameMode' + gameMode.ID" v-model="build.gameModeID" :value="gameMode.ID" class="form-check-input" type="radio">
											<label :for="'buildGameMode' + gameMode.ID" class="form-check-label">{{$t('gameMode.' + gameMode.name)}}</label>
										</div>
									</div>

									<div class="form-group form-check">
										<input id="buildHardcore" v-model="build.hardcore" class="form-check-input" type="checkbox">
										<label class="form-check-label" for="buildHardcore"> Hardcore</label>
									</div>
									<div class="form-group form-check">
										<input id="buildAFKAble" v-model="build.afkAble" class="form-check-input" type="checkbox">
										<label class="form-check-label" for="buildAFKAble"> AFK able</label>
									</div>

									<div class="form-group">
										<label>XP Per Run:</label>
										<input id="expPerRun" class="form-control" maxlength="20" placeholder="XP Per Run" type="text" value="">
									</div>
									<div class="form-group">
										<label>Time Per Run:</label>
										<input id="timePerRun" class="form-control" maxlength="20" placeholder="XP Per Run" type="text" value="">
									</div>

									<h4>Mana Used: <strong id="manaUsed">0</strong></h4>
									<h4>Mana to Upgrade: <strong id="manaUpgrade">0</strong></h4>

									<button class="btn btn-primary">Save</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- TODO description ckeditor -->
			<!--<div class="container build-description-container">
				<div class="panel panel-default">
					<div class="panel-heading text-center"><strong>Description</strong></div>
				</div>
			</div>-->
		</div>
	</div>
</template>

<script>
import axios from 'axios';

export default {
	name: 'BuildAddView',
	data() {
		return {
			build: {
				difficultyID: 1,
				gameModeID: 0,
				buildStatus: 1,
				hardcore: false,
				afkAble: false,
			},
			disabledHeros: [],
			map: {},
			gameModes: [],
			heros: [],
			towers: [],
			difficulties: [],
		};
	},
	created() {
		axios
			.get('/maps/editor/' + this.$route.params.mapID)
			.then(({ data }) => {
				this.map = data.map;
				this.towers = data.towers;
				this.heros = data.heros;
				this.difficulties = data.difficulties;
				this.gameModes = data.gameModes;
			})
			.catch(() => {
				this.$route.push({ name: 'home' });
			});
	},
	methods: {
		toggleHeroClass(heroID) {
			let idx = this.disabledHeros.indexOf(heroID);
			if ( idx >= 0 ) {
				this.disabledHeros.splice(idx, 1);
			}
			else {
				this.disabledHeros.push(heroID);
			}
		}
	},
	computed: {
		heroList() {
			let heros = [];

			for (let hero of this.heros) {
				if (hero.isHero) {
					heros.push(hero);
				}
			}

			return heros;
		},
	},
};
</script>

<style lang="scss">

.tower-container {
	position: relative;
	display: inline-block;

	.menu {
		display: none;
		height: 20px;
		z-index: 20;
		cursor: pointer;
		position: absolute;
		top: -20px;
		width: 100%;
		left: 11px;
		transition: 0.2s 1s;

		i {
			text-shadow: 0 0 3px #000;
		}
	}
}

.tower {
	width: 35px;
	height: 35px;
}

.ddmap, .tower-container img {
	-webkit-user-select: none; /* Chrome all / Safari all */
	-moz-user-select: none; /* Firefox all */
	-ms-user-select: none; /* IE 10+ */
	user-select: none; /* Likely future */
}

.ddmap {
	width: 1024px;
	height: 1024px;
}

.disableTowerCheckbox {
	width: 48px;
	height: 48px;
	line-height: 64px;
	cursor: pointer;
}

.heroAttribute {
	width: 32px;
	height: 32px;
}

.disableTowerCheckbox.disabled {
	opacity: 0.2;
}
</style>