<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasImage
 *
 * This class extends the Validator class and is responsible for checking if a product has images.
 */
class HasImage extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 90;

	/**
	 * Does this product have stock left?
	 */
	public function passes(): bool {
		return \has_post_thumbnail( $this->product->get_id() );
	}

	/**
	 * Actionable message / advice
	 */
	public function message(): string {
		/* Translators: %s is the link to the product */
		return \__( 'The product is missing a product image.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Images serve two purposes, more product information and attraction.', 'wooping-shop-health' );
	}
}
