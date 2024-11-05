<?php

namespace Wooping\ShopHealth\Models;

use Wooping\ShopHealth\Contracts\Model;
use Wooping\ShopHealth\Models\Scopes\ProductScope;

/**
 * Class Product
 *
 * Represents a product
 */
class Product extends Model {

	/**
	 * Products use the posts table
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * Only the id and order_id are guarded
	 *
	 * @var array<string>
	 */
	protected $guarded = [ 'id' ];

	/**
	 * When creating a product, always take the scope with you
	 *
	 * @return void
	 */
	protected static function boot() {
		parent::boot();
		static::addGlobalScope( new ProductScope() );
	}

	/**
	 * A product has many issues
	 *
	 * @return Wooping\ShopHealth\Models\Issue
	 */
	public function issues() {
		return $this->hasMany( Issue::class );
	}
}
