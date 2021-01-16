import Vue from 'vue';
import Router from 'vue-router';
import store from '../store';
import routes from './routes';

Vue.use(Router);

const router = new Router({
	mode: 'history',
	routes,
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

	store
		.dispatch('authentication/checkAuth')
		.then(customNext)
		.catch(customNext);
});

export default router;