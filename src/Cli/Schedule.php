<?php

namespace Wooping\ShopHealth\Cli;

use Wooping\ShopHealth\Controllers\Cron;
use Wooping\ShopHealth\Validators\SettingContainer;
use WP_CLI;
use WP_CLI_Command;

/**
 * Class Schedule
 *
 * Holds all the posibilities of scheduling jobs via WP CLI.
 */
class Schedule extends WP_CLI_Command {

	/**
	 * Schedule a single setting
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	public function setting_scan( array $args ): void {

		// first, check if a slug has been set as one of the args.
		$slug = ( $args[0] ?? null );
		if ( \is_null( $slug ) ) {
			WP_CLI::error( 'Please provide a setting slug to scan.' );
		}

		// then check if it exists.
		$instance = ( new SettingContainer() )->get_class( $slug );
		if ( \is_null( $instance ) ) {
			WP_CLI::error( \sprintf( 'No validator found for slug %s.', \esc_html( $slug ) ) );
		}

		// everything's fine, lets schedule the setting scan.
		( new Cron() )->schedule_setting_scan( $slug );

		// and give positive feedback.
		WP_CLI::success( \sprintf( 'Scan scheduled for setting "%s"', \esc_html( $slug ) ) );
	}

	/**
	 * Schedule a single product
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	public function product_scan( array $args ): void {

		// first, check if a product_id has been set as one of the args.
		$product_id = ( $args[0] ?? null );
		if ( \is_null( $product_id ) ) {
			WP_CLI::error( 'Please provide a product id to scan.' );
		}

		// then check if it's actually a product.
		if ( \get_post_type( $product_id ) !== 'product' ) {
			WP_CLI::error( 'Please provide a valid product id.' );
		}

		// everything's fine, lets schedule the product scan.
		Cron::schedule_product_scan( $product_id );

		// and give positive feedback.
		WP_CLI::success( \sprintf( 'Scan scheduled for product "%s"', \esc_html( \get_the_title( $product_id ) ) ) );
	}

	/**
	 * Schedule all product scans
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	public function all_product_scans(): void {
		// schedule all product scans.
		( new Cron() )->schedule_all_product_scans();

		// and give positive feedback.
		WP_CLI::success( 'Scans for all products have been scheduled.' );
	}

	/**
	 * Schedule all setting scans
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	public function all_setting_scans(): void {
		// schedule all product scans.
		( new Cron() )->schedule_all_setting_scans();

		// and give positive feedback.
		WP_CLI::success( 'Scans for all settings have been scheduled.' );
	}

	/**
	 * Schedule all scans
	 *
	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	public function all(): void {
		$this->all_setting_scans();
		$this->all_product_scans();
	}
}
