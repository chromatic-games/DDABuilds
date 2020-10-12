import axios from 'axios';

export default {
	namespaced: true,
	state: {
		checked: false,
		user: {
			steamID: 0,
			name: '',
			avatarHash: '',
		},
	},
	mutations: {
		SET_USER(state, payload) {
			for (let key in payload) {
				if (payload.hasOwnProperty(key)) {
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
					steamID: 0,
				});
			});
		},
		checkAuth({ state, commit }) {
			if (state.checked) {
				return Promise.resolve();
			}

			return axios.get('/auth').then(({ data }) => {
				commit('SET_USER', data);
			}).finally(() => {
				commit('SET_CHECKED', true);
			});
		},
	},
};