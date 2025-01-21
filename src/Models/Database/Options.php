<?php

namespace Wooping\ShopHealth\Models\Database;

use Wooping\ShopHealth\Helpers\Statistics;

/**
 * Wooping Options class. Used for setting and cleaning up options set by the Wooping plugins.
 */
class Options {

	/**
	 * The group name under which the options are stored.
	 */
	protected static string $group_name = 'wooping_shop_health';

	/**
	 * Save the statistics for the dashboard page
	 */
	public function save_statistics(): array {

		$stats = ( new Statistics() )->get();
		self::set( 'statistics', $stats );

		return $stats;
	}

	/**
	 * Save a timestamp for when the queue was last updated with issues
	 */
	public function set_queue_timestamp(): void {

		\update_option( 'wooping_shop_health_scan_last_triggered', \time() );
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

	/**
	 * A temporary method to save a version number.
	 * We will need this for future update routines.
	 *
	 * Should be replaced when a decent Option class and update routine are in place.
	 *
	 * @since 1.2
	 */
	public function version_number() {
		$options = \get_option( self::$group_name );

		if( ! isset( $options['version'] ) ) {
			$options['version'] = SHOP_HEALTH_VERSION;
			\update_option( self::$group_name, $options );
		}

		return $options['version'];
	}

	public static function get( $key ) {
		$options = get_option( self::$group_name );

		return $options[ $key ] ?? false;
	}

	public static function set( $key, $value ) {
		$options = get_option( self::$group_name );

		$options[ $key ] = $value;

		return \update_option( self::$group_name, $options );
	}
}
