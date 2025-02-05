<?php
/**
 * Plugin Name: Wooping Shop Health
 * Plugin URI: https://wooping.io
 * Description: Shop Health is a free tool for Woocommerce that helps you increase your sales with actionable advice.
 * Version: 1.3.0
 * Author: Wooping
 * Author URI: https://wooping.io
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wooping-shop-health
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * 
 * WC requires at least: 8.6
 * WC tested up to: 9.4
 * 
 * GitHub Plugin URI: https://github.com/MindblownHQ/wooping-shop-health
 * Primary Branch: main
 * Release Asset: true
 */

use Wooping\ShopHealth\Plugin;
use Wooping\ShopHealth\Updates\Installer;
use Wooping\ShopHealth\Updates\Uninstaller;

define( 'SHOP_HEALTH_SLUG', 'wooping-shop-health' );
define( 'SHOP_HEALTH_FILE', __FILE__ );
define( 'SHOP_HEALTH_PATH', dirname( SHOP_HEALTH_FILE ) );
define( 'SHOP_HEALTH_VERSION', '1.3.0' );
define( 'SHOP_HEALTH_DOCUMENTATION_URL', 'https://wooping.io/docs/shop-health/' );

if ( ! file_exists( SHOP_HEALTH_PATH . '/vendor/autoload.php' ) ) {
	return;
}

require SHOP_HEALTH_PATH . '/vendor/autoload.php';

// Init conductor and register our plugin
conductor()->plugins()->register( SHOP_HEALTH_SLUG, [
	'file' 				=> SHOP_HEALTH_FILE,
	'path'				=> SHOP_HEALTH_PATH,
	'version' 			=> SHOP_HEALTH_VERSION,
	'on_activate'		=> new Installer(),
	'on_deactivate'		=> new Uninstaller(),
]);

/**
 * Bootstrap the plugin.
 */
function shop_health_plugin(): Plugin {
	static $plugin;

	if ( is_object( $plugin ) ) {
		return $plugin;
	}

	$plugin = new Plugin();

	do_action( 'wooping/shop-health/before_boot' );
	
	$plugin->boot();

	do_action( 'wooping/shop-health/after_boot' );

	$plugin->init();

	// Allow other plugins to hook into this.
	do_action( 'wooping/shop-health/ready' );

	return $plugin;
}

add_action( 'plugins_loaded', 'shop_health_plugin' );
