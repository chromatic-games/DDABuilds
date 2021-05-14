<?php

namespace App\Laravel;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BasePaginatedResourceResponse;

class PaginatedResourceResponse extends BasePaginatedResourceResponse {
	protected function paginationInformation($request)
	{
		return $this->meta($this->resource->resource->toArray());
	}
}