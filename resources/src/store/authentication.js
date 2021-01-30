import axios from 'axios';
import {hideAjaxLoader, showAjaxLoader} from './index';

export default {
	namespaced: true,
	state: {
		user: window.APP.user || {
			ID: 0,
			name: '',
			avatarHash: '',
			isMaintainer: false,
		},
	},
	getters: {
		isLoggedIn(state) {
			return !!state.user.ID;
		},
	},
	mutations: {
		SET_USER(state, payload) {
			for (let key in payload) {
				if (Object.prototype.hasOwnProperty.call(payload, key)) {
					state.user[key] = payload[key];
				}
			}
		},
	},
	actions: {
		logout({ commit }) {
			showAjaxLoader();

			return axios
				.delete('/auth')
				.then(() => {
					commit('SET_USER', {
						ID: 0,
					});
				})
				.catch(() => {
					// TODO error handling
				})
				.finally(() => {
					hideAjaxLoader();
				});
		},
	},
};