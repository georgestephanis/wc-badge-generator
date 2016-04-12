( function( wp, $ ) {
	'use strict';

	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize;
	var removedCss = false; // todo include in above        // todo rename?
	
	// todo add try/catch
	// todo test in all browsers

	api( 'setting_camptix_html_badge_css', function( value ) {
		value.bind( function( newCSS ) {
			updateCSS( newCSS );
		} );
	} );

	/**
	 * Update the CSS in the Previewer
	 * 
	 * @param {string} newCSS
	 */
	function updateCSS( newCSS ) {
		var badgeStyleElement = $( '#camptix-html-badges-css' );

		// todo clean this up

		if ( ! removedCss ) {
			badgeStyleElement.remove();
			$( '<style/>', {
				type: 'text/css',
				id: 'camptix-html-badges-css'
			} ).appendTo( 'head' );

			removedCss = true;
			badgeStyleElement = $( '#camptix-html-badges-css' );
		}

		badgeStyleElement.text( newCSS ); // todo xss?
	}
} )( window.wp, jQuery );
