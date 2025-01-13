<?php
/**
 * Handle update routines for the plugin.
 */

namespace Wooping\ShopHealth\Updates;

use Wooping\ShopHealth\Models\Database\Options;

class Updates {
	protected Options $options;

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

		// After all update routines are done, update the version in the database
		Options::set( 'version', SHOP_HEALTH_VERSION );


		// Provide the ability to hook into
		do_action( 'wooping/shop-health/after_update_routines', Options::get( 'version' ) );
	}

	protected function update_130() {
		$old_options = [
			'wooping_shop_health_ignored_validators',
			'wooping_shop_health_scan_last_triggered',
			'wooping_shop_health_statistics',
			'wooping_shop_health_max_scores',
		];

		foreach( $old_options as $old_option ) {
			$new_option_name = str_replace( 'wooping_shop_health_', '', $old_option );
			Options::set( $new_option_name, get_option( $old_option, '' ) );
		}
	}
}