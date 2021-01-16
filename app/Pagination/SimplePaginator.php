<?php

namespace App\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

class SimplePaginator extends LengthAwarePaginator {
	public function toArray() {
		return [
			// 'path' => $this->path(),
			'data' => $this->items->toArray(),
			'currentPage' => $this->currentPage(),
			'lastPage' => $this->lastPage(),
			// 'per_page' => $this->perPage(),
			// 'from' => $this->firstItem(),
			// 'to' => $this->lastItem(),
			'total' => $this->total(),
			// 'links' => $this->linkCollection()->toArray(),
		];
	}
}