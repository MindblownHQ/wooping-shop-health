<?php

namespace Wooping\ShopHealth\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class ProductScope
 *
 * Represents a scope that can be applied to a query for filtering products.
 */
class ProductScope implements Scope {

	/**
	 * Apply this scope
	 *
	 * $model is not used, hence the added phpcs:ignore.
	 *
	 * @param Builder $builder The query builder instance.
	 * @param Model   $model   The model instance.
	 *
	 * @return void
	 */
	public function apply( Builder $builder, Model $model ) { //phpcs:ignore
		$builder->where(
			[
				'post_type' => 'product',
			]
		);
	}
}
