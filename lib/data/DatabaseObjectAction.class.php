<?php

namespace data;

use system\Core;
use system\exception\NamedUserException;
use system\exception\PermissionDeniedException;
use system\exception\UserInputException;

class DatabaseObjectAction {
	const TYPE_INTEGER = 1;

	const TYPE_STRING  = 2;

	const TYPE_BOOLEAN = 3;

	const TYPE_JSON    = 4;

	const STRUCT_FLAT  = 1;

	const STRUCT_ARRAY = 2;

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
		if ( empty($this->objects) ) {
			$this->readObjects();
		}

		// get ids
		$objectIDs = [];
		foreach ( $this->getObjects() as $object ) {
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
	 * Reads a boolean value and validates it.
	 *
	 * @param string $variableName
	 * @param bool   $allowEmpty
	 * @param string $arrayIndex
	 *
	 * @throws UserInputException
	 */
	protected function readBoolean($variableName, $allowEmpty = false, $arrayIndex = '') {
		$this->readValue($variableName, $allowEmpty, $arrayIndex, self::TYPE_BOOLEAN, self::STRUCT_FLAT);
	}

	/**
	 * Reads a value and validates it. If you set $allowEmpty to true, no exception will
	 * be thrown if the variable evaluates to 0 (integer) or '' (string). Furthermore the
	 * variable will be always created with a sane value if it does not exist.
	 *
	 * @param string  $variableName
	 * @param boolean $allowEmpty
	 * @param string  $arrayIndex
	 * @param integer $type
	 * @param integer $structure
	 *
	 * @throws UserInputException
	 */
	protected function readValue($variableName, $allowEmpty, $arrayIndex, $type, $structure) {
		if ( $arrayIndex ) {
			if ( !isset($this->parameters[$arrayIndex]) ) {
				throw new NamedUserException("Corrupt parameters, index '".$arrayIndex."' is missing");
			}

			$target =& $this->parameters[$arrayIndex];
		}
		else {
			$target =& $this->parameters;
		}

		switch ( $type ) {
			/*case self::TYPE_INTEGER:
				if ( !isset($target[$variableName]) ) {
					if ( $allowEmpty ) {
						$target[$variableName] = ($structure === self::STRUCT_FLAT) ? 0 : [];
					}
					else {
						throw new UserInputException($variableName);
					}
				}
				else {
					if ( $structure === self::STRUCT_FLAT ) {
						$target[$variableName] = intval($target[$variableName]);
						if ( !$allowEmpty && !$target[$variableName] ) {
							throw new UserInputException($variableName);
						}
					}
					else {
						$target[$variableName] = ArrayUtil::toIntegerArray($target[$variableName]);
						if ( !is_array($target[$variableName]) ) {
							throw new UserInputException($variableName);
						}

						for ( $i = 0, $length = count($target[$variableName]);$i < $length;$i++ ) {
							if ( $target[$variableName][$i] === 0 ) {
								throw new UserInputException($variableName);
							}
						}
					}
				}
				break;*/

			/*case self::TYPE_STRING:
				if (!isset($target[$variableName])) {
					if ($allowEmpty) {
						$target[$variableName] = ($structure === self::STRUCT_FLAT) ? '' : [];
					}
					else {
						throw new UserInputException($variableName);
					}
				}
				else {
					if ($structure === self::STRUCT_FLAT) {
						$target[$variableName] = StringUtil::trim($target[$variableName]);
						if (!$allowEmpty && empty($target[$variableName])) {
							throw new UserInputException($variableName);
						}
					}
					else {
						$target[$variableName] = ArrayUtil::trim($target[$variableName]);
						if (!is_array($target[$variableName])) {
							throw new UserInputException($variableName);
						}

						for ($i = 0, $length = count($target[$variableName]); $i < $length; $i++) {
							if (empty($target[$variableName][$i])) {
								throw new UserInputException($variableName);
							}
						}
					}
				}
				break;*/

			case self::TYPE_BOOLEAN:
				if ( !isset($target[$variableName]) ) {
					if ( $allowEmpty ) {
						$target[$variableName] = false;
					}
					else {
						throw new UserInputException($variableName);
					}
				}
				else {
					if ( is_numeric($target[$variableName]) ) {
						$target[$variableName] = (bool) $target[$variableName];
					}
					else {
						$target[$variableName] = $target[$variableName] != 'false';
					}
				}
				break;
		}
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