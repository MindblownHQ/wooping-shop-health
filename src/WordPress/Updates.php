<?php

namespace Wooping\ShopHealth\WordPress;

use Throwable;
use Wooping\ShopHealth\Contracts\Interfaces\Hookable;
use WP_Upgrader;
use Wooping\ShopHealth\Models\Database\Options;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Class Updates
 *
 * This class handles updates for this plugin.
 */
class Updates implements Hookable {

	/**
	 * The url constants with which we communicate to the world outside.
	 */
	protected const UPDATE_URL   = 'https://updates.wooping.io/wooping-shop-health';
	protected const FEEDBACK_URL = 'https://wooping.io/wp-json/wooping/v1/';

	/**
	 * Register hooks for enqueueing scripts and styles
	 *
	 * @return void
	 */
	public function register_hooks(): void {

		// Register git updater, if it exists.
		if ( \class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {

			try {
				PucFactory::buildUpdateChecker(
					static::UPDATE_URL,
					\SHOP_HEALTH_FILE,
					'shop-health'
				);

			} catch ( Throwable $error ) {
				// Do nothing. Just no new updates found.
			}
		}

		// Send data after plugin update.
		\add_action( 'upgrader_process_complete', [ $this, 'plugin_updated' ], 10, 2 );

		// Update any option-table values after our update is done.
		\add_action( 'upgrader_process_complete', [ $this, 'update_options' ], 100, 2 );
	}

	/**
	 * Send activation data back to Wooping
	 */
	public function plugin_activated(): void {

		$url      = self::FEEDBACK_URL . 'plugin/activated';
		$response = \wp_remote_post( $url, $this->get_plugin_info() );
	}

	/**
	 * Send deactivation data back to Wooping
	 */
	public function plugin_deactivated(): void {

		$url      = self::FEEDBACK_URL . 'plugin/deactivated';
		$response = \wp_remote_post( $url, $this->get_plugin_info() );
	}

	/**
	 * Send update data back to Wooping
	 *
	 * @param WP_Upgrader   $wp_upgrader Upgrader class.
	 * @param array<string> $options     Array with upgrader options (see https://developer.wordpress.org/reference/hooks/upgrader_process_complete/).
	 */
	public function plugin_updated( WP_Upgrader $wp_upgrader, array $options ): void {

		$url = self::FEEDBACK_URL . 'plugin/updated';
		// Check if we're updating plugins.
		if ( $this->verify_update_is_ours( $options ) ) {
			\wp_remote_post( $url, $this->get_plugin_info() );
		}
	}

	/**
	 * Returns an array of simple plugin information
	 */
	public function get_plugin_info(): array {
		return [
			'body' => [
				'plugin'    => 'shop-health',
				'version'   => \SHOP_HEALTH_VERSION,
				'timestamp' => \time(),
				'site_url'  => \get_site_url(),
			],
		];
	}

	/**
	 * Check if we need to update the option-table values
	 *
	 * @param WP_Upgrader   $wp_upgrader Upgrader class.
	 * @param array<string> $options     Array with upgrader options (see https://developer.wordpress.org/reference/hooks/upgrader_process_complete/).
	 */
	public function update_options( WP_Upgrader $wp_upgrader, array $options ): void {

		// Check if we're updating plugins.
		if ( $this->verify_update_is_ours( $options ) ) {
			// Schedule the max-score calculation.
			\as_enqueue_async_action( 'woop_calculate_max_scores', [], '', true );
		
			// Re-save the statistics.
			( new Options() )->save_statistics();

      return;

    }
	}

	/**
	 * Check if an update being run belongs to us
	 */
	public function verify_update_is_ours( array $options ): bool {
		if ( $options['action'] === 'update' && $options['type'] === 'plugin' ) {
			// loop through the plugins that we're updating.
			foreach ( $options['plugins'] as $plugin ) {
				// check if we're updating this plugin.
				if ( $plugin === \plugin_basename( \SHOP_HEALTH_FILE ) ) {
					return true;
				}
			}
		}

		return false;
	}
}
