<?php

namespace Wooping\ShopHealth\Validators\Settings;

use Wooping\ShopHealth\Contracts\SettingsValidator;

/**
 * Class HasMyAccountPage
 *
 * This class extends the ShopValidator class and verifies that the My Account page is properly configured in WooCommerce settings.
 */
class HasMyAccountPage extends SettingsValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 75;

	/**
	 * Test if the WooCommerce My Account page is set.
	 *
	 * @return bool True if the My Account page is set, otherwise false.
	 */
	public function passes(): bool {
		$page_id = \wc_get_page_id( 'myaccount' );

		// $page_url should not be the same as the home URL and the url_to_post ID should not be -1 (failed).
		return \get_permalink( $page_id ) !== \get_home_url() && $page_id !== -1;
	}

	/**
	 * Provides a message if the My Account page is not set.
	 *
	 * @return string The message advising to set the My Account page.
	 */
	public function message(): string {
		return \__( 'The WooCommerce My Account page is not set. Please configure this in WooCommerce settings.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Accounts helps you achieve more loyal customers.', 'wooping-shop-health' );
	}
}
