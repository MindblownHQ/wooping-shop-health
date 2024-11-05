<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasTags
 *
 * This class extends the Validator class and represents a validator for checking if a product has tags.
 */
class HasTags extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 34;

	/**
	 * Define the importance of this validator.
	 */
	protected string $importance = 'low';

	/**
	 * Does this product have tags?
	 *
	 * @return bool
	 */
	public function passes(): bool {
		return ! empty( $this->product->get_tag_ids() );
	}

	/**
	 * Actionable message / advice
	 *
	 * @return string
	 */
	public function message(): string {
		return \__( 'This product doesn\'t have any tags. Adding tags can help with searchability.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Tags help identifying and filter your products.', 'wooping-shop-health' );
	}
}
