<template>
	<section id="main">
		<header :style="{backgroundImage: 'url(' + headerImage + ')'}" class="index-header-image" />
		<div class="container marginTop">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="section-heading">
						Welcome to DD:A Builder!
					</h1>
					<p class="lead">
						Either browse through the existing builds or log in via Steam and create your own to share them with the world.
					</p>
					<p>
						DD:A Builder site originally created by Chakratos at
						<a href="https://dundefplanner.com" target="_blank">dundefplanner.com</a> for Dungeon Defender 1 builds.
					</p>
				</div>
			</div>

			<template v-if="contributors.length">
				<h3 class="marginTop">
					Contributors
				</h3>
				<div class="d-flex flex-wrap">
					<div v-for="contributor in contributors" :key="contributor.id" class="text-center mt-3" style="flex-basis: 150px">
						<img :src="contributor.avatar_url" class="text-center rounded-circle" style="width: 64px; height:64px;"><br>
						<a :href="contributor.html_url" target="_blank">@{{contributor.login}}</a>
					</div>
				</div>
			</template>
		</div>
	</section>
</template>

<script>
// import axios from 'axios';

export default {
	name: 'IndexView',
	data() {
		let imageCount = 1;

		return {
			imageNumber: Math.floor((Math.random() * imageCount) + 1),
			contributors: [],
		};
	},
	computed: {
		headerImage() {
			return '/assets/images/index/' + this.imageNumber + '.jpg';
		},
	},
	created() {
		// axios.get('https://api.github.com/repos/RefreshingWater/DDABuilds/contributors').then(({ data }) => {
		// 	this.contributors = data;
		// });
	},
};
</script>