<?php
/**
 * Handle update routines for the plugin.
 */

namespace Wooping\ShopHealth\Updates;

use Wooping\ShopHealth\Models\Database\Options;

class Routines {

	/**
	 * An array of version numbers with their corresponding update routines.
	 * Each routine represents a method.
	 */
	protected array $update_routines = [
		'1.3.0' => '130',
	];

	/**
	 * Run defined update routines.
	 */
	public function run_updates(): void {
		foreach ( $this->update_routines as $version => $callback) {
			// Check if a update routine has a higher version number than the one currently stored in the database.
			// If so: we need to run this routine.
			if ( version_compare( Options::get( 'version' ), $version, '<' ) ) {
				call_user_func( [ $this, 'update_' . $callback ] );
			}
		}

		$old_version = Options::get( 'version' );

		// After all update routines are done, update the version in the database
		Options::set( 'version', SHOP_HEALTH_VERSION );

		$new_version = Options::get( 'version' );

		/**
		 * This action fires after the update process is completed.
		 *
		 * @var string $old_version The previous version of the plugin.
		 * @var string $new_version The new (current) version of the plugin.
		 */
		\do_action( 'wooping/shop-health/after_update_routines', $old_version, $new_version );
	}

	/**
	 * Update routine for version 1.3.0.
	 *
	 * Handles updating of prefixed options to a single option in the WordPress option table.
	 */
	protected function update_130(): void {
		$old_options = [
			'wooping_shop_health_ignored_validators',
			'wooping_shop_health_scan_last_triggered',
			'wooping_shop_health_statistics',
			'wooping_shop_health_max_scores',
		];

		foreach( $old_options as $old_option ) {
			$new_option_name = \str_replace( 'wooping_shop_health_', '', $old_option );
			$success = Options::set( $new_option_name, \get_option( $old_option, '' ) );

			// If the option was moved successfully, remove the old one.
			if( $success ) {
				\delete_option( $old_option );
			}
		}
	}
}