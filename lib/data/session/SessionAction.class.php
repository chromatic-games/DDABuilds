<?php

namespace data\session;

use data\DatabaseObjectAction;

class SessionAction extends DatabaseObjectAction {
	/**
	 * delete all expired sessions
	 */
	public static function deleteExpiredSessions() {
		$sessionList = new SessionList();
		$sessionList->getConditionBuilder()->add('expires < ?', [TIME_NOW]);
		$sessionList->readObjects();

		$sessions = $sessionList->getObjects();
		if ( !empty($sessions) ) {
			$action = new SessionAction($sessions, 'delete');
			$action->executeAction();
		}
	}
}