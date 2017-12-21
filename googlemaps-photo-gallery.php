<?php
/*
 Plugin Name: Google Maps Photo Gallery
 Plugin URI: http://www.sysbird.jp/wptips/googlemaps-photo-gallery/
 Description: The shortcode for gallery on Google Maps with geotagged photos.
 Author: sysbird
 Author URI: https://profiles.wordpress.org/sysbird/
 Text Domain: googlemaps-photo-gallery
 Domain Path: /languages
Version: 1.2
 License: GPLv2 or later
*/

//////////////////////////////////////////////////////
// Wordpress 3.0+
global $wp_version;
if ( version_compare( $wp_version, "4.0", "<" ) ){
	return false;
}

//////////////////////////////////////////////////////
// Start the plugin
class GoogleMapsPhotoGallery {

	//////////////////////////////////////////
	// construct
	function __construct() {

		add_action( 'init', array( $this, 'init' ) );
	}

	//////////////////////////////////////////
	// init
	function init() {

		add_shortcode('googlemaps-photo-gallery', array( &$this, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'add_script' ) );
		add_action( 'wp_print_styles', array( &$this, 'add_style' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_init', array( &$this, 'settings_init' ) );
		add_action( 'admin_init', array( $this, 'load_textdomain' ) );

		$option = get_option( 'googlemaps_photo_gallery_settings' );
		$apikey = trim( $option['api_key_field'] );
		if ( ! isset( $apikey ) || empty( $apikey ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice__error' ) );
		}
	}

	//////////////////////////////////////////
	// Options page at dashboard
	function admin_menu(){

		add_options_page(
			'Google Maps Photo Gallery',
			'Google Maps Photo Gallery',
			'manage_options',
			'googlemaps_photo_gallery',
			array( $this, 'options_page' )
		);
	}

	//////////////////////////////////////////////////////
	// Register settings.
	function settings_init() {

		register_setting(
			'googlemapsphotogallerypage',
			'googlemaps_photo_gallery_settings',
			array( $this, 'sanitize' )
		);

		add_settings_section(
			'googlemaps_photo_gallery_settings_section',
			esc_html__( 'Google Maps Photo Gallery settings', 'googlemaps-photo-gallery' ),
			array( $this, 'googlemaps_photo_gallery_settings_section_callback' ),
			'googlemapsphotogallerypage'
		);

		add_settings_field(
			'api_key_field',
			esc_html__( 'Set API Key', 'googlemaps-photo-gallery' ),
			array( $this, 'api_key_field_render' ),
			'googlemapsphotogallerypage',
			'googlemaps_photo_gallery_settings_section'
		);
	}

	//////////////////////////////////////////////////////
	// Load textdomain.
	function load_textdomain() {

		load_plugin_textdomain(
			'googlemaps-photo-gallery',
			false,
			plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	//////////////////////////////////////////////////////
	// Add description of Post Notifier.
	function googlemaps_photo_gallery_settings_section_callback() {

		echo esc_html__( 'The shortcode for gallery on Google Maps with geotagged photos.', 'googlemaps-photo-gallery' );
		?>
		<br>
		<?php
		echo esc_html__( 'Please set your Google Maps API key.', 'googlemaps-photo-gallery' );
	}

	//////////////////////////////////////////////////////
	// Output text field
	function api_key_field_render() {

		$options = get_option( 'googlemaps_photo_gallery_settings' );
		$apikey  = isset( $options['api_key_field'] ) ? $options['api_key_field'] : '';

		?>

		<input type="text" name="googlemaps_photo_gallery_settings[api_key_field]" value="<?php echo esc_attr( $apikey ); ?>" size="30">

		<?php
	}

	//////////////////////////////////////////////////////
	// Update Settings
	function options_page() {

		?>
		<form action='options.php' method='post'>

		<?php
			settings_fields( 'googlemapsphotogallerypage' );
			do_settings_sections( 'googlemapsphotogallerypage' );

			submit_button();

			$maps_api_for_web_link = sprintf(
				'%1$s<a href="https://developers.google.com/maps/web/">%2$s</a>',
				esc_html__( 'Go to ', 'googlemaps-photo-gallery' ),
				esc_html__( 'Google Maps API Web', 'googlemaps-photo-gallery' )
			);

			$html  = '';
			$html .= '<h2>' .esc_html__( 'How to get API key?', 'googlemaps-photo-gallery' ) .'</h2>';
			$html .= '<ol>';
			$html .= '<li>' .$maps_api_for_web_link .'</li>';
			$html .= '<li>' .esc_html__( 'Get the API key on the [Credentials] page.', 'googlemaps-photo-gallery' ) .'</li>';
			$html .= '<li>' .esc_html__( 'Set the API key limit for referrer at only your website.', 'googlemaps-photo-gallery' ) .'</li>';
			$html .= '<li>' .esc_html__( 'Activate the Google Maps JavaScript API.', 'googlemaps-photo-gallery' ) .'</li>';
			$html .= '</ol>';

			echo $html;

		?>
		</form>

		<?php
	}

	//////////////////////////////////////////////////////
	// Sanitize API key
	public function sanitize( $input ) {

		$new_input = array();
		$api_key = isset( $input['api_key_field'] ) ? $input['api_key_field'] : '';

		if ( ! empty( $api_key ) ) {

			if ( strlen( $api_key ) === mb_strlen( $api_key ) ) {

				$new_input['api_key_field'] = esc_attr( $api_key );

			} else {

				add_settings_error(
					'googlemaps_photo_gallery_settings',
					'api_key_field',
					esc_html__( 'Check your API key.', 'googlemaps-photo-gallery' ),
					'error'
				);
				$new_input['api_key_field'] = '';

			}
		} else {

			add_settings_error(
				'googlemaps_photo_gallery_settings',
				'api_key_field',
				esc_html__( 'Check your API key.', 'googlemaps-photo-gallery' ),
				'error'
			);

			$new_input['api_key_field'] = '';

		}

		return $new_input;
	}

	//////////////////////////////////////////
	// notice no API key
	function admin_notice__error() {

		$class = 'notice notice-warning is-dismissible';
		$url  = sprintf(
			'<a href="%1$s">%2$s</a>',
			admin_url( 'options-general.php?page=googlemaps_photo_gallery' ),
			esc_html__( 'Settings page', 'googlemaps-photo-gallery' )
		);

		$message = sprintf(
			__( 'Google Maps Photo Gallery, you need an API key. Please move to the %1$s.', 'googlemaps-photo-gallery' ),
			$url
		);
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
	}

	//////////////////////////////////////////
	// add JavaScript
	function add_script() {

		// Google Maps API
		wp_enqueue_script( 'gmap', $this->get_api_url());

		// swiper
		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/swiper/js/swiper.min.js';
		wp_enqueue_script( 'googlemaps-photo-gallery-swiper', $filename, array( 'jquery' ), '4.0.7' );

		// fancybox
		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/fancybox/jquery.fancybox.min.js';
		wp_enqueue_script( 'googlemaps-photo-gallery-fancybox', $filename, array( 'jquery' ), 'v3.2.10' );

		// this plugin
		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/googlemaps-photo-gallery.js';
		wp_enqueue_script( 'googlemaps-photo-gallery', $filename, array( 'jquery' ), '1.2' );
	}

	//////////////////////////////////////////
	// add CSS
	function add_style() {

		// swiper
		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/swiper/css/swiper.min.css';
		wp_enqueue_style( 'googlemaps-photo-gallery-swiper', $filename, false, '4.0.7' );

		// fancybox
		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/fancybox/jquery.fancybox.min.css';
		wp_enqueue_style( 'googlemaps-photo-gallery-fancybox', $filename, false, 'v3.2.10' );

		// this plugin
		$filename = plugins_url( dirname( '/' .plugin_basename( __FILE__ ) ) ).'/googlemaps-photo-gallery.css';
		wp_enqueue_style( 'googlemaps-photo-gallery', $filename, false, '1.2' );
	}

	//////////////////////////////////////////
	// Google Maps URL and API Key
	function get_api_url() {

		$options = get_option( 'googlemaps_photo_gallery_settings' );

		if ( ! empty( $options['api_key_field'] ) ) {
			$apikey  = "?key=" . $options['api_key_field'];
		} else {
			$apikey  = "";
		}

		$url = esc_url( '//maps.googleapis.com/maps/api/js' . $apikey );

		return $url;
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
		if( 0 <> $zoom ){
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
				$thumbnail = wp_get_attachment_image_src( $image->ID, 'large' );
				$file = get_attached_file( $image->ID );
				$gps = $this->getGPS( $file );
				$attr = '';
				if($gps){
					$attr = ' lat="' .$gps['lat'] .'" lon="' .$gps['lon'] .'"';

					$output .= ' <div class="swiper-slide"><a href="' .$src .'" data-fancybox="gallery-' .$post->ID .'" data-caption="' .$image->post_title .'" id="googlemaps_photo_gallery-' .$image->ID .'"><img src="' .$thumbnail[0] .'" alt="' .$image->post_title .'"' .$attr .'></a></div>';
				}
			}

			wp_reset_postdata();
		}

		if( !empty( $output ) ){
			$output = '<div id="googlemaps_photo_gallery"><div id="gmap"' .$param .'></div><div class="swiper-container"><div class="swiper-wrapper">' .$output .'</div><div class="swiper-button-next"></div><div class="swiper-button-next"></div></div></div>';
		}

		return $output;
	}
}
$GoogleMapsPhotoGallery = new GoogleMapsPhotoGallery();
?>