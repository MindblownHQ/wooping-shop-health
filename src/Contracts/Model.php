<?php

namespace Wooping\ShopHealth\Contracts;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as OriginalModel;
use Illuminate\Database\Query\Builder;

/**
 * Class Model
 *
 * Represents a model in the application.
 */
abstract class Model extends OriginalModel {

	/**
	 * Custom prefix for this table. Default 'wp_woop_'.
	 */
	protected string $prefix = '';

	/**
	 * Name of the table this model gets or manipulates its data from.
	 *
	 * @var string
	 */
	protected $table = '';

	/**
	 * Initializes a new instance of the class and sets its attributes.
	 *
	 * @param array<string|array|int|float> $attributes Mixed array of any variable in this Model that maps to the models' table.
	 */
	public function __construct( array $attributes = [] ) {
		parent::__construct( $attributes );

		global $wpdb;
		if ( ! \is_null( $wpdb ) ) {
			$this->table = $wpdb->prefix . $this->table;
		}
	}

	/**
	 * Returns the Query Builder for this models' table.
	 * More info on the Query Builder: https://laravel.com/docs/11.x/queries
	 */
	public static function table( string $table_name ): Builder {
		global $wpdb;
		return Capsule::table( $wpdb->prefix . $table_name );
	}
}
