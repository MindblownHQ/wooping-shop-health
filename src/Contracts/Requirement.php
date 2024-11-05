<?php
namespace Wooping\ShopHealth\Contracts;

/**
 * The Requirement class
 */
abstract class Requirement {

	/**
	 * The validator that requires this class
	 */
	protected Validator $validator;

	/**
	 * Constructor
	 */
	public function __construct( Validator $validator ) {
		$this->validator = $validator;
	}

	/**
	 * A Requirement always has a passes function that returns a bool.
	 */
	abstract public function passes(): bool;
}
