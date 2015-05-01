=== Google Maps Photo Gallery ===
Contributors: sysbird
Plugin URI: http://wordpress.org/plugins/googlemaps-photo-gallery/
Tags: shortcode, google, map, photo, gallery, gps, geo, location, lightbox
Requires at least: 3.8
Tested up to: 4.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The gallery on Google Maps with geotagged photos.  


== Description ==
The gallery on Google Maps with geotagged photos.  

= Features =
* The plugin displays a gallery on Google Maps with geotagged photos that has been uploaded to post.  
* When clicked the photo on the gallery, zoom in image with lightbox.  
* It's compatible with responsive web design.    
* Based on [Google Maps JavaScript API v3](https://developers.google.com/maps/documentation/javascript/).  
* [Boxer](http://classic.formstone.it/boxer/) the jQuery plugin is licensed under MIT.  
* [Swiper](http://www.idangero.us/swiper/) the jQuery plugin is licensed under MIT.  

= Usage =
1. Upload geotagged photos in post.  
2. Please write shortcode [googlemaps-photo-gallery] in the content.  
3. You can set center photo and zoom size of Google Maps as the initial display.  
   example. [googlemaps-photo-gallery center="5" zoom="16"]  
   center: menu order of photo(default:the last photo)  
   zoom: Google Maps zooming parameter(0-18 default:15)  

[Demo](http://www.sysbird.jp/birdsite/2007/09/21/20070921-hakodate/)  

= Contributors =
TORIYAMA Yuko at [sysbird](https://profiles.wordpress.org/sysbird/)  

= More Information =
[Description in Japanese](http://www.sysbird.jp/wptips/googlemaps-photo-gallery/)  


== Installation ==
1. Upload the "Google Maps Photo Gallery" folder to the plugins directory in your WordPress installation.  
2. Go to plugins list and activate "Google Maps Photo Gallery".  


== Screenshots ==
1. Display photo gallery on Google Maps  
2. Zoom in image with lightbox  


== Changelog ==
= 1.2 =
* Changed the jQuery plugin for lightbox by Boxer.  
* Add the jQuery plugin for mobile touch slider by Swiper.  

= 1.1 =
* Changed the jQuery plugin for lightbox.  

= 1.0 =
* Hello, world!  
