import { slideToggle } from "./utilities/slideToggle";

document.addEventListener( "DOMContentLoaded", () => {

	const products = document.querySelectorAll( ".wsh-issues-table__product" );
	[ ...products ].forEach( ( product ) => {
		product.addEventListener( "click", ( e ) => {
			console.log( e );
			if ( e.target.nodeName === "A" || e.target.nodeName === "BUTTON" || e.target.nodeName === "SPAN" ) {
				return;
			}
			slideToggle( product.querySelector( ".wsh-issues-table__issues-container" ), 300, { display: "grid" } );
		} );
	} );

} );
