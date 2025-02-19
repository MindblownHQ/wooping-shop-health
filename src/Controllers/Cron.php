<?php

namespace Wooping\ShopHealth\Controllers;

use Wooping\ShopHealth\Contracts\Controller;
use Wooping\ShopHealth\Helpers\Scans;
use Wooping\ShopHealth\Models\Database\Options;
use Wooping\ShopHealth\Queue\BatchScanProducts;
use Wooping\ShopHealth\Queue\ScanProduct;
use Wooping\ShopHealth\Queue\ScanSetting;
use Wooping\ShopHealth\Validators\SettingContainer;
use WP_REST_Response;

/**
 * Class CronController
 *
 *  Controller for triggering anything scheduled.
 */
class Cron extends Controller {

	/**
	 * Schedule absolutely every scan we have.
	 */
	public function schedule_every_scan(): void {
		$this->schedule_all_product_scans();
		$this->schedule_all_setting_scans();

		// if this request came from admin, redirect back to the dashboard.
		if ( \is_admin() ) {
			\wp_safe_redirect( \woop_get_route( 'dashboard' ) );
			exit();
		}
	}

	/**
	 * Schedule a scan for all available products.
	 */
	public function schedule_all_product_scans(): void {

		// add timestamp here, so our progress bar can query on a certain timestamp.
		( new Options() )->set_queue_timestamp();

		// Remove any of the old product scans in the old queue.
		\as_unschedule_all_actions( 'wooping/shop-health/product/batch_scan' );
		\as_unschedule_all_actions( 'wooping/shop-health/product/scan' );

		// query all products.
		$product_amount = Scans::get_total_product_amount();

		// get the amount of batches.
		$batch_size = 1000;
		$batches    = ( $product_amount / $batch_size );

		// loop through all batches and schedule them.
		for ( $page = 0; $page < $batches; $page++ ) {
			( new BatchScanProducts() )->schedule( $page, $batch_size );
		}
	}

	/**
	 * Schedule a scan for a single product.
	 * The function is static because the save_post_product in WooCommerce/Admin.php requires it to be.
	 */
	public static function schedule_product_scan( int $product_id ): void {

		( new ScanProduct() )->schedule( $product_id );
	}

	/**
	 * Schedule a full settings-scan.
	 */
	public function schedule_all_setting_scans(): void {

		$validators = ( new SettingContainer() )->validators();

		foreach ( $validators as $slug => $class ) {
			$this->schedule_setting_scan( $slug );
		}
	}

	/**
	 * Schedule a single setting scan.
	 */
	public function schedule_setting_scan( string $slug ): void {
		( new ScanSetting() )->schedule( $slug );
	}

	/**
	 * Run a scan for a single product.
	 * The function is static because the save_post_product in WooCommerce/Admin.php requires it to be.
	 */
	public static function run_product_scan( int $product_id ): void {
		( new ScanProduct() )->scan( $product_id );
	}

	/**
	 * Run a single setting scan.
	 */
	public static function run_setting_scan( string $slug ): void {
		( new ScanSetting() )->scan( $slug );
	}

	/**
	 * Returns the progress of the current active scan.
	 */
	public static function progress(): WP_REST_Response {

		// set our defaults.
		$percentage = 100;
		$message    = \__( 'Scan completed.', 'wooping-shop-health' );
		$queue      = Scans::get_open_in_queue();

		// if we have batches, we're still preparing the scans.
		if ( $queue['batches'] > 0 ) {
			$percentage = 0;
			$message    = \__( 'Preparing scans...', 'wooping-shop-health' );

			// if we have product scans...
		}elseif ( $queue['products'] > 0 ) {
			$total_products   = Scans::get_total_product_amount();
			$scanned_products = ( $total_products - $queue['products'] );
			$percentage       = ( ( $scanned_products / $total_products ) * 100 );

			$message = \sprintf(
				/* translators: %1$s is amount of  scanned products, %2$s is the amount of total products */
				\__( '%1$s out of %2$s done.', 'wooping-shop-health' ),
				$scanned_products,
				$total_products
			);
		}

		// send our findings over a WP_REST response.
		return new WP_REST_Response(
			[
				'status'     => 'success',
				'message'    => $message,
				'percentage' => (int) $percentage,
			],
			200
		);
	}
}
