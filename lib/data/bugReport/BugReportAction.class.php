<?php

namespace data\bugReport;

use data\DatabaseObjectAction;
use system\Core;
use system\exception\PermissionDeniedException;

class BugReportAction extends DatabaseObjectAction {
	/**
	 * @throws PermissionDeniedException
	 */
	public function validateClose() {
		if ( !Core::getUser()->isMaintainer() ) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * close a bug report
	 *
	 * @throws \Exception
	 */
	public function close() {
		if ( empty($this->objects) ) {
			$this->readObjects();
		}

		/** @var BugReport $object */
		foreach ( $this->objects as $object ) {
			if ( $object->status === BugReport::STATUS_OPEN ) {
				$object->update([
					'status' => BugReport::STATUS_CLOSED,
				]);
			}
		}
	}
}