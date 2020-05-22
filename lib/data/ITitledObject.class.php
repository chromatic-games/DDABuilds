<?php
namespace data;

/**
 * Every titled object has to implement this interface.
 */
interface ITitledObject {
	/**
	 * Returns the title of the object.
	 *
	 * @return	string
	 */
	public function getTitle();
}