<?php

namespace App\Laravel;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class ResourceCollection extends AnonymousResourceCollection {
	public function toResponse($request) {
		if ( $this->resource instanceof AbstractPaginator ) {
			if ( $this->preserveAllQueryParameters ) {
				$this->resource->appends($request->query());
			}
			elseif ( !is_null($this->queryParameters) ) {
				$this->resource->appends($this->queryParameters);
			}

			return (new PaginatedResourceResponse($this))->toResponse($request);
		}

		return parent::toResponse($request);
	}
}