<?php

namespace Wooping\ShopHealth\Updates;

use Carbon\Carbon;
use Wooping\ShopHealth\Models\Database\Options;
use Wooping\ShopHealth\Models\Database\Migrations;

/**
 * Installer class. Runs when this plugin gets activated.
 */
class Installer{


	/**
	 * This function gets run when the installer gets invoked by conductor.
	 */
	public function __invoke(): void {
		// Run migrations.
		( new Migrations() )->run();

		// Save a copy of the stats.
		( new Options() )->save_statistics();

		// Schedule a max_scores calculation.
		if ( \function_exists( 'as_enqueue_async_action' ) ) {
			\as_enqueue_async_action( 'wooping/shop-health/calculate_max_scores', [], '', true );
		}

		// Set activated.
		\add_option( 'shophealth_activated', Carbon::now(), '', false );
	}

}
