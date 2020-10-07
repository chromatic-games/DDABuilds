import axios from 'axios';
import Vue from 'vue';
import App from './App.vue';
import router from './router';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

new Vue({
	el: '#app',
	router,
	render: h => h(App),
});