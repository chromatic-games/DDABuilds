import Vue from 'vue';
import i18n from '../i18n';
import store from '../store';
import AuthView from '../views/AuthView';
import NotificationListView from '../views/NotificationListView';

const NotFound = () => import('../views/NotFound');
const IndexView = () => import('../views/IndexView');
const BuildListView = () => import('../views/Build/BuildListView');
const ChangelogView = () => import('../views/ChangelogView');
const IssueListView = () => import('../views/Issue/IssueListView');
const IssueView = () => import('../views/Issue/IssueView');
const IssueAddView = () => import('../views/Issue/IssueAddView');
const BuildAddSelectView = () => import('../views/Build/BuildAddSelectView');
const BuildAddView = () => import('../views/Build/BuildAddView');

const routes = [
	{
		name: 'home',
		path: '/',
		component: IndexView,
		meta: {
			ignoreSection: true,
		},
	},
	{
		name: 'buildList',
		path: '/builds/:page?',
		component: BuildListView,
	},
	{
		name: 'changelog',
		path: '/changelog',
		component: ChangelogView,
	},
	// builds
	{
		name: 'buildAddSelect',
		path: '/build-add-select',
		component: BuildAddSelectView,
		meta: {
			requiredAuth: true,
		},
	},
	{
		name: 'buildAdd',
		path: '/build-add/:mapID-:name',
		component: BuildAddView,
		meta: {
			requiredAuth: true,
		},
	},
	{
		name: 'build',
		path: '/build/:id-:title',
		component: BuildAddView,
		props: {
			isView: true,
		},
	},
	// issues
	{
		name: 'issueList',
		path: '/issues/:page?',
		component: IssueListView,
		meta: {
			requiredAuth: true,
		},
	},
	{
		name: 'issueAdd',
		path: '/issue-add',
		component: IssueAddView,
		meta: {
			requiredAuth: true,
		},
	},
	{
		name: 'issue',
		path: '/issue/:id-:title/:page?',
		component: IssueView,
		meta: {
			requiredAuth: true,
		},
	},
	// user related pages
	{
		name: 'myIssueList',
		path: '/my-issues/:page?',
		component: IssueListView,
		meta: {
			requiredAuth: true,
		},
		props: {
			mineList: true,
		},
	},
	{
		name: 'myBuildList',
		path: '/my-builds/:page?',
		component: BuildListView,
		meta: {
			requiredAuth: true,
		},
		props: {
			fetchParams: { mine: 1 },
			hideFilter: true,
		},
	},
	{
		name: 'likedBuildList',
		path: '/liked-builds/:page?',
		component: BuildListView,
		meta: {
			requiredAuth: true,
		},
		props: {
			fetchParams: { liked: 1 },
			hideFilter: true,
		},
	},
	{
		name: 'favoriteBuildList',
		path: '/favorite-builds/:page?',
		component: BuildListView,
		meta: {
			requiredAuth: true,
		},
		props: {
			fetchParams: { watch: 1 },
			hideFilter: true,
		},
	},
	{
		name: 'notificationList',
		path: '/notifications/:page?',
		component: NotificationListView,
		meta: {
			requiredAuth: true,
		},
	},
	// auth
	{
		name: 'logout',
		path: '/logout',
		beforeEnter: (to, from, next) => {
			store
				.dispatch('authentication/logout')
				.then(() => {
					if (from.meta.requiredAuth) {
						return next({ name: 'home' });
					}

					next(from);
				})
				.catch(() => {
					Vue.notify({
						type: 'error',
						text: i18n.t('error.default'),
					});

					next(false);
				});
		},
	},
	{
		name: 'auth',
		path: '/auth',
		component: AuthView,
	},
	// not found
	{
		name: 'notFound',
		path: '*',
		component: NotFound,
	},
];

export default routes;