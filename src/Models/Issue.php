<?php

namespace Wooping\ShopHealth\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Wooping\ShopHealth\Contracts\Model;

/**
 * Class Issue
 *
 * This class represents an issue
 *
 * @package Wooping\ShopHealth\Models
 */
class Issue extends Model {
	/**
	 * All allowed statuses of an issue
	 *
	 * @var array
	 */
	public const STATUSES = [
		'open',
		'resolved',
		'ignored',
		'sticky',
	];

	/**
	 * All final statuses (these can't be altered)
	 *
	 * @var array
	 */
	public const FINAL_STATUSES = [
		'resolved',
		'ignored',
	];

	/**
	 * Define the table name
	 *
	 * @var string
	 */
	protected $table = 'woop_issues';

	/**
	 * Everything is fillable, except id:
	 *
	 * @var string[]
	 */
	protected $guarded = [ 'id' ];

	/**
	 * An issue belongs to a product
	 *
	 * @return BelongsTo The associated product.
	 */
	public function scanned_object(): BelongsTo {
		return $this->belongsTo( ScannedObject::class );
	}

	/**
	 * Return a link to the documentation of this issue
	 *
	 * This function is directly accessible using $issue->docs_link (Laravel Accessors)
	 */
	public function getDocsLinkAttribute(): string {
		return \woop_get_link( \trailingslashit( \SHOP_HEALTH_DOCUMENTATION_URL ) . $this->validator );
	}

	/**
	 * Return the text of the documentation link for this issue
	 *
	 * This function is directly accessible using $issue->docs_description (Laravel Accessors)
	 */
	public function getDocsDescriptionAttribute(): ?string {
		$validator = $this->validator_class;

		if ( \class_exists( $validator ) ) {
			return $validator::documentation();
		}

		return null;
	}

	/**
	 * Return the validator class
	 *
	 * This function is directly accessible using $issue->validator_class (Laravel Accessors)
	 */
	public function getValidatorClassAttribute(): string {
		$base = 'Wooping\\ShopHealth\\Validators\\';
		if ( \is_null( $this->scanned_object->object_id ) ) {
			return $base . 'Settings\\' . $this->validator;
		} else {
			return $base . 'Products\\' . $this->validator;
		}
	}
}
