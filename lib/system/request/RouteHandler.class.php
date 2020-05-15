<?php

namespace system\request;

use action\AbstractAction;
use page\AbstractPage;
use system\SingletonFactory;

class RouteHandler extends SingletonFactory {
	/**
	 * current path info component
	 *
	 * @var string
	 */
	protected static $pathInfo;

	/**
	 * parsed request data
	 * @var    mixed[]
	 */
	protected $routeData = [];

	/**
	 * schema for outgoing links
	 *
	 * @var    mixed[][]
	 */
	protected $buildSchema = [];

	protected function init() {
		$this->buildSchema = [];

		$buildSchema = ltrim('/{controller}/{id}-{title}/', '/');
		$components = preg_split('~({(?:[a-z]+)})~', $buildSchema, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		foreach ( $components as $component ) {
			$type = 'component';
			if ( preg_match('~{([a-z]+)}~', $component, $matches) ) {
				$component = $matches[1];
			}
			else {
				$type = 'separator';
			}

			$this->buildSchema[] = [
				'type'  => $type,
				'value' => $component,
			];
		}
	}

	/**
	 * Returns current path info component.
	 *
	 * @return    string
	 */
	public static function getPathInfo() {
		if ( self::$pathInfo === null ) {
			self::$pathInfo = '';

			if ( !empty($_SERVER['QUERY_STRING']) ) {
				// don't use parse_str as it replaces dots with underscores
				$components = explode('&', $_SERVER['QUERY_STRING']);
				for ( $i = 0, $length = count($components);$i < $length;$i++ ) {
					$component = $components[$i];

					$pos = mb_strpos($component, '=');
					if ( $pos !== false && $pos + 1 === mb_strlen($component) ) {
						$component = mb_substr($component, 0, -1);
						$pos = false;
					}

					if ( $pos === false ) {
						self::$pathInfo = urldecode($component);
						break;
					}
				}
			}

			// translate legacy controller names [MyNiceController - my-nice-controller]
			if ( preg_match('~^(?P<controller>(?:[A-Z]+[a-z0-9]+)+)(?:/|$)~', self::$pathInfo, $matches) ) {
				$parts = preg_split('~([A-Z]+[a-z0-9]+)~', $matches['controller'], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
				$parts = array_map('strtolower', $parts);

				self::$pathInfo = implode('-', $parts).mb_substr(self::$pathInfo, mb_strlen($matches['controller']));
			}
		}

		return self::$pathInfo;
	}

	/**
	 * @inheritDoc
	 */
	public function matches($requestURL) {
		$pattern = '~
			/?
			(?:
				(?P<controller>
					(?:
						[a-z][a-z0-9]+
						(?:-[a-z][a-z0-9]+)*
					)+
				)
				(?:/|$)
				(?:
					(?P<id>\d+)
					(?:
						-
						(?P<title>[^/]+)
					)?
				)?
			)?
		~x';
		if ( preg_match($pattern, $requestURL, $matches) ) {
			foreach ( $matches as $key => $value ) {
				if ( !is_numeric($key) ) {
					$this->routeData[$key] = $value;
				}
			}

			if ( !empty($this->routeData) ) {
				if ( !empty($matches['id']) ) {
					$this->routeData['id'] = $matches['id'];

					if ( !empty($matches['title']) ) {
						$this->routeData['title'] = $matches['title'];
					}
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Builds the actual link, the parameter $useBuildSchema can be set to false for
	 * empty routes, e.g. for the default page.
	 *
	 * @param string[] $components
	 * @param boolean  $useBuildSchema
	 *
	 * @return    string
	 */
	public function buildLink(array $components, $useBuildSchema = true) {
		$link = '';

		if ( $useBuildSchema ) {
			$lastSeparator = null;
			$skipToLastSeparator = false;
			foreach ( $this->buildSchema as $component ) {
				$value = $component['value'];

				if ( $component['type'] === 'separator' ) {
					$lastSeparator = $value;
				}
				elseif ( $skipToLastSeparator === false ) {
					// routes are build from left-to-right
					if ( empty($components[$value]) ) {
						$skipToLastSeparator = true;

						// drop empty components to avoid them being appended as query string argument
						unset($components[$value]);

						continue;
					}

					if ( $lastSeparator !== null ) {
						$link .= $lastSeparator;
						$lastSeparator = null;
					}

					// handle controller names
					if ( $value === 'controller' ) {
						$components[$value] = self::transformController($components[$value]);
					}

					$link .= $components[$value];
					unset($components[$value]);
				}
			}

			if ( !empty($link) && $lastSeparator !== null ) {
				$link .= $lastSeparator;
			}
		}

		if ( !empty($link) ) {
			$link = 'index.php?'.$link;
		}

		if ( !empty($components) ) {
			if ( strpos($link, '?') === false ) {
				$link .= '?';
			}
			else {
				$link .= '&';
			}

			$link .= http_build_query($components, '', '&');
		}

		return $link;
	}

	/**
	 * handles a http request
	 *
	 * @throws \Exception
	 */
	public function handle() {
		try {
			if ( !$this->matches(self::getPathInfo()) ) {
				throw new \Exception('illegal link?'); // todo illegallinkexception
			}

			$routeData = $this->routeData;
			if ( empty($routeData['controller']) ) {
				$routeData['controller'] = 'index';
			}

			$controller = $routeData['controller'];// form controller
			$parts = explode('-', $controller);
			$parts = array_map('ucfirst', $parts);
			$controller = implode('', $parts);

			// resolve controller
			$classData = $this->getClassData($controller, 'page');
			if ( $classData === null ) {
				$classData = $this->getClassData($controller, 'form');
			}
			if ( $classData === null ) {
				$classData = $this->getClassData($controller, 'action');
			}

			// no controller found
			if ( $classData === null ) {
				throw new \Exception('illegal link exception'); // TODO
			}

			/** @var AbstractPage|AbstractAction $requestObject */
			$requestObject = new $classData['className']();
			$requestObject->__run();
			exit;
		} catch ( \Exception $e ) { // todo nameduserexception
			// todo show exception
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * Transforms a controller into its URL representation.
	 *
	 * @param string $controller controller, e.g. 'BoardList'
	 *
	 * @return    string        url representation, e.g. 'board-list'
	 */
	public static function transformController($controller) {
		// work-around for broken controllers that violate the strict naming rules
		if ( preg_match('~[A-Z]{2,}~', $controller) ) {
			$parts = preg_split('~([A-Z][a-z0-9]+)~', $controller, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

			// fix for invalid pages that would cause single character fragments
			$sanitizedParts = [];
			$tmp = '';
			foreach ( $parts as $part ) {
				if ( strlen($part) === 1 ) {
					$tmp .= $part;
					continue;
				}

				$sanitizedParts[] = $tmp.$part;
				$tmp = '';
			}
			if ( $tmp ) {
				$sanitizedParts[] = $tmp;
			}
			$parts = $sanitizedParts;
		}
		else {
			$parts = preg_split('~([A-Z][a-z0-9]+)~', $controller, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		}

		$parts = array_map('strtolower', $parts);

		return implode('-', $parts);
	}

	/**
	 * @param string $controller
	 * @param string $pageType
	 *
	 * @return null|array
	 * @throws \ReflectionException
	 */
	protected function getClassData($controller, $pageType) {
		$className = $pageType.'\\'.$controller.ucfirst($pageType);
		if ( !class_exists($className) ) {
			return null;
		}

		$reflection = new \ReflectionClass($className);
		if ( $reflection->isAbstract() ) {
			return null;
		}

		return [
			'className'  => $className,
			'controller' => $controller,
			'pageType'   => $pageType,
		];
	}
}