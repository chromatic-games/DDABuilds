import Vue from 'vue';
import Vuex from 'vuex';
import authentication from './authentication';

Vue.use(Vuex);

const store = new Vuex.Store({
	state: {
		darkMode: false,
	},
	modules: {
		authentication,
	},
	mutations: {
		SET_DARKMODE(state, darkMode) {
			state.darkMode = darkMode;
		},
	},
});

export default store;