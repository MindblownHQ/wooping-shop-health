document.addEventListener("DOMContentLoaded", () => {
	// Select the specific "Deactivate" links for the Wooping Shop Health plugin
	const deactivationLinks = document.querySelectorAll('#deactivate-shop-health, #deactivate-wooping-shop-health');

	deactivationLinks.forEach(link => {
		if (link) {
			link.addEventListener('click', function (event) {
				//@todo, make this string translatable:
				const confirmation = confirm('Are you sure you wish to deactivate Wooping Shop Health? All of your issue-data will be removed.');
				if (!confirmation) {
					// Prevent the default action of the link if the user cancels
					event.preventDefault();
				}
			});
		}
	});
});
