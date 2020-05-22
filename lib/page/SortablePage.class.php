<?php

namespace page;

use system\Core;

/**
 * Provides default implementations for a sortable page of listed items.
 * Handles the sorting parameters automatically.
 *
 * @package page
 */
abstract class SortablePage extends MultipleLinkPage {
	/**
	 * default sort field
	 * @var    string
	 */
	public $defaultSortField = '';

	/**
	 * default sort order
	 * @var    string
	 */
	public $defaultSortOrder = 'ASC';

	/**
	 * list of valid sort fields
	 * @var    string[]
	 */
	public $validSortFields = [];

	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();

		// read sorting parameter
		if ( isset($_REQUEST['sortField']) ) {
			$this->sortField = $_REQUEST['sortField'];
		}
		if ( isset($_REQUEST['sortOrder']) ) {
			$this->sortOrder = $_REQUEST['sortOrder'];
		}
	}

	/**
	 * @inheritDoc
	 */
	public function readData() {
		$this->validateSortOrder();
		$this->validateSortField();

		parent::readData();
	}

	/**
	 * Validates the given sort field parameter.
	 */
	public function validateSortField() {
		if ( !in_array($this->sortField, $this->validSortFields) ) {
			$this->sortField = $this->defaultSortField;
		}
	}

	/**
	 * Validates the given sort order parameter.
	 */
	public function validateSortOrder() {
		switch ( $this->sortOrder ) {
			case 'ASC':
			case 'DESC':
				break;

			default:
				$this->sortOrder = $this->defaultSortOrder;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();

		// assign sorting parameters
		Core::getTPL()->assign([
			'sortField' => $this->sortField,
			'sortOrder' => $this->sortOrder,
		]);
	}
}