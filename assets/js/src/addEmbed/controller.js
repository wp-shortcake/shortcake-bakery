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
			post_id: wp.media.view.settings.post.id,
			_wpnonce: ShortcakeBakery.nonces.customEmbedReverse
		});

		promise.then(function( response ) {
			self.props.set( 'doing_ajax', false );

			if ( response.success ) {
				send_to_editor( response.reversal );
				self.reset();
				self.frame.close();
			} else {
				self.props.set( 'no_matches', true );
				self.refresh();
			}
		});
	},

});

wp.media.controller.addEmbed = addEmbedController;
module.exports = addEmbedController;
