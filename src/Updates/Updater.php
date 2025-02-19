<?php

namespace Wooping\ShopHealth\Updates;

use WP_Upgrader;
use ShopMaestro\Conductor\Contracts\Interfaces\Hookable;

/**
 * Class Updates
 *
 * This class handles what to do after a plugin update.
 */
class Updater implements Hookable {

	/**
	 * Register hooks to run after a plugin update
	 *
	 * @return void
	 */
	public function register_hooks(): void {

		// Update any option-table values after our update is done.
		\add_action( 'conductor_updated_wooping_shop_health', [ $this, 'update_options' ], 100, 2 );
	}

	
	/**
	 * Check if we need to update the option-table values
	 *
	 * @param WP_Upgrader   $wp_upgrader Upgrader class.
	 * @param array<string> $options     Array with upgrader options (see https://developer.wordpress.org/reference/hooks/upgrader_process_complete/).
	 */
	public function update_options( WP_Upgrader $wp_upgrader, array $options ): void {

		// Schedule the max-score calculation.
		\as_enqueue_async_action( 'wooping/shop-health/calculate_max_scores', [], '', true );
	
		// Re-save the statistics.
		\as_enqueue_async_action( 'wooping/shop-health/refresh_stats', [], '', true );

		// Run general migration scrips 
		\as_enqueue_async_action( 'woop_after_update', $options, '', true );

	}
}
