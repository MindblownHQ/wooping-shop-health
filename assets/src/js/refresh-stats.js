import { addNotice } from "./notice";

document.addEventListener( "DOMContentLoaded", () => {
	const refreshButton = document.querySelector( ".wooping-refresh-statistics" );
	const returning = document.getElementById( "stats-returning" );
	const customers = document.getElementById( "stats-customers" );
	const revenue = document.getElementById( "stats-revenue" );

	if ( refreshButton ) {
		refreshButton.addEventListener( "click", ( e ) => {
			e.preventDefault();
			document.querySelectorAll( ".wsh-statistics .wsh-box" ).forEach( element => {
				element.classList.add( "refreshing" );
			} );
			fetch( wpApiSettings.root + "shop-health/v1/stats/refresh", {
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

						returning.querySelector( ".wsh-statistics__result" ).innerHTML = result.data.returning.percentage + "&percnt; " + result.data.returning.addendum;
						returning.querySelector( ".wsh-statistics__number").innerHTML = result.data.returning.total;

						customers.querySelector( ".wsh-statistics__result" ).innerHTML = result.data.customers.percentage + "&percnt; " + result.data.customers.addendum;
						customers.querySelector( ".wsh-statistics__number").innerHTML = result.data.customers.total;

						revenue.querySelector( ".wsh-statistics__result" ).innerHTML = result.data.revenue.percentage + "&percnt; " + result.data.revenue.addendum;
						revenue.querySelector( ".wsh-statistics__number").innerHTML = shopHealth.currencySymbol + result.data.revenue.total;
					}
					document.querySelectorAll( ".wsh-statistics .wsh-box" ).forEach( element => {
						element.classList.remove( "refreshing" );
					} );
				} );
		} );

	}
} );