wp.customize.CampTixHtmlBadgesPreviewer = ( function( $, api ) {
	'use strict';

	var self = {
		removedCss : false  // todo include in above [what did i mean by that?]       // todo rename?
	};

	// todo test in all browsers

	/**
	 * Initialize
	 */
	self.initialize = function() {
		try {
			api( 'setting_camptix_html_badge_css', function( value ) {
				value.bind( self.updateCSS );
			} );
		} catch( exception ) {
			self.log( exception );
		}
	};

	/**
	 * Update the CSS in the Previewer
	 *
	 * @param {string} newCSS
	 */
	self.updateCSS = function( newCSS ) {
		try {
			var badgeStyleElement = $( '#camptix-html-badges-css' );

			// todo clean this up

			if ( ! self.removedCss ) {
				badgeStyleElement.remove();
				$( '<style/>', {
					type: 'text/css',
					id: 'camptix-html-badges-css'
				} ).appendTo( 'head' );

				self.removedCss = true;
				badgeStyleElement = $( '#camptix-html-badges-css' );
			}

			badgeStyleElement.text( newCSS ); // todo xss?
		} catch( exception ) {
			self.log( exception );
		}
	};

	/**
	 * Log a message to the console
	 *
	 * @todo make DRY with CampTixHtmlBadgesCustomerizer
	 *
	 * @param {*} error
	 */
	self.log = function( error ) {
		var messageLabel = 'CampTix HTML Badges: ';

		if ( ! window.console ) {
			return;
		}

		if ( 'string' === typeof error ) {
			console.log( messageLabel + error );
		} else {
			console.log( messageLabel, error );
		}
	};

	self.initialize();
	return self;

} ( jQuery, wp.customize ) );
