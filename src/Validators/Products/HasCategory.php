<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasCategory
 *
 * This class extends the Validator class and represents a validator for checking if a product belongs to a category.
 */
class HasCategory extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 40;

	/**
	 * Does this product belong to a category?
	 *
	 * @return bool
	 */
	public function passes(): bool {
		$uncategorized_term_id = \absint( \get_option( 'default_product_cat' ) );
		$category_ids          = $this->product->get_category_ids();

		return ( \count( $category_ids ) > 1 || ! \in_array( $uncategorized_term_id, $category_ids, true ) );
	}

	/**
	 * Actionable message / advice
	 *
	 * @return string
	 */
	public function message(): string {
		return \__( 'This product doesn\'t belong to any category. Please assign a category to this product.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Categorizing products helps your customers and the search engines to find products and related products on your webshop.', 'wooping-shop-health' );
	}
}
