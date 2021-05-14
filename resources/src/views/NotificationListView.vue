<template>
	<div class="container">
		<div v-if="unreadNotifications" class="text-right">
			<button class="btn btn-primary" @click="markAllAsRead">
				{{$t('notification.markAllAsRead')}}
			</button>
		</div>

		<template v-if="notifications.length">
			<div v-for="notification in notifications" :key="notification.id" :class="{marginTop: unreadNotifications}" class="notification alert alert-info">
				<i18next :options="{context: notification.data.context, notification: notification.data}" :path="'notification.' + notification.type" tag="div">
					<a :href="'https://steamcommunity.com/profiles/' + notification.data.user.ID" place="user" target="_blank">{{notification.data.user.name}}</a>
					<router-link v-if="notification.data.build" :to="{name: 'build', params: buildLinkParams(notification.data.build)}" place="buildLink">
						{{notification.data.build.title}}<!--
						fix for space after build title
						-->
					</router-link>
				</i18next>
				<i v-if="!notification.read" class="fa fa-check pointer" @click="markAsRead(notification)" />
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
import {hideAjaxLoader, hidePageLoader, showAjaxLoader, showPageLoader} from '../store';
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
	computed: {
		unreadNotifications() {
			return this.$store.state.authentication.user.unreadNotifications;
		},
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
		markAllAsRead() {
			showAjaxLoader();
			axios
				.get('/notifications/mark-all-as-read')
				.then(() => {
					this.$store.commit('authentication/ADD_UNREAD_NOTIFICATIONS', this.unreadNotifications * -1);
					for (let notification of this.notifications) {
						notification.read = true;
					}
				})
				.catch(() => {
					this.$notify({
						type: 'error',
						text: this.$t('error.default'),
					});
				})
				.finally(hideAjaxLoader);
		},
		markAsRead(notification) {
			showAjaxLoader();
			axios
				.post('/notifications/mark-as-read/' + notification.id)
				.then(() => {
					this.$store.commit('authentication/ADD_UNREAD_NOTIFICATIONS', -1);
					notification.read = true;
				})
				.catch(() => {
					this.$notify({
						type: 'error',
						text: this.$t('error.default'),
					});
				})
				.finally(hideAjaxLoader);
		},
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

<style lang="scss">
.notification {
	display: flex;

	> i {
		margin-left: auto;
		align-self: flex-end;
	}
}
</style>