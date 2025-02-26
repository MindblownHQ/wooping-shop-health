<?php

namespace Wooping\ShopHealth\WooCommerce;

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;

/**
 * Class Compatibility
 *
 * Keep track of compatibility issues with the WooCommerce - Shop Health plugins.
 */
class Compatibility implements Hookable {

	/**
	 * Register class hooks
	 */
	public function register_hooks(): void {
		\add_action( 'before_woocommerce_init', [ $this, 'declare_hpos_compatibility' ] );
	}

	/**
	 * Declare compatibility for custom order tables
	 */
	public function declare_hpos_compatibility(): void {
		FeaturesUtil::declare_compatibility( 'custom_order_tables', \SHOP_HEALTH_FILE, true );
	}
}
