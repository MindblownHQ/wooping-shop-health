<?php

namespace Wooping\ShopHealth\Queue;

use Wooping\ShopHealth\Contracts\Queueable;
use Wooping\ShopHealth\Models\Database\Options;

/**
 * Class RefreshStatistics
 *
 * This class is responsible for an hourly cron that refreshes the statistics
 */
class RefreshStatistics extends Queueable {

	/**
	 * Hook on which this job runs
	 */
	protected string $hook = 'wooping/shop-health/refresh_stats';

	/**
	 * This job updates issues and makes them more important as they grow older
	 *
	 * @return void
	 */
	public function run(): void {

		( new Options() )->save_statistics();
	}
}
