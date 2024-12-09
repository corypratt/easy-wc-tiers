(function( $ ) {
	'use strict';
	let isPageUnloading = false;

	$( window ).on( 'beforeunload', function () {
		isPageUnloading = true;
	} );
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(function() {
		// Set up attributes, if current page has the attributes list.
		const $product_tiers = $( '.product_tiers' );
		if ( $product_tiers.length === 1 ) {
			// When the attributes tab is shown, add an empty attribute to be filled out by the user.
			$( '#ewt_product_tiers' ).on( 'woocommerce_tab_shown', function() {
				remove_blank_custom_tier_if_no_other_tiers();
				const woocommerce_tier_items = $product_tiers.find( '.woocommerce_tier' ).get();
// console.log( woocommerce_tier_items.length );
				// If the product has no attributes, add an empty attribute to be filled out by the user.
				if ( woocommerce_tier_items.length === 0  ) {
					add_custom_tier_to_list();
				}
			} );
		}

		function add_custom_tier_to_list() {
			add_tier_to_list();
		}
	
		async function add_tier_to_list( globalAttributeId ) {
			try {
				block_tiers_tab_container();
	
				const numberOfAttributesInList = $(
					'.product_tiers .woocommerce_tier'
				).length;
				const newAttributeListItemHtml =
					await get_new_tier_list_item_html(
						numberOfAttributesInList,
						globalAttributeId
					);
	
				const $attributesListContainer = $(
					'#ewt_product_tiers .product_tiers'
				);
	
				const $attributeListItem = $( newAttributeListItemHtml ).appendTo(
					$attributesListContainer
				);
	
				update_attribute_row_indexes();
	
				toggle_expansion_of_attribute_list_item( $attributeListItem );
	
				$( document.body ).trigger( 'woocommerce_added_attribute' );
	
				jQuery.maybe_disable_save_button();
			} catch ( error ) {
				if ( isPageUnloading ) {
					// If the page is unloading, the outstanding ajax fetch may fail in Firefox (and possible other browsers, too).
					// We don't want to show an error message in this case, because it was caused by the user leaving the page.
					return;
				}
	
				alert( woocommerce_admin_meta_boxes.i18n_add_attribute_error_notice );
				throw error;
			} finally {
				unblock_tiers_tab_container();
			}
		}
	
		function get_new_tier_list_item_html(
			indexInList,
			globalAttributeId
		) {
			return new Promise( function ( resolve, reject ) {
				$.post( {
					url: easy_wc_tiers.ajax_url,
					data: {
						action: 'ewt_add_tier',
						i: indexInList,
						security: easy_wc_tiers.add_tier_nonce,
					},
					success: function ( newTierListItemHtml ) {
						resolve( newTierListItemHtml );
					},
					error: function ( jqXHR, textStatus, errorThrown ) {
						reject( { jqXHR, textStatus, errorThrown } );
					},
				} );
			} );
		}
		function block_tiers_tab_container() {
			const $tierTabContainer = $( '#ewt_product_tiers' );
	
			$tierTabContainer.block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6,
				},
			} );
		}
	
		function unblock_tiers_tab_container() {
			const $attributesTabContainer = $( '#ewt_product_tiers' );
			$attributesTabContainer.unblock();
		}
	
		function update_attribute_row_indexes() {
			$( '.product_tiers .woocommerce_tier' ).each( function (
				index,
				el
			) {
				let i = parseInt( $( el ).index('.product_tiers .woocommerce_tier' ), 10 );
				$( '.attribute_position', el ).val(i).attr('name', 'tier_position[' + i + ']');
				$('.min-qty', el).attr('name', 'min_qty[' + i + ']');
				$('.max-qty', el).attr('name', 'max_qty[' + i + ']');
				$('.discount-type', el).attr('name', 'ewt_discount_type[' + i + ']');
				$('.discount', el).attr('name', 'discount[' + i + ']');
			} );
		}
	
		function toggle_expansion_of_attribute_list_item( $attributeListItem ) {
			$attributeListItem.find( 'h3' ).trigger( 'click' );
		}

		function add_custom_tier_to_list() {
			add_tier_to_list();
		}

		// Add rows.
		$( 'button.add_custom_tier' ).on( 'click', function () {
			add_custom_tier_to_list();

			return false;
		} );
	
		$( '#ewt_product_tiers' ).on(
			'click',
			'.product_tiers .remove_row',
			function () {
				var $parent = $( this ).parent().parent();
	
				if ( window.confirm( woocommerce_admin_meta_boxes.i18n_remove_used_attribute_confirmation_message ) ) {
					if ( $parent.is( '.taxonomy' ) ) {
						$parent.find( 'select, input[type=text]' ).val( '' );
						$parent.hide();
						$( 'select.attribute_taxonomy' )
							.find(
								'option[value="' + $parent.data( 'taxonomy' ) + '"]'
							)
							.prop( 'disabled', false );
						selectedAttributes = selectedAttributes.filter(
							( attr ) => attr !== $parent.data( 'taxonomy' )
						);
						$( 'select.wc-attribute-search' ).data(
							'disabled-items',
							selectedAttributes
						);
					} else {
						$parent.find( 'select, input[type=text]' ).val( '' );
						$parent.hide();
						update_attribute_row_indexes();
					}
	
					$parent.remove();
	
					window.wcTracks.recordEvent( 'product_attributes_buttons', {
						action: 'remove_attribute',
					} );
	
					jQuery.maybe_disable_save_button();
				}
				return false;
			}
		);

		// Save attributes and update variations.
		$( '.save_tiers' ).on( 'click', function ( event ) {
			if ( $( this ).hasClass( 'disabled' ) ) {
				event.preventDefault();
				return;
			}
			$( '.product_tiers' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6,
				},
			} );

			var original_data = $( '.product_tiers' ).find(
				'input, select, textarea'
			);
			var data = {
				post_id: woocommerce_admin_meta_boxes.post_id,
				data: original_data.serialize(),
				action: 'ewt_save_tiers',
				security: easy_wc_tiers.save_tier_nonce,
			};

			$.post(
				easy_wc_tiers.ajax_url,
				data,
				function ( response ) {
					if ( response.error ) {
						// Error.
						window.alert( response.error );
					} else if ( response.data ) {
						// Success.
						$( '.product_tiers' ).html( response.data.html );
						$( '.product_tiers' ).unblock();

						// Hide the 'Used for variations' checkbox if not viewing a variable product
						// show_and_hide_panels();

						$( document.body ).trigger(
							'woocommerce_tiers_saved'
						);
					}
				}
			);
		} );

		// Attribute ordering.
		$( '.product_tiers' ).sortable( {
			items: '.woocommerce_tier',
			cursor: 'move',
			axis: 'y',
			handle: 'h3',
			scrollSensitivity: 40,
			forcePlaceholderSize: true,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wc-metabox-sortable-placeholder',
			start: function ( event, ui ) {
				ui.item.css( 'background-color', '#f6f6f6' );
			},
			stop: function ( event, ui ) {
				ui.item.removeAttr( 'style' );
				update_attribute_row_indexes();
			},
		} );


		/**
		 * Function to maybe disable the save button.
		 */
		$.maybe_disable_save_tiers_button = function () {
			let $tab = $( '.product_tiers' );
			let $save_button = $( 'button.save_tiers' );

			var attributes_and_variations_data = $tab.find(
				'input, select, textarea'
			);
			if (
				jQuery.is_attribute_or_variation_empty(
					attributes_and_variations_data
				)
			) {
				if ( ! $save_button.hasClass( 'disabled' ) ) {
					$save_button.addClass( 'disabled' );
					$save_button.attr( 'aria-disabled', true );
				}
			} else {
				$save_button.removeClass( 'disabled' );
				$save_button.removeAttr( 'aria-disabled' );
			}
		};
		$( '#product_tiers' ).on(
			'change',
			$.maybe_disable_save_button
		);

		$.maybe_disable_save_tiers_button();
	});


	function remove_blank_custom_tier_if_no_other_tiers() {
		const $attributes = $( '.product_tiers .woocommerce_tier' );

		if ( $attributes.length === 1 ) {
			const $attribute = $attributes.first();

			const $attributeName = $attribute.find(
				'input[name="min_qty[0]"]'
			);
			const $attributeValue = $attribute.find(
				'input[name="max_qty[0]"]'
			);

			if ( ! $attributeName.val() && ! $attributeValue.val() ) {
				$attribute.remove();
			}
		}
	}
	

})( jQuery );
