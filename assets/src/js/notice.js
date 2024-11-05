/**
 * Adds a notice in the WordPress Admin Dashboard
 *
 */
export function addNotice( message ) {

	let noticeElem = document.createElement('div');
	noticeElem.className = 'notice woocommerce-message';
	noticeElem.id = 'message';

	let noticeContent = document.createElement('p');
	noticeContent.textContent = message;

	noticeElem.appendChild(noticeContent);

	let targetContainer = document.querySelector('#woocommerce-layout__notice-catcher');
	if (targetContainer) {
		targetContainer.parentNode.insertBefore(noticeElem, targetContainer.nextSibling);
	}

	noticeElem.style.transition = "opacity 1s";

	// Wait for 5 seconds, then fade out and remove the item
	setTimeout( () => {
		noticeElem.style.opacity = "0"; // Start fade-out effect

		// Remove the item after fade-out transition ends
		setTimeout( () => {
			noticeElem.remove();
		}, 1000 ); // Match the duration of the fade-out effect
	}, 5000 ); // Wait time before starting the fade-out effect

}
