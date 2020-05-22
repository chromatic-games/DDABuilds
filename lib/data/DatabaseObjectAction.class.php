<?php

namespace data;

use system\Core;
use system\exception\NamedUserException;
use system\exception\PermissionDeniedException;

class DatabaseObjectAction {
	/**
	 * @var string[]
	 */
	public $allowGuestAccess = [];

	/**
	 * pending action
	 * @var    string
	 */
	protected $action = '';

	/**
	 * database object class name
	 * @var    string
	 */
	protected $className = '';

	/**
	 * list of object ids
	 * @var    integer[]
	 */
	protected $objectIDs = [];

	/**
	 * list of object editors
	 * @var    DatabaseObject[]
	 */
	protected $objects = [];

	/**
	 * multi-dimensional array of parameters required by an action
	 * @var    array
	 */
	protected $parameters = [];

	/**
	 * values returned by executed action
	 * @var    mixed
	 */
	protected $returnValues;

	/**
	 * Initialize a new DatabaseObject-related action.
	 *
	 * @param DatabaseObject[] $objects
	 * @param string           $action
	 * @param array            $parameters
	 *
	 * @throws NamedUserException
	 */
	public function __construct(array $objects, $action, array $parameters = []) {
		// set class name
		if ( empty($this->className) ) {
			$className = get_called_class();

			if ( mb_substr($className, -6) === 'Action' ) {
				$this->className = mb_substr($className, 0, -6);
			}
		}

		$indexName = call_user_func([$this->className, 'getDatabaseTableIndexName']);

		foreach ( $objects as $object ) {
			if ( is_object($object) ) {
				if ( $object instanceof $this->className ) {
					$this->objects[] = $object;
				}
				else {
					throw new NamedUserException('invalid value of parameter objects given');
				}

				/** @noinspection PhpVariableVariableInspection */
				$this->objectIDs[] = $object->$indexName;
			}
			else {
				$this->objectIDs[] = $object;
			}
		}

		$this->action = $action;
		$this->parameters = $parameters;
	}

	/**
	 * @inheritDoc
	 */
	public function validateAction() {
		// validate if user is logged in
		if ( !Core::getUser()->steamID && !in_array($this->getActionName(), $this->allowGuestAccess) ) {
			throw new PermissionDeniedException();
		}

		// validate action name
		if ( !method_exists($this, $this->getActionName()) ) {
			throw new NamedUserException("unknown action '".$this->getActionName()."'");
		}

		$actionName = 'validate'.ucfirst($this->getActionName());
		if ( !method_exists($this, $actionName) ) {
			throw new PermissionDeniedException();
		}

		// execute action
		call_user_func_array([$this, $actionName], $this->getParameters());
	}

	/**
	 * Executes the previously chosen action.
	 */
	public function executeAction() {
		// execute action
		if ( !method_exists($this, $this->getActionName()) ) {
			throw new NamedUserException("call to undefined function '".$this->getActionName()."'");
		}

		$this->returnValues = call_user_func([$this, $this->getActionName()]);

		return $this->getReturnValues();
	}

	public function create() {
		return call_user_func([$this->className, 'create'], $this->parameters['data']);
	}

	/**
	 * Updates data.
	 */
	public function update() {
		if ( empty($this->objects) ) {
			$this->readObjects();
		}

		if ( isset($this->parameters['data']) ) {
			foreach ( $this->getObjects() as $object ) {
				$object->update($this->parameters['data']);
			}
		}
	}

	/**
	 * Deletes the relevant objects and returns the number of deleted objects.
	 */
	public function delete() {
		if (empty($this->objects)) {
			$this->readObjects();
		}

		// get ids
		$objectIDs = [];
		foreach ($this->getObjects() as $object) {
			$objectIDs[] = $object->getObjectID();
		}

		// execute action
		return call_user_func([$this->className, 'deleteAll'], $objectIDs);
	}

	/**
	 * Reads data by data id.
	 */
	protected function readObjects() {
		if ( empty($this->objectIDs) ) {
			return;
		}

		// get objects
		$sql = "SELECT	*
			FROM	".$this->getDatabaseTableName()."
			WHERE	".$this->getDatabaseTableIndexName()." IN (".str_repeat('?,', count($this->objectIDs) - 1)."?)";
		$statement = Core::getDB()->prepareStatement($sql);
		$statement->execute($this->objectIDs);
		while ( $object = $statement->fetchObject($this->className) ) {
			$this->objects[] = $object;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getDatabaseTableAlias() {
		return call_user_func([$this->className, 'getDatabaseTableAlias']);
	}

	/**
	 * @inheritDoc
	 */
	public function getDatabaseTableName() {
		return call_user_func([$this->className, 'getDatabaseTableName']);
	}

	/**
	 * @inheritDoc
	 */
	public function getDatabaseTableIndexIsIdentity() {
		return call_user_func([$this->className, 'getDatabaseTableIndexIsIdentity']);
	}

	/**
	 * @inheritDoc
	 */
	public function getDatabaseTableIndexName() {
		return call_user_func([$this->className, 'getDatabaseTableIndexName']);
	}

	/**
	 * Returns results returned by active action.
	 *
	 * @eturn mixed
	 */
	public function getReturnValues() {
		return [
			'actionName'   => $this->action,
			'objectIDs'    => $this->getObjectIDs(),
			'returnValues' => $this->returnValues,
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getActionName() {
		return $this->action;
	}

	/**
	 * @inheritDoc
	 */
	public function getObjectIDs() {
		return $this->objectIDs;
	}

	/**
	 * @inheritDoc
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * Returns a list of currently loaded objects.
	 *
	 * @return DatabaseObject[]
	 */
	public function getObjects() {
		return $this->objects;
	}
}