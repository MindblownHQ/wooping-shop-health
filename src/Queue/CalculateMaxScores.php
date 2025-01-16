<?php

namespace Wooping\ShopHealth\Queue;

use Wooping\ShopHealth\Contracts\Queueable;

/**
 * Class CalculateMaxScores
 *
 * This class is responsible for calculating the max scores for our ScannedO
 */
class CalculateMaxScores extends Queueable {

	/**
	 * Hook on which this job runs
	 */
	protected string $hook = 'wooping/shop-health/calculate_max_scores';

	/**
	 * This job updates issues and makes them more important as they grow older
	 *
	 * @return void
	 */
	public function run(): void {

		$scores = [];

		$scores['products'] = $this->get_total( 'products' );

		// @todo pages max score calculator, once the folder exists.
		$scores['pages'] = 0;

		$scores['settings'] = $this->get_total( 'settings' );

		// save our values.
		\update_option( 'wooping_shop_health_max_scores', $scores );
	}

	/**
	 * Return the max score of a certain type
	 */
	public function get_total( string $type ): int {

		$total = 0;

		// get validators.
		$validators = $this->get_validators( $type );

		// loop through em and add their severity constant.
		foreach ( $validators as $validator ) {
			$total += $validator::SEVERITY;
		}

		return $total;
	}

	/**
	 * Get validator classes from a certain folder
	 *
	 * @return array<string> validators
	 */
	public function get_validators( string $folder ): array {

		// get the directory and namespace setup.
		$response  = [];
		$folder    = \ucwords( $folder );
		$dir       = \SHOP_HEALTH_PATH . '/src/Validators/' . $folder;
		$namespace = '\\Wooping\\ShopHealth\\Validators\\' . $folder . '\\';

		$files       = \scandir( $dir );
		$not_allowed = [ '.', '..', '.DS_Store' ];

		// populate the array of classes.
		foreach ( $files as $file ) {
			if ( ! \in_array( $file, $not_allowed, true ) ) {
				$class      = \str_replace( '.php', '', $file );
				$response[] = $namespace . $class;
			}
		}

		return $response;
	}
}
