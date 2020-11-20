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

export {
	lcfirst,
	ucfirst,
};