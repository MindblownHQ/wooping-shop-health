/**
 * This library creates a jQuery slideToggle() like effect. Can also be used with just slideDown() and slideUp().
 */

/**
 * Slide an element up.
 *
 * @param target The target element to toggle.
 * @param duration The duration of the animation in ms.
 */
export function slideUp( target, duration ) {
	target.style.transitionProperty = "height, margin, padding"; /* [1.1] */
	target.style.transitionDuration = duration + "ms"; /* [1.2] */
	target.style.boxSizing = "border-box"; /* [2] */
	target.style.height = target.offsetHeight + "px"; /* [3] */

	requestAnimationFrame( () => {
		target.style.height = 0; /* [4] */
		target.style.paddingTop = 0; /* [5.1] */
		target.style.paddingBottom = 0; /* [5.2] */
		target.style.marginTop = 0; /* [6.1] */
		target.style.marginBottom = 0; /* [7.2] */
		target.style.overflow = "hidden"; /* [7] */
	} );

	window.setTimeout( () => {
		target.style.display = "none"; /* [8] */
		target.style.removeProperty( "height" ); /* [9] */
		target.style.removeProperty( "padding-top" );  /* [10.1] */
		target.style.removeProperty( "padding-bottom" );  /* [10.2] */
		target.style.removeProperty( "margin-top" );  /* [11.1] */
		target.style.removeProperty( "margin-bottom" );  /* [11.2] */
		target.style.removeProperty( "overflow" );  /* [12] */
		target.style.removeProperty( "transition-duration" );  /* [13.1] */
		target.style.removeProperty( "transition-property" );  /* [13.2] */
	}, duration );
}

/**
 * Slide an element down.
 *
 * @param target The target element to toggle.
 * @param duration The duration of the animation in ms.
 * @param options Allow for extra options to be passed.
 */
export function slideDown( target, duration, options = { display: "block" } ) {
	target.style.removeProperty( "display" ); /* [1] */
	let display = window.getComputedStyle( target ).display;

	if ( display === "none" ) { /* [2] */
		display = options.display;
	}
	target.style.display = display;

	let height = target.clientHeight; /* [3] */
	target.style.height = 0; /* [4] */
	target.style.paddingTop = 0; /* [5.1] */
	target.style.paddingBottom = 0; /* [5.2] */
	target.style.marginTop = 0; /* [6.1] */
	target.style.marginBottom = 0; /* [6.2] */
	target.style.overflow = "hidden"; /* [7] */

	requestAnimationFrame( () => {
		target.style.boxSizing = "border-box"; /* [8] */
		target.style.transitionProperty = "height, margin, padding";  /* [9.1] */
		target.style.transitionDuration = duration + "ms"; /* [9.2] */
		target.style.height = height + "px"; /* [10] */
		target.style.removeProperty( "padding-top" ); /* [11.1] */
		target.style.removeProperty( "padding-bottom" ); /* [11.2] */
		target.style.removeProperty( "margin-top" ); /* [12.1] */
		target.style.removeProperty( "margin-bottom" ); /* [12.2] */
	} );

	window.setTimeout( () => {
		target.style.removeProperty( "height" ); /* [13] */
		target.style.removeProperty( "overflow" ); /* [14] */
		target.style.removeProperty( "transition-duration" ); /* [15.1] */
		target.style.removeProperty( "transition-property" ); /* [15.2] */
	}, duration );
}

/**
 * Side an element up or down depending on the current state.
 *
 * @param target The target element to toggle.
 * @param duration The duration of the animation in ms.
 * @param options Allow for extra options to be passed.
 */
export function slideToggle( target, duration = 300, options = { display: "block" } ) {
	if ( window.getComputedStyle( target ).display === "none" ) {
		slideDown( target, duration, options );
	} else {
		slideUp( target, duration );
	}
}
