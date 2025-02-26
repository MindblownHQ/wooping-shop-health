<?php

namespace Wooping\ShopHealth;

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;
use Wooping\ShopHealth\Cli\Clean as CleanCommands;
use Wooping\ShopHealth\Cli\Run as RunCommands;
use Wooping\ShopHealth\Cli\Schedule as ScheduleCommands;
use Wooping\ShopHealth\Models\Database\Options;
use Wooping\ShopHealth\Queue\Register as Queue;
use Wooping\ShopHealth\Rest\RefreshStats;
use Wooping\ShopHealth\Rest\ScanProgress;
use Wooping\ShopHealth\Rest\ScanShop;
use Wooping\ShopHealth\Rest\UpdateIssueStatus;
use Wooping\ShopHealth\Updates\Routines;
use Wooping\ShopHealth\Updates\Updater;
use Wooping\ShopHealth\WooCommerce\Admin\Products;
use Wooping\ShopHealth\WooCommerce\Admin\Settings;
use Wooping\ShopHealth\WooCommerce\Compatibility;
use Wooping\ShopHealth\WordPress\Assets;
use Wooping\ShopHealth\WordPress\Routes;
use Wooping\ShopHealth\WordPress\ManagePluginActions;
use Wooping\ShopHealth\WordPress\Notices;
use WP_CLI;

/**
 * Plugin God class.
 */
class Plugin {

	/**
	 * Call all classes needed for the custom functionality.
	 */
	public function init(): void {

		// Check if updates should be run
		if ( \version_compare( Options::get( 'version' ), \SHOP_HEALTH_VERSION, '<' ) ) {
			( new Routines() )->run_updates();
		}

		// General WordPress hooks.
		( new Routes() )->register_hooks();
		( new Assets() )->register_hooks();
		( new ManagePluginActions() )->register_hooks();
		( new Notices() )->register_hooks();
		( new Queue() )->register_hooks();

		// Rest Endpoints.
		( new UpdateIssueStatus() )->register_hooks();
		( new ScanShop() )->register_hooks();
		( new ScanProgress() )->register_hooks();
		( new RefreshStats() )->register_hooks();

		// WooCommerce hooks.
		( new Compatibility() )->register_hooks();
		( new Products() )->register_hooks();
		( new Settings() )->register_hooks();

		// ShopHealth hooks.
		( new Updater() )->register_hooks();
		( new Options() )->version_number();

		// If we're in WP CLI mode, enable commands.
		if ( \defined( 'WP_CLI' ) && \WP_CLI ) {
			WP_CLI::add_command( 'shop-health schedule', ScheduleCommands::class );
			WP_CLI::add_command( 'shop-health run', RunCommands::class );
			WP_CLI::add_command( 'shop-health clean', CleanCommands::class );
		}
	}

	/**
	 * Boot the plugin
	 *
	 * @return void
	 */
	public function boot(): void {
		$capsule = new Capsule();

		// Retrieve the default port
		$default_port = \ini_get( 'mysqli.default_port' );

		// Overwrite default port when it's set in DB_HOST
		if ( \strpos( \DB_HOST, ':' ) !== false ) {
			[ , $default_port ] = \explode( ':', \DB_HOST );
		}

		$capsule->addConnection(
			[
				'driver'    => 'mysql',
				'host'      => \DB_HOST,
				'port'      => $default_port,
				'database'  => \DB_NAME,
				'username'  => \DB_USER,
				'password'  => \DB_PASSWORD,
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
			]
		);

		// Make this Capsule instance available globally via static methods... (optional).
		$capsule->setAsGlobal();

		// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher()).
		$capsule->bootEloquent();
	}
}
