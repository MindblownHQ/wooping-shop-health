<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasCrossSells
 *
 * This class extends the Validator class and represents a validator for checking if a product has cross-sell products.
 */
class HasCrossSells extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 60;

	protected string $type = 'revenue';

	/**
	 * Does this product have cross-sell products?
	 *
	 * @return bool
	 */
	public function passes(): bool {
		return \count( $this->product->get_cross_sell_ids() ) > 0;
	}

	/**
	 * Actionable message / advice
	 *
	 * @return string
	 */
	public function message(): string {
		return \__( 'This product doesn\'t have any cross-sell products. Consider adding cross-sell products to increase sales.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Cross Sells are complementary products, these help you achieve a higher order value.', 'wooping-shop-health' );
	}
}
