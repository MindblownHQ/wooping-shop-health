<?php

namespace Wooping\ShopHealth\Contracts;

/**
 * Class ValidationQueueable
 *
 * An abstract class that extends the Queueable class to handle validations.
 */
abstract class ValidationQueueable extends Queueable {

	/**
	 * Folder where the validator classes reside for this queueable.
	 */
	protected string $folder = '';

	/**
	 * Scan a single object
	 *
	 * @param string|int $object_id The id of the object to run validators on.
	 */
	abstract public function scan( $object_id ): void;

	/**
	 * Register this job
	 */
	public function register_hooks(): void {
		// only hook into the hook, if we have a scan action
		// we can't give a default for this function because of
		// how action scheduler deals with arguments.
		if ( \method_exists( $this, 'scan' ) ) {
			\add_action( $this->hook, [ $this, 'scan' ] );
		}
	}

	/**
	 * Returns all validator classes.
	 *
	 * @return array<string> An array of callable class names.
	 */
	public function validators(): array {
		$response  = [];
		$dir       = \SHOP_HEALTH_PATH . '/src/Validators/' . $this->folder;
		$namespace = '\\Wooping\\ShopHealth\\Validators\\' . $this->folder . '\\';

		$files       = \scandir( $dir );
		$not_allowed = [ '.', '..', '.DS_Store' ];

		foreach ( $files as $file ) {
			if ( ! \in_array( $file, $not_allowed, true ) ) {
				$class      = \str_replace( '.php', '', $file );
				$response[] = $namespace . $class;
			}
		}

		return $response;
	}

	/**
	 * Schedule an async action in the Action Scheduler.
	 *
	 * @param int|string $object_id Contains the id or slug
	 * of a product or setting to schedule validation for.
	 */
	abstract public function schedule( $object_id ): void;
}
