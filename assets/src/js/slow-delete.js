/**
 * Applies a visual effect to a list item, then removes it from the DOM.
 *
 * This function highlights the list item with a red background, waits for a
 * brief period, then fades the item out before removing it from the DOM.
 *
 * @param {HTMLElement} listItem - The DOM element representing the list item to be deleted.
 *
 */
export function slowDelete( listItem ) {

	// Apply highlighting effect
	listItem.style.transition = "background-color 0.3s, opacity 0.3s";
	listItem.style.backgroundColor = "rgba(255, 0, 0, 0.5)";

	// Wait for 1 second, then fade out and remove the item
	setTimeout( () => {
		listItem.style.opacity = "0"; // Start fade-out effect

		// Remove the item after fade-out transition ends
		setTimeout( () => {
			listItem.remove();
		}, 300 ); // Match the duration of the fade-out effect
	}, 500 ); // Wait time before starting the fade-out effect
}
