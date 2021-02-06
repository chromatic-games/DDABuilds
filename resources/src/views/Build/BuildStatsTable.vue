<template>
	<table v-if="editMode || heroStatsList.length" class="table table-transparent" :class="{'table-dark': $store.state.darkMode}">
		<thead>
			<tr>
				<td class="text-right" colspan="2">{{$t('build.heroStats.fortify')}}</td>
				<td class="text-right">{{$t('build.heroStats.power')}}</td>
				<td class="text-right">{{$t('build.heroStats.range')}}</td>
				<td class="text-right">{{$t('build.heroStats.defRate')}}</td>
			</tr>
		</thead>
		<tbody>
			<tr v-for="hero in heroStatsList" :key="hero.heroID">
				<td>
					<img v-b-tooltip="$t('hero.' + hero.name)" :alt="$t('hero.' + hero.name)" :src="'/assets/images/hero/' + hero.name + '.png'"
						class="heroAttribute">
				</td>
				<td>
					<input v-if="editMode" v-model.number="heroStats[hero.heroID].hp" class="form-control" min="0" size="5" type="text">
					<div v-else class="text-right">{{heroStats[hero.heroID].hp}}</div>
				</td>
				<td>
					<input v-if="editMode" v-model.number="heroStats[hero.heroID].damage" class="form-control" min="0" size="5" type="text">
					<div v-else class="text-right">{{heroStats[hero.heroID].damage}}</div>
				</td>
				<td>
					<input v-if="editMode" v-model.number="heroStats[hero.heroID].range" class="form-control" min="0" size="5" type="text">
					<div v-else class="text-right">{{heroStats[hero.heroID].range}}</div>
				</td>
				<td>
					<input v-if="editMode" v-model.number="heroStats[hero.heroID].rate" class="form-control" min="0" size="5" type="text">
					<div v-else class="text-right">{{heroStats[hero.heroID].rate}}</div>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
export default {
	name: 'BuildStatsTable',
	props: {
		editMode: Boolean,
		value: {
			type: Object,
			default() {
				return {};
			},
		},
		heroList: {
			type: Object,
			default() {
				return {};
			},
		},
	},
	data() {
		return {
			heroStats: this.value,
		};
	},
	computed: {
		heroStatsList() {
			let heroStats = [];
			for (let key in this.heroStats) {
				if (Object.prototype.hasOwnProperty.call(this.heroStats, key)) {
					let show = this.editMode;
					if (!show) {
						for (let key2 of ['hp', 'range', 'damage', 'rate']) {
							if (this.heroStats[key][key2] > 0) {
								show = true;
								break;
							}
						}
					}

					if (show) {
						heroStats.push(Object.assign({}, { ...this.heroStats[key] }, { name: this.heroList[key], heroID: key }));
					}
				}
			}

			return heroStats;
		},
	},
	watch: {
		value(newValue) {
			this.heroStats = newValue;
		},
		heroStats(newValue) {
			this.$emit('input', newValue);
		},
	},
};
</script>