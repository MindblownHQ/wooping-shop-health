<?php

namespace Wooping\ShopHealth\Validators\Settings;

use Wooping\ShopHealth\Contracts\SettingsValidator;

/**
 * Class HasCheckoutPage
 *
 * This class extends the ShopValidator class and checks if the checkout page is properly configured in WooCommerce.
 */
class HasCheckoutPage extends SettingsValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 100;

	/**
	 * Test if the WooCommerce checkout page is set.
	 *
	 * @return bool True if the checkout page is set, otherwise false.
	 */
	public function passes(): bool {
		$page_url = \wc_get_checkout_url();

		// $page_url should not be the same as the home URL and the url_to_post ID should not be 0 (failed).
		return $page_url !== \get_home_url() && \url_to_postid( $page_url ) !== 0;
	}

	/**
	 * Provides a message if the checkout page is not set.
	 *
	 * @return string The message advising to set the checkout page.
	 */
	public function message(): string {
		return \__( 'The WooCommerce checkout page is not set. Please configure this in WooCommerce settings.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Please provide WooCommerce with the url of your checkout page.', 'wooping-shop-health' );
	}
}
