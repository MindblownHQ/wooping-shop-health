<?php

namespace Wooping\ShopHealth\Models\Database;

use Wooping\ShopHealth\Helpers\Statistics;

/**
 * Wooping Options class. Used for setting and cleaning up options set by the Wooping plugins.
 */
class Options {

	/**
	 * Save the statistics for the dashboard page
	 */
	public function save_statistics(): array {

		$stats = ( new Statistics() )->get();
		\update_option( 'wooping_shop_health_statistics', $stats );

		return $stats;
	}

	/**
	 * Clean up Wooping records in the options table
	 */
	public function clean_up(): void {
		global $wpdb;

		// Fetch all options that start with 'shop_health'.
		$options = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like( 'wooping_shop_health' ) . '%'
			)
		);

		// Loop through and delete each option.
		foreach ( $options as $option ) {
			\delete_option( $option );
		}
	}
}
