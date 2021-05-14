<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IndexController extends AbstractController {
	public function notFound() {
		throw new NotFoundHttpException();
	}
}