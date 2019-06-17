=== Google Maps Photo Gallery ===
Contributors: sysbird
Plugin URI: https://wordpress.org/plugins/googlemaps-photo-gallery/
Tags: shortcode, google maps, photo, gallery, gps, geo
Requires at least: 4.0
Tested up to: 5.2
Stable tag: 1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html


The shortcode for gallery on Google Maps with geotagged photos.


== Description ==
The shortcode for gallery on Google Maps with geotagged photos.

= Features =
* The plugin displays a gallery on Google Maps with geotagged photos that has been uploaded to post.
* When clicked the photo on the gallery, zoom in image with lightbox.
* It's compatible with responsive web design.
* Based on [Google Maps JavaScript API v3](https://developers.google.com/maps/documentation/javascript/).
* [fancyBox3](https://fancyapps.com/fancybox/3/) the jQuery plugin is Licensed GPLv3 for open source use.
* [Swiper](http://idangero.us/swiper/)  the jQuery plugin is under the MIT License.


== Installation ==
1. Upload the "Google Maps Photo Gallery" folder to the plugins directory in your WordPress installation.
2. Go to [Google Maps API Web](https://developers.google.com/maps/web/)
3. Click the [GET A KEY] button and get your API key.
4. Set API key limit for referrer at only your website.
5. Activate the Google Maps JavaScript API.
6. Go to plugins list and activate "Google Maps Photo Gallery".
7. Set your API Key in "Google Maps Photo Gallery settings page".


= Usage =
1. Upload geotagged photos in post.
2. Please write shortcode [googlemaps-photo-gallery] in the content.
3. You can set center photo, zoom size and height of Google Maps as the initial display.
   example. [googlemaps-photo-gallery center="5" zoom="16" height="750"]
   center: menu order of photo(default:the last photo)
   zoom: Google Maps zooming parameter(0-18 default:15)
   height: Google Maps height(px)(default:500)

[Demo](https://birdsite.jp/2007/09/21/20070921-hakodate/)

= Contributors =
TORIYAMA Yuko at [sysbird](https://profiles.wordpress.org/sysbird)

= More Information =
[Description in Japanese](http://sysbird.jp/wptips/googlemaps-photo-gallery/)


== Screenshots ==
1. Display photo gallery on Google Maps
2. Zoom in image with lightbox


== Changelog ==
= 1.2 =
* Fix to use the Google Maps API key.
* Changed the jQuery plugin for lightbox and swipe.
* add option 'height'.

= 1.1 =
* Changed the jQuery plugin for lightbox.

= 1.0 =
* Hello, world!
