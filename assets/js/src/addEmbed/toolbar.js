var _               = require('underscore');
var wp              = require('wp');
var ShortcakeBakery = require('ShortcakeBakery');

var addEmbedToolbar = wp.media.view.Toolbar.extend({
	initialize : function() {
		_.defaults(this.options, {
			event: 'embedReverse',
			clone: false,
			items: {
				embedReverse: {
					text: ShortcakeBakery.text.insertButton,
					style: 'primary',
					priority: 80,
					requires: false,
					click: this.embedReverse
				}
			}
		});

		// Call 'initialize' directly on the parent class.
		wp.media.view.Toolbar.prototype.initialize.apply(this, arguments);
	},

	refresh : function() {
		var custom_embed_code = this.controller.state().props.get('custom_embed_code');
		var doing_ajax = this.controller.state().props.get('doing_ajax');

		this.get( 'embedReverse' ).model.set( 'disabled', ! custom_embed_code || doing_ajax );

		wp.media.view.Toolbar.prototype.refresh.apply( this, arguments );
	},

	embedReverse: function() {
		this.controller.state().embedReverse();
	}
});

wp.media.view.Toolbar.addEmbed = addEmbedToolbar;
module.exports = addEmbedToolbar;
