import Vue from 'vue';

let addSelector = (el, { value, arg }) => {
	if (!arg) {
		throw 'You need to set the type of acceptance selector.';
	}

	let attributeName = 'data-test-selector-' + arg;
	el.setAttribute(attributeName, value || '');
};

export default {
	install() {
		Vue.directive('acceptance-selector', {
			inserted: addSelector,
			update: addSelector,
			componentUpdated: addSelector
		});
	},
};