<?php

namespace data;

/**
 * Every linkable object has to implement this interface.
 */
interface ILinkableObject {
	/**
	 * Returns the link to the object.
	 *
	 * @return string
	 */
	public function getLink();
}