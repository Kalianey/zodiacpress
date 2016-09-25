(function( $ ) {
	// Insert hidden input with Geonames Timezone ID
	function getGeoTZ(latdeci, longdeci) {
		
		// Get timezone id by coordinates from Geonames webservice
		$.ajax({
			url: "//api.geonames.org/timezoneJSON",
			dataType: "jsonp",			
			data: {
				lat: latdeci,
				lng: longdeci,
				username: zp_ajax_object.geonames_user
			},
			success: function( response ) {
				$('<input>').attr({
					type: 'hidden',
					id: 'geo_timezone_id',
					name: 'geo_timezone_id',
					value:  response.timezoneId
				}).appendTo( '#zp-timezone-id' );
				
			}
		});
	}

	$(function() {

		// Autocomplete city
		$( '#city' ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "//api.geonames.org/searchJSON",
					dataType: "jsonp",
					data: {
						featureClass: "P",
						style: "full",
						maxRows: 12,
						name_startsWith: request.term,
						username: zp_ajax_object.geonames_user,
						lang: zp_ajax_object.lang
					},
					success: function( data ) {
						response( $.map( data.geonames, function( item ) {
							return {
								label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName, 
								value: item.name,
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
				// Display selected city and coordinates.
				$( '#citylabel' ).text( zp_ajax_object.selected );
				$( '#place' ).val( ui.item.label );
				$( '#zp-lat-label' ).text( zp_ajax_object.lat );
				$( '#zp-long-label' ).text( zp_ajax_object.long );
				$( '#zp-coordinates' ).show();
				$( '#zp_lat_decimal' ).val(ui.item.latdeci);
				$( '#zp_long_decimal' ).val(ui.item.lngdeci);

				// Reset the Offset section in case of changing city.
				$( '#zp-offset-wrap' ).hide();
				$( '#zp-fetch-birthreport' ).hide();
				$( '#zp-form-tip' ).hide();
				$( '#zp-fetch-offset' ).show();

				getGeoTZ( ui.item.latdeci, ui.item.lngdeci );// get timezone, to get offset				
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
							var distance = $('#zp-report-wrap').offset().top
							$( 'html,body' ).animate({scrollTop: distance}, 'slow');

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

	});
	
})( jQuery );
