<?php

namespace Wooping\ShopHealth\Rest;

use Wooping\ShopHealth\Contracts\Rest;
use Wooping\ShopHealth\Controllers\Cron;
use WP_REST_Response;

/**
 * Class ScanShop
 *
 * This class adds a REST endpoint for scheduling a shop scan
 */
class ScanShop extends Rest {

	/**
	 * Register endpoints
	 *
	 * @return void
	 */
	public function register_endpoints(): void {
		\register_rest_route(
			self::NAMESPACE,
			'/scan/all',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'scan_shop' ],
				'permission_callback' => [ $this, 'has_permission' ],
			]
		);
	}

	/**
	 * Trigger a Shop Scan
	 *
	 * @return WP_REST_Response
	 */
	public function scan_shop(): WP_REST_Response {

		$cron = new Cron();

		$cron->schedule_all_product_scans();
		$cron->schedule_all_setting_scans();

		return new WP_REST_Response(
			[
				'status'  => 'success',
				'message' => \__( 'The scan started and will be processed in the background!', 'wooping-shop-health' ),
			],
			200
		);
	}
}
