<?php

namespace Wooping\ShopHealth\Queue;

use Wooping\ShopHealth\Contracts\ProductValidator;
use Wooping\ShopHealth\Contracts\ValidationQueueable;
use Wooping\ShopHealth\Models\ScannedObject;
use Wooping\ShopHealth\Validators\ProductContainer;

/**
 * Class ScanProduct
 *
 * Represents a validator for a product in a validation queue.
 * Extends the ValidationQueueable class.
 */
class ScanProduct extends ValidationQueueable {

	/**
	 * Cronjob hook on which this job runs
	 */
	protected string $hook = 'woop_scan_product';

	/**
	 * All Action Scheduler async actions should be assigned to a group.
	 * With this we are able to check whether a group of async actions is still running.
	 */
	protected static string $group = 'woop_scanned_product_';

	/**
	 * Define the Folder
	 */
	protected string $folder = 'Products';

	/**
	 * Run this validator
	 *
	 * @param string|int $object_id The id of the product to run validators on.
	 *
	 * @return void
	 */
	public function scan( $object_id ): void {

		// check if $object_id is valid.
		if ( ! \is_numeric( $object_id ) ) {
			return;
		}

		$product    = \wc_get_product( $object_id );
		$validators = ( new ProductContainer() )->validators();

		// check if this is a valid product.
		if ( ! $product ) {
			return;
		}

		// then, see if a scanned_object already exists.
		$scanned_object = ScannedObject::product()
				->where( 'object_id', $product->get_id() )
					->first();

		// if not, created it.
		if ( \is_null( $scanned_object ) ) {
			$scanned_object              = new ScannedObject();
			$scanned_object->description = \get_the_title( $object_id );
			$scanned_object->object_id   = $product->get_id();
			$scanned_object->object_type = 'product';
			$scanned_object->save();
		}

		// @var ProductValidator $validator ProductValidator instance for IDE click-troughs.
		foreach ( $validators as $validator ) {

			if ( ! \class_exists( $validator ) ) {
				continue;
			}

			/**
			 * ProductValidator
			 *
			 * @var ProductValidator $instance
			 */
			$instance = new $validator( $product, $scanned_object );

			// first, check if we can run this validator.
			if ( ! $instance->can_run() ) {
				// skip if we can't.
				continue;
			}

			// we have a failed test.
			if ( ! $instance->passes() ) {
				$instance->maybe_save_issue();
			} else {

				// if it passed, maybe remove any old
				// issues associated with this validator.
				$instance->maybe_resolve_issue();
			}
		}

		// After all validators have run, calculate the product score and save it.
		$scanned_object->recalculate_score()->save();
	}

	/**
	 * Schedule a task in the action scheduler to scan a product.
	 *
	 * @param int $product_id The product_id to schedule actions for.
	 */
	public function schedule( $product_id ): void {
		\as_enqueue_async_action(
			$this->hook,
			[ 'product_id' => $product_id ],
			self::$group . $product_id // add a product_id to the group, to batch per product.
		);
	}
}
