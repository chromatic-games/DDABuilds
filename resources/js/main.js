import Vue from 'vue';
import axios from 'axios';
import App from './App.vue';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

new Vue({
	el: '#app',
	render: h => h(App),
});