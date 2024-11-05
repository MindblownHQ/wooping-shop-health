<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasValidVariations
 *
 * Checks if a Variable Products has Variations
 */
class HasValidVariations extends ProductValidator {

	/**
	 * Define the severity of this validator.
	 */
	protected int $severity = 90;

	/**
	 * Define the validator type.
	 */
	protected string $type = 'revenue';

	/**
	 * Is the product variable and does it have variations?
	 */
	public function passes(): bool {
		if ( $this->product->is_type( 'variable' ) ) {
			return ! empty( $this->product->get_children() );
		}
		return true;
	}

	/**
	 * Actionable message / advice
	 */
	public function message(): string {
		return \__( 'This variable product doesn\'t have variations. Please provide variations for this product.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Issues with your product variations can result in unsellable items, make sure to check / resolve these issues.', 'wooping-shop-health' );
	}
}
