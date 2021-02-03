import i18n from '../i18n';

export function relativeDate(elTimestamp) {
	let timestamp = Math.trunc(Date.now() / 1000);
	elTimestamp = Math.trunc(elTimestamp);
	if (elTimestamp === 0) {
		return i18n.i18next.t('date.relative.never');
	}

	// timestamp is less than 60 seconds ago
	if (elTimestamp >= timestamp || timestamp < (elTimestamp + 60)) {
		return i18n.i18next.t('date.relative.now');
	}
	// timestamp is less than 60 minutes ago (display 1 hour ago rather than 60 minutes ago)
	else if (timestamp < (elTimestamp + 3540)) {
		let minutes = Math.max(Math.round((timestamp - elTimestamp) / 60), 1);

		return i18n.i18next.t('date.relative.minutes', { count: minutes });
	}
	// timestamp is less than 24 hours ago
	else if (timestamp < (elTimestamp + 86400)) {
		let hours = Math.round((timestamp - elTimestamp) / 3600);

		return i18n.i18next.t('date.relative.hours', { count: hours });
	}
	// timestamp is less than 6 days ago
	else if (timestamp < (elTimestamp + 518400)) {
		let date = new Date(timestamp);
		let midnight = new Date(date.getFullYear(), date.getMonth(), date.getDate());
		let days = Math.ceil((midnight / 1000 - elTimestamp) / 86400);

		// get day of week
		let dateObj = new Date(elTimestamp * 1000);
		let dow = dateObj.getDay();
		let day = i18n.i18next.t('date.dayNames', { returnObjects: true })[dow];

		return i18n.i18next.t('date.relative.pastDays', {
			count: days,
			day,
			time: formatDate(i18n.i18next.t('date.timeFormat'), dateObj),
		});
	}
	// timestamp is between ~700 million years BC and last week
	else {
		return formatDate(i18n.i18next.t('date.datetimeFormat'), new Date(elTimestamp * 1000));
	}
}

export default function formatDate(date, format) {
	let char;
	let out = '';

	if (typeof date === 'undefined') {
		date = new Date();
	}
	else if (typeof date === 'string') {
		date = new Date(date);
	}
	else if ( typeof date === 'number' ) {
		date = new Date(date * 1_000);
	}

	// ISO 8601 date, best recognition by PHP's strtotime()
	if (format === 'c') {
		format = 'Y-m-dTH:i:sP';
	}
	else if (typeof format === 'undefined') {
		format = i18n.i18next.t('date.datetimeFormat');
	}

	for (let i = 0; i < format.length; i++) {
		switch (format[i]) {
		// seconds
		case 's':
			// `00` through `59`
			char = ('0' + date.getSeconds().toString()).slice(-2);
			break;

			// minutes
		case 'i':
			// `00` through `59`
			char = date.getMinutes();
			if (char < 10) {
				char = '0' + char;
			}
			break;

			// hours
		case 'a':
			// `am` or `pm`
			char = (date.getHours() > 11) ? 'pm' : 'am';
			break;
		case 'g':
			// `1` through `12`
			char = date.getHours();
			if (char === 0) {
				char = 12;
			}
			else if (char > 12) {
				char -= 12;
			}
			break;
		case 'h':
			// `01` through `12`
			char = date.getHours();
			if (char === 0) {
				char = 12;
			}
			else if (char > 12) {
				char -= 12;
			}

			char = ('0' + char.toString()).slice(-2);
			break;
		case 'A':
			// `AM` or `PM`
			char = (date.getHours() > 11) ? 'PM' : 'AM';
			break;
		case 'G':
			// `0` through `23`
			char = date.getHours();
			break;
		case 'H':
			// `00` through `23`
			char = date.getHours();
			char = ('0' + char.toString()).slice(-2);
			break;

			// day
		case 'd':
			// `01` through `31`
			char = date.getDate();
			char = ('0' + char.toString()).slice(-2);
			break;
		case 'j':
			// `1` through `31`
			char = date.getDate();
			break;
		case 'l':
			// `Monday` through `Sunday` (localized)
			char = i18n.t('date.dayNames', {
				returnObjects: true,
			})[date.getDay()];
			break;
		case 'D':
			// `Mon` through `Sun` (localized)
			char = i18n.t('date.dayNamesShort', { returnObjects: true })[date.getDay()];
			break;
		case 'S':
			// ignore english ordinal suffix
			char = '';

			let day = date.getDate();
			if (day > 3 && day < 21) {
				char = 'th';
			}
			else {
				switch (day % 10) {
				case 1:
					char = 'st';
					break;
				case 2:
					char = 'nd';
					break;
				case 3:
					char = 'rd';
					break;
				default:
					char = 'th';
					break;
				}
			}
			break;

			// month
		case 'm':
			// `01` through `12`
			char = date.getMonth() + 1;
			char = ('0' + char.toString()).slice(-2);
			break;
		case 'n':
			// `1` through `12`
			char = date.getMonth() + 1;
			break;
		case 'F':
			// `January` through `December` (localized)
			char = i18n.t('date.monthNames', { returnObjects: true })[date.getMonth()];
			break;
		case 'M':
			// `Jan` through `Dec` (localized)
			char = i18n.t('date.monthNamesShort', { returnObjects: true })[date.getMonth()];
			break;

			// year
		case 'y':
			// `00` through `99`
			char = date.getFullYear().toString()
				.slice(-2);
			break;
		case 'Y':
			// Examples: `1988` or `2015`
			char = date.getFullYear();
			break;

			// timezone
		case 'P':
			let offset = date.getTimezoneOffset();
			char = (offset > 0) ? '-' : '+';

			offset = Math.abs(offset);

			char += ('0' + (~~(offset / 60)).toString()).slice(-2);
			char += ':';
			char += ('0' + (offset % 60).toString()).slice(-2);

			break;

			// specials
		case 'r':
			char = date.toString();
			break;
		case 'U':
			char = Math.round(date.getTime() / 1000);
			break;

			// escape sequence
		case '\\':
			char = '';
			if (i + 1 < length) {
				char = format[++i];
			}
			break;

		default:
			char = format[i];
			break;
		}

		out += char;
	}

	return out;
}