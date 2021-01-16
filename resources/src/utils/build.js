import {formatSEOTitle, ucfirst} from './string';

function buildLinkParams(build) {
	return {
		id: build.ID,
		title: formatSEOTitle(build.title),
	};
}

function buildListSearch(options = {}) {
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

const STATUS_PUBLIC = 1;
const STATUS_UNLISTED = 2;
const STATUS_PRIVATE = 3;

export {
	STATUS_PUBLIC,
	STATUS_UNLISTED,
	STATUS_PRIVATE,
	buildLinkParams,
	buildListSearch,
};