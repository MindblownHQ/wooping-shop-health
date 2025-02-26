<?php

namespace Wooping\ShopHealth\Models\Database;

use Wooping\ShopHealth\Models\Schema\AddIssuesTable;
use Wooping\ShopHealth\Models\Schema\AddScannedObjectsTable;

/**
 * Migrations class
 *
 * Deals with all database migrations that Shop Health requires
 */
class Migrations {

	/**
	 * Run the database migrations for this plugin
	 */
	public function run(): void {

		// Get the migrations and loop through them.
		$migrations = $this->get_migrations();
		$sites      = $this->get_sites();

		foreach ( $sites as $site ) {

			// Switch to another site to run these migrations.
			if ( ! \is_null( $site ) ) {
				\switch_to_blog( \absint( $site ) );
			}

			foreach ( $migrations as $migration ) {

				// Only create the tables that don't exist.
				if ( $migration->exists() === false ) {
					$migration->run();
				}
			}
		}
	}

	/**
	 * Revert the database migrations for this plugin
	 */
	public function roll_back(): void {

		// Get the migrations and loop through them.
		$migrations = $this->get_migrations();
		$sites      = $this->get_sites();

		foreach ( $sites as $site ) {

			// Switch to another site to run these migrations.
			if ( ! \is_null( $site ) ) {
				\switch_to_blog( \absint( $site ) );
			}

			// Reverse the migrations array, because roll-backs happen in the reverse order.
			foreach ( \array_reverse( $migrations ) as $migration ) {

				// Only delete the tables if they exist.
				if ( $migration->exists() === true ) {
					$migration->roll_back();
				}
			}
		}
	}

	/**
	 * Returns the available migrations as instances
	 *
	 * @return array<Schema>
	 */
	public function get_migrations(): array {

		return [
			new AddScannedObjectsTable(),
			new AddIssuesTable(),
		];
	}

	/**
	 * If we're dealing with a multisite, this function provides an array of blog_ids to run this migration for.,
	 *
	 * @return array<int>
	 */
	public function get_sites(): array {

		// Only respond with an array of site ids if we're in the network admin.
		if ( \is_multisite() && \is_network_admin() ) {

			$response = [];
			foreach ( \get_sites() as $site ) {
				$response[] = $site->blog_id;
			}

			return $response;
		}

		// Default to an array with null, so we can still loop through it.
		return [ null ];
	}
}
