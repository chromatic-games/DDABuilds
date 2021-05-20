<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IndexController extends AbstractController {
	public function index() {
		// TODO remove after url changed in game
		if ( !isset($_SERVER['QUERY_STRING']) ) {
			return view('index');
		}
		if ( !Str::startsWith($_SERVER['QUERY_STRING'], 'build-list') ) {
			return view('index');
		}

		$add = [];
		$params = array_merge(array_map(function ($value) use (&$add) {
			$exploded = explode('?', $value);
			if ( count($exploded) >= 2 ) {
				$newValue = explode('=', $exploded[1]);
				$add[array_shift($newValue)] = implode('=', $newValue);

				return $exploded[0];
			}

			return $value;
		}, $_GET), $add);

		$values = [];
		foreach ( $params as $name => $value ) {
			if ( empty($value) ) {
				continue;
			}

			if ( $name === 'gamemode' ) {
				$name = 'gameMode';
			}

			$values[$name] = $value;
		}

		$redirect = config('app.url').'/builds/?'.http_build_query($values);

		return redirect($redirect);
	}

	public function notFound() {
		throw new NotFoundHttpException();
	}
}