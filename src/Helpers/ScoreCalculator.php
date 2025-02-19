<?php
namespace Wooping\ShopHealth\Helpers;

use Illuminate\Support\Collection;
use Wooping\ShopHealth\Models\Issue;
use Wooping\ShopHealth\Models\ScannedObject;

/**
 * Score calculator helper class
 */
class ScoreCalculator {

	/**
	 * Get the shop settings score
	 */
	public function settings(): int {
		// Return the average score.
		$setting_score = ScannedObject::setting()->avg( 'score' );
		if ( ! $setting_score ) {
			$setting_score = 100;
		}
		return (int) $setting_score;
	}

	/**
	 * Get the score of all products
	 *
	 * @returns The score in a percentage.
	 */
	public function global_product(): int {
		// Return the average score.
		$product_score = ScannedObject::product()->avg( 'score' );
		if ( ! $product_score ) {
			$product_score = 100;
		}
		return (int) $product_score;
	}

	/**
	 * Returns a total on a collection of ScannedObjects
	 */
	public function get_total( Collection $collection ): int {
		$total = 0;
		// loop through 'em and add the running total.
		if ( $collection->isEmpty() === false ) {
			foreach ( $collection as $object ) {
				if ( $object->object_id || $object->object_type === 'setting' ) {
					$total += $object->score;
				}
			}
		}

		return $total;
	}

	/**
	 * Returns the cumulated result of a scanned object.
	 */
	public function scanned_object( ScannedObject $scanned_object ): int {

		$total = 0;
		foreach ( $scanned_object->issues as $issue ) {
			if ( ! \in_array( $issue->status, Issue::FINAL_STATUSES, true ) ) {
				$total += $issue->severity;
			}
		}

		return $total;
	}
}
