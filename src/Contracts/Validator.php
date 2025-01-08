<?php

namespace Wooping\ShopHealth\Contracts;

use ReflectionClass;
use Wooping\ShopHealth\Models\Issue;
use Wooping\ShopHealth\Models\ScannedObject;

/**
 * Abstract validator class to define the requirements for a validators for products, settings and pages.
 */
abstract class Validator {

	/**
	 * The importance of this validation.
	 */
	public const SEVERITY = 10;

	/**
	 * The requirements of this validator.
	 */
	public const REQUIREMENTS = [];

	/**
	 * Holds the object id. Can be a page or product id, setting slug, product archive slug or category slug.
	 */
	public ScannedObject $object;

	/**
	 * Holds the validator type. Either `revenue`, `customers` or `orders`.
	 */
	protected string $type = 'revenue';

	/**
	 * Check if validation tests are passed.
	 */
	abstract protected function passes(): bool;

	/**
	 * Returns an actionable message or advice if validation fails
	 */
	abstract protected function message(): string;

	/**
	 * Returns a single line of documentation for this validator.
	 */
	abstract public static function documentation(): string;

	/**
	 * Does this validator have the requirements to run at all?
	 */
	public function can_run(): bool {

		// loop through all requirements.
		foreach ( $this->requirements() as $requirement ) {

			// turn them into an instance and see if they pass.
			$instance = new $requirement( $this );
			if ( ! $instance->passes() ) {
				return false;
			}
		}

		// everything passed.
		return true;
	}


	/**
	 * Returns the requirements for this validator. 
	 */
	public function requirements(): array {
		
		// Retrieve the slug
		$slug = $this->get_validator_slug();
		
		// This turns into 'wooping/validators/has_category/requirements'.
		return apply_filters( "wooping/validators/$slug/requirements", static::REQUIREMENTS );
	}

	/**
	 * Returns the severity. Defaults to the constant at the top of this class.
	 */
	protected function severity(): int {

		// Retrieve the slug
		$slug = $this->get_validator_slug();
		
		// This turns into 'wooping/validators/has_category/severity'.
		return apply_filters( "wooping/validators/$slug/severity", static::SEVERITY );
	}

	/**
	 * Returns the current class short name
	 */
	public function get_validator_short_name(): string {
		return ( new ReflectionClass( static::class ) )->getShortName();
	}

	/**
	 * Returns the validator's short_name as a snake-case string.
	 */
	public function get_validator_slug(): string {
		return strtolower( 
			preg_replace(
				'/([a-z])([A-Z])/', 
				'$1_$2', 
				$this->get_validator_short_name() 
			) 
		);
	}

	/**
	 * Check whether to maybe save an issue to the database.
	 */
	public function maybe_save_issue(): void {

		// find the issue for this validator.
		$issue = $this->find_issue();

		// Bail if the issue already exists.
		if ( ! \is_null( $issue ) ) {
			return;
		}

		// If there is no issue for the object, create one.
		$this->save_issue();
	}

	/**
	 * Maybe remove an issue after this validator has run.
	 */
	public function maybe_resolve_issue(): void {

		// find the issue for this validator.
		$issue = $this->find_issue();

		// Allow plugin develors to check if this validator can be resolved. Defaults to yes.
		$slug = $this->get_validator_slug();
		$can_be_resolved = apply_filters( "wooping/validators/$slug/can_be_resolved", true, $this );

		// mark issue as resolved, if it exists.
		if ( ! \is_null( $issue ) && $can_be_resolved ) {

			$issue->status = 'resolved';
			$issue->save();
		}
	}

	/**
	 * Check whether or not this validator has an issue associated with them.
	 */
	public function find_issue(): ?Issue {

		return $this->object->issues()->where( 'validator', $this->get_validator_short_name() )
				->where( 'status', '!=', 'resolved' )
					->first();
	}

	/**
	 * Create an issue out of this faulty validator
	 */
	protected function save_issue(): void {
		$issue = $this->object->issues()->create();
		$issue->fill(
			[
				'message'   => $this->message(),
				'severity'  => $this->severity(),
				'validator' => $this->get_validator_short_name(),
			]
		)->save();
	}
}
