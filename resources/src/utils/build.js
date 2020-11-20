import {ucfirst} from './string';

export function buildLinkParams(build) {
	let title = build.title.toLowerCase().replace(/[^\p{L}\p{N}]+/ug, '-').substr(0, 80);
	if (title[title.length - 1] === '-') {
		title = title.substr(0, title.length - 2);
	}

	return {
		id: build.ID,
		title,
	};
}

export function buildListSearch(options = {}) {
	// clear empty fields
	for (let key of Object.keys(options)) {
		if (!options[key] || Array.isArray(options[key]) && options[key].length === 0) {
			delete options[key];
		}
	}

	for (let key of ['map', 'gameMode', 'difficulty']) {
		if (options[key]) {
			options[key] = Array.isArray(options[key]) ? ucfirst(options[key]).join(',') : ucfirst(options[key]);

		}
	}

	return options;
}