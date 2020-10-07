import Vue from 'vue';
import Router from 'vue-router';

const IndexView = () => import('../views/IndexView');
const BuildListView = () => import('../views/BuildListView');
const ChangelogView = () => import('../views/ChangelogView');

Vue.use(Router);

const router = new Router({
	mode: 'history',
	routes: [
		{
			name: 'index',
			path: '/',
			component: IndexView,
			meta: {
				ignoreSection: true,
			}
		},
		{
			name: 'buildList',
			path: '/build-list',
			component: BuildListView,
		},
		{
			name: 'changelog',
			path: '/changelog',
			component: ChangelogView,
		}
	]
});

export default router;