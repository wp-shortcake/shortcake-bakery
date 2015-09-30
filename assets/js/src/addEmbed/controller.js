var wp = require('wp');
var Backbone = require('Backbone');
var $ = require('jquery');

var addEmbedController = wp.media.controller.State.extend({

	initialize: function(){
		this.props = new Backbone.Model({
			custom_embed_code: '',
			doing_ajax: false,
			no_matches: false
		});

		this.props.on( 'change:custom_embed_code', this.refresh, this );
	},

	refresh: function() {
		if ( this.frame && this.frame.toolbar ) {
			this.frame.toolbar.get().refresh();
		}
	},

	embedReverse: function() {
		var self = this;

		this.props.set( 'doing_ajax', true );
		this.refresh();

		var promise = jQuery.post(ajaxurl + '?action=shortcake_bakery_embed_reverse', {
			custom_embed_code: this.props.get( 'custom_embed_code' ),
			_wpnonce: ShortcakeBakery.nonces.customEmbedReverse
		});

		promise.then(function( response ) {
			self.props.set( 'doing_ajax', false );

			if ( response.success ) {
				self.reset();
				self.frame.close();

				// If there's UI registered for this shortcode, open the Shortcode UI form
				if ( currentShortcode = self.getShortcode( response.shortcodes[0] ) ) {
					var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
						frame : "post",
						state : 'shortcode-ui',
						currentShortcode : currentShortcode,
					});
					wp_media_frame.open();

				// Elsewise, just send the shortcode to the editor.
				} else {
					send_to_editor( response.reversal );
				}
			} else {
				self.props.set( 'no_matches', true );
				self.refresh();
			}
		});
	},

	/**
	 * Given a reversal result, check if it matches a shortcode registered with
	 * Shortcake.
	 *
	 * Some of this logic was taken from Shortcake, where it's not exposed.
	 *
	 * @param obj a "shortcode" returned from the embed_reversal ajax action
	 * @return obj Shortcake object to set as currentShortcode for editing.
	 */
	getShortcode: function( reversalShortcode ) {
		defaultShortcode = sui.shortcodes.findWhere({
			shortcode_tag : reversalShortcode.shortcode
		});

		if ( ! defaultShortcode ) {
			return;
		}

		currentShortcode = defaultShortcode.clone();
		var attributes_backup = {};

		if ( _.size( reversalShortcode.attributes ) ) {
			_.each( reversalShortcode.attributes, function( attrValue, attrKey ) {
				attr = currentShortcode.get( 'attrs' ).findWhere( { attr: attrKey } );

				// If attribute found - set value. Else, back up into
				// attributes_backup so it doesn't get overwritten.
				if ( attr ) {
					attr.set( 'value', attrValue );
				} else {
					attributes_backup[ attrKey ] = attrValue;
				}
			} );
		}

		currentShortcode.set( 'attributes_backup', attributes_backup );

		if ( reversalShortcode.inner_content ) {
			var inner_content = currentShortcode.get( 'inner_content' );
			if ( inner_content ) {
				inner_content.set( 'value', reversalShortcode.inner_content );
			} else {
				currentShortcode.set( 'inner_content_backup', reversalShortcode.inner_content );
			}
		}

		return currentShortcode;
	}
});

wp.media.controller.addEmbed = addEmbedController;
module.exports = addEmbedController;
