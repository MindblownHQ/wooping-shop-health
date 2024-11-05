<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasSku
 *
 * This class extends the ProductValidator class and is used to check if a product has a SKU.
 */
class HasSku extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 30;

	/**
	 * Does this product have a SKU?
	 */
	public function passes(): bool {
		return ! empty( $this->product->get_sku() );
	}

	/**
	 * Actionable message / advice.
	 */
	public function message(): string {
		return \__( 'This product doesn\'t have an SKU.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'While not mandatory, SKU’s offer more than stock keeping if used to it’s full potential.', 'wooping-shop-health' );
	}
}
