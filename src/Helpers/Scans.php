<?php
namespace Wooping\ShopHealth\Helpers;

use ActionScheduler_Store;
use WC_Product_Query;

/**
 * Helper class for all scan data
 */
class Scans {

	/**
	 * Returns an array of the total open jobs (batches and products)
	 *
	 * @return array
	 */
	public static function get_open_in_queue(): array {

		// see if we have batches to query.
		$batches = \as_get_scheduled_actions(
			[
				'hook'          => 'woop_batch_scan_products',
				'per_page'      => -1,
				'status'        => ActionScheduler_Store::STATUS_PENDING,
			]
		);

		// form the response object.
		$response = [
			'batches'  => \count( $batches ),
			'products' => 0, // default to zero.
		];

		// only check out products if the batches are empty.
		if ( \count( $batches ) === 0 ) {
			$product_scans = \as_get_scheduled_actions(
				[
					'hook'          => 'woop_scan_product',
					'per_page'      => -1,
					'status'        => ActionScheduler_Store::STATUS_PENDING,
				]
			);

			$response['products'] = \count( $product_scans );
		}

		return $response;
	}

	/**
	 * Returns the total of relevant pending jobs in queue
	 *
	 * @return int
	 */
	public static function get_total_pending_jobs_in_queue(): int {
		$jobs  = static::get_open_in_queue();
		$total = 0;
		foreach ( $jobs as $job ) {
			$total += $job;
		}

		return $total;
	}

	/**
	 * Return the total number of products
	 *
	 * @return int
	 */
	public static function get_total_product_amount(): int {

		$products = ( new WC_Product_Query(
			[
				'limit'  => -1,
				'return' => 'ids',
			]
		) )->get_products();

		if ( ! \is_array( $products ) ) {
			return 0;
		}

		return \count( $products );
	}
}
