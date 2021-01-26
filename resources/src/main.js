import CKEditor from '@ckeditor/ckeditor5-vue2';
import axios from 'axios';
import {NavbarPlugin, TooltipPlugin} from 'bootstrap-vue';
import Vue from 'vue';
import App from './App.vue';
import i18n from './i18n';
import router from './router';
import store from './store';

Vue.config.productionTip = false;

require('webpack-jquery-ui/draggable');
require('webpack-jquery-ui/droppable');

axios.defaults.baseURL = '/api/';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.interceptors.response.use((response) => {
	if (response.data._debug) {
		console.log(response.request.responseURL, response.data._debug);
		delete response.data._debug;
	}

	return response;
}, (error) => {
	if (error.response && error.response.data) {
		return Promise.reject(error.response.data);
	}

	return Promise.reject(error.message);
});

Vue.use(CKEditor);
Vue.use(NavbarPlugin);
Vue.use(TooltipPlugin);

new Vue({
	el: '#app',
	i18n,
	router,
	store,
	render: h => h(App),
});