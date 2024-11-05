<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasDimensions
 *
 * This class extends the Validator class and represents a validator for checking if a product has dimensions.
 */
class HasDimensions extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 20;

	/**
	 * Define the importance of this validator.
	 */
	protected string $importance = 'low';

	/**
	 * Does this product have dimensions?
	 *
	 * @return bool
	 */
	public function passes(): bool {
		return $this->product->has_weight() && $this->product->has_dimensions();
	}

	/**
	 * Actionable message / advice
	 *
	 * @return string
	 */
	public function message(): string {
		return \__( 'This product doesn\'t have dimensions. Please provide dimensions for this product.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Setting the product dimensions gives you the opportunity to apply (extra) shipping costs or offer a other delivery  method once this product is added to the cart.', 'wooping-shop-health' );
	}
}
