/* =========================================================
 * css_editor.js v1.0.1
 * =========================================================
 * Copyright 2014 Wpbakery
 *
 * Shortcodes css editor for edit form backbone/underscore version
 * ========================================================= */
// Safety first
/** global window.i18nLocale */
if ( _.isUndefined( window.vc ) ) {
	var vc = { atts: {} };
}
(function ( $ ) {
	var preloaderUrl = ajaxurl.replace( /admin\-ajax\.php/, 'images/wpspin_light.gif' ),
		template_options = {
			evaluate: /<#([\s\S]+?)#>/g,
			interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
			escape: /\{\{([^\}]+?)\}\}(?!\})/g
		},
		removeOldDesignOptions;
	
	/**
	 * Css editor view.
	 * @type {*}
	 */
	var VcCssEditor;
	VcCssEditor = vc.CssEditor = Backbone.View.extend( {
		attrs: {},
		layouts: [
			'margin',
			'border-width',
			'padding'
		],
		positions: [
			'top',
			'right',
			'bottom',
			'left'
		],
		$field: false,
		simplify: false,
		$simplify: false,
		events: {
			'change .vc_simplify': 'changeSimplify'
		},
		initialize: function () {
			// _.bindAll(wp.media.vc_css_editor, 'open');
			_.bindAll( this, 'setSimplify' )
		},
		render: function ( value ) {
			this.attrs = {};
			this.$simplify = this.$el.find( '.vc_simplify' );
			_.isString( value ) && this.parse( value );
			// wp.media.vc_css_editor.init(this);
			return this;
		},
		parse: function ( value ) {
			var data_split = value.split( /\s*(\.[^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/g );
			data_split[ 2 ] && this.parseAtts( data_split[ 2 ].replace( /\s+!important/g, '' ) );
		},
		changeSimplify: function () {
			var f = _.debounce( this.setSimplify, 100 );
			f && f();
		},
		setSimplify: function () {
			this.simplifiedMode( this.$simplify.is( ':checked' ) );

		},
		simplifiedMode: function ( enable ) {
			if ( enable ) {
				this.simplify = true;
				this.$el.addClass( 'vc_simplified' );
			} else {
				this.simplify = false;
				this.$el.removeClass( 'vc_simplified' );
				_.each( this.layouts, function ( attr ) {
					if ( attr === 'border-width' ) {
						attr = 'border';
					}
					var $control = $( '[data-attribute=' + attr + '].vc_top' );
					this.$el.find( '[data-attribute=' + attr + ']:not(.vc_top)' ).val( $control.val() );
				}, this );
			}
		},
		removeImage: function ( e ) {
			var $control = $( e.currentTarget );
			e.preventDefault();
			$control.parent().remove();
		},
		parseAtts: function ( string ) {
			var border_regex, background_regex, background_size;
			border_regex = /(\d+\S*)\s+(\w+)\s+([\d\w#\(,]+)/;
			background_regex = /^([^\s]+)\s+url\(([^\)]+)\)([\d\w]+\s+[\d\w]+)?$/;
			background_size = false;
			_.map( string.split( ';' ), function ( val ) {
				var val_s = val.split( /:\s/ ), val_pos, border_split, background_split,
					value = val_s[ 1 ] || '',
					name = val_s[ 0 ] || '';
				if ( value ) {
					value = value.trim();
				}
				if ( name.match( new RegExp( '^(' + this.layouts.join( '|' ).replace( '-',
						'\\-' ) + ')$' ) ) && value ) {
					val_pos = value.split( /\s+/g );
					if ( val_pos.length == 1 ) {
						val_pos = [
							val_pos[ 0 ],
							val_pos[ 0 ],
							val_pos[ 0 ],
							val_pos[ 0 ]
						];
					} else if ( val_pos.length === 2 ) {
						val_pos[ 2 ] = val_pos[ 0 ];
						val_pos[ 3 ] = val_pos[ 1 ];
					} else if ( val_pos.length === 3 ) {
						val_pos[ 3 ] = val_pos[ 1 ];
					}
					_.each( this.positions, function ( pos, key ) {
						this.$el.find( '[data-name=' + name + '-' + pos + ']' ).val( val_pos[ key ] );
					}, this );
				} else if ( name == 'border' && value && value.match( border_regex ) ) {
					border_split = value.split( border_regex );
					val_pos = [
						border_split[ 1 ],
						border_split[ 1 ],
						border_split[ 1 ],
						border_split[ 1 ]
					];
					_.each( this.positions, function ( pos, key ) {
						this.$el.find( '[name=' + name + '_' + pos + '_width]' ).val( val_pos[ key ] );
					}, this );
					this.$el.find( '[name=border_style]' ).val( border_split[ 2 ] );
					this.$el.find( '[name=border_color]' ).val( border_split[ 3 ] ).trigger( 'change' );
				} else if ( name.indexOf( 'border' ) != - 1 && value ) {
					if ( name.indexOf( 'style' ) != - 1 ) {
						this.$el.find( '[name=border_style]' ).val( value );
					} else if ( name.indexOf( 'color' ) != - 1 ) {
						this.$el.find( '[name=border_color]' ).val( value ).trigger( 'change' );
					} else if ( name.match( /^[\w\-\d]+$/ ) ) {
						this.$el.find( '[name=' + name.replace( /\-+/g, '_' ) + ']' ).val( value );
					}
				} else if ( name.match( /^[\w\-\d]+$/ ) && value ) {
					this.$el.find( '[name=' + name.replace( /\-+/g, '_' ) + ']' ).val( value );
				}
			}, this );
		},
		save: function () {
			var string = '';
			this.attrs = {};
			_.each( this.layouts, function ( type ) {
				this.getFields( type )
			}, this );
			this.getBorder();
			if ( ! _.isEmpty( this.attrs ) ) {
				string = '.vc_custom_' + (+ new Date) + '{' + _.reduce( this.attrs, function ( memo, value, key ) {
					return value ? memo + key + ': ' + value + ' !important;' : memo;
				}, '', this ) + '}';
			}
			string && vc.frame_window && vc.frame_window.vc_iframe.setCustomShortcodeCss( string );
			return string;
		},
		getBorder: function () {
			var style = this.$el.find( '[name=border_style]' ).val(),
				color = this.$el.find( '[name=border_color]' ).val();
			var sides = [
				'left',
				'right',
				'top',
				'bottom'
			];
			if ( style && color && this.attrs[ 'border-width' ] && this.attrs[ 'border-width' ].match( /^\d+\S+$/ ) ) {
				this.attrs.border = this.attrs[ 'border-width' ] + ' ' + style + ' ' + color;
				this.attrs[ 'border-width' ] = undefined;
			} else {
				_.each( sides, function ( side ) {
					if ( this.attrs[ 'border-' + side + '-width' ] ) {
						if ( color ) {
							this.attrs[ 'border-' + side + '-color' ] = color;
						}
						if ( style ) {
							this.attrs[ 'border-' + side + '-style' ] = style;
						}
					}
				}, this );
			}
		},
		getFields: function ( type ) {
			var data = [];
			if ( this.simplify ) {
				return this.getSimplifiedField( type );
			}
			_.each( this.positions, function ( pos ) {
				var val = this.$el.find( '[data-name=' + type + '-' + pos + ']' ).val().replace( /\s+/, '' );
				if ( ! val.match( /^\d*(\.\d?){0,1}(%|in|cm|mm|em|rem|ex|pt|pc|px|vw|vh|vmin|vmax)$/ ) ) {
					val = (isNaN( parseFloat( val ) ) ? '' : '' + parseFloat( val ) + 'px');
				}
				val.length && data.push( { name: pos, val: val } );
			}, this );
			_.each( data, function ( attr ) {
				var attr_name = type == 'border-width' ? 'border-' + attr.name + '-width' : type + '-' + attr.name;
				this.attrs[ attr_name ] = attr.val;
			}, this );
		},
		getSimplifiedField: function ( type ) {
			var pos = 'top',
				val = this.$el.find( '[data-name=' + type + '-' + pos + ']' ).val().replace( /\s+/, '' );
			if ( ! val.match( /^-?\d*(\.\d?){0,1}(%|in|cm|mm|em|rem|ex|pt|pc|px|vw|vh|vmin|vmax)$/ ) ) {
				val = (isNaN( parseFloat( val ) ) ? '' : '' + parseFloat( val ) + 'px');
			}
			if ( val.length ) {
				this.attrs[ type ] = val;
			}
		}
	} );
	/**
	 * Add new param to atts types list for vc
	 * @type {Object}
	 */
	vc.atts.css_editor = {
		parse: function ( param ) {
			var $field, css_editor, result;
			$field = this.content().find( 'input.wpb_vc_param_value[name="' + param.param_name + '"]' );
			css_editor = $field.data( 'vcFieldManager' );
			result = css_editor.save();

			if ( result ) {
				vc.edit_form_callbacks.push( removeOldDesignOptions );
			}
			return result;
		},
		init: function ( param, $field ) {
			/**
			 * Find all fields with css_editor type and initialize.
			 */
			$( '[data-css-editor=true]', this.content() ).each( function () {
				var $editor = $( this ),
					$param = $editor.find( 'input.wpb_vc_param_value[name="' + param.param_name + '"]' ),
					value = $param.val();
				if ( ! value ) {
					value = parseOldDesignOptions();
				}
				$param.data( 'vcFieldManager', new VcCssEditor( { el: $editor } ).render( value ) );
			} );
			vc.atts.colorpicker.init.call( this, param, $field );
		}
	};
	/**
	 * Backward capability for old css attributes
	 * @return {String} - Css settings with class name and css attributes settings.
	 */
	var parseOldDesignOptions = function () {
		var keys = {
				'padding': 'padding',
				'margin_bottom': 'margin-bottom'
			},
			params = vc.edit_element_block_view.model.get( 'params' ),
			cssString = _.reduce( keys, function ( memo, css_name, attr_name ) {
				var value = params[ attr_name ];
				if ( _.isUndefined( value ) || ! value.length ) {
					return memo;
				}
				return memo + css_name + ': ' + value + ';';
			}, '', this );
		return cssString ? '.tmp_class{' + cssString + '}' : '';
	};
	removeOldDesignOptions = function () {
		this.params = _.omit( this.params, 'padding', 'margin_bottom' );
	};

})( window.jQuery );