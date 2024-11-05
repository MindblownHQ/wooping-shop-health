<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasStock
 *
 * Represents a validator for checking if a product has stock left.
 */
class HasStock extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 90;

	/**
	 * Define the importance of this validator.
	 */
	protected string $importance = 'medium';

	/**
	 * Does this product have stock left?
	 */
	public function passes(): bool {

		// Check if in stock.
		if ( $this->product->is_in_stock() ) {

			// Check if stock is managed.
			if ( $this->product->get_manage_stock() ) {

				// Get threshold and quantity.
				$threshold = \get_option( 'woocommerce_notify_low_stock_amount', 2 );
				$quantity  = $this->product->get_stock_quantity();

				return $quantity >= $threshold;
			}

			// Pass if in stock and not managed.
			return true;
		}

		// Do not pass if not in stock.
		return false;
	}

	/**
	 * Actionable message / advice.
	 */
	public function message(): string {
		$quantity = $this->product->get_stock_quantity();
		if ( $quantity <= 0 ) {
			return \__( 'This product has no stock left.', 'wooping-shop-health' );
		}

		return \sprintf(
		/* translators: %d is replaced with the number of products currently in stock. */
			\__( 'This product has a really low stock (%d)', 'wooping-shop-health' ),
			$quantity
		);
	}

	/**
	 * Return the importance based on the remaining stock.
	 */
	public function importance(): string {
		$quantity = $this->product->get_stock_quantity();
		if ( $quantity <= 0 ) {
			return 'high';
		}

		return $this->importance;
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'If you’ve enabled stock keeping, make sure all your products are in stock.', 'wooping-shop-health' );
	}
}
