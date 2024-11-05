<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;
use Wooping\ShopHealth\Validators\Requirements\SuccessfulHTTPResponse;

/**
 * Class HasOpenGraph
 *
 * Represents a validator for checking if a product has the required Open Graph properties.
 */
class HasOpenGraph extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 65;

	/**
	 * This validator has the following requirements
	 */
	public const REQUIREMENTS = [ SuccessfulHTTPResponse::class ];

	/**
	 * Check if a product passes certain validation checks.
	 */
	public function passes(): bool {
		return ( $this->has_title() && $this->has_description() );
	}

	/**
	 * Check whether this product has an open graph title.
	 */
	public function has_title(): bool {
		return $this->object->html()->analyser()->query( "//meta[starts-with(@property, 'og:title')]" )->length;
	}

	/**
	 * Check whether this product has a description.
	 */
	public function has_description(): bool {
		return $this->object->html()->analyser()->query( "//meta[starts-with(@property, 'og:description')]" )->length;
	}

	/**
	 * Actionable message / advice
	 */
	public function message(): string {
		if ( $this->has_title() === false ) {
			return \__( 'The open graph title has not been set.', 'wooping-shop-health' );
		}
		elseif ( $this->has_description() === false ) {
			return \__( 'The open graph description has not been set.', 'wooping-shop-health' );
		}
	}

	/**
	 * Add importance
	 */
	public function severity(): int {
		if ( $this->has_title() === false ) {
			return 50;
		}
		elseif ( $this->has_description() === false ) {
			return 20;
		}
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Open Graph is a standard used by social media platforms to quickly get all the important information to show when sharing a url.', 'wooping-shop-health' );
	}
}
