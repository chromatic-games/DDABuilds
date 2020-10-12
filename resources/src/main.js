import axios from 'axios';
import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

axios.defaults.baseURL = '/api/';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

new Vue({
	el: '#app',
	router,
	store,
	render: h => h(App),
});