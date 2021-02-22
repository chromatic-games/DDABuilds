<template>
	<div class="container">
		<template v-if="notifications.length">
			<div v-for="notification in notifications" :key="notification.id">
				<i18next :path="'notification.' + notification.type" class="alert alert-info" tag="div">
					<!-- TODO add the user avatar -->
					<a :href="'https://steamcommunity.com/profiles/' + notification.data.user.ID" place="user" target="_blank">{{notification.data.user.name}}</a>
					<router-link v-if="notification.type === 'buildComment'" :to="{name: 'build', params: buildLinkParams(notification.data.build)}" place="buildLink">
						{{notification.data.build.title}}<!--
						fix for space after build title
						-->
					</router-link>
				</i18next>
			</div>

			<app-pagination :current-page="page" :pages="pages" />
		</template>
		<div v-else class="alert alert-info">
			{{$t('notification.noEntities')}}
		</div>
	</div>
</template>

<script>
import axios from 'axios';
import AppPagination from '../components/AppPagination';
import {hidePageLoader, showPageLoader} from '../store';
import {buildLinkParams} from '../utils/build';

export default {
	name: 'NotificationListView',
	components: { AppPagination },
	data() {
		return {
			notifications: [],
			page: 0,
			pages: 0,
		};
	},
	watch: {
		'$route.params.page'() {
			this.fetchList();
		},
	},
	created() {
		this.fetchList();
	},
	methods: {
		buildLinkParams,
		fetchList() {
			showPageLoader();

			let page = this.$route.params.page || 0;

			axios
				.get('/notifications/?page=' + page)
				.then(({ data: { data, lastPage, currentPage } }) => {
					this.notifications = data;
					this.page = currentPage;
					this.pages = lastPage;
				})
				.catch(() => {
					this.$notify({
						type: 'error',
						text: this.$t('error.default'),
					});
					this.$router.push({ name: 'home' });
				})
				.finally(hidePageLoader);
		},
	},
};
</script>