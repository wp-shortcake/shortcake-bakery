var wp = require('wp');

var addEmbedView = wp.media.View.extend({
	className: 'media-add-embed',

	// bind view events
	events: {
		'input':  'custom_update',
		'keyup':  'custom_update',
		'change': 'custom_update'
	},

	initialize: function() {

	    // create an input
		var form = jQuery( '<div></div>', {
			class: "embed-reverse"
		});

		var label = jQuery( '<label></label>', {
			class: "custom_embed"
		}).text( ShortcakeBakery.text.customEmbedLabel );

	    this.input = jQuery( '<textarea></textarea>', {
			name: 'custom_embed_code',
			class: 'custom-embed-entry',
			value: this.model.get('custom_embed_code')
		});

		this.noMatches = jQuery( '<p></p>', {
			class: 'error',
			style: 'display: none'
		}).text( ShortcakeBakery.text.noReversalMatches );

		label.appendTo(form);
		this.input.appendTo(form);
		this.noMatches.appendTo(form);

		// insert it in the view
	    this.$el.append(form);

	    // re-render the view when the model changes
	    this.model.on( 'change:custom_embed_code', this.render, this );
	    this.model.on( 'change:no_matches', this.toggle_no_matches, this );
	},

	render: function(){
	    this.input.value = this.model.get('custom_embed_code');
	    return this;
	},

	toggle_no_matches: function() {
		this.noMatches.toggle( this.model.get('no_matches') );
		return this;
	},

	custom_update: function( event ) {
		this.model.set( 'custom_embed_code', event.target.value );
	}
});

wp.media.view.addEmbed = addEmbedView;
module.exports = addEmbedView;
