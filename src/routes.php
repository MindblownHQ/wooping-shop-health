<?php
/**
 * Contains all routes of this plugin
 */

use Wooping\ShopHealth\Controllers\Dashboard\Dashboard;
use Wooping\ShopHealth\Controllers\Dashboard\Products;
use Wooping\ShopHealth\Controllers\Dashboard\Shop;
use Wooping\ShopHealth\Controllers\Settings;


if ( ! function_exists( 'wooping_get_routes' ) ) {

	/**
	 * Returns an array of routes for this,
	 * and possibly other Wooping plugins
	 *
	 * @return array<array|string> $routes
	 */
	function wooping_get_routes(): array {
		$routes = [
			// all routes in wp admin.
			'admin' => [
				// post routes are urls we need to process.
				'post' => [
					'save_settings' => [
						'triggers' => [ Settings::class, 'update' ],
					],
				],

				// get routes are urls that (usually) return a view.
				'get'  => [
					'shop_issues' => [
						'label'    => __( 'Shop Issues', 'wooping-shop-health' ),
						'location' => 'shop-health',
						'display'  => 'hidden',
						'triggers' => [ Shop::class, 'display' ],
					],
					'product_issues' => [
						'label'    => __( 'Product Issues', 'wooping-shop-health' ),
						'location' => 'shop-health',
						'display'  => 'hidden',
						'triggers' => [ Products::class, 'display' ],
					],
					'wooping' => [
						'label'    => __( 'Wooping', 'wooping-shop-health' ),
						'location' => 'menu',
						'triggers' => [ Dashboard::class, 'display' ],
					],
					'dashboard' => [
						'label'    => __( 'Shop Health', 'wooping-shop-health' ),
						'location' => 'menu',
						'triggers' => [ Dashboard::class, 'display' ],
					],
					'settings' => [
						'label'    => __( 'Settings', 'wooping-shop-health' ),
						'location' => 'menu',
						'triggers' => [ Settings::class, 'display' ],
					],
				],
			],
		];

		// add a filter for other plugins and return the routes.
		return apply_filters( 'wooping_routes', $routes );
	}
}
