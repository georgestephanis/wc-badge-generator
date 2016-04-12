( function( wp, $ ) {
	'use strict';

	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize;
	
	// todo add try/catch
	
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

			
			if ( ! urlParams.hasOwnProperty( 'url' ) ) {    // todo maybe need to be mroe specific, make sure it has camptix-badges in url
				// todo when open customizer directly, then click on section, need to set on previerwe url to `{site_url}?camptix-badges`
				// might need to be somewhere different than here, like onclick handler or something
				// window.parent.location = ...;

				// when opening customize.php manually and browsing to the section, detect if url has query param, if doesn't, then redirect previewer frame to that url

				// when navigating away from this section, need to refresh previewer w/out our URL, to add styles back etc

				// add click handler on section, or maybe the API already provides a way for that
					// maybe check section.expanded, listen for click event, override onChangeExpanded
					// window.parent.location = 'https://2014.content.wordcamp.dev/wp-admin/customize.php?camptix-html-badges&url=https%3A%2F%2F2014.content.wordcamp.dev%3Fcamptix-badges';   // todo dynamic
			}
		}
	} );

	/**
	 * todo
	 */
	api.controlConstructor.button = api.Control.extend( {
		/**
		 * Initialize the control after it's loaded
		 */
		ready : function() {
			if ( 'cbg_control_print_badges' !== this.id ) {
				return;
			}

			$( '#customize-control-cbg_control_print_badges' ).find( 'input[type=button]' ).click( function() {
				window.frames[0].print();
			} );
		}
	} );

	/**
	 * todo
	 *
	 * todo high - this does overwrite the earlier button and breaks it
	api.controlConstructor.button = api.Control.extend( {
		/**
		 * Initialize the control after it's loaded
		 *
		ready : function() {
			if ( 'cbg_control_reset_css' !== this.id ) {
				return;
			}

			$( '#customize-control-cbg_control_reset_css' ).find( 'input[type=button]' ).click( function() {
				//api( 'setting_camptix_html_badge_css' ).
				// todo i think there's a way through api() to reset to orig value, or at least retrieve orig value and set()
					// doesn't look like it's available in JS anywhere, just make your own by using data attribute or a js var or something
					// can use new 4.5 include_script whatever instead of localize_script
			} );

			// todo can i extend button twice? think i might need to just do it once, and maybe even shouldn't do it, b/c what if other plugin does it too?
				// it works, but probably overwrites the first one, so don't want that. could just have a single one, but there's probably a better practice
				// look at how good plugins do it
		}
	} );*/

	/**
	 * todo
	 */
	api.controlConstructor.textarea = api.Control.extend( {
		/**
		 * Initialize the control after it's loaded
		 */
		ready : function() {
			if ( 'setting_camptix_html_badge_css' !== this.id ) {
				return;
			}

			api.section( 'section_camptix_html_badges' ).container.bind( 'expanded', function() {
				// todo should do this from the section? prob not
				
				var cmEditor = CodeMirror.fromTextArea(
					$( '#customize-control-setting_camptix_html_badge_css' ).find( 'textarea' ).get(0),
					{
						tabSize        : 2,
						indentWithTabs : true,
						lineWrapping   : true
					}
				);

				// todo set height? seems to be done automatically, but maybe want it 100% insetad of fixed 500px
				//cmEditor.setSize( null, 400 );  // todo high - probably don't need, or want to do 100%

				// Update the Customizer textarea when the CodeMirror textarea changes
				cmEditor.on( 'change', _.bind( function( editor ) {
					api( 'setting_camptix_html_badge_css' ).set( editor.getValue() );
				}, this ) );
			} );
		}
	} );
	
	/**
	 * todo make dry with site cloner?
	 * todo still using this now that have autofocus? maybe for url param
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
