/*
 Plugin Name: Google Maps Photo Gallery
 googlemaps-photo-gallery.js
*/
jQuery( function(){
	if( 0 == jQuery( '#googlemaps_photo_gallery' ).length ){
		return;
	}

	var map;
	var click_marker = false;

	jQuery( window ).load( function() {
		initialize();
	} );

	function initialize() {
		var count = 0;

		// Get parameter
		var center = jQuery( '#googlemaps_photo_gallery img' ).length;
		if(0 <jQuery( '#googlemaps_photo_gallery #gmap[center]' ).length ){
			center = jQuery( '#googlemaps_photo_gallery #gmap' ).attr( 'center' );
		}

		var zoom = 15;
		if(0 <jQuery( '#googlemaps_photo_gallery #gmap[zoom]' ).length ){
			zoom = jQuery( '#googlemaps_photo_gallery #gmap' ).attr( 'zoom' );
		}

		// Init Googlemap
		var myOptions = {
			zoom: parseInt( zoom ),
			mapTypeId: google.maps.MapTypeId.ROADMAP };
		map = new google.maps.Map( document.getElementById( 'gmap' ), myOptions );

		// Set maeker with photo
		jQuery( '#googlemaps_photo_gallery .swiper-container img' ).each( function(){
			var lat = jQuery(this).attr( 'lat' );
			var lon = jQuery(this).attr( 'lon' );
			var src = jQuery(this).attr( 'src' );
			var id = jQuery(this).parent( 'a' ).attr( 'id' );
			var image = new google.maps.MarkerImage( src );
			image.size = new google.maps.Size( 75, 75 );
			image.origin = new google.maps.Point( 0, 0 );
			image.scaledSize = new google.maps.Size( 75, 75 );
			var marker = new google.maps.Marker({
								position:new google.maps.LatLng( lat, lon ),
								map: map,
								icon: image,
								info: id });

			// Marker Click
			google.maps.event.addListener( marker, 'click', function( event ) {
				var id = marker.info;
				click_marker = true;
				jQuery( "#" + id ).click();
			} );

			// Center
			count++;
			if( count == center ){
				map.setCenter( new google.maps.LatLng( lat, lon ) );
			}
		} );
	}

	// Swiper for thumbnail
	var swiper = new Swiper('.swiper-container', {
		slidesPerView: 7,
		spaceBetween: 10,
		freeMode: true,
		grabCursor: true,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		breakpoints: {
			1000: {
				slidesPerView: 5,
				spaceBetween: 10,
			},
			750: {
				slidesPerView: 3,
				spaceBetween: 5,
			},
		}
	});

	// Zoom for thumbnail
	jQuery("[data-fancybox]").fancybox({
		loop : true,
		buttons : [
			'close'
		],
	});
} );
