<?php

namespace data;

/**
 * Default interface for a route object.
 */
interface IRouteObject extends ITitledObject {
	/**
	 * Returns the id of the object.
	 *
	 * @return    integer
	 */
	public function getObjectID();
}
