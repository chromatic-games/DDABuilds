import axios from 'axios';

const STATUS_OPEN = 1;
const STATUS_CLOSED = 2;

function closeIssue(issue) {
	let promise = axios.put('/issues/' + issue.ID + '/', { status: STATUS_CLOSED });
	promise.then(() => {
		issue.status = STATUS_CLOSED;
	});

	return promise;
}

export {
	STATUS_OPEN,
	STATUS_CLOSED,
	closeIssue,
};