<?php

namespace Wooping\ShopHealth\Validators\Settings;

use Wooping\ShopHealth\Contracts\SettingsValidator;

/**
 * Class HasShopPage
 *
 * This class extends the ShopValidator class and checks if the WooCommerce shop page is configured.
 */
class HasShopPage extends SettingsValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 60;

	/**
	 * Test if the WooCommerce shop page is set.
	 *
	 * @return bool True if the shop page is set, otherwise false.
	 */
	public function passes(): bool {
		$page_id = \wc_get_page_id( 'shop' );
		return \is_int( $page_id ) && $page_id > 0;
	}

	/**
	 * Provides a message if the shop page is not set.
	 *
	 * @return string The message advising to set the shop page.
	 */
	public function message(): string {
		return \__( 'The WooCommerce shop page is not set. Please configure this in WooCommerce settings.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Make sure to set your shop-/ product archive page', 'wooping-shop-health' );
	}
}
