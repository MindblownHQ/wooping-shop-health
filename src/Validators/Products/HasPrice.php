<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasPrice
 *
 * This class extends the Validator class and represents a validator for checking if a product has a price.
 */
class HasPrice extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 100;

	/**
	 * Define the importance of this validator.
	 */
	protected string $importance = 'critical';

	/**
	 * Does this product have a price?
	 */
	public function passes(): bool {
		return \intval( $this->product->get_price() ) > 0;
	}

	/**
	 * Actionable message / advice
	 */
	public function message(): string {
		return \__( 'This product doesn\'t have a price. Please set a price for this product.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'A product without a price is either free or not for sale, make sure all your prices are set correctly.', 'wooping-shop-health' );
	}
}
