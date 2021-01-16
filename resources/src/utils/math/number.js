import i18n from '../../i18n';

function number(value, decimals, maxDecimals) {
	let options = {
		minimumFractionDigits: decimals || 0,
	};

	if (maxDecimals) {
		options.maximumFractionDigits = maxDecimals;
	}

	return parseFloat(value).toLocaleString(i18n.i18next.language, options);
}

export {
	number as default,
};