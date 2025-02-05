<?php

namespace Wooping\ShopHealth\Updates;

use Wooping\ShopHealth\Queue\Register as Queue;
use Wooping\ShopHealth\Models\Database\Options;
use Wooping\ShopHealth\Models\Database\Migrations;

/**
 * Uninstaller class. Runs when this plugin gets deactivated
 */
class Uninstaller{
	
	/**
	 * The invoke method gets called by Conductor after deactivation.
	 */
	public function __invoke(): void {
		// Remove all scheduled tasks.
		( new Queue() )->clean();

		// Roll back our migrations.
		( new Migrations() )->roll_back();

		// Clean up the Wooping options.
		( new Options() )->clean_up();
	}
}
