<?php

namespace Wooping\ShopHealth\Helpers;

use Carbon\Carbon;

/**
 * Helper to generate the statistics
 */
class Statistics {

	/**
	 * Get all statistics in an array
	 *
	 * @return array <int|float>
	 */
	public function get( ?Carbon $from = null, ?Carbon $to = null ): array {

		// Set defaults for our carbon objects.
		if ( \is_null( $from ) ) {
			$from = Carbon::now()->subMonth();
		}

		if ( \is_null( $to ) ) {
			$to = Carbon::now();
		}

		// Set default response.
		$response = $this->get_default_response_array();

		// Check if HPOS table wc_orders exists.
		if ( ! $this->table_exists( 'wc_orders' ) ) {
			return $response;
		}

		// Set base response.
		$data                        = $this->get_customers_and_revenue( $from->format( 'Y-m-d' ), $to->format( 'Y-m-d' ) );
		$data['returning_customers'] = $this->get_returning_customer_count( $from->format( 'Y-m-d' ), $to->format( 'Y-m-d' ) );

		// Change date to last period.
		$from = $from->subMonth();
		$to   = $to->subMonth();

		// Get last period results and add them to the response.
		$prev                             = $this->get_customers_and_revenue( $from->format( 'Y-m-d' ), $to->format( 'Y-m-d' ) );
		$data['prev_avg_revenue']         = $prev['avg_revenue'];
		$data['prev_customers']           = $prev['customers'];
		$data['prev_returning_customers'] = $this->get_returning_customer_count( $from->format( 'Y-m-d' ), $to->format( 'Y-m-d' ) );

		$returning = $this->calculate_differences( $data['prev_returning_customers'], $data['returning_customers'] );
		$revenue   = $this->calculate_differences( $data['prev_avg_revenue'], $data['avg_revenue'] );
		$customers = $this->calculate_differences( $data['prev_customers'], $data['customers'] );

		$response = [
			'returning' => [
				'percentage' => $returning['percentage'],
				'label'      => 'Returning customers',
				'text'       => ( ( $returning['diff'] > 0 ) ? '+' : '' ) . $returning['diff'],
				'addendum'   => ( $returning['diff'] >= 0 ) ? \__( 'increase', 'wooping-shop-health' ) : \__( 'decrease', 'wooping-shop-health' ),
				'diff'       => $returning['diff'],
				'total'      => $data['returning_customers'],
				'id'         => 'returning',
			],
			'revenue'   => [
				'percentage' => $revenue['percentage'],
				'text'       => ( ( $revenue['diff'] > 0 ) ? '+' : '' ) . $revenue['diff'],
				'label'      => 'Order value',
				'addendum'   => ( $revenue['diff'] >= 0 ) ? \__( 'increase', 'wooping-shop-health' ) : \__( 'decrease', 'wooping-shop-health' ),
				'diff'       => $revenue['diff'],
				'total'      => $data['avg_revenue'],
				'id'         => 'revenue',
			],
			'customers' => [
				'percentage' => $customers['percentage'],
				'text'       => ( ( $customers['diff'] > 0 ) ? '+' : '' ) . $customers['diff'],
				'label'      => 'New customers',
				'addendum'   => ( $customers['diff'] >= 0 ) ? \__( 'increase', 'wooping-shop-health' ) : \__( 'decrease', 'wooping-shop-health' ),
				'diff'       => $customers['diff'],
				'total'      => $data['customers'],
				'id'         => 'customers',
			],
		];

		// Return all results.
		return $response;
	}

	/**
	 * Get the amount of customers returning in a period
	 */
	protected function get_returning_customer_count( string $from, string $to ): int {

		global $wpdb;

		$table = $wpdb->prefix . 'wc_orders';

		// phpcs:disable
		$sql = $wpdb->prepare(
			"
            SELECT COUNT(DISTINCT(billing_email)) AS returning_customers
            FROM `%1\$s`
            WHERE date_created_gmt < '%2\$s'  
            AND billing_email IN (
                SELECT billing_email FROM `%3\$s`
                WHERE ( date_created_gmt > '%4\$s' AND date_created_gmt < '%5\$s' ) )
        ",
			$table,
			$from,
			$table,
			$from,
			$to
		);

		// No caching yet. Also this is prepared.
		$result = $wpdb->get_row( $sql );

		// phpcs:enable

		return \intval( $result->returning_customers );
	}

	/**
	 * Get the average revenue and customers based on the period given
	 *
	 * @return array <int>
	 */
	protected function get_customers_and_revenue( string $from, string $to ): array {

		global $wpdb;

		$table = $wpdb->prefix . 'wc_orders';

		// phpcs:disable
		$sql = $wpdb->prepare(
			"
            SELECT COUNT(DISTINCT(billing_email)) AS customers, 
            CASE WHEN AVG(total_amount) IS NULL THEN 0.0 ELSE AVG(total_amount) END AS avg_revenue 
            FROM `%1\$s` 
            WHERE ( date_created_gmt > '%2\$s'  AND date_created_gmt < '%3\$s' )
        ",
			$table,
			$from,
			$to
		);

		// This is prepared, you're just not looking at the whole picture, dumb computer.
		$response = $wpdb->get_row( $sql, \ARRAY_A );

		// phpcs:enable

		return $response;
	}

	/**
	 * Get the average revenue and customers based on the period given
	 *
	 * @return bool
	 */
	protected function table_exists( string $table_name ) {
		global $wpdb;

		$table = $wpdb->prefix . $table_name;

		// phpcs:ignore
		$table_check = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) );

		// phpcs:ignore
		if ( $wpdb->get_var( $table_check ) == $table ) {
			return true;
		}
		return false;
	}

	/**
	 * calculate diff in number and percentage
	 *
	 * @return array <int|float>
	 */
	protected function calculate_differences( float $old_value, float $new_value ) {
		$percentage = -100;
		$diff       = \round( ( $new_value - $old_value ), 2 );

		if ( $old_value <= 0 ) {
			$percentage = 100;
		}elseif ( $diff == 0 ) {
			$percentage = 0;
		}else {
			$percentage = \round( ( ( $diff / $old_value ) * 100 ), 2 );
		}
		return [
			'percentage' => $percentage,
			'diff'       => $diff,
		];
	}

	/**
	 * Get default statistics array
	 *
	 * @return array <int|float>
	 */
	protected function get_default_response_array() {
		return [
			'returning' => [
				'percentage' => 0,
				'label'      => 'Returning customers',
				'text'       => '+0',
				'addendum'   => \__( 'increase', 'wooping-shop-health' ),
				'diff'       => 0,
				'total'      => 0,
				'id'         => 'returning',
			],
			'revenue'   => [
				'percentage' => 0,
				'text'       => '+0',
				'label'      => 'Order value',
				'addendum'   => \__( 'increase', 'wooping-shop-health' ),
				'diff'       => 0,
				'total'      => 0,
				'id'         => 'revenue',
			],
			'customers' => [
				'percentage' => 0,
				'text'       => '+0',
				'label'      => 'New customers',
				'addendum'   => \__( 'increase', 'wooping-shop-health' ),
				'diff'       => 0,
				'total'      => 0,
				'id'         => 'customers',
			],
		];
	}
}
