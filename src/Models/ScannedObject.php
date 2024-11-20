<?php

namespace Wooping\ShopHealth\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Wooping\ShopHealth\Analysers\HTML;
use Wooping\ShopHealth\Contracts\Model;
use Wooping\ShopHealth\Helpers\ScoreCalculator;

/**
 * Class Issue
 *
 * This class represents an issue
 *
 * @package Wooping\ShopHealth\Models
 */
class ScannedObject extends Model {

	/**
	 * Define the table name
	 *
	 * @var string
	 */
	protected $table = 'woop_scanned_objects';

	/**
	 * Everything is fillable, except id:
	 *
	 * @var string[] $guared
	 */
	protected $guarded = [ 'id' ];

	/**
	 * Instance of the HTML Analyser, nullable.
	 */
	protected ?HTML $html_analyser = null;

	/**
	 * A ScannedObject has issues:
	 *
	 * @return HasMany
	 */
	public function issues(): HasMany {
		return $this->hasMany( Issue::class );
	}

	/**
	 * Return relevant issues (without a final status)
	 *
	 * @return HasMany
	 */
	public function relevant_issues(): HasMany {
		return $this->hasMany( Issue::class )->whereNotIn( 'status', Issue::FINAL_STATUSES );
	}

	/**
	 * Delete issues for scanned objects
	 */
	public static function boot(): void {
		parent::boot();
		static::deleted(
			static function ( $model ) {
				$model->issues()->delete();
			}
		);
	}

	/**
	 * Returns the html analyser
	 *
	 * @throws Exception If loading HTML from the given URL fails, this will throw an Exception.
	 */
	public function html(): ?HTML {
		// check if this scanned_object has an actual URL associated with it.
		if ( \is_null( $this->object_url ) ) {
			return null;
		}

		// if we have in initiated already, always return the saved analyser.
		if ( ! \is_null( $this->html_analyser ) ) {
			return $this->html_analyser;
		}

		// set the variable and return it, once initted.
		$this->html_analyser = new HTML( $this->object_url );
		return $this->html_analyser;
	}

	/**
	 * Recalculate the score for this object
	 */
	public function recalculate_score(): ScannedObject {

		$score = ( new ScoreCalculator() )->scanned_object( $scanned_object );

		// For a product, get the relative score based on the max_score
		if ( $this->object_type == 'product' ) {

			// get total negatives, and calculate our total score against it
			$max_scores      = \get_option( 'wooping_shop_health_max_scores', [] );
			$total_negatives = ( $max_scores['products'] ?? 1000 );
			$score           = ( $score / $total_negatives * 100 ); // percentage.
		}

		// score between 100-0, 100 being perfect, 0 being horrible.
		$this->score = (int) ( 100 - $score );

		return $this;
	}

	/**
	 * Magic method to create the object_url class variable.
	 */
	public function getObjectUrlAttribute(): ?string {
		// if it's associated with a post_id, just get the permalink.
		if ( ! \is_null( $this->object_id ) ) {
			return \get_permalink( $this->object_id );
		}

		// return null for now, but maybe expand this later for certain settings like the cart-page.
		return null;
	}

	/**
	 * Query scope for products
	 *
	 * @see https://laravel.com/docs/11.x/eloquent#query-scopes
	 */
	public function scopeProduct( Builder $builder ): Builder {
		return $builder->where( 'object_type', 'product' );
	}

	/**
	 * Query scope for shop
	 *
	 * @see https://laravel.com/docs/11.x/eloquent#query-scopes
	 */
	public function scopeShop( Builder $builder ): Builder {
		return $builder->where( 'object_type', 'shop' );
	}

	/**
	 * Query scope for settings
	 *
	 * @see https://laravel.com/docs/11.x/eloquent#query-scopes
	 */
	public function scopeSetting( Builder $builder ): Builder {
		return $builder->where( 'object_type', 'setting' );
	}
}
