<?php

namespace action;

class SaveMapAction extends AbstractAction {
	public function execute() {
		parent::execute();

		include LIB_DIR.'map/mapPostHandler.php';
	}
}