<?php

namespace Wooping\ShopHealth;

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;
use Wooping\ShopHealth\Cli\Clean as CleanCommands;
use Wooping\ShopHealth\Cli\Run as RunCommands;
use Wooping\ShopHealth\Cli\Schedule as ScheduleCommands;
use Wooping\ShopHealth\Models\Database\Migrations;
use Wooping\ShopHealth\Models\Database\Options;
use Wooping\ShopHealth\Queue\Register as Queue;
use Wooping\ShopHealth\Rest\RefreshStats;
use Wooping\ShopHealth\Rest\ScanProgress;
use Wooping\ShopHealth\Rest\ScanShop;
use Wooping\ShopHealth\Rest\UpdateIssueStatus;
use Wooping\ShopHealth\WooCommerce\Admin\Products;
use Wooping\ShopHealth\WooCommerce\Admin\Settings;
use Wooping\ShopHealth\WooCommerce\Compatibility;
use Wooping\ShopHealth\WordPress\Assets;
use Wooping\ShopHealth\WordPress\HandleAdminRequest;
use Wooping\ShopHealth\WordPress\ManagePluginActions;
use Wooping\ShopHealth\WordPress\Notices;
use Wooping\ShopHealth\WordPress\RegisterPages;
use Wooping\ShopHealth\WordPress\Updates;
use WP_CLI;

/**
 * Plugin God class.
 */
class Plugin {

	/**
	 * Runs when the plugin is first activated.
	 *
	 * @return void
	 */
	public function install(): void {

		// Run migrations.
		( new Migrations() )->run();

		// Save a copy of the stats.
		( new Options() )->save_statistics();

		// Schedule a max_scores calculation.
		if ( \function_exists( 'as_enqueue_async_action' ) ) {
			\as_enqueue_async_action( 'woop_calculate_max_scores', [], '', true );
		}

		// Log the activation.
		( new Updates() )->plugin_activated();

		// Set activated.
		\add_option( 'shophealth_activated', Carbon::now(), '', false );
	}

	/**
	 * Runs when the plugin gets deactivated.
	 *
	 * @return void
	 */
	public function uninstall(): void {

		// Remove all scheduled tasks.
		( new Queue() )->clean();

		// Roll back our migrations.
		( new Migrations() )->roll_back();

		// Clean up the Wooping options.
		( new Options() )->clean_up();

		// Log the deactivation.
		( new Updates() )->plugin_deactivated();
	}

	/**
	 * Call all classes needed for the custom functionality.
	 */
	public function init(): void {

		// Check if updates should be run
		if ( version_compare( Options::get( 'version' ), SHOP_HEALTH_VERSION, '<' ) ) {
			( new \Wooping\ShopHealth\Updates\Updates() )->run_updates();
		}

		// General WordPress hooks.
		( new Assets() )->register_hooks();
		( new RegisterPages() )->register_hooks();
		( new ManagePluginActions() )->register_hooks();
		( new Notices() )->register_hooks();
		( new HandleAdminRequest() )->register_hooks();
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
		( new Updates() )->register_hooks();

		( new Options )->version_number();

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
