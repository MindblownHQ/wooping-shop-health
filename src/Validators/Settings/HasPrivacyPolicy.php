<?php

namespace Wooping\ShopHealth\Validators\Settings;

use Wooping\ShopHealth\Contracts\SettingsValidator;

/**
 * Class HasPrivacyPolicy
 *
 * This class extends the ShopValidator class to ensure that a privacy policy page is linked and configured in WordPress settings.
 */
class HasPrivacyPolicy extends SettingsValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 50;

	/**
	 * Test if the privacy policy page is set in WordPress settings.
	 *
	 * @return bool True if the privacy policy page is configured, otherwise false.
	 */
	public function passes(): bool {
		$page_id = \get_option( 'wp_page_for_privacy_policy' );
		return ! empty( $page_id );
	}

	/**
	 * Provides a message if the privacy policy page is not set.
	 *
	 * @return string The message advising to set the privacy policy page.
	 */
	public function message(): string {
		return \__( 'The privacy policy page is not set. Please configure this in WordPress settings under Privacy.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'A privacy policy is mandatory for a webshop.', 'wooping-shop-health' );
	}
}
