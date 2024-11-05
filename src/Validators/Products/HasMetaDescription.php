<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasMetaDescription
 *
 * This class is a validator that checks whether a product has a meta description.
 */
class HasMetaDescription extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 70;

	/**
	 * Does this product have stock left?
	 */
	public function passes(): bool {
		return $this->object->html()->analyser()->query( "//meta[@name='description' and string-length(@content) > 0]" )->length;
	}

	/**
	 * Actionable message / advice.
	 */
	public function message(): string {
		return \__( 'This product doesn\'t seem to have a meta description.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Product meta is used by search engines to show in the search results.', 'wooping-shop-health' );
	}
}
