import Vue from 'vue';
import Router from 'vue-router';
import BuildListView from '../views/BuildListView';
import IndexView from '../views/IndexView';

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
		}
	]
});

export default router;