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
	var photo_max = jQuery( '#googlemaps_photo_gallery .swiper-wrapper a' ).length;
	var thumbnail_width = jQuery( '#googlemaps_photo_gallery .swiper-wrapper a' ).outerWidth() + parseInt(jQuery( '#googlemaps_photo_gallery .swiper-wrapper a').css('margin-left'), 10 ) + parseInt( jQuery('#googlemaps_photo_gallery .swiper-wrapper a').css( 'margin-right' ), 10 );
	var thumbnails_clip = jQuery( '#googlemaps_photo_gallery .clip' ).width();
	var thumbnails_max = thumbnail_width * photo_max;
	jQuery( '#googlemaps_photo_gallery .swiper-wrapper' ).css( 'width', thumbnail_width * thumbnails_max + 'px' );
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

		// Set marker with photo
		jQuery( '#googlemaps_photo_gallery .swiper-wrapper a img' ).each( function(){
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

	// Click thumbnail
	jQuery('#googlemaps_photo_gallery .swiper-wrapper a').click(function () {
		if( !click_marker ) {
			var lat = jQuery(this).find('img').attr( 'lat' );
			var lon = jQuery(this).find('img').attr( 'lon' );
			map.setCenter( new google.maps.LatLng( lat, lon ) );
		}

		click_marker = false;
	} );

   	// Swiper
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: 6,
        paginationClickable: true,
        spaceBetween: 5,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        grabCursor: true
    });

   	// Boxer
	var boxer_mobile = false;
	var boxer_class = jQuery( '#googlemaps_photo_gallery' ).attr( 'class' );
	if( boxer_class ){
		if( 0 <= boxer_class.indexOf( 'mobile' ) ){
			boxer_mobile = true;
		}
	}

	jQuery('.swiper-wrapper a').boxer( {
		mobile: boxer_mobile,
		customClass: "googlemaps_photo_gallery",
	} );
} ); 
