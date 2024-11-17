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
		$settingScore = ScannedObject::setting()->avg( 'score' );
		if ( ! $settingScore ) {
			$settingScore = 100;
		}
		return (int) $settingScore;
	}

	/**
	 * Get the score of all products
	 *
	 * @returns The score in a percentage.
	 */
	public function global_product(): int {
		// Return the average score.
		$productScore = ScannedObject::product()->avg( 'score' );
		if ( ! $productScore ) {
			$productScore = 100;
		}
		return (int) $productScore;
	}

	/**
	 * Calculate the score
	 */
	public function calculate( int $total, string $type ): int {

		// get total negatives.
		$scores          = \get_option( 'wooping_shop_health_max_scores', [] );
		$total_negatives = ( $scores[ $type ] ?? 1000 );

		if ( $total === 0 ) {
			return 100;
		}

		if ( $total_negatives === 0 ) {
			return 0;
		}

		$negatives = ( $total / $total_negatives * 100 ); // percentage.
		return (int) ( 100 - $negatives ); // score between 100-0, 100 being perfect, 0 being horrible.
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
