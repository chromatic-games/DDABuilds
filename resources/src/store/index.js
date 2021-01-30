import Vue from 'vue';
import Vuex from 'vuex';
import authentication from './authentication';

Vue.use(Vuex);

const store = new Vuex.Store({
	state: {
		darkMode: false,
		ajaxLoader: false,
		pageLoader: false,
	},
	modules: {
		authentication,
	},
	mutations: {
		SET_AJAX_LOADER(state, loading) {
			state.ajaxLoader = loading;
		},
		SET_PAGE_LOADER(state, loading) {
			state.pageLoader = loading;
		},
		SET_DARKMODE(state, darkMode) {
			state.darkMode = darkMode;
		},
	},
});

function showAjaxLoader() {
	store.commit('SET_AJAX_LOADER', true);
}

function hideAjaxLoader() {
	store.commit('SET_AJAX_LOADER', false);
}

function showPageLoader() {
	console.debug('[PageLoader] show');
	store.commit('SET_PAGE_LOADER', true);
}

function hidePageLoader() {
	console.debug('[PageLoader] hide');
	store.commit('SET_PAGE_LOADER', false);
}

export default store;

export {
	showAjaxLoader,
	hideAjaxLoader,
	showPageLoader,
	hidePageLoader,
};