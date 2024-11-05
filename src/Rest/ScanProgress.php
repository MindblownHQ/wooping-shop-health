<?php

namespace Wooping\ShopHealth\Rest;

use Wooping\ShopHealth\Contracts\Rest;
use Wooping\ShopHealth\Controllers\Cron;

/**
 * Class ScanShop
 *
 * This class adds a REST endpoint for scheduling a shop scan
 */
class ScanProgress extends Rest {

	/**
	 * Register endpoints
	 *
	 * @return void
	 */
	public function register_endpoints(): void {
		\register_rest_route(
			self::NAMESPACE,
			'/scan/progress',
			[
				'methods'             => 'GET',
				'callback'            => [ Cron::class, 'progress' ],
				'permission_callback' => [ $this, 'has_permission' ],
			]
		);
	}

	/**
	 * Has Permission
	 */
	public function has_permission(): bool {
		return true;
	}
}
