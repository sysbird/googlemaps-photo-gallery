<?php
/*
 Plugin Name: Google Maps Photo Gallery
 Plugin URI: http://www.sysbird.jp/wptips/googlemaps-photo-gallery/
 Description: The gallery on Google Maps of photos with GPS.
 Author: sysbird
 Author URI: http://www.sysbird.jp/wptips
 Version: 1.2
 License: GPLv2 or later
*/

//////////////////////////////////////////////////////
// Wordpress 3.0+
global $wp_version;
if ( version_compare( $wp_version, "3.4", "<" ) ){
	return false;
}

//////////////////////////////////////////////////////
// Start the plugin
class GoogleMapsPhotoGallery {

	//////////////////////////////////////////
	// construct
	function __construct() {
		add_shortcode('googlemaps-photo-gallery', array( &$this, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'add_script' ) );
		add_action( 'wp_print_styles', array( &$this, 'add_style' ) );
	}

	//////////////////////////////////////////
	// add JavaScript
	function add_script() {
		wp_enqueue_script( 'gmap', 'http://maps.google.com/maps/api/js?sensor=false');

		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/Swiper/js/swiper.jquery.js';
		wp_enqueue_script( 'googlemaps-photo-gallery-swiper', $filename, array( 'jquery' ), '3.0.7' );

		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/Boxer/jquery.fs.boxer.js';
		wp_enqueue_script( 'googlemaps-photo-gallery-boxer', $filename, array( 'jquery' ), '3.3.0' );

		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/googlemaps-photo-gallery.js';
		wp_enqueue_script( 'googlemaps-photo-gallery', $filename, array( 'jquery' ), '1.2' );
	}

	//////////////////////////////////////////
	// add css
	function add_style() {
		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/Swiper/css/swiper.css';
		wp_enqueue_style( 'googlemaps-photo-gallery-swiper', $filename, false, '3.0.7' );

		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/Boxer/jquery.fs.boxer.css';
		wp_enqueue_style( 'googlemaps-photo-gallery-boxer', $filename, false, '3.3.0' );

		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/googlemaps-photo-gallery.css';
		wp_enqueue_style( 'googlemaps-photo-gallery', $filename, false, '1.2' );
	}

	//////////////////////////////////////////
	// getGPS
	function getGPS( $file ){

		if( $exif = exif_read_data( $file ) ) {

			if( isset( $exif['GPSLatitudeRef'] )
				&& isset( $exif['GPSLatitude'] )
				&& isset( $exif['GPSLongitudeRef'] )
				&& isset( $exif['GPSLongitude'] ) ){

				$lat = $exif['GPSLatitude'];
				$lat_d = explode( "/",$lat[0] );
				$lat_m = explode( "/",$lat[1] );
				$lat_s = explode( "/",$lat[2] );
				$latitude = intval( $lat_d[0] ) / intval( $lat_d[1] )
				             + ( intval($lat_m[0] ) / intval($lat_m[1] ) )/60
				             + ( intval($lat_s[0] ) / intval($lat_s[1] ) )/3600;
				if ( $exif['GPSLatitudeRef'] == "S" ) {
					$latitude = $latitude * -1;
				}

				$lng = $exif['GPSLongitude'];
				$lng_d = explode( "/",$lng[0] );
				$lng_m = explode( "/",$lng[1] );
				$lng_s = explode( "/",$lng[2] );
				$longitude = intval( $lng_d[0] ) / intval($lng_d[1] )
				             + ( intval($lng_m[0] ) / intval($lng_m[1] ) )/60
				             + ( intval($lng_s[0] ) / intval($lng_s[1] ) )/3600;
				if ( $exif['GPSLongitudeRef'] == "W" ) {
					$longitude = $longitude * -1;
				}

				return array( 'lat'=>$latitude, 'lon'=>$longitude );
			}
		}

		return false;
	}

	//////////////////////////////////////////
	// ShoetCode
	function shortcode( $atts ) {

		global $post;

		$atts = shortcode_atts( array( 'center' => 0, 'zoom' => 0 ), $atts );
		$param = '';
		$center = $atts['center'];
		if(0 <> $center){
			$param .= ' center="' .$center .'"';
		}

		$zoom = $atts['zoom'];
		if(0 <> $zoom){
			$param .= ' zoom="' .$zoom .'"';
		}

		$output = '';
		$args = array( 'post_type'       => 'attachment',
						'posts_per_page' => -1,
						'post_parent'    => $post->ID,
						'post_mime_type' => 'image',
						'orderby'        => 'menu_order',
						'order'          => 'ASC' );
		$images = get_posts( $args );
		if ( $images ) {
			foreach( $images as $image ){
				$src = wp_get_attachment_url( $image->ID );
				$thumbnail = wp_get_attachment_image_src($image->ID, 'thumbnail');
				$file = get_attached_file( $image->ID );
				$gps = $this->getGPS($file);
				$attr = '';
				if($gps){
					$attr = ' lat="' .$gps['lat'] .'" lon="' .$gps['lon'] .'"';
					$output .= '<div class="swiper-slide"><a href="' .$src .'" id="googlemaps_photo_gallery-' .$image->ID .'" data-gallery="gallery"><img src="' .$thumbnail[0] .'" alt="' .$image->post_title .'"' .$attr .'></a></div>';
				}
  			}
		}

		if( !empty( $output ) ){

			$mobile = '';
			if ( wp_is_mobile() ){
				$mobile = ' class="mobile"';
			}

			$output = '<div id="googlemaps_photo_gallery"' .$mobile .'><div id="gmap"' .$param .'></div>' .'<div class="swiper-container"><div class="swiper-wrapper">' .$output .'</div><div class="swiper-pagination"></div><div class="swiper-button-next"></div><div class="swiper-button-prev"></div></div>' .'</div>';
		}

   		return $output;
	}
}
$GoogleMapsPhotoGallery = new GoogleMapsPhotoGallery();
?>