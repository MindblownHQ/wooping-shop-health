<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasUpsells
 *
 * This class extends the Validator class and represents a validator for checking if a product has upsell products.
 */
class HasUpsells extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 60;

	/**
	 * Does this product have upsell products?
	 *
	 * @return bool
	 */
	public function passes(): bool {
		return \count( $this->product->get_upsell_ids() ) > 0;
	}

	/**
	 * Actionable message / advice
	 *
	 * @return string
	 */
	public function message(): string {
		return \__( 'This product doesn\'t have any upsell products. Consider adding upsell products to increase sales.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Like Cross Sells, Upsells contribute to a higher order value. Make sure to relate higher priced alternatives..', 'wooping-shop-health' );
	}
}
