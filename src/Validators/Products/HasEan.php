<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasEan
 *
 * This class extends the Validator class and represents a validator for checking if a product has an EAN number.
 */
class HasEan extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 30;

	/**
	 * Define the importance of this validator.
	 */
	protected string $importance = 'critical';

	/**
	 * Does this product have an EAN?
	 */
	public function passes(): bool {
		return ! empty( $this->product->get_meta( '_global_unique_id' ) );
	}

	/**
	 * Actionable message / advice.
	 */
	public function message(): string {
		return \__( 'This product doesn\'t have an EAN-number. EAN numbers are required by the European Union.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'An EAN is a European standard, needed if you plan on using dropship platforms.', 'wooping-shop-health' );
	}
}
