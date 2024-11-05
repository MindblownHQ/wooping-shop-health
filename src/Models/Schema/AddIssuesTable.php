<?php

namespace Wooping\ShopHealth\Models\Schema;

use Wooping\ShopHealth\Contracts\Schema;

/**
 * Class AddIssuesTable
 *
 * Represents a table to add issues.
 */
class AddIssuesTable extends Schema {

	/**
	 * Define the table name.
	 */
	protected string $table_name = 'woop_issues';

	/**
	 * Run the query that will add our table
	 *
	 * @return void
	 */
	public function run(): void {
		global $wpdb;
		$charset_collate            = $wpdb->get_charset_collate();
		$table_name                 = \esc_sql( $this->get_table_name() );
		$scanned_objects_table_name = ( new AddScannedObjectsTable() )->get_table_name();

		$query = "CREATE TABLE `$table_name` (
            id int NOT NULL AUTO_INCREMENT,
            scanned_object_id int DEFAULT NULL,
            message text DEFAULT NULL,
            severity int DEFAULT '0',
			pilar ENUM( 'general', 'revenue', 'customers', 'orders') DEFAULT 'general',
            validator varchar(250) DEFAULT NULL,
            status ENUM('open', 'resolved', 'ignored', 'sticky') DEFAULT 'open',
            status_changed_on datetime DEFAULT CURRENT_TIMESTAMP,
            status_changed_by int DEFAULT NULL,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
			FOREIGN KEY (scanned_object_id) REFERENCES " . $scanned_objects_table_name . "(id)
            ) $charset_collate";

		require_once \ABSPATH . 'wp-admin/includes/upgrade.php';

		// @todo: possibly have this function return a state in the future, to see if a migration went ok.

		// phpcs:ignore
		\dbDelta( $query );
	}
}
