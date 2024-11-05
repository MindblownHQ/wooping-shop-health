<?php

namespace Wooping\ShopHealth\Controllers\Dashboard;

use Automattic\WooCommerce\Utilities\OrderUtil;
use Wooping\ShopHealth\Contracts\Controller;
use Wooping\ShopHealth\Helpers\Scans;
use Wooping\ShopHealth\Helpers\ScoreCalculator;
use Wooping\ShopHealth\Models\Issue;

/**
 * Class DashboardController
 *
 * This class is responsible for rendering the dashboard page.
 */
class Dashboard extends Controller {

	/**
	 * Renders and displays the dashboard page view.
	 *
	 * This method is responsible for rendering and displaying the dashboard page view.
	 * It uses the `\woop_view` function to render the 'dashboard-page' view with an empty data array,
	 * and then calls the `render` method on the rendered view to display it.
	 *
	 * @return void
	 */
	public function display(): void {

		$score_calculator = new ScoreCalculator();
		$settings         = $score_calculator->settings();
		$products         = $score_calculator->global_product();
		$has_hpos         = OrderUtil::custom_orders_table_usage_is_enabled();

		$shop_issues = Issue::with( 'scanned_object' )
				->whereNotIn( 'status', Issue::FINAL_STATUSES )
				->whereHas(
					'scanned_object',
					static function ( $query ) {
									$query->where( 'object_type', 'setting' );
					}
				)
							->where( 'severity', '>=', 0 )
							->get();

		$product_issues = Issue::with( 'scanned_object' )
				->whereNotIn( 'status', Issue::FINAL_STATUSES )
				->whereHas(
					'scanned_object',
					static function ( $query ) {
										$query->where( 'object_type', 'product' );
					}
				);

		$all_product_issues = $product_issues->get();

		$pressing_product_issues = $product_issues
				->orderByDesc( 'severity' )
				->orderBy( 'created_at' )
				->limit( 5 )
				->get();

		// @todo add pressing issues
		$data = [
			'shop_score'              => $settings,
			'product_score'           => $products,
			'shop_issues'             => $shop_issues,
			'product_issues'          => $all_product_issues,
			'pressing_product_issues' => $pressing_product_issues,
			'stats'                   => \get_option( 'wooping_shop_health_statistics' ),
			'has_hpos'                => $has_hpos,
			'last_scan'				  => Scans::get_last_scan(),
			'scans_in_progress'       => Scans::get_total_pending_jobs_in_queue(),
		];

		// render the view.
		\woop_view( 'dashboard-page', $data )->render();
	}
}
