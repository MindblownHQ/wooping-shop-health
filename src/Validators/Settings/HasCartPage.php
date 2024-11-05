<?php

namespace Wooping\ShopHealth\Validators\Settings;

use Wooping\ShopHealth\Contracts\SettingsValidator;

/**
 * Class HasCartPage
 *
 * This class extends the ShopValidator class and verifies that the cart page is properly set in WooCommerce settings.
 */
class HasCartPage extends SettingsValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 90;

	/**
	 * Test if the WooCommerce cart page is set.
	 */
	public function passes(): bool {
		$page_url = \wc_get_cart_url();

		// $page_url should not be the same as the home URL and the url_to_post ID should not be 0 (failed).
		return $page_url !== \get_home_url() && \url_to_postid( $page_url ) !== 0;
	}

	/**
	 * Provides a message if the cart page is not set.
	 *
	 * @return string The message advising to set the cart page.
	 */
	public function message(): string {
		return \__( 'The WooCommerce cart page is not set. Please configure this in WooCommerce settings.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'By default, a cart-page is mandatory, if not explicitly chosen not to use one, check this setting.', 'wooping-shop-health' );
	}
}
