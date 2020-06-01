<?php

namespace system\exception;

/**
 * Denotes an Exception with extra information for the human reader.
 */
interface IExtraInformationException {
	/**
	 * Returns an array of (key, value) tuples with extra information to show
	 * in the human readable error log.
	 * Avoid including sensitive information (such as private keys or passwords).
	 *
	 * @return	mixed[][]
	 */
	public function getExtraInformation();
}