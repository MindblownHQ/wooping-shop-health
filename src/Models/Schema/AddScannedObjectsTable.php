<?php

namespace Wooping\ShopHealth\Models\Schema;

use Wooping\ShopHealth\Contracts\Schema;

/**
 * Class AddIssuesTable
 *
 * Represents a table to add issues.
 */
class AddScannedObjectsTable extends Schema {

	/**
	 * Define the table name.
	 */
	protected string $table_name = 'woop_scanned_objects';

	/**
	 * Run the query that will add our table
	 *
	 * @return void
	 */
	public function run(): void {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = \esc_sql( $this->get_table_name() );

		$query = "CREATE TABLE `$table_name` (
            id int NOT NULL AUTO_INCREMENT,
            description TEXT DEFAULT NULL,
            object_id int DEFAULT NULL,
			object_slug varchar(150) DEFAULT NULL,
			object_type ENUM('product', 'shop', 'setting') DEFAULT 'product',
            score int DEFAULT '0',
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
            ) $charset_collate";

		require_once \ABSPATH . 'wp-admin/includes/upgrade.php';

		// @todo: possibly have this function return a state in the future, to see if a migration went ok.

		// phpcs:ignore
		\dbDelta( $query );
	}
}
