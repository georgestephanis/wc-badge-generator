wp.customize.CampTixHtmlBadgesPreviewer = ( function( $, api ) {
	'use strict';

	var self = {
		removedCSS : false
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
			var badgeStyleID      = 'camptix-html-badges-css',
			    badgeStyleElement = $( '#' + badgeStyleID );

			// In order to set the content of a <style> element, you first have to remove it and re-create it.
			if ( ! self.removedCSS ) {
				badgeStyleElement.remove();

				$( '<style />', {
					type : 'text/css',
					id   : badgeStyleID
				} ).appendTo( 'head' );

				self.removedCSS   = true;
				badgeStyleElement = $( '#' + badgeStyleID );
			}

			badgeStyleElement.text( newCSS ); // todo high - xss?
		} catch( exception ) {
			self.log( exception );
		}
	};

	/**
	 * Log a message to the console
	 *
	 * @todo make DRY with CampTixHtmlBadgesCustomeizer
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
