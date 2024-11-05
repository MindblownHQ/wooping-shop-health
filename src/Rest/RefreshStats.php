<?php

namespace Wooping\ShopHealth\Rest;

use Wooping\ShopHealth\Contracts\Rest;
use Wooping\ShopHealth\Models\Database\Options;
use WP_REST_Response;

/**
 * Class RefreshStats
 *
 * This class adds a REST endpoint for refreshing the statistics
 */
class RefreshStats extends Rest {

	/**
	 * Register endpoints
	 *
	 * @return void
	 */
	public function register_endpoints(): void {
		\register_rest_route(
			self::NAMESPACE,
			'/stats/refresh',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'refresh_stats' ],
				'permission_callback' => [ $this, 'has_permission' ],
			]
		);
	}

	/**
	 * Trigger a Shop Scan
	 *
	 * @return WP_REST_Response
	 */
	public function refresh_stats(): WP_REST_Response {

		$stats = ( new Options() )->save_statistics();

		return new WP_REST_Response(
			[
				'status'  => 'success',
				'message' => \__( 'The statistics are being refreshed.', 'wooping-shop-health' ),
				'data'    => $stats,
			],
			200
		);
	}
}
