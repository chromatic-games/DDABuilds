<?php

namespace App\Laravel;

use App\Pagination\SimplePaginator;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder as BaseBuilder;
use Illuminate\Pagination\Paginator;

class Builder extends BaseBuilder
{
	public function simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
	{
		$page = $page ?: Paginator::resolveCurrentPage($pageName);

		$perPage = $perPage ?: $this->model->getPerPage();

		$items = ($total = $this->toBase()->getCountForPagination())
			? $this->forPage($page, $perPage)->get($columns)
			: $this->model->newCollection();

		$options = [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => $pageName,
		];

		return Container::getInstance()->makeWith(SimplePaginator::class, compact(
			'items', 'total', 'perPage', 'page', 'options'
		));
	}

	protected function simplePaginator($items, $perPage, $currentPage, $options)
	{
		return Container::getInstance()->makeWith(SimplePaginator::class, compact(
			'items', 'perPage', 'currentPage', 'options'
		));
	}
}