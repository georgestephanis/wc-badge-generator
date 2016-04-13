wp.customize.CampTixHtmlBadgesPreviewer = ( function( $, api ) {
	'use strict';

	var self = {
		removedCSS : false
	};
	
	/**
	 * Initialize
	 */
	self.initialize = function() {
		api( 'setting_camptix_html_badge_css', function( value ) {
			value.bind( self.updateCSS );
		} );
	};

	/**
	 * Update the CSS in the Previewer
	 *
	 * @param {string} newCSS
	 */
	self.updateCSS = function( newCSS ) {
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
	};

	self.initialize();
	return self;

} ( jQuery, wp.customize ) );
