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

		// set defaults for our carbon objects.
		if ( \is_null( $from ) ) {
			$from = Carbon::now()->subMonth();
		}

		if ( \is_null( $to ) ) {
			$to = Carbon::now();
		}

		// set default response.
		$response = $this->get_default_response_array();

		//check if HPOS table wc_orders exists:
		if ( $this->table_exists( 'wc_orders' ) ) {

			// set base response.
			$data                        = $this->get_customers_and_revenue( $from->format( 'Y-m-d' ), $to->format( 'Y-m-d' ) );
			$data['returning_customers'] = $this->get_returning_customer_count( $from->format( 'Y-m-d' ), $to->format( 'Y-m-d' ) );

			// change date to last period.
			$from = $from->subMonth();
			$to   = $to->subMonth();

			// get last period results and add them to the response.
			$prev                             = $this->get_customers_and_revenue( $from->format( 'Y-m-d' ), $to->format( 'Y-m-d' ) );
			$data['prev_avg_revenue']         = $prev['avg_revenue'];
			$data['prev_customers']           = $prev['customers'];
			$data['prev_returning_customers'] = $this->get_returning_customer_count( $from->format( 'Y-m-d' ), $to->format( 'Y-m-d' ) );

			$returning = [
				'percentage' => \ceil( ( $data['returning_customers'] > 0 && $data['prev_returning_customers'] > 0 ) ? ( ( $data['returning_customers'] / $data['prev_returning_customers'] ) * 100 ) : 100 ),
				'diff'       => \round( ( $data['returning_customers'] - $data['prev_returning_customers'] ), 2 ),
			];

			$revenue = [
				'percentage' => \ceil( ( $data['avg_revenue'] > 0 && $data['prev_avg_revenue'] > 0 ) ? ( ( $data['avg_revenue'] / $data['prev_avg_revenue'] ) * 100 ) : 100 ),
				'diff'       => \round( ( $data['avg_revenue'] - $data['prev_avg_revenue'] ), 2 ),
			];

			$customers = [
				'percentage' => \ceil( ( $data['customers'] > 0 && $data['prev_customers'] > 0 ) ? ( ( $data['customers'] / $data['prev_customers'] ) * 100 ) : 100 ),
				'diff'       => \round( ( $data['customers'] - $data['prev_customers'] ), 2 ),
			];

			$response = [
				'returning' => [
					'percentage' => ( ( $returning['diff'] <= 0 ) ? $returning['percentage'] - 100 : $returning['percentage'] ),
					'label'      => 'Returning customers',
					'text'       => ( ( $returning['diff'] > 0 ) ? '+' : '' ) . $returning['diff'],
					'addendum'   => ( $returning['diff'] >= 0 ) ? \__( 'increase', 'wooping-shop-health' ) : \__( 'decrease', 'wooping-shop-health' ),
					'diff'       => $returning['diff'],
					'id'         => 'returning',
				],
				'revenue'   => [
					'percentage' => ( ( $revenue['diff'] <= 0 ) ? $revenue['percentage'] - 100 : $revenue['percentage'] ),
					'text'       => ( ( $revenue['diff'] > 0 ) ? '+' : '' ) . $revenue['diff'],
					'label'      => 'Order value',
					'addendum'   => ( $revenue['diff'] >= 0 ) ? \__( 'increase', 'wooping-shop-health' ) : \__( 'decrease', 'wooping-shop-health' ),
					'diff'       => $revenue['diff'],
					'id'         => 'revenue',
				],
				'customers' => [
					'percentage' => ( ( $customers['diff'] <= 0 ) ? $customers['percentage'] - 100 : $customers['percentage'] ),
					'text'       => ( ( $customers['diff'] > 0 ) ? '+' : '' ) . $customers['diff'],
					'label'      => 'New customers',
					'addendum'   => ( $customers['diff'] >= 0 ) ? \__( 'increase', 'wooping-shop-health' ) : \__( 'decrease', 'wooping-shop-health' ),
					'diff'       => $customers['diff'],
					'id'         => 'customers',
				],
			];
		} 

		// return all results.
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
				'id'         => 'returning',
			],
			'revenue'   => [
				'percentage' => 0,
				'text'       => '+0',
				'label'      => 'Order value',
				'addendum'   => \__( 'increase', 'wooping-shop-health' ),
				'diff'       => 0,
				'id'         => 'revenue',
			],
			'customers' => [
				'percentage' => 0,
				'text'       => '+0',
				'label'      => 'New customers',
				'addendum'   => \__( 'increase', 'wooping-shop-health' ),
				'diff'       => 0,
				'id'         => 'customers',
			]
		];
	}
}
