<template>
	<div class="container">
		<loading-indicator v-if="loading" />
		<template v-else>
			<div class="list-group text-center">
				<a v-for="mapCategory in mapCategories" :key="mapCategory.ID" :href="'#category-' + mapCategory.name"
					class="list-group-item list-group-item-action">{{$t('mapCategory.' + mapCategory.name)}}</a>
			</div>

			<div v-for="mapCategory in mapCategories" :id="'category-' + mapCategory.name" :key="mapCategory.ID" :ref="'category_' + mapCategory.name">
				<h1 class="page-header">{{$t('mapCategory.' + mapCategory.name)}}</h1>
				<ol class="buildList mapSelection">
				<li v-for="map in mapCategory.maps" :key="map.ID">
					<div class="buildBox">
						<div class="box128">
							<div class="buildDataContainer">
								<h4 class="buildSubject">
									<router-link :to="{name: 'buildAdd', params: map.routeParams }">{{$t('map.' + map.name)}}</router-link>
								</h4>
								<router-link :to="{name: 'buildAdd', params: map.routeParams }">
									<img :src="'/assets/images/map/' + map.name + '.png'" class="img-responsive" style="height: 200px;margin: 15px auto auto;">
								</router-link>
							</div>
						</div>
						<div class="buildFiller"></div>
					</div>
				</li>
				</ol>
			</div>
		</template>
	</div>
</template>

<script>
import axios from 'axios';
import LoadingIndicator from '../../components/LoadingIndicator';
import {formatSEOTitle, formatString} from '../../utils/string';

export default {
	name: 'BuildAddSelectView',
	components: { LoadingIndicator },
	data() {
		return {
			mapCategories: [],
			loading: true,
		};
	},
	created() {
		axios
			.get('/maps')
			.then(({ data }) => {
				for (let idx in data) {
					let mapCategory = data[idx];
					for (let map of mapCategory.maps || []) {
						map.routeParams = {
							mapID: map.ID,
							name: formatSEOTitle(formatString(map.name, '-')),
						};
					}
				}

				this.mapCategories = data;
			})
			.catch(() => {
				// TODO error handling
			})
			.finally(() => {
				this.loading = false;
			});
	},
};
</script>