<?php

namespace Wooping\ShopHealth\WordPress;

use Wooping\ShopHealth\Contracts\Interfaces\Hookable;

/**
 * Class Admin
 *
 * Handles Admin logic
 *
 * Adds a dashboard link to plugin action links
 */
class ManagePluginActions implements Hookable {

	/**
	 * Registers the hooks for the menu items in the admin menu.
	 */
	public function register_hooks(): void {
		
		// Add settings link.
		\add_action( 'plugin_action_links_' . \plugin_basename( \SHOP_HEALTH_FILE ), [ $this, 'add_settings_link' ], 100 );

		// @temp Remove support for network activation 
		\add_filter( 'all_plugins', [ $this, 'remove_plugin_in_network_context' ]);
	}

	/**
	 * Add a Dashboard link to the plugin action links
	 *
	 * @param array<string> $links An array of existing links.
	 *
	 * @return array<string> $links An updated array of links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = \sprintf( '<a href="%1$s">%2$s</a>', \woop_get_route( 'dashboard' ), \__( 'Dashboard', 'wooping-shop-health' ) );
		\array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Remove Wooping Shop Health on the network plugins page
	 * @temporary fix until there's full multisite support.
	 * 
	 */
	public function remove_plugin_in_network_context( array $plugins ): array {
		global $current_screen;

		if( $current_screen->is_network ){
			unset( $plugins[ 'wooping-shop-health/shop-health.php' ] );
		}
		
		return $plugins;

	}
}
