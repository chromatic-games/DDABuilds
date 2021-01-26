<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 text-center">
				<h3>Map: <strong>{{$t('map.' + map.name)}}</strong></h3>
			</div>
			<div class="col-md-5 text-center">
				<label for="buildName">Build Name:</label>
				<input id="buildName" v-model.trim="build.title" class="form-control" maxlength="128" placeholder="Build Name" type="text" />
			</div>
			<div class="col-md-4 text-center">
				<label for="buildAuthor">Author:</label>
				<input id="buildAuthor" v-model.trim="build.author" class="form-control" maxlength="20" placeholder="Author" type="text" />
			</div>
			<div class="col-md-1 text-center">
				<h3>
					DU: <strong><span>{{unitsUsed}}</span>/<span>{{unitsMax}}</span></strong>
				</h3>
			</div>
		</div>

		<div class="tab-content">
			<div class="row">
				<div class="col-lg-9">
					<div id="mapContainer">
						<img :src="'/assets/images/map/' + map.name + '.png'" class="ddmap" />

						<div v-for="(tower, key) of waveTowersFiltered"
							:key="key"
							ref="placedTower"
							:data-class="towers[tower.ID].heroClassID"
							:style="{position: 'absolute', left: tower.x + 'px', top: tower.y + 'px', transform: 'rotate(' + tower.rotation + 'deg)'}"
							class="tower-container pointer"
							@mousemove="towerMouseMove(tower, $event)"
							@mouseout="towerMouseOut(tower)"
							@mouseover="towerMouseOver(tower, key)"
							@mouseup="towerMouseUp(tower, key)"
							@contextmenu.prevent="towerDelete(tower)">
							<!--v-b-tooltip="$t('tower.' + towers[tower.ID].name)"-->
							<img :src="'/assets/images/tower/' + towers[tower.ID].name + (tower.size || '') + '.png'" class="tower" />
							<div v-if="(towers[tower.ID].isResizable || towers[tower.ID].isRotatable) && tower.mouseOver" class="menu">
								<i v-if="tower.size > towers[tower.ID].unitCost" class="fa fa-minus du-decrease" @click="towerUpdateSize(tower, -1)"></i>
								<i v-if="towers[tower.ID].isRotatable" class="fa fa-repeat" @mousedown="towerMouseDown(tower, key)"></i>
								<i v-if="tower.size < towers[tower.ID].maxUnitCost" class="fa fa-plus du-increase" @click="towerUpdateSize(tower, 1)"></i>
							</div>
						</div>
					</div>
				</div>
				<div id="towerControlPanel" class="col-lg-3">
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-header">
									<i v-b-tooltip="'click the image to enable or disable the type of towers'" class="fa fa-question-circle"></i> Disable Tower
								</div>
								<div class="card-body">
									<div class="card-text">
										<img v-for="hero in heros" :key="hero.ID" v-b-tooltip="$t('hero.' + hero.name)" :class="{disabled: disabledHeros.includes(hero.ID)}"
											:src="'/assets/images/hero/' + hero.name + '.png'" class="disableTowerCheckbox" @click="toggleHeroClass(hero.ID)" />
									</div>
								</div>
							</div>
						</div>
						<div v-for="hero in heros" :key="hero.ID" :class="{'col-sm-6': hero.towers.length < 8, 'col-sm-12': hero.towers.length >= 8}">
							<div class="card">
								<div class="card-header">{{$t('hero.' + hero.name)}}</div>
								<div class="card-body">
									<div v-for="tower of hero.towers"
										:key="tower.ID"
										:class="{disabled: unitsUsed + tower.unitCost > unitsMax}"
										:data-class="hero.ID"
										:data-tower="tower.ID"
										class="tower-container pointer dummy">
										<img v-b-tooltip="$t('tower.' + tower.name)" :src="'/assets/images/tower/' + tower.name + '.png'" class="tower" />
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
												<td><img v-b-tooltip="$t('hero.' + hero.name)" :alt="$t('hero.' + hero.name)" :src="'/assets/images/hero/' + hero.name + '.png'"
													class="heroAttribute" /></td>
												<td>
													<input v-model.number="build.heroStats[hero.ID].hp" class="form-control" min="0" size="5" type="text" />
												</td>
												<td>
													<input v-model.number="build.heroStats[hero.ID].damage" class="form-control" min="0" size="5" type="text" />
												</td>
												<td>
													<input v-model.number="build.heroStats[hero.ID].range" class="form-control" min="0" size="5" type="text" />
												</td>
												<td>
													<input v-model.number="build.heroStats[hero.ID].rate" class="form-control" min="0" size="5" type="text" />
												</td>
											</tr>
											</tbody>
										</table>
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
										<input id="buildTimePerRun" v-model="build.timePerRun" class="form-control" maxlength="20" placeholder="XP Per Run" type="text" />
									</div>

									<h4>Mana Used: <strong>0</strong></h4>
									<h4>Mana to Upgrade: <strong>0</strong></h4>

									<button class="btn btn-primary" @click="save">Save</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="container">
				{{build.description}}
				<classic-ckeditor v-model="build.description" />
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
import $ from 'jquery';
import Vue from 'vue';
import ClassicCkeditor from '../../components/ClassicCkeditor';

export default {
	name: 'BuildAddView',
	components: { ClassicCkeditor },
	data() {
		return {
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
			towers: {},
			difficulties: [],
			mouseoverTimeout: null,
		};
	},
	props: {
		isView: {
			type: Boolean,
			default: false,
		},
	},
	watch: {
		waveTowersFiltered() {
			let canvas = $('#mapContainer');

			this.$nextTick(() => {
				for (let key in this.$refs.placedTower) {
					let el = this.$refs.placedTower[key];
					$(el).draggable({
						stop: (event, ui) => {
							let offset = canvas.offset();
							this.placedTowers[key].x = ui.offset.left;
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
					x: ui.offset.left,
					y: ui.offset.top - offset.top,
					rotation: 0,
					mouseOver: false,
				});
			},
		});
	},
	created() {
		let mapID = 0;
		// TODO tmp, would be optimized
		const fetchMap = () => {
			axios
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
				.catch((response) => {
					console.log('xxx', response);
					this.$router.push({ name: 'home' });
				});
		};

		if (!this.isView) {
			mapID = this.$route.params.mapID;
			fetchMap();
		}
		else {
			console.log('fetch build');
			axios.get('/builds/' + this.$route.params.id).then(({ data: data }) => {
				console.log('build', data);

				let heroStats = {};
				let towers = [];
				for (let stats of data.heroStats) {
					heroStats[stats.heroID] = stats;
				}
				data.heroStats = heroStats;

				for (let wave of data.waves) {
					for (let tower of wave.towers ) {
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

				this.build = data;
				this.placedTowers = towers;
				mapID = data.mapID;
				fetchMap();
			});
		}
	},
	methods: {
		towerMouseOver(tower, key) {
			if (!this.towers[tower.ID].isRotatable) {
				return;
			}

			if (this.mouseoverTimeout) {
				window.clearTimeout(this.mouseoverTimeout);
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
		towerMouseMove(tower, event) {
			if (typeof tower.mousemove === 'function') {
				tower.mousemove(event);
			}
		},
		towerMouseUp(tower, key) {
			console.debug('mouse up', Date.now());
			delete tower.mousemove;
			$(this.$refs.placedTower[key]).draggable('enable');
		},
		towerMouseDown(tower, key) {
			console.debug('mouse down', Date.now());
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
			if (this.mouseoverTimeout) {
				window.clearTimeout(this.mouseoverTimeout);
			}

			this.mouseoverTimeout = window.setTimeout(() => {
				tower.mouseOver = false;
				this.towerMouseUp(tower);
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
		save() {
			let build = { ...this.build };
			build.towers = this.placedTowers.map((placedTower) => {
				let tower = { ...placedTower };

				delete tower.mouseOver;

				return tower;
			});
			build.mapID = this.map.ID;

			axios.post('/builds', build).then(() => {

			});
		},
	},
	computed: {
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
			for (let tower of this.waveTowers) {
				if (!this.disabledHeros.includes(this.towers[tower.ID].heroClassID)) {
					towers.push(tower);
				}
			}

			return towers;
		},
		unitsUsed() {
			let units = 0;
			for (let tower of this.placedTowers) {
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

.ddmap,
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
	width: 1024px;
	height: 1024px;

	img {
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
</style>