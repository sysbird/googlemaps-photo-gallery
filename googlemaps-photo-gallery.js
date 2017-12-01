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
	var photo_max = jQuery( '#googlemaps_photo_gallery .zoom-gallery a' ).length;
	var thumbnail_width = jQuery( '#googlemaps_photo_gallery .zoom-gallery a' ).outerWidth() + parseInt(jQuery( '#googlemaps_photo_gallery .zoom-gallery a').css('margin-left'), 10 ) + parseInt( jQuery('#googlemaps_photo_gallery .zoom-gallery a').css( 'margin-right' ), 10 );
	var thumbnails_clip = jQuery( '#googlemaps_photo_gallery .clip' ).width();
	var thumbnails_max = thumbnail_width * photo_max;
	jQuery( '#googlemaps_photo_gallery .zoom-gallery' ).css( 'width', thumbnail_width * thumbnails_max + 'px' );
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
		jQuery( '#googlemaps_photo_gallery .zoom-gallery a img' ).each( function(){
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

	// Hover thumbnail
	jQuery('#googlemaps_photo_gallery .zoom-gallery a').hover(function () {
		var index = jQuery( '#googlemaps_photo_gallery .zoom-gallery a' ).index( this );
		var left = parseInt( jQuery( '#googlemaps_photo_gallery .zoom-gallery' ).css( 'left' ) ) +(thumbnail_width * index);
		if( 0 >= left ) {
			thumbnails_scroll(false);
		}
		else {
			left += thumbnail_width;
			if( thumbnails_clip <= left ) {
				thumbnails_scroll(true);
			}
		}
	} );

	// Click thumbnail
	jQuery('#googlemaps_photo_gallery .zoom-gallery a').click(function () {
		if( !click_marker ) {
			var lat = jQuery(this).find('img').attr( 'lat' );
			var lon = jQuery(this).find('img').attr( 'lon' );
			map.setCenter( new google.maps.LatLng( lat, lon ) );
		}

		click_marker = false;
	} );

	// Click thumbnails page
	jQuery('#googlemaps_photo_gallery a.page').click(function () {
		var param = jQuery( this ).attr( 'class' );
		if( 0 <= param.indexOf( "left", 0 ) ) {
			thumbnails_scroll(false);
		}
		else{
			// right
			thumbnails_scroll(true);
		}

		return false;
	} );

	// thumbnails scroll
	function thumbnails_scroll(right) {
		var left = parseInt( jQuery( '#googlemaps_photo_gallery .zoom-gallery' ).css( 'left' ) );
		if( right ) {
			// right
			left -= thumbnail_width;
			if( left < ( thumbnails_clip - thumbnails_max ) ){
				left = thumbnails_clip - thumbnails_max;
			}
		}
		else{
			// left
			left += thumbnail_width;
			if( 0 < left ) {
				left = 0;
			}
		}
		jQuery( '#googlemaps_photo_gallery .zoom-gallery' ).stop( true, true ).animate( { left: left + 'px' } );
	}

	// Zoom-gallery
	jQuery('.zoom-gallery').magnificPopup( {
		delegate: 'a',
		type: 'image',
		closeOnContentClick: false,
		closeBtnInside: false,
		mainClass: 'mfp-with-zoom mfp-img-mobile',
		image: {
			verticalFit: true,
			titleSrc: function( item ) {
				return item.el.find( 'img' ).attr( 'alt' ) + '';
			}
		},
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
			opener: function(element) {
				return element.find('img');
			}
		}
	} );
} ); 
