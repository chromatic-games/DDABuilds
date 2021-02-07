<?php

namespace Tests\Browser\Traits;

trait TBrowserHelper {
	public function getVueSelector(string $arg, string $value = null) {
		$selectorValue = '';

		if ( $value !== null ) {
			$selectorValue = '="'.(string) $value.'"';
		}

		return '[data-test-selector-'.$arg.$selectorValue.']';
	}
}