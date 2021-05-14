import Vue from 'vue';
import Router from 'vue-router';
import store from '../store';
import routes from './routes';

Vue.use(Router);

const router = new Router({
	mode: 'history',
	routes,
	scrollBehavior(to, from, savedPosition) {
		return new Promise((resolve) => {
			setTimeout(() => {
				if (to.hash) {
					resolve({ selector: to.hash, offset: {y: 55, x:0} });
				}
				else if (savedPosition) {
					resolve(savedPosition);
				}
				else {
					resolve({ x: 0, y: 0 });
				}
			}, 200);
		});
	},
});

router.afterEach(() => {
	window.scrollTo(0, 0);
});

router.beforeEach((to, from, next) => {
	if (to.meta.requiredAuth && !store.state.authentication.user.ID) {
		return next({ name: 'home' });
	}

	next();
});

export default router;