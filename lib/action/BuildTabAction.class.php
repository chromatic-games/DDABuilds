<?php

namespace action;

class BuildTabAction extends AbstractAction {
	public function execute() {
		parent::execute();

		require_once(LIB_DIR.'map/tab/buildTab.php');
	}
}