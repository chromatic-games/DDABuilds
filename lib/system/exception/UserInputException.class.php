<?php

namespace system\exception;

class UserInputException extends \Exception {
	/**
	 * error message
	 * @var    string
	 */
	protected $errorMessage = '';

	/**
	 * erroneous field name
	 * @var    string
	 */
	protected $fieldName = '';

	public function __construct($fieldName, $errorMessage = 'empty') {
		$this->fieldName = $fieldName;
		$this->errorMessage = $errorMessage;
		if ( $errorMessage === 'empty' ) {
			$this->errorMessage = 'Please fill in this field.';
		}
		elseif ( $errorMessage === 'invalid' ) {
			$this->errorMessage = 'The value in this field is invalid.';
		}
		elseif ( $errorMessage === 'noValidSelection' ) {
			$this->errorMessage = 'Choose one of the available options.';
		}
		$this->message = 'The parameter “'.$fieldName.'” is missing or invalid.';
	}

	/**
	 * format error message in html form error
	 *
	 * @return string
	 */
	public function getHtml() {
		return '<div class="alert alert-danger">'.$this->getErrorMessage().'</div>';
	}

	/**
	 * Returns error message.
	 *
	 * @return    string
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * Returns erroneous field name.
	 *
	 * @return    string
	 */
	public function getFieldName() {
		return $this->fieldName;
	}

	/**
	 * @inheritDoc
	 */
	public function __toString() {
		return $this->message;
	}
}