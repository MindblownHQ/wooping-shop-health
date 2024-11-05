<?php

namespace Wooping\ShopHealth\WordPress;

use Wooping\ShopHealth\Contracts\Interfaces\Hookable;
use Wooping\ShopHealth\Contracts\Router;
use WC_Admin_Notices;

/**
 * Class Notices
 *
 * Adds Woocommerce Admin Notices to routes
 */
class Notices extends Router implements Hookable {

	/**
	 * Registers the hooks
	 */
	public function register_hooks(): void {
		\add_action( 'admin_init', [ $this, 'add_notices' ] );
	}

	/**
	 * Add the Woocommerce Notices to our routes
	 *
	 * @return void
	 */
	public function add_notices(): void {
		$admin_routes = $this->get_routes( 'admin' );
		if ( isset( $_GET['page'] ) ) { // phpcs:ignore
			$pagename = \wp_unslash( \sanitize_text_field( $_GET['page'] ) ); // phpcs:ignore
			if ( \array_key_exists( \str_replace( 'woop_', '', $pagename ), $admin_routes['get'] ) ) {
				WC_Admin_Notices::add_notices();
			}
		}
	}
}
