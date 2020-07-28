<?php

namespace data\bugReport\comment;

use data\DatabaseObject;

/**
 * represents a bug report
 *
 * @property-read integer $commentID
 * @property-read integer $bugReportID
 * @property-read integer $time
 * @property-read string  $steamID
 * @property-read string  $description
 */
class BugReportComment extends DatabaseObject {
	public function getDate() {
		return date('d F Y H:i:s', $this->time);
	}
}