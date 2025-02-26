<?php

namespace Wooping\ShopHealth\WordPress;

use WP_Rest_Server;
use Wooping\ShopHealth\Controllers\Settings;
use Wooping\ShopHealth\Controllers\Dashboard;
use Wooping\SHopHealth\Middleware\IsAllowed;
use Wooping\SHopHealth\Middleware\ValidNonce;
use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;

class Routes implements Hookable{

	/**
	 *  Register our routes with conductor
	 *
	 * @return void
	 */
	public function register_hooks() : void {
		
		// Dashboard
		\register_conductor_route( 'shop_health', [
			'method' => WP_Rest_Server::READABLE,
			'callback' => [ Dashboard::class, 'index' ],
			'menu_label' => __( 'Shop Health', 'wooping-shop-health' ),
		]);

		// Shop Issues
		\register_conductor_route( 'shop_issues', [
			'method' => WP_Rest_Server::READABLE,
			'callback' => [ Dashboard::class, 'shop' ],
		]);

		// Product Issues
		\register_conductor_route( 'product_issues', [
			'method' => WP_Rest_Server::READABLE,
			'callback' => [ Dashboard::class, 'products' ],
		]);

		// Register settings page
		conductor()->settings()->register_page( 'shop_health', [
			'label' 			=> __( 'Shop Health', 'wooping-shop-health' ),
			'callback'		 	=> [ Settings::class, 'display' ],
			'middleware' 		=> [ IsAllowed::class, ValidNonce::class ]  
		]);
	}
}
