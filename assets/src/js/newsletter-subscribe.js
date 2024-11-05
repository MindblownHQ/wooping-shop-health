document.addEventListener( "DOMContentLoaded", () => {
	const form = document.querySelector( ".wsh-newsletter-subscribe" );

	form && form.addEventListener( "submit", async ( e ) => {

		e.preventDefault();

		const formData = new FormData( form );

		const api_url = shopHealth.api_url + "/subscribe";

		const response = await fetch( api_url, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify( {
				name: formData.get( "name" ),
				email: formData.get( "email" ),
				origin: "wooping-dashboard-signup"
			} )
		} );

		const data = await response.json();

		const successDiv = document.createElement( "div" );
		successDiv.className = "wsh-form__success";
		const successMessage = document.createElement( "p" );
		successMessage.innerHTML = `<strong>${data.message}</strong>`;
		successDiv.appendChild( successMessage );
		form.parentNode.appendChild( successDiv );

		if ( response.status === 200 ) {
			form.reset();
			form.remove();
		}

	} );
} );