<?php

namespace data\bugReport;

use data\DatabaseObject;
use data\ILinkableObject;
use data\IRouteObject;
use data\ITitledObject;
use system\request\LinkHandler;

/**
 * represents a bug report
 *
 * @property-read integer $reportID
 * @property-read integer $time
 * @property-read string  $title
 * @property-read string  $steamID
 * @property-read string  $description
 * @property-read integer $status
 */
class BugReport extends DatabaseObject implements ITitledObject, IRouteObject, ILinkableObject {
	/**
	 * maintainer there can close bug reports
	 */
	const MAINTAINER = ['76561198054589426', '76561198051185047', '76561198004171907'];

	/** @var int */
	const STATUS_OPEN = 1;

	/** @var int */
	const STATUS_CLOSED = 2;

	/**
	 * get status as text
	 *
	 * @return string
	 */
	public function getStatus() {
		if ( $this->status === self::STATUS_OPEN ) {
			return 'Open';
		}
		if ( $this->status === self::STATUS_CLOSED ) {
			return 'Closed';
		}

		return 'Unknown';
	}

	public function getDate() {
		return date('d F Y H:i:s', $this->time);
	}

	/** @inheritDoc */
	public function getTitle() {
		return $this->title;
	}

	/** @inheritDoc */
	public function getLink() {
		return LinkHandler::getInstance()->getLink('BugReport', ['object' => $this]);
	}
}