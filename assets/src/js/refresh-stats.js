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
						returning.querySelector( ".wsh-statistics__number" ).innerHTML = result.data.returning.total;
						returning.querySelector( ".wsh-statistics__mutation" ).innerHTML = wsh_get_mutation_html( result.data.returning );

						customers.querySelector( ".wsh-statistics__result" ).innerHTML = result.data.customers.percentage + "&percnt; " + result.data.customers.addendum;
						customers.querySelector( ".wsh-statistics__number").innerHTML = result.data.customers.total;
						customers.querySelector(".wsh-statistics__mutation").innerHTML = wsh_get_mutation_html(result.data.customers);

						revenue.querySelector( ".wsh-statistics__result" ).innerHTML = result.data.revenue.percentage + "&percnt; " + result.data.revenue.addendum;
						revenue.querySelector( ".wsh-statistics__number").innerHTML = shopHealth.currencySymbol + result.data.revenue.total;
						revenue.querySelector(".wsh-statistics__mutation").innerHTML = wsh_get_mutation_html(result.data.revenue);
					}
					document.querySelectorAll( ".wsh-statistics .wsh-box" ).forEach( element => {
						element.classList.remove( "refreshing" );
					} );
				} );
		} );

	}


	function wsh_get_mutation_html( data ){
		const _class = ( data.percentage >= 0 ? 'positive' : 'negative' );
		const _label = ( data.percentage >= 0 ? 'increase' : 'decrease' );
		return `<span class="wsh-statistics__result wsh-statistics__result--${_class}">
			${data.percentage}&percnt; ${_label}
		</span>`;
	}
} );
