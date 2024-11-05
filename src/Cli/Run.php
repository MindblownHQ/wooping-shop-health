<?php

namespace Wooping\ShopHealth\Cli;

use Wooping\ShopHealth\Controllers\Cron;
use Wooping\ShopHealth\Validators\SettingContainer;
use WC_Product_Query;
use WP_CLI;
use WP_CLI_Command;

/**
 * Class Run
 *
 * Holds all the posibilities of running jobs via WP CLI.
 */
class Run extends WP_CLI_Command {

	/**
	 * Run a single setting scan
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

		// everything's fine, lets run the setting scan.
		( new Cron() )->run_setting_scan( $slug );

		// and give positive feedback.
		WP_CLI::success( \sprintf( 'Scan ran for setting "%s"', \esc_html( $slug ) ) );
	}

	/**
	 * Run a single product scan
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

		// everything's fine, lets run the product scan.
		Cron::run_product_scan( $product_id );

		// and give positive feedback.
		WP_CLI::success( \sprintf( 'Scan ran for product "%s"', \esc_html( \get_the_title( $product_id ) ) ) );
	}

	/**
	 * Run all product scans
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	public function all_product_scans(): void {
		// run all product scans.
		$products = ( new WC_Product_Query(
			[
				'limit'  => -1,
			]
		) )->get_products();

		// run a scan for a single product.
		foreach ( $products as $product ) {
			Cron::run_product_scan( $product->get_id() );
			WP_CLI::log( 'Scanned ' . $product->get_title() );
		}

		// and give positive feedback.
		WP_CLI::success( 'Scans for all products have ran.' );
	}

	/**
	 * Run all setting scans
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	public function all_setting_scans(): void {
		// run all product scans.
		$validators = ( new SettingContainer() )->validators();

		foreach ( $validators as $slug => $class ) {
			Cron::run_setting_scan( $slug );
		}
		WP_CLI::success( 'Scans for all settings have run.' );
	}

	/**
	 * Run all scans
	 *
	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	public function all(): void {
		// run all product scans.
		$this->all_setting_scans();
		$this->all_product_scans();
	}
}
