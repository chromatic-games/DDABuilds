import axios from 'axios';
import {NavbarPlugin, TooltipPlugin} from 'bootstrap-vue';
import Vue from 'vue';
import App from './App.vue';
import i18n from './i18n';
import router from './router';
import store from './store';

axios.defaults.baseURL = '/api/';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.use(NavbarPlugin);
Vue.use(TooltipPlugin);

new Vue({
	el: '#app',
	i18n,
	router,
	store,
	render: h => h(App),
});