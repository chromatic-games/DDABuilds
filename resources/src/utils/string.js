function ucfirst(string) {
	if (Array.isArray(string)) {
		return string.map((str) => ucfirst(str));
	}

	return string.substr(0, 1).toUpperCase() + string.substr(1);
}

function lcfirst(string) {
	if (Array.isArray(string)) {
		return string.map((str) => lcfirst(str));
	}

	return string.substr(0, 1).toLowerCase() + string.substr(1);
}

function formatSEOTitle(title) {
	title = title
		.toLowerCase()
		.replace(/[^\p{L}\p{N}]+/ug, '-')
		.substr(0, 80);
	if (title[title.length - 1] === '-') {
		title = title.substr(0, title.length - 1);
	}

	return title;
}

export {
	lcfirst,
	ucfirst,
	formatSEOTitle,
};