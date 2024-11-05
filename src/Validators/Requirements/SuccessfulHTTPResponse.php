<?php
// phpcs:disable Yoast.NamingConventions.ObjectNameDepth.MaxExceeded

namespace Wooping\ShopHealth\Validators\Requirements;

use Throwable;
use Wooping\ShopHealth\Contracts\Requirement;

/**
 * Class SuccessfulHTTPResponse
 *
 * This class checks wether or not the ScannedObjects' html analyser has
 * a positive http status code for us
 */
class SuccessfulHTTPResponse extends Requirement {

	/**
	 * A successful HTTP response has a status code below 300.
	 */
	public function passes(): bool {
		try{
			return ( $this->validator->object->html()->get_status_code() < 300 );
		}catch( Throwable $error ){
			return false;
		}
	}
}
