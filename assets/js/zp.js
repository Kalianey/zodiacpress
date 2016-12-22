(function( $ ) {

		// Disable Next button until ajax response is ready
		$( "#zp-fetch-offset" ).prop( 'disabled', true );
		
		// Autocomplete city

		$( '#place' ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: zp_ajax_object.autocomplete_ajaxurl,
					dataType: zp_ajax_object.dataType,
					type: zp_ajax_object.type,
					data: {
						featureClass: "P",
						style: "full",
						maxRows: 12,
						username: zp_ajax_object.geonames_user,
						action: zp_ajax_object.autocomplete_action ? zp_ajax_object.autocomplete_action : undefined,
						name_startsWith: request.term,
						lang: zp_ajax_object.lang
					},
					success: function( data ) {

						$( "#zp-fetch-offset" ).prop( 'disabled', true );
							// disable also submit button in case of changing city after offset is calculated
						$( "#zp-fetch-birthreport" ).prop( 'disabled', true );

						response( $.map( data.geonames, function( item ) {
							return {
								value: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName, 
								label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
								lngdeci: item.lng,
								latdeci: item.lat
							}
						}));
					}
				});
			},
			minLength: 2,
			select: function( event, ui ) {

				$( '.ui-state-error' ).hide();
	
				// Get timezone id by coordinates from Geonames webservice
				// timezone id is used to calculate offset

				$.ajax({
					url: zp_ajax_object.timezone_ajaxurl,
					dataType: zp_ajax_object.dataType,
					type: zp_ajax_object.type,
					data: {
						action: zp_ajax_object.timezone_id_action ? zp_ajax_object.timezone_id_action : undefined,
						lat: ui.item.latdeci,
						lng: ui.item.lngdeci,
						username: zp_ajax_object.geonames_user
					},
					success: function( response ) {

						// Insert hidden input with Geonames Timezone ID
						$('<input>').attr({
							type: 'hidden',
							id: 'geo_timezone_id',
							name: 'geo_timezone_id',
							value:  response.timezoneId
						}).appendTo( '#zp-timezone-id' );

						// Grab the birthplace coordinates

						$('<input>').attr({
							type: 'hidden',
							id: 'zp_lat_decimal',
							name: 'zp_lat_decimal',
							value:  ui.item.latdeci
						}).appendTo( '#zp-timezone-id' );
						
						$('<input>').attr({
							type: 'hidden',
							id: 'zp_long_decimal',
							name: 'zp_long_decimal',
							value:  ui.item.lngdeci
						}).appendTo( '#zp-timezone-id' );

						// Reset the Offset section in case of changing city.
						$( '#zp-offset-wrap' ).hide();
						$( '#zp-fetch-birthreport' ).hide();
						$( '#zp-form-tip' ).hide();
						$( '#zp-fetch-offset' ).show();

						// Enable the button
						$( "#zp-fetch-offset" ).prop( 'disabled', false );
						
					}
				});
			}
		});
	
		// Fill in time offset upon clicking Next.

		$('#zp-fetch-offset').click(function(e) {
			var data = {
				action: 'zp_tz_offset',
				post_data: $( '#zp-ajax-birth-data :input' ).serialize()
			};
			$.ajax({
				url: zp_ajax_object.ajaxurl,
				type: "POST",
				data: data,
				dataType: "json",
				success: function( data ) {
		
					if (data.error) {
						$( '.ui-state-error' ).hide();
						var span = $( '<span />' );
						span.attr( 'class', 'ui-state-error' );
						span.text( data.error );
						$( '#zp-ajax-birth-data' ).append( span );
					} else {

						// if not null, blank, nor false 
						if ($.trim(data.offset_geo) && 'false' != $.trim(data.offset_geo)) {
							$( '.ui-state-error' ).hide();
							
							// Display offset.
							$( '#zp-offset-wrap' ).show();
							$( '#zp-offset-label' ).text( zp_ajax_object.utc + " " );
							$( '#zp_offset_geo' ).val(data.offset_geo);
							$( '#zp-form-tip' ).show();

							// Switch buttons
							$( '#zp-fetch-offset' ).hide();
							$( '#zp-fetch-birthreport' ).show();
							$( "#zp-fetch-birthreport" ).prop( 'disabled', false );
						}
					}
					
				}
			});
			return false;
		});

		// Fetch birth report upon clicking submit

		$( '#zp-fetch-birthreport' ).click(function() {
			$.ajax({
				url: zp_ajax_object.ajaxurl,
				type: "POST",
				data: $( '#zp-birthreport-form' ).serialize(),
				dataType: "json",
				success: function( reportData ) {

					if (reportData.error) {
						$( '.ui-state-error' ).hide();
						var span = $( '<span />' );
						span.attr( 'class', 'ui-state-error' );
						span.text( reportData.error );
						$( '#zp-offset-wrap' ).after( span );

					} else {

						// if neither null, blank, nor false 
						if ($.trim(reportData.report) && 'false' != $.trim(reportData.report)) {
							
							$( '.ui-state-error' ).hide();

							// Display report.
							$( '#zp-report-wrap' ).show();
							$( '#zp-report-content' ).append(reportData.report);
							$( '#zp-form-wrap' ).hide();
							// Scroll to top of report
							var distance = $('#zp-report-wrap').offset().top - 70;
							$( 'html,body' ).animate({
								scrollTop: distance
							}, 'slow');
						}
					
					}					

				}
			});
			return false;
		});

		// Reset the Offset if date is changed.

		$('#month, #day, #year').on('change', function () {
			var changed = !this.options[this.selectedIndex].defaultSelected;
			if (changed) {
				$( '#zp-offset-wrap' ).hide();
				$( '#zp-fetch-birthreport' ).hide();
				$( '#zp-form-tip' ).hide();
				$( '#zp-fetch-offset' ).show();				

			}
		});

})( jQuery );
