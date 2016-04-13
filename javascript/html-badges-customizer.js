wp.customize.CampTixHtmlBadgesCustomizer = ( function( $, api ) {
	'use strict';

	var self = {
		sectionID    : 'section_camptix_html_badges',
		cssSettingID : 'setting_camptix_html_badge_css',
		siteURL      : window.location.protocol + '//' + window.location.hostname,
		cmEditor     : null
	};

	self.badgesPageURL = self.siteURL + '?camptix-badges';

	$.extend( self, cbgHtmlCustomizerData );
	window.cbgHtmlCustomizerData = null;

	/**
	 * Initialize
	 */
	self.initialize = function() {
		api.section( self.sectionID ).container.bind( 'expanded',  self.loadBadgesPage   );
		api.section( self.sectionID ).container.bind( 'collapsed', self.unloadBadgesPage );
		api.section( self.sectionID ).container.bind( 'expanded',  self.setupCodeMirror  );

		$( '#customize-control-cbg_control_print_badges' ).find( 'input[type=button]' ).click( self.printBadges );
		$( '#customize-control-cbg_control_reset_css'    ).find( 'input[type=button]' ).click( self.resetCSS    );
	};

	/**
	 * Load the Badges pages when navigating to our Section
	 *
	 * @param {object} event
	 */
	self.loadBadgesPage = function( event ) {
		if ( self.badgesPageURL !== api.previewer.previewUrl.get() ) {
			api.previewer.previewUrl.set( self.badgesPageURL );
		}
	};

	/**
	 * Unload the Badges page when navigating away from our Section
	 *
	 * @param {object} event
	 */
	self.unloadBadgesPage = function( event ) {
		if ( self.badgesPageURL === api.previewer.previewUrl.get() ) {
			api.previewer.previewUrl.set( self.siteURL );
		}
	};

	/**
	 * Replace the plain textarea with a nice CSS editor
	 *
	 * @param {object} event
	 */
	self.setupCodeMirror = function( event ) {
		self.cmEditor = CodeMirror.fromTextArea(
			$( '#customize-control-setting_camptix_html_badge_css' ).find( 'textarea' ).get(0),
			{
				tabSize        : 2,
				indentWithTabs : true,
				lineWrapping   : true
			}
		);

		self.cmEditor.setSize( null, 'auto' );

		self.cmEditor.on( 'change', function() {
			api( self.cssSettingID ).set( self.cmEditor.getValue() );
		} );
	};

	/**
	 * Print the badges in the Previewer frame
	 *
	 * @param {object} event
	 */
	self.printBadges = function( event ) {
		window.frames[0].print();
	};

	/**
	 * Reset to the default CSS
	 *
	 * @param {object} event
	 */
	self.resetCSS = function( event ) {
		api( self.cssSettingID ).set( self.defaultCSS );    // todo high - xss?
		self.cmEditor.setValue( self.defaultCSS );          // todo high - xss?
	};

	api.bind( 'ready', self.initialize );
	return self;

} ( jQuery, wp.customize ) );
