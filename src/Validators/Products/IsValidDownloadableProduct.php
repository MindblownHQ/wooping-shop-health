<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class IsValidDownloadableProduct
 *
 * This class extends the Validator class and represents a validator for checking if a downloadable product has files.
 */
class IsValidDownloadableProduct extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 70;

	/**
	 * Define the importance of this validator.
	 */
	protected string $importance = 'critical';

	/**
	 * Does this product have downloadable files?
	 */
	public function passes(): bool {
		return ! $this->product->is_downloadable() || \count( $this->product->get_downloads() ) > 0;
	}

	/**
	 * Actionable message / advice
	 */
	public function message(): string {
		return \__( 'This downloadable product doesn\'t have any files associated with it. Please provide downloadable files for this product.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'If you have not configured a custom way to serve your virtual product when sold, please make sure these settings are correct.', 'wooping-shop-health' );
	}
}
