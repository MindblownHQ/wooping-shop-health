export function initScanProgress( $el ){

	const progressBar = $el.querySelector('.progress-bar');
	const message = $el.querySelector('.message');
	const percentage = $el.querySelector('.percentage');

	//show our progress bar
	$el.classList.add('in-progress');

	//check progress every second
	const poll = setInterval(() => {

		//check a REST endpoint for progress
		fetch(wpApiSettings.root + "shop-health/v1/scan/progress", {
			method: "GET",
			headers: {
				"X-WP-Nonce": wpApiSettings.nonce,
				"Content-Type": "application/json",
			},
		})
			.then(res => res.json())
			.then(result => {

				//if we have a valid result
				if (result.status === "success") {

					let progress = result.percentage + '%';

					//set the percentage property, and the clear text.
					progressBar.style.setProperty('--percentage', progress);
					percentage.innerHTML = progress;
					message.innerHTML = result.message;

					//if we're at 100%, stop polling.
					if (result.percentage >= 100) {
						clearInterval(poll);
					}
				}
			});
	}, 1000);
}
