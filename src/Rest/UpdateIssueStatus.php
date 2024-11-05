<?php

namespace Wooping\ShopHealth\Rest;

use Wooping\ShopHealth\Contracts\Rest;
use Wooping\ShopHealth\Controllers\Issues;

/**
 * Class IgnoreIssue
 *
 * This class adds a REST endpoint for ignoring issues.
 */
class UpdateIssueStatus extends Rest {

	/**
	 * Register endpoints
	 *
	 * @return void
	 */
	public function register_endpoints(): void {
		\register_rest_route(
			self::NAMESPACE,
			'/issue/update/(?P<id>\d+)',
			[
				'methods'             => 'POST',
				'callback'            => [ Issues::class, 'set_status' ],
				'permission_callback' => [ $this, 'has_permission' ],
			]
		);
	}
}
