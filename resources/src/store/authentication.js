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
			unreadNotifications: 0,
		},
	},
	getters: {
		isLoggedIn(state) {
			return !!state.user.ID;
		},
	},
	mutations: {
		ADD_UNREAD_NOTIFICATIONS(state, payload) {
			state.user.unreadNotifications += payload;
		},
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

			let request = axios
				.delete('/auth')
				.then(() => {
					commit('SET_USER', {
						ID: 0,
					});
				});

			request.finally(() => {
				hideAjaxLoader();
			});

			return request;
		},
	},
};