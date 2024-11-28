<?php

namespace Wooping\ShopHealth\Queue;

use Wooping\ShopHealth\Contracts\ValidationQueueable;
use Wooping\ShopHealth\Models\ScannedObject;
use Wooping\ShopHealth\Validators\SettingContainer;

/**
 * Class ScanSetting
 *
 * This class extends the ValidationQueueable class and is responsible for scanning a setting issue.
 */
class ScanSetting extends ValidationQueueable {

	/**
	 * Hook on which this job runs.
	 */
	protected string $hook = 'woop_scan_setting';

	/**
	 * All Action Scheduler async actions should be assigned to a group.
	 * With this we are able to check whether a group of async actions is still running.
	 */
	protected static string $group = 'woop_scanned_settings';

	/**
	 * Define the Folder.
	 */
	protected string $folder = 'Settings';

	/**
	 * Scan a specific validator
	 *
	 * @param string $slug The slug of the validator.
	 *
	 * @return void
	 */
	public function scan( $slug ): void {

		// get the setting from the container.
		$setting = ( new SettingContainer() )->get_class( $slug );

		$scanned_object = ScannedObject::setting()
				->where( 'object_slug', $slug )
					->first();

		// if not, created it.
		if ( \is_null( $scanned_object ) ) {
			$scanned_object              = new ScannedObject();
			$scanned_object->description = $slug;
			$scanned_object->object_slug = $slug;
			$scanned_object->object_type = 'setting';
			$scanned_object->save();
		}

		$instance = new $setting( \WC(), $scanned_object );

		// we have a failed test.
		if ( ! $instance->passes() ) {
			$instance->maybe_save_issue();
		} else {
			// if it passed, maybe remove any old
			// issues associated with this validator.
			$instance->maybe_resolve_issue();
		}

		// After all validators have run, calculate the product score and save it.
		$scanned_object->recalculate_score()->save();
	}

	/**
	 * Schedule an async action in the Action Scheduler.
	 *
	 * @param string $setting_slug Holds the setting to schedule.
	 */
	public function schedule( $setting_slug ): void {
		\as_enqueue_async_action(
			$this->hook,
			[ 'slug' => $setting_slug ],
			self::$group
		);
	}
}
