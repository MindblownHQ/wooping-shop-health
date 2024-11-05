<?php

namespace Wooping\ShopHealth\Validators\Settings;

use Wooping\ShopHealth\Contracts\SettingsValidator;

/**
 * Class HasTermsAndConditions
 *
 * This class extends the ShopValidator class to ensure that the terms and conditions page is configured in WooCommerce settings.
 */
class HasTermsAndConditions extends SettingsValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 95;

	/**
	 * Test if the terms and conditions page is set in WooCommerce.
	 *
	 * @return bool True if the terms and conditions page is set, otherwise false.
	 */
	public function passes(): bool {
		$page_id = \wc_terms_and_conditions_page_id();
		return \is_int( $page_id ) && $page_id > 0;
	}

	/**
	 * Provides a message if the terms and conditions page is not set.
	 *
	 * @return string The message advising to set the terms and conditions page.
	 */
	public function message(): string {
		return \__( 'The terms and conditions page is not set. Please configure this in WooCommerce settings.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Make sure to add the terms and conditions to your webshop.', 'wooping-shop-health' );
	}
}
