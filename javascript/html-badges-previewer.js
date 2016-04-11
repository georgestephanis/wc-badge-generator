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

console.log('hi');
	//updateCSS( wp.customize('setting_camptix_html_badge_css').get() );  // todo calling too soon?

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

		console.log( newCSS );

		//badgeStyleElement.text( newCSS );   // todo need to reget after removing it?
		$( '#camptix-html-badges-css' ).text( newCSS ); // todo xss?
	}
} )( window.wp, jQuery );

