( function( wp, $ ) {
	'use strict';

	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize;
	var removedCss = false; // todo include in above
	
	// todo add try/catch
	// todo errors in dev console. unrelated?
	// todo test in all browsers


	// todo do this on load too
		updateCSS( 'body{ background-color: yellow; }' ); // todo probably running too early? need to wait until frame fully loaded?
		// have php preload it w/ the customizer value instead of the default, so don't get flash unstyled content

	api( 'setting_camptix_html_badge_css', function( value ) {
		value.bind( function( newCSS ) {
			updateCSS( newCSS );
		} );
	} );

	// todo jsdoc
	function updateCSS( newCSS ) {
		var badgeStyleElement = $( '#camptix-html-badges-css' );

		if ( ! removedCss ) {
			badgeStyleElement.remove();
			$( '<style/>', {
				type: 'text/css',
				id: 'camptix-html-badges-css'
			} ).appendTo( 'head' );


			removedCss = true;
		}

		//badgeStyleElement.text( newCSS );   // todo need to reget after removing it?
		$( '#camptix-html-badges-css' ).text( newCSS );
	}
} )( window.wp, jQuery );

