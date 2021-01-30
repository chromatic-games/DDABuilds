<template>
	<div class="container-fluid">
		<div class="tab-content">
			<div class="row">
				<div class="col-lg-9">
					<ul class="nav nav-tabs">
						<li v-for="(waveName, key) of waveNames" :key="key" class="nav-item" @click="waveSelect(key)">
							<a :class="{active: selectedWave === key}" class="nav-link pointer">
								{{waveName}}
								<template v-if="isEditMode">
									<i class="fa fa-edit" @click.stop="waveEdit(key)"></i>
									<i v-if="key !== 0" class="fa fa-trash" @click.stop="waveDelete(key)"></i>
								</template>
							</a>
						</li>
						<li v-if="isEditMode" class="nav-item" @click="waveAdd"><a class="nav-link pointer">+</a></li>
						<li v-if="build.ID" class="nav-item"><a class="nav-link">Comments (<span>{{build.comments}}</span>)</a></li>
					</ul>

					<div id="mapContainer">
						<img :src="'/assets/images/map/' + map.name + '.png'" alt="map image" class="buildMap" />

						<div v-for="(entry, key) of waveTowersFiltered"
							:key="key"
							ref="placedTower"
							:data-class="entry.tower.heroClassID"
							:style="{position: 'absolute', left: entry.placed.x + 'px', top: entry.placed.y + 'px', transform: 'rotate(' + entry.placed.rotation + 'deg)'}"
							:title="$t('tower.' + entry.tower.name) + (entry.tower.isResizable ? ' (' + entry.placed.size + ')' : '')"
							class="tower-container pointer"
							@mouseout="towerMouseOut(entry.placed, key)"
							@mouseover.stop="towerMouseOver(entry.placed, key)"
							@contextmenu.prevent="towerDelete(entry.placed)">
							<img :alt="entry.tower.name" :src="'/assets/images/tower/' + entry.tower.name + (entry.placed.size || '') + '.png'" class="tower" />
							<div v-if="(entry.tower.isResizable || entry.tower.isRotatable) && entry.placed.mouseOver" class="menu">
								<i v-if="entry.placed.size > entry.tower.unitCost" class="fa fa-minus du-decrease" @click="towerUpdateSize(entry.placed, -1)"></i>
								<i v-if="entry.tower.isRotatable" class="fa fa-repeat" @mousedown="towerMouseDown(entry.placed, key)"></i>
								<i v-if="entry.placed.size < entry.tower.maxUnitCost" class="fa fa-plus du-increase" @click="towerUpdateSize(entry.placed, 1)"></i>
							</div>
						</div>
					</div>
				</div>
				<div id="towerControlPanel" class="col-lg-3">
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-header">
									<i v-if="build.buildStatus !== buildStatusPublic" v-b-tooltip.hover="$t('build.isPrivate')" class="fa fa-eye-slash"></i>
									<span v-if="build.rifted" class="badge badge-success">Rifted</span>
									<span v-if="build.afkAble" class="badge badge-success">AFK Able</span>
									<span v-if="build.hardcore" class="badge badge-success">Hardcore</span>
									<template v-if="build.title">{{build.title}}</template>
									<i v-else>Enter a build name</i>
								</div>
								<div class="card-body">
									<div class="card-text">
										<template v-if="isEditMode">
											<i class="fa fa-map"></i> {{$t('map.' + map.name)}}<br />
											<div class="form-group">
												<label for="buildName">Build Name:</label>
												<input id="buildName" v-model.trim="build.title" class="form-control" maxlength="128" placeholder="Build Name" type="text" />
											</div>
											<div class="form-group">
												<label for="buildAuthor">Author:</label>
												<input id="buildAuthor" v-model.trim="build.author" class="form-control" maxlength="20" placeholder="Author" type="text" />
											</div>

											DU: <strong :style="{color: unitsUsed === unitsMax ? 'red': ''}">{{unitsUsed}}/{{unitsMax}}</strong><br />
											Mana used: <strong>{{manaUsed}}</strong><br />
											Mana to upgrade: <strong>{{manaUpgrade}}</strong><br />
										</template>
										<template v-else>
											<ul>
												<li><i class="fa fa-map"></i> {{$t('map.' + map.name)}}</li>
												<li><i class="fa fa-user"></i> <a>{{build.author}}</a></li> <!-- TODO link to build list with filter author -->
												<li><i class="fa fa-gamepad"></i> {{$t('gameMode.' + build.gameModeName)}}</li>
												<li v-if="build.date"><i class="fa fa-clock-o"></i> {{build.date}}</li>
												<li>XP per Run: {{build.expPerRun}}</li>
												<li>Time per Run: {{build.timePerRun}}</li>
												<li>Mana used: {{waveTowersFiltered.length}}</li>
												<li>Mana to upgrade: {{manaUpgrade}}</li>
												<li>DU: <strong><span>{{unitsUsed}}</span>/<span>{{unitsMax}}</span></strong></li>
											</ul>

											<build-stats-table v-model="build.heroStats" :edit-mode="isEditMode" :hero-list="heroList" />

											<button class="btn btn-secondary" @click="buildChangeMode(false)">Editor Mode</button>
											<button v-if="build.ID" class="btn btn-secondary">
												<i class="fa fa-thumbs-up"></i>
											</button>
										</template>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="card">
								<div class="card-header">
									<i v-b-tooltip="'click the image to enable or disable the type of towers'" class="fa fa-question-circle"></i> Disable Tower
								</div>
								<div class="card-body">
									<div class="card-text">
										<img v-for="hero in heros" :key="hero.ID" v-b-tooltip.hover="$t('hero.' + hero.name)" :class="{disabled: disabledHeros.includes(hero.ID)}"
											:src="'/assets/images/hero/' + hero.name + '.png'" class="disableTowerCheckbox" @click="toggleHeroClass(hero.ID)" />
									</div>
								</div>
							</div>
						</div>
						<template v-if="isEditMode">
							<div v-for="hero in heros" :key="hero.ID" :class="{'col-sm-6': hero.towers.length < 8, 'col-sm-12': hero.towers.length >= 8}">
								<div class="card">
									<div class="card-header">{{$t('hero.' + hero.name)}}</div>
									<div class="card-body card-hero-body">
										<div v-for="tower of hero.towers"
											:key="tower.ID"
											:class="{disabled: unitsUsed + tower.unitCost > unitsMax}"
											:data-class="hero.ID"
											:data-tower="tower.ID"
											class="tower-container pointer dummy">
											<img :src="'/assets/images/tower/' + tower.name + '.png'" :title="$t('tower.' + tower.name)" class="tower" />
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

											<build-stats-table v-model="build.heroStats" :hero-list="heroList" edit-mode />
										</div>

										<!-- build status -->
										<div class="form-group">
											<label for="buildStatus">Build Status:</label>
											<select id="buildStatus" v-model.number="build.buildStatus" class="form-control">
												<option value="1">Public</option>
												<option value="2">Unlisted</option>
												<option value="3">Private</option>
											</select>
										</div>

										<!-- difficulty -->
										<div class="form-group">
											<label for="difficulty">Difficulty:</label>
											<select id="difficulty" v-model="build.difficultyID" :class="'difficulty-' + build.difficultyID" class="form-control">
												<option v-for="difficulty in difficulties" :key="difficulty.ID" :class="'difficulty-' + difficulty.ID"
													:value="difficulty.ID">{{$t('difficulty.' + difficulty.name)}}
												</option>
											</select>
										</div>
										<div class="form-group">
											<div v-for="gameMode in gameModes" :key="gameMode.ID" class="form-check form-check-inline">
												<input :id="'buildGameMode' + gameMode.ID" v-model="build.gameModeID" :value="gameMode.ID" class="form-check-input" type="radio">
												<label :for="'buildGameMode' + gameMode.ID" class="form-check-label">{{$t('gameMode.' + gameMode.name)}}</label>
											</div>
										</div>

										<div class="form-group form-check">
											<input id="buildHardcore" v-model="build.hardcore" class="form-check-input" type="checkbox" />
											<label class="form-check-label" for="buildHardcore"> Hardcore</label>
										</div>
										<div class="form-group form-check">
											<input id="buildAFKAble" v-model="build.afkAble" class="form-check-input" type="checkbox" />
											<label class="form-check-label" for="buildAFKAble"> AFK able</label>
										</div>
										<div class="form-group form-check">
											<input id="buildRifted" v-model="build.rifted" class="form-check-input" type="checkbox" />
											<label class="form-check-label" for="buildRifted"> Rifted</label>
										</div>

										<div class="form-group">
											<label for="buildExpPerRun">XP Per Run:</label>
											<input id="buildExpPerRun" v-model="build.expPerRun" class="form-control" maxlength="20" placeholder="XP Per Run" type="text" />
										</div>
										<div class="form-group">
											<label for="buildTimePerRun">Time Per Run:</label>
											<input id="buildTimePerRun" v-model="build.timePerRun" class="form-control" maxlength="20" placeholder="Time Per Run" type="text" />
										</div>

										<button class="btn btn-primary" @click="save">Save</button>
										<button class="btn btn-secondary" @click="buildChangeMode(true)">Viewer Mode</button>
										<button v-if="build.ID" class="btn btn-danger" @click="buildDelete">Delete</button>
									</div>
								</div>
							</div>
						</template>
					</div>
				</div>
			</div>

			<div class="container">
				<div class="card">
					<div class="card-header">{{$t('build.description')}}</div>
					<div v-if="isEditMode" class="card-body">
						<classic-ckeditor v-model="build.description" />
					</div>
					<div v-else class="card-body" v-html="build.description"></div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import axios from 'axios';
import $ from 'jquery';
import Vue from 'vue';
import ClassicCkeditor from '../../components/ClassicCkeditor';
import {STATUS_PUBLIC} from '../../utils/build';
import {formatSEOTitle} from '../../utils/string';
import BuildStatsTable from './BuildStatsTable';

window.$ = $;

export default {
	name: 'BuildAddView',
	components: { BuildStatsTable, ClassicCkeditor },
	data() {
		return {
			buildStatusPublic: STATUS_PUBLIC,
			build: {
				title: '',
				author: '',
				description: '',
				difficultyID: 1,
				gameModeID: 0,
				buildStatus: 1,
				timePerRun: '',
				expPerRun: '',
				heroStats: {},
				hardcore: false,
				afkAble: false,
				rifted: false,
			},
			selectedWave: 0,
			placedTowers: [],
			units: {},
			disabledHeros: [],
			map: {},
			gameModes: [],
			heros: [],
			waveNames: ['Build'],
			towers: {},
			difficulties: [],
			demoMode: false,
			rotateTower: false,
		};
	},
	props: {
		isView: {
			type: Boolean,
			default: false,
		},
	},
	watch: {
		'$route.params.id'() {
			this.fetch();
		},
		'$route.params.mapID'() {
			this.fetch();
		},
		waveTowersFiltered() {
			let canvas = $('#mapContainer');

			this.$nextTick(() => {
				for (let key in this.$refs.placedTower) {
					let el = this.$refs.placedTower[key];
					$(el).draggable({
						containment: '#mapContainer',
						stop: (event, ui) => {
							let offset = canvas.offset();
							this.placedTowers[key].x = ui.offset.left - offset.left;
							this.placedTowers[key].y = ui.offset.top - offset.top;
						},
					});
				}
			});
		},
	},
	mounted() {
		let canvas = $('#mapContainer');
		canvas.droppable({
			accept: '.tower-container',
			drop: (event, ui) => {
				if (!$(ui.helper).hasClass('dummy')) {
					return;
				}

				let offset = canvas.offset();
				let towerID = ui.helper.data('tower');
				let tower = this.towers[towerID];

				this.placedTowers.push({
					ID: towerID,
					waveID: this.selectedWave,
					size: tower.unitCost < tower.maxUnitCost ? tower.unitCost : 0,
					x: ui.offset.left - offset.left,
					y: ui.offset.top - offset.top,
					rotation: 0,
					mouseOver: false,
				});
			},
		});
	},
	created() {
		this.fetch();
	},
	methods: {
		fetch() {
			let mapID = 0;
			// TODO tmp, would be optimized
			const fetchMap = () => {
				return axios
					.get('/maps/editor/' + mapID)
					.then(({ data }) => {
						let towers = {};
						for (let hero of data.heros) {
							if (hero.isHero && !this.build.heroStats[hero.ID]) {
								Vue.set(this.build.heroStats, hero.ID, {
									hp: 0,
									damage: 0,
									range: 0,
									rate: 0,
								});
							}

							for (let tower of hero.towers) {
								towers[tower.ID] = tower;
							}
						}

						this.map = data.map;
						this.heros = data.heros;
						this.difficulties = data.difficulties;
						this.gameModes = data.gameModes;
						this.towers = towers;

						this.$nextTick(() => {
							this.startDraggable();
						});
					})
					.catch(() => {
						this.$router.push({ name: 'home' });
					});
			};

			if (!this.isView) {
				mapID = this.$route.params.mapID;
				fetchMap();
			}
			else {
				axios.get('/builds/' + this.$route.params.id).then(async ({ data: data }) => {
					// redirect to correct title url, if title not equal to the url title
					let title = formatSEOTitle(data.title);
					if (this.$route.params.title !== title) {
						this.$router.push({
							name: this.$route.name,
							params: Object.assign({}, this.$route.params, { title }),
						});
					}

					// fetch map data
					mapID = data.mapID;
					await fetchMap();

					// parse hero stats
					let heroStats = this.build.heroStats;
					let towers = [];
					for (let stats of data.heroStats) {
						heroStats[stats.heroID] = stats;
					}
					data.heroStats = heroStats;

					// load waves
					let waveNames = [];
					for (let wave of data.waves) {
						waveNames.push(wave.name);
						for (let tower of wave.towers) {
							towers.push({
								ID: tower.towerID,
								waveID: 0,
								size: tower.overrideUnits,
								x: tower.x,
								y: tower.y,
								rotation: tower.rotation,
								mouseOver: false,
							});
						}
					}

					this.waveNames = waveNames;
					this.build = data;
					this.placedTowers = towers;
				});
			}
		},
		towerMouseOver(tower, key) {
			if (!this.towers[tower.ID].isRotatable || this.rotateTower) {
				return;
			}

			if (tower.mouseoverTimeout) {
				window.clearTimeout(tower.mouseoverTimeout);
			}

			tower.mouseOver = true;

			let defense = $(this.$refs.placedTower[key]);
			defense.on('wheel', (event) => {
				event.preventDefault();

				let scrollSpeed = 3 * (event.originalEvent.deltaY <= 0 ? -1 : 1);
				let delta = 3;
				if (event.shiftKey) {
					delta /= 2;
				}
				else if (event.ctrlKey) {
					delta *= 3;
				}

				tower.rotation += (scrollSpeed * delta);
				if (tower.rotation > 360) {
					tower.rotation -= 360;
				}
				else if (tower.rotation < 0) {
					tower.rotation += 360;
				}
			});
		},
		mousemove(event) {
			if (!this.rotateTower) {
				return;
			}

			let placed = this.waveTowersFiltered[this.rotateTower - 1]?.placed;
			if (placed && typeof placed.mousemove === 'function') {
				placed.mousemove(event);
			}
		},
		mouseup() {
			window.removeEventListener('mouseup', this.mouseup);
			window.addEventListener('mousemove', this.mousemove);

			$(this.$refs.placedTower[this.rotateTower - 1]).draggable('enable');
			this.rotateTower = 0;
		},
		towerMouseDown(tower, key) {
			this.rotateTower = key + 1;

			window.addEventListener('mouseup', this.mouseup);
			window.addEventListener('mousemove', this.mousemove);

			let el = this.$refs.placedTower[key];
			let rect = el.getBoundingClientRect();
			let offset = {
				top: rect.top + (window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0),
				left: rect.left + document.body.scrollLeft,
			};
			let style = getComputedStyle(el, null);
			let width = parseFloat(style.width.replace('px', '')) / 2;
			let height = parseFloat(style.height.replace('px', '')) / 2;

			$(el).draggable('disable');
			tower.mousemove = (e) => {
				let mouse_x = e.pageX - offset.left - width;
				let mouse_y = e.pageY - offset.top - height;
				let mouse_cur_angle = Math.atan2(mouse_y, mouse_x);
				tower.rotation = mouse_cur_angle * (180 / Math.PI) + 90;
			};
		},
		towerMouseOut(tower) {
			if (tower.mouseoverTimeout) {
				window.clearTimeout(tower.mouseoverTimeout);
			}

			tower.mouseoverTimeout = window.setTimeout(() => {
				tower.mouseOver = false;
			}, 50);
		},
		towerUpdateSize(tower, update) {
			let newTowerSize = tower.size + update;
			let towerInfo = this.towers[tower.ID];
			if (newTowerSize < towerInfo.unitCost || newTowerSize > towerInfo.maxUnitCost) {
				return;
			}

			tower.size += update;
		},
		towerDelete(tower) {
			let idx = this.placedTowers.indexOf(tower);
			this.placedTowers.splice(idx, 1);
		},
		toggleHeroClass(heroID) {
			let idx = this.disabledHeros.indexOf(heroID);
			if (idx >= 0) {
				this.disabledHeros.splice(idx, 1);
			}
			else {
				this.disabledHeros.push(heroID);
			}
		},
		startDraggable() {
			$('#towerControlPanel .tower-container').draggable({
				helper: 'clone',
				start: (event, ui) => {
					let instance = $(event.target).draggable('instance');
					let element = instance.element;
					let tower = this.towers[element.data('tower')];
					if (element.hasClass('disabled')) {
						return false;
					}

					function updatePosition() {
						// center the icon on
						instance.offset.click = {
							left: Math.floor(ui.helper.width() * 0.5),
							top: Math.floor(ui.helper.height() * 0.5),
						};
					}

					if (tower.unitCost < tower.maxUnitCost) {
						let towerImage = ui.helper.find('.tower');
						towerImage.attr('title', 'XD');
						towerImage = towerImage[0];
						towerImage.src = towerImage.src.replace('.png', tower.unitCost + '.png');
						towerImage.onload = updatePosition;
					}

					updatePosition();
				},
			});
		},
		waveAdd() {
			this.selectedWave = this.waveNames.push('custom wave ' + (this.waveNames.length + 1)) - 1;
		},
		waveSelect(waveID) {
			if (waveID <= this.waveNames.length + 1) {
				this.selectedWave = waveID;
			}
		},
		waveEdit(waveID) {
			let newWaveName = window.prompt('Wave name (max 24 characters)');
			if (typeof newWaveName === 'string') {
				newWaveName = newWaveName.trim().substr(0, 24);
				if (newWaveName) {
					Vue.set(this.waveNames, waveID, newWaveName);
				}
			}
		},
		waveDelete(waveID) {
			for (let tower of this.placedTowers) {
				if (tower.waveID === waveID) {
					if (!window.confirm('Want delete the wave?')) {
						return;
					}
					break;
				}
			}

			this.waveNames.splice(this.waveNames, 1);
			for (let tower of this.placedTowers) {
				if (tower.waveID === waveID) {
					this.placedTowers.splice(this.placedTowers.indexOf(tower), 1);
				}
				else if (tower.waveID >= waveID) {
					tower.waveID--;
				}
			}

			if (this.selectedWave === waveID) {
				this.selectedWave--;
			}
		},
		buildChangeMode(newMode) {
			this.demoMode = newMode;
			window.scrollTo(0,0);
		},
		buildDelete() {
			axios
				.delete('/builds/' + this.build.ID)
				.then(() => {
					this.$router.push({ name: 'home' });
				})
				.catch(() => {
					// TODO error handling
				});
		},
		save() {
			let build = {
				...this.build,
				waves: this.waveNames,
			};
			build.towers = this.placedTowers.map((placedTower) => {
				let tower = { ...placedTower };

				delete tower.mouseOver;

				return tower;
			});
			build.mapID = this.map.ID;

			// TODO update build

			axios
				.post('/builds', build)
				.then(({ data }) => {
					console.log('push route');
					this.$router.push({
						name: 'build',
						params: {
							id: data.ID,
							title: formatSEOTitle(data.title),
						},
					});
				})
				.catch(() => {
					// TODO error handling
				});
		},
	},
	computed: {
		isEditMode() {
			if (this.demoMode) {
				return false;
			}

			if (!this.isView) {
				return true;
			}

			return this.build.steamID === this.$store.state.authentication.user.ID;
		},
		waveTowers() {
			let towers = [];
			for (let tower of this.placedTowers) {
				if (tower.waveID === this.selectedWave) {
					towers.push(tower);
				}
			}

			return towers;
		},
		waveTowersFiltered() {
			let towers = [];
			for (let placed of this.waveTowers) {
				let tower = this.towers[placed.ID];
				if (!this.disabledHeros.includes(tower.heroClassID)) {
					towers.push({
						placed: placed,
						tower,
					});
				}
			}

			return towers;
		},
		manaUpgrade() {
			return this.waveTowers.length * 2620;
		},
		manaUsed() {
			let unitCost = 0;
			for (let tower of this.waveTowers) {
				unitCost += this.towers[tower.ID].manaCost;
			}

			return unitCost;
		},
		unitsUsed() {
			let units = 0;
			for (let tower of this.waveTowers) {
				let unitCount = tower.size ? tower.size : this.towers[tower.ID].unitCost;
				units += unitCount;
			}

			return units;
		},
		unitsMax() {
			if (this.map.difficultyUnits && this.map.difficultyUnits.length) {
				for (let difficulty of this.map.difficultyUnits) {
					if (this.build.difficultyID === difficulty.difficultyID) {
						return difficulty.units;
					}
				}
			}

			return this.map.units || 0;
		},
		heroList() {
			let heros = {};

			for (let hero of this.heros) {
				if (hero.isHero) {
					heros[hero.ID] = hero.name;
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
	z-index: 10;

	&.disabled {
		opacity: .5;
		cursor: not-allowed;
	}

	.menu {
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

.buildMap {
	border: 1px solid black;
}

.buildMap,
.tower-container img {
	-webkit-user-select: none; /* Chrome all / Safari all */
	-moz-user-select: none; /* Firefox all */
	-ms-user-select: none; /* IE 10+ */
	user-select: none; /* Likely future */
}

// series ev
.tower-container[data-class="5"].ui-draggable-dragging img.tower,
#mapContainer .tower-container[data-class="5"] img.tower {
	width: auto;
	height: auto;
}

/* traps */
.tower-container[data-class="3"].ui-draggable-dragging img.tower,
#mapContainer .tower-container[data-class="3"] img.tower {
	width: 45px;
	height: 45px;
	line-height: 45px;
}

/* monk aura */
.tower-container[data-class="4"].ui-draggable-dragging img.tower,
#mapContainer .tower-container[data-class="4"] img.tower {
	width: 100px;
	height: 100px;
	line-height: 100px;
	opacity: 0.8;
}

#mapContainer {
	position: relative;
	width: 1024px;
	height: 1024px;

	.buildMap {
		width: 100%;
	}
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

.card-hero-body {
	padding: 10px;
}
</style>