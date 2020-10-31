import Vue from 'vue';
import Router from 'vue-router';
import store from '../store';
import AuthView from '../views/AuthView';

const NotFound = () => import('../views/NotFound');
const IndexView = () => import('../views/IndexView');
const BuildListView = () => import('../views/BuildListView');
const BuildView = () => import('../views/BuildView');
const ChangelogView = () => import('../views/ChangelogView');

Vue.use(Router);

const router = new Router({
	mode: 'history',
	routes: [
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
			path: '/build-list',
			component: BuildListView,
		},
		{
			name: 'build',
			path: '/build/:id-:title',
			component: BuildView,
		},
		{
			name: 'changelog',
			path: '/changelog',
			component: ChangelogView,
		},
		{
			name: 'logout',
			path: '/logout',
			beforeEnter: (to, from, next) => {
				store.dispatch('authentication/logout').then(() => {
					if (from.meta.requiredAuth) {
						router.push({ name: 'home' });
					}
					else {
						router.push(from);
					}
				}).catch(() => {
					console.error('failed logout');
					// TODO error handling
				});
			},
		},
		{
			name: 'auth',
			path: '/auth',
			component: AuthView,
		},
		{
			name: 'notFound',
			path: '*',
			component: NotFound,
		},
	],
});

router.afterEach(() => {
	window.scrollTo(0, 0);
});

router.beforeEach((to, from, next) => {
	let customNext = () => {
		if (to.meta.requiredAuth && !store.state.authentication.user.steamID) {
			return next({ name: 'home' });
		}

		next();
	};

	if (store.state.authentication.checked) {
		return customNext();
	}

	store.dispatch('authentication/checkAuth').then(customNext).catch(customNext);
});

export default router;