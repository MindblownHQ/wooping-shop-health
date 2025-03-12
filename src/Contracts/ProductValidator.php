<?php

namespace Wooping\ShopHealth\Contracts;

use WC_Product;
use Wooping\ShopHealth\Models\Database\Options;
use Wooping\ShopHealth\Models\ScannedObject;

/**
 * Blueprint for a product validator.
 */
abstract class ProductValidator extends Validator {

	/**
	 * The WooCommerce product object.
	 */
	protected WC_Product $product;

	/**
	 * Constructor
	 */
	public function __construct( WC_Product $product, ScannedObject $scanned_object ) {
		$this->product = $product;
		$this->object  = $scanned_object;
	}

	/**
	 * Does this validator have the requirements to run at all?
	 */
	public function can_run(): bool {
		// Check if this validator is on the "mass-ignore" list.
		$ignored_validators = Options::get( 'ignored_validators' );

		// Don't run it if it is.
		if ( \is_array( $ignored_validators )
			&& \in_array( $this->get_validator_short_name(), $ignored_validators, true )
		) {
			return false;
		}

		// check the rest of the requirements.
		return parent::can_run();
	}
}
