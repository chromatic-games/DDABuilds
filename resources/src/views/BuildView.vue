<template>
	<loading-indicator v-if="statusCode === 0" />
	<div v-else-if="statusCode === 200" class="container-fluid">
		<img class="ddmap" src="/assets/images/map/The_Ramparts.png">

	</div>
	<div v-else class="container">
		<div class="alert alert-danger" v-text="$t('build.error.' + statusCode)"></div>
	</div>
</template>

<script>
import axios from 'axios';
import LoadingIndicator from '../components/LoadingIndicator';

export default {
	name: 'BuildView',
	components: { LoadingIndicator },
	data() {
		return {
			statusCode: 0,
			build: {},
		};
	},
	created() {
		this.fetch();
	},
	methods: {
		fetch() {
			if (!this.$route.params.id) {
				this.$router.push({ name: 'buildList' });
				return;
			}

			axios
				.get('/builds/' + this.$route.params.id)
				.then(({ data }) => {
					this.statusCode = 200;
					this.build = data;
				})
				.catch(({ response: { status } }) => {
					this.statusCode = status;
				});
		},
	},
};
</script>