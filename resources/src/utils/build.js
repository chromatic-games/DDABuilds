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

// TODO move to string utils directory
function lcfirst(string) {
	return string.substr(0, 1).toUpperCase() + string.substr(1);
}

export function buildListSearch(options = {}) {
	if (options.map) {
		options.map = lcfirst(options.map);
	}
	if (options.gameMode) {
		options.gameMode = lcfirst(options.gameMode);
	}
	if (options.difficulty) {
		options.difficulty = lcfirst(options.difficulty);
	}

	return options;
}