
wp.media.controller.addEmbed = wp.media.controller.State.extend({

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
				send_to_editor( response.data );
				self.reset();
				self.frame.close();
			} else {
				self.props.set( 'no_matches', true );
				self.refresh();
			}
		});


	},

});

wp.media.view.Toolbar.addEmbed = wp.media.view.Toolbar.extend({
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

wp.media.view.addEmbed = wp.media.View.extend({
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

/**
 * Wait until document.ready so that we can add our panels and controller to
 * the media frame after Shortcode adds its menu item and toolbars.
 *
 */
jQuery( document ).ready( function( $ ) {

	var postMediaFrame = wp.media.view.MediaFrame.Post;

	var mediaFrame = postMediaFrame.extend( {

		initialize: function() {

			postMediaFrame.prototype.initialize.apply( this, arguments );

			var id = 'shortcake-bakery-embed';

			this.states.add([
				new wp.media.controller.addEmbed({
					id:         id,
					menu:       'default', // menu event = menu:render:default
					content:    id + '-content-insert',
					title:      ShortcakeBakery.text.addEmbed,
					priority:   100,
					toolbar:    id + '-toolbar',
					type:       'link'
				})
			]);

			this.on( 'content:render:' + id + '-content-insert', _.bind( this.contentRender, this, 'shortcake-bakery-embed', 'insert' ) );
			this.on( 'toolbar:create:' + id + '-toolbar', this.toolbarCreate, this );
			this.on( 'toolbar:render:' + id + '-toolbar', this.toolbarRender, this );
			this.on( 'menu:render:default', this.renderShortcakeBakeryMenu );

		},

		events: function() {
			return _.extend( {}, postMediaFrame.prototype.events, {
			} );
		},

		contentRender : function( id, tab ) {
			this.$el.addClass('hide-router');

			var view = new wp.media.view.addEmbed({
				controller: this,
				model: this.state().props
			});

			this.content.set( view );
		},

		toolbarRender: function( toolbar ) {},

		toolbarCreate : function( toolbar ) {
			toolbar.view = new wp.media.view.Toolbar.addEmbed({
				controller: this
			});
		},

		renderShortcakeBakeryMenu: function( view ) {

			// Add a menu separator link.
			view.set({
				'shortcake-bakery-embed-separator': new wp.media.View({
					className: 'separator',
					priority: 105
				})
			});

		},

		insertAction: function() {
			this.controller.state().insert();
		},

	} );

	wp.media.view.MediaFrame.Post = mediaFrame;
});
