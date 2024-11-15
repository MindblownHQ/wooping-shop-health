<?php

namespace Wooping\ShopHealth\Validators\Settings;

use Wooping\ShopHealth\Contracts\SettingsValidator;

/**
 * Class HasWPCron
 *
 * This class extends the ShopValidator class to ensure that the site isn't using WP Cron but a server-based crontab.
 */
class HasWPCron extends SettingsValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 75;

	/**
	 * Test if the terms and conditions page is set in WooCommerce.
	 *
	 * @return bool True if the terms and conditions page is set, otherwise false.
	 */
	public function passes(): bool {
		return ( !defined('DISABLE_WP_CRON') || DISABLE_WP_CRON === false );
	}

	/**
	 * Provides a message if the terms and conditions page is not set.
	 *
	 * @return string The message advising to set the terms and conditions page.
	 */
	public function message(): string {
		return \__( 'You aren\'t optimally using automated tasks. Add a server cronjob', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Add a server cronjob to do automated tasks. Learn how or ask your host.', 'wooping-shop-health' );
	}
}
