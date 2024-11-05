<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasValidSalePrice
 *
 * This class extends the Validator class and represents a validator for checking if a product has a valid sale price.
 */
class HasValidSalePrice extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 75;

	/**
	 * Does this product have a valid sale price?
	 */
	public function passes(): bool {
		if ( ! empty( $this->product->get_sale_price() ) ) {
			return \floatval( $this->product->get_sale_price() ) < \floatval( $this->product->get_regular_price() );
		}
		return true;
	}

	/**
	 * Actionable message / advice
	 */
	public function message(): string {
		return \__( 'This product\'s sale price is invalid. Please set a valid sale price lower than the regular price.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'If a product is on sale, make sure the sale price is set correctly.', 'wooping-shop-health' );
	}
}
