<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasShortDescription
 *
 * This class extends the Validator class and represents a validator for checking if a product has a short description.
 */
class HasShortDescription extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 70;

	/**
	 * Define the importance of this validator.
	 */
	protected string $importance = 'critical';

	/**
	 * Does this product have a short description?
	 */
	public function passes(): bool {
		return ! empty( $this->product->get_short_description() );
	}

	/**
	 * Actionable message / advice
	 */
	public function message(): string {
		return \__( 'This product doesn\'t have a short description. Please provide a short description for this product.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'A short product description makes your products/content easier to scan for your customers.', 'wooping-shop-health' );
	}
}
