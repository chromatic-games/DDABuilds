<?php

namespace action;

use system\Core;
use system\util\HeaderUtil;

class LogoutAction extends AbstractAction {
	/** @inheritDoc */
	public function execute() {
		parent::execute();

		if ( Core::getSession()->getObjectID() ) {
			Core::getSession()->delete();
		}

		HeaderUtil::redirect('/');
	}
}