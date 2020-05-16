<?php

namespace action;

class LogoutAction extends AbstractAction {
	public function execute() {
		parent::execute();

		session_unset();
		session_destroy();
		header('Location: /');
	}
}