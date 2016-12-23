( function( $ ) {
	'use strict';
	wp.customize.section( 'zp_chart_colors', function( section ) {

		// Set a custom Preview URL only for the ZP Chart section
		var previousUrl, clearPreviousUrl, previewUrlValue, previewPageID;
		previewUrlValue = wp.customize.previewer.previewUrl;
		clearPreviousUrl = function() {
			previousUrl = null;
		};
			
		section.expanded.bind( function( isExpanded ) {
			var keys, zpGetSettings, replaceImage;

			if ( isExpanded ) {

				keys = ['outer_bg_color','signs_wheel_color','signs_divider_color',
							'signs_border_color','wheel_bg_color','houses_border_color',
							'houses_divider_color','angles_arrow_color','planet_glyph_color',
							'house_number_color','degree_color','angle_degree_color',
							'inner_wheel_color','inner_wheel_border_color','hard_aspect_color',
							'soft_aspect_color','minor_aspect_color','fire_sign_color',
							'earth_sign_color','air_sign_color','water_sign_color'];

				// Grab all our live customizer ZP settings, even those not saved yet
				zpGetSettings = function () {
					var settings = {};

					keys.forEach(function(element) {
						settings[element] = wp.customize.value('zp_customizer[' + element + ']')();
					});

					return settings;
				};

				// Replaces the image with a new image with new colors
				replaceImage = _.debounce(function (e) {

					// Get new image
					$.ajax({
						url: zp_chart_colors.ajaxurl,
						type: "POST",
						data: {
							action: 'zp_customize_preview_image',
							post_data: zpGetSettings()
						},
						dataType: "json",
						success: function( data ) {
							var chartWheel = $('#customize-preview iframe').contents().find('.zp-chart-drawing');
							chartWheel.replaceWith( data.image );					
						}
					});
				}, 1000);

				// Hide the close-customizer button since I need to detect when this section closes 
				// which can only be detected upon closing the section, not closing the customizer.
				$( '.customize-controls-close' ).hide();

				// Create the page for our live preview

				$.ajax({
					method: "POST",
					url: zp_chart_colors.root + 'wp/v2/pages',
					data: {
						title: zp_chart_colors.page_title,
						content: '[zp_chart_drawing_preview]',
						type: 'page',
						status: 'draft'
					},
					beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', zp_chart_colors.nonce );
					},
					success : function( response ) {
						// save the page id so we can delete it when finished
						previewPageID = response.id;
						// set the live preview URL
						previousUrl = previewUrlValue.get();
						previewUrlValue.set( response.link );
						previewUrlValue.bind( clearPreviousUrl );
					}
				});

				// Update colors in real time

				keys.forEach(function(element) {
					wp.customize( 'zp_customizer[' + element + ']', function( value ) {
						value.bind( replaceImage );
					});
				});

			} else {

				// When section closes...

				$( '.customize-controls-close' ).show();

				// Upon closing the ZP Chart section, return to the previous preview URL
				previewUrlValue.unbind( clearPreviousUrl );
				if ( previousUrl ) {
					previewUrlValue.set( previousUrl );
				}

				// delete the ZP preview page
				if ( previewPageID ) {
					$.ajax({
						method: "DELETE",
						url: zp_chart_colors.root + 'wp/v2/pages/' + previewPageID + '?force=true',
						beforeSend: function ( xhr ) {
							xhr.setRequestHeader( 'X-WP-Nonce', zp_chart_colors.nonce );
						}
					});
				}

			} // end section closes
		} );
	} );

} )( jQuery );
