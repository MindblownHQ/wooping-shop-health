<?php

namespace Wooping\ShopHealth\Contracts;

use Wooping\ShopHealth\Models\ScannedObject;
use WooCommerce;

/**
 * Blueprint for validating WooCommerce settings.
 */
abstract class SettingsValidator extends Validator {

	/**
	 * Holds the main WooCommerce class.
	 */
	protected WooCommerce $woocommerce;

	/**
	 * Constructor
	 */
	public function __construct( WooCommerce $woocommerce, ScannedObject $scanned_object ) {
		$this->woocommerce = $woocommerce;

		$this->object = $scanned_object;
		$this->object->save();
	}
}
