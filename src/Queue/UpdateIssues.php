<?php

namespace Wooping\ShopHealth\Queue;

use Carbon\Carbon;
use Wooping\ShopHealth\Contracts\Queueable;
use Wooping\ShopHealth\Models\Issue;

/**
 * Class UpdateIssues
 *
 * This class is responsible for updating issues
 */
class UpdateIssues extends Queueable {

	/**
	 * Hook on which this job runs
	 */
	protected string $hook = 'wooping/shop-health/update_issues';

	/**
	 * This job updates issues and makes them more important as they grow older
	 *
	 * @return void
	 */
	public function run(): void {
		$time   = Carbon::now()->subWeeks( 2 );
		$issues = Issue::where( 'created_at', '<=', $time )
				->whereNotIn( 'status', Issue::FINAL_STATUSES )
				->get();

		if ( $issues->isEmpty() === false ) {
			foreach ( $issues as $issue ) {
				$issue->importance += 1;
				$issue->save();
			}
		}
	}
}
