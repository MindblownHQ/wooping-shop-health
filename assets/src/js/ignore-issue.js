import { slowDelete } from "./slow-delete";

document.addEventListener( "DOMContentLoaded", () => {
	let issuesContainer = document.querySelector( ".wsh-issues-table__issues-container, .wsh-issues-table" );
	if ( issuesContainer !== null ) {
		issuesContainer.addEventListener( "click", ( e ) => {
			if ( e.target.nodeName === "BUTTON" ) {
				e.preventDefault();
				if ( e.target.className.includes( "ignore-issue-btn" ) ) {
					let issue_id = e.target.dataset.issueId;
					fetch( wpApiSettings.root + "shop-health/v1/issue/update/" + issue_id, {
						method: "POST",
						headers: {
							"X-WP-Nonce": wpApiSettings.nonce,
							"Content-Type": "application/json",
						},
						body: JSON.stringify( { status: "ignored" } )
					} )
						.then( res => res.json() )
						.then( result => {
							if ( result.status === "success" ) {

								// Update the issue count when in dashboard.
								const productContainer = e.target.closest( ".wsh-issues-table__product");
								if ( productContainer !== null ){
									const issueCounterContainer = productContainer.querySelector("[data-id=\"issue-count\"]");
									if ( issueCounterContainer !== null ) {
										let counter = parseInt( issueCounterContainer.querySelector( ".issue-number").innerHTML );
										issueCounterContainer.querySelector( ".issue-number").innerHTML = counter - 1;
									}								
								}

								//delete the issue
								slowDelete(e.target.closest(".wsh-issues-table__issue"));

							}
						} );
				}
			}
		} );
	}
} );
