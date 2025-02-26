<?php

namespace Wooping\ShopHealth\Controllers;

use Wooping\ShopHealth\Contracts\Controller;
use Wooping\ShopHealth\Models\Database\Options;

/**
 * Class IssuesController
 *
 * This class is responsible for handling issue changes.
 */
class Settings extends Controller {

	/**
	 * Renders the settings-page view
	 */
	public function display(): void {

		// set the base variables.
		$settings = [];
		$keys     = [ 'ignored_validators' ];
		$defaults = $this->get_default_settings();

		// loop through each of the keys and query the option associated with them.
		foreach ( $keys as $key ) {

			// also, grab the default out of the defaults array if nothing is set.
			$settings[ $key ] = \get_option( 'wooping_shop_health_' . $key, $defaults[ $key ] );
		}

		// render the view.
		\woop_view( 'settings-page', \compact( 'settings' ) )->render();
	}

	/**
	 * Update settings
	 */
	public function update(): void {

		if ( isset( $_POST['ignored_validators'] ) && \is_array( $_POST['ignored_validators'] ) ) { // phpcs:ignore

			// get the old ignored validators.
			$old_ignored_validators = Options::get( 'ignored_validators' ) ?? [];

			// sanitize value. ignored_validators are sanitized, but as text.
			$validators = [];
			foreach ( $_POST['ignored_validators'] as $validator ) { // phpcs:ignore 
				$validators[] = \sanitize_text_field( $validator );
			}

			// update the option.
			Options::set( 'ignored_validators', $validators );

			// and trigger a bulk-ignore check.
			\as_enqueue_async_action(
				'wooping/shop-health/check_bulk_ignored_validators',
				[
					'old' => $old_ignored_validators,
					'new' => $validators,
				],
				'',
				true
			);

		}else {
			// update the option with an empty array as default.
			Options::set( 'ignored_validators', [] );
		}

		// return back to the settings page.
		\wp_safe_redirect( \conductor_get_route_url( 'shop_health_settings' ) );
		exit();
	}

	/**
	 * Return default settings for this plugin
	 *
	 * @return array<string> An array of default settings.
	 */
	public function get_default_settings(): array {
		return [
			'ignored_validators' => [],
		];
	}
}
