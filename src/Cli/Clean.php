<?php

namespace Wooping\ShopHealth\Cli;

use Wooping\ShopHealth\Models\Issue;
use Wooping\ShopHealth\Models\ScannedObject;
use Wooping\ShopHealth\Queue\Register as Queue;
use WP_CLI;
use WP_CLI_Command;

/**
 * Class Run
 *
 * Allows deleting issues and scanned objects from the Shop Health tables
 * Allows cancelling Shop Health actions in Action Scheduler
 */
class Clean extends WP_CLI_Command {

	/**
	 * Delete all issues
	 */
	public function issues() {
		Issue::query()->delete();
		WP_CLI::success( 'Removed all issues.' );
	}

	/**
	 * Delete all scanned objects
	 */
	public function objects() {
		ScannedObject::query()->delete();
		WP_CLI::success( 'Removed all scanned objects.' );
	}

	/**
	 * Cancel all Action Scheduler actions
	 */
	public function actions() {
		( new Queue() )->clean();
		WP_CLI::success( 'Removed all scheduled actions.' );
	}

	/**
	 * Remove all issues and scanned objects from the database
	 */
	public function all() {
		$this->actions();
		$this->issues();
		$this->objects();
	}
}
