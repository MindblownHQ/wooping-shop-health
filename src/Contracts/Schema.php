<?php

namespace Wooping\ShopHealth\Contracts;

/**
 * Class Schema
 *
 * Classes extending Schema manipulate the database with custom queries (like migrations)
 */
abstract class Schema {

	/**
	 * Get the complete table name.
	 */
	protected function get_table_name(): string {
		global $wpdb;

		return $wpdb->base_prefix . $this->table_name;
	}

	/**
	 * Check if our table exists, if not, run the schema.
	 */
	public function exists(): bool {
		global $wpdb;
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $this->get_table_name() ) );

		// phpcs:ignore
		if ( $wpdb->get_var( $query ) == $this->get_table_name() ) {
			return true;
		}

		return false;
	}

	/**
	 * Roll back this table
	 */
	public function roll_back(): void {

		$table_name = \esc_sql( $this->get_table_name() );
		$query      = "DROP TABLE IF EXISTS $table_name";

		// phpcs:ignore
		global $wpdb;
		$wpdb->query( $query );
	}
}
