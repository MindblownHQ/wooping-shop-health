<?php

namespace Wooping\ShopHealth\WordPress;

use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;

/**
 * Class Assets
 *
 * This class handles the enqueueing of custom CSS and JS assets for the plugin.
 */
class Assets implements Hookable {

	/**
	 * Register hooks for enqueueing scripts and styles
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		\add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	/**
	 * Add our custom css and js
	 */
	public function enqueue( string $hook ): void {
		$current_screen = \get_current_screen();

		if ( 
			!is_conductor_route( ( $_GET['page'] ?? '' ) )
			&& $hook !== 'post.php'
			&& $hook !== 'plugins.php'
			&& $current_screen->id !== 'edit-product'
		) {
			return;
		}

		$url = \plugin_dir_url( \SHOP_HEALTH_FILE ) . 'assets/dist';

		\wp_register_script( 'wooping_shop_health_js', $url . '/js/main.js', [ 'wp-api' ], \SHOP_HEALTH_VERSION, [ 'in_footer' => true ] );
		\wp_localize_script(
			'wooping_shop_health_js',
			'shopHealth',
			[
				'currencySymbol' => \get_woocommerce_currency_symbol(),
				'api_url'        => \apply_filters( 'wooping/global/api_url', 'https://wooping.io/wp-json/wooping/v1' ),
			]
		);
		\wp_enqueue_script( 'wooping_shop_health_js' );
		\wp_enqueue_style( 'wooping_shop_health_admin_css', $url . '/css/admin.css', [], \SHOP_HEALTH_VERSION );
	}
}
