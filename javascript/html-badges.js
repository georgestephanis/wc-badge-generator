( function( wp, $ ) {
	'use strict';

	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize;

	/**
	 * The CampTix HTML Badges panel
	 */
	api.sectionConstructor.cbgSection = api.Section.extend( {
		/**
		 * Open this section when it's directly requested via a URL parameter
		 */
		ready : function() {
			var urlParams = getUrlParams( window.location.href );

			// todo could just do this with string search and get rid of the function?

			if ( urlParams.hasOwnProperty( 'camptix-html-badges' ) ) {
				this.expand();
			}

			// todo when open customizer directly, then click on section, need to set on previerwe url to `{site_url}?camptix-badges`
				// might need to be somewhere different than here, like onclick handler or something
		}
	} );

	/**
	 * todo make dry with site cloner?
	 *
	 * Parse the URL parameters
	 *
	 * Based on https://stackoverflow.com/a/2880929/450127
	 *
	 * @param {string} url
	 *
	 * @returns {object}
	 */
	function getUrlParams( url ) {
		var match, questionMarkIndex, query,
			urlParams = {},
			pl        = /\+/g,  // Regex for replacing addition symbol with a space
			search    = /([^&=]+)=?([^&]*)/g,
			decode    = function ( s ) {
				return decodeURIComponent( s.replace( pl, " " ) );
			};

		questionMarkIndex = url.indexOf( '?' );

		if ( -1 === questionMarkIndex ) {
			return urlParams;
		} else {
			query = url.substring( questionMarkIndex + 1 );
		}

		while ( match = search.exec( query ) ) {
			urlParams[ decode( match[ 1 ] ) ] = decode( match[ 2 ] );
		}

		return urlParams;
	}
} )( window.wp, jQuery );
