<?php

namespace Wooping\ShopHealth\Validators\Settings;

use Wooping\ShopHealth\Contracts\SettingsValidator;

/**
 * Class HasWPCron
 *
 * This class extends the ShopValidator class to check if WordPress is using the default WP Cron system
 * or if it has been properly disabled in favor of a server-based crontab.
 */
class HasWPCron extends SettingsValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 75;

	/**
	 * Check if WP Cron is disabled via wp-config.php constant.
	 *
	 * @return bool True if WP Cron is disabled, false if it's still enabled
	 */
	public function passes(): bool {
		return ! ( ! \defined( 'DISABLE_WP_CRON' ) || \DISABLE_WP_CRON === false );
	}

	/**
	 * Provides a message if WP Cron is still enabled.
	 *
	 * @return string The message advising to disable WP Cron and use server cronjob instead.
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
