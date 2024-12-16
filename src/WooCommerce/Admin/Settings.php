<?php

namespace Wooping\ShopHealth\WooCommerce\Admin;

use Wooping\ShopHealth\Contracts\Interfaces\Hookable;
use Wooping\ShopHealth\Controllers\Cron;

/**
 * Handle all admin settings.
 */
class Settings implements Hookable {

	/**
	 * Registers WordPress action and filter hooks.
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		\add_action( 'woocommerce_update_options', [ $this, 'scan_on_setting_save' ], 10 );
	}

	/**
	 * Scan all settings when WooCommerce settings are saved.
	 */
	public function scan_on_setting_save(): void {
		( new Cron() )->schedule_all_setting_scans();
	}
}