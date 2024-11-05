import { addNotice } from "./notice";
import { initScanProgress } from './scan-progress';

document.addEventListener( "DOMContentLoaded", () => {

	//check if we have a "scan shop" button
	const scanButton = document.querySelector( ".button-primary.scan-all" );
	const progressBar = document.querySelector( "#wooping-scan-progress" );

	if ( scanButton !== null ) {
		scanButton.addEventListener( "click", ( e ) => {
			fetch( wpApiSettings.root + "shop-health/v1/scan/all", {
				method: "POST",
				headers: {
					"X-WP-Nonce": wpApiSettings.nonce,
					"Content-Type": "application/json",
				},
			} )
				.then( res => res.json() )
				.then( result => {
					if ( result.status === "success" ) {
						addNotice( result.message );

						//show progress bar
						if (progressBar !== null) {
							initScanProgress( progressBar );
						}
					}
				} );
		} );
	}

	//check if we have a progress bar on this page:
	if (
		progressBar !== null &&
		progressBar.classList.contains('in-progress')
	) {
		initScanProgress(progressBar);
	}

} );
