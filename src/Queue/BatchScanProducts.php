<?php

namespace Wooping\ShopHealth\Queue;

use WC_Product_Query;
use Wooping\ShopHealth\Contracts\Queueable;

/**
 * Class BatchScanProducts
 *
 * Runs a query of products
 * Extends the Queueable class.
 */
class BatchScanProducts extends Queueable {

	/**
	 * Cronjob hook on which this job runs
	 */
	protected string $hook = 'woop_batch_scan_products';

	/**
	 * Schedule a task in the action scheduler to schedule a batch of products.
	 *
	 * @param int $page  The paginator index to schedule products in.
	 * @param int $limit The amount of products to query in this batch.
	 */
	public function schedule( int $page, int $limit ): void {
		\as_enqueue_async_action(
			$this->hook,
			[
				'page'  => $page,
				'limit' => $limit,
			]
		);
	}

	/**
	 * Run this queueable
	 *
	 * @param int $page  The paginator index.
	 * @param int $limit The amount of products we're supposed to query.
	 *
	 * @return void
	 */
	public function run( int $page, int $limit ): void {

		// get all products in this batch.
		$products = ( new WC_Product_Query(
			[
				'limit'  => $limit,
				'page'   => $page,
				'return' => 'ids',
			]
		) )->get_products();

		// schedule a scan for a single product.
		foreach ( $products as $product_id ) {
			( new ScanProduct() )->schedule( $product_id );
		}
	}
}
