import axios from 'axios';

export default {
	namespaced: true,
	state: {
		checked: false,
		user: {
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
		SET_CHECKED(state, payload) {
			state.checked = payload;
		},
	},
	actions: {
		logout({ commit }) {
			return axios.delete('/auth').then(() => {
				commit('SET_USER', {
					ID: 0,
				});
			});
		},
		checkAuth({ state, commit }) {
			if (state.checked) {
				return Promise.resolve();
			}

			return axios
				.get('/auth')
				.then(({ data }) => {
					commit('SET_USER', data);
				})
				.finally(() => {
					commit('SET_CHECKED', true);
				});
		},
	},
};