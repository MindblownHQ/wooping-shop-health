<?php

namespace Wooping\ShopHealth\Validators;

/**
 * Class SettingContainer
 *
 * Holds all setting validators.
 */
class SettingContainer {

	/**
	 * Return all setting validators as an array
	 *
	 * @return array <string>
	 */
	public function validators(): array {

		return [
			'has_cart_page'             => Settings\HasCartPage::class,
			'has_checkout_page'         => Settings\HasCheckoutPage::class,
			'has_my_account_page'       => Settings\HasMyAccountPage::class,
			'has_privacy_policy'        => Settings\HasPrivacyPolicy::class,
			'has_shop_page'             => Settings\HasShopPage::class,
			'has_terms_and_conditions'  => Settings\HasTermsAndConditions::class,
			'has_wp_cron'               => Settings\HasWPCron::class,
		];
	}

	/**
	 * Return the validator class based on slug
	 */
	public function get_class( string $slug ): ?string {
		$validators = $this->validators();
		if ( isset( $validators[ $slug ] ) ) {
			return $validators[ $slug ];
		}
	}
}
