<?php
/*
Plugin Name: Vimeo Gallery Shortcode
Plugin URI: http://vimeo-plugin.christiannaths.com.s65597.gridserver.com/
Description: Create video galleries straight from your vimeo account using simple shortcode tags.
Version: 0.1
Author: Christian Naths
Author URI: http://christiannaths.com
License: Private
*/

require_once('admin-functions.php');
require_once('vimeo-gallery-shortcode-functions.php');

function vgs_shortcode($atts, $content=null) {

  $ratio = explode(':', get_option('vgs_thumbnail_ratio'));

  extract(shortcode_atts(array(
    'video_id' 	=> '',
    'album_id' => '',
    'user_id' => '',
    'style' => get_option('vgs_gallery_style'),
    'width' 	=> '',
    'height' 	=> '',
  ), $atts));
  
  $is_single_video = (!empty($video_id) && (empty($album_id) && empty($user_id)));
  $is_album_gallery = (!empty($album_id) && (empty($video_id) && empty($user_id)));
  $is_activity_gallery = (!empty($user_id) && (empty($video_id) && empty($album_id)));
  $is_confused = (!$is_activity_gallery && !$is_album_gallery && !$is_single_video);
  $is_invalid_album = ($is_album_gallery && !is_numeric($album_id));
  $is_invalid_video = ($is_single_video && !is_numeric($video_id));
  
  if($is_confused) return "<!-- Vimeo Gallery Shortcode: [error] There is something wrong with your usage of the shortcode. Make sure you are specifying ONE OF a video_id, an album_id, OR a user_id. -->";
  if($is_invalid_album) return "<!-- Vimeo Gallery Shortcode: [error] The Vimeo album ID you provided appears to be invalid. You should be passing the ID number only -->";
  if($is_invalid_video) return "<!-- Vimeo Gallery Shortcode: [error] The Vimeo video ID you provided appears to be invalid. You should be passing the ID number only -->";
  
  
  if($is_single_video){
    if(empty($width)) $width = get_option('vgs_embedded_video_width');
    $width = empty($width) ? "540" : $width;
    if(empty($height)) $height = intval($width * $ratio[1] / $ratio[0]);
    
    return embedded_video_output($video_id, $width, $height);
  }
  
  if($is_album_gallery){
    if( (strtolower($style) == "slide gallery") || (strtolower($style) == "slide") || (strtolower($style) == "slider") || (strtolower($style) == "sliding") || (strtolower($style) == "sliding gallery") ){
      if(empty($width)) $width = get_option('vgs_slide_gallery_width');
      $width = empty($width) ? "540" : $width;
      if(empty($height)) $height = intval($width * $ratio[1] / $ratio[0]);
      
      return slide_gallery_output($album_id, $width, $height);
      
    } else {
      if(empty($width)) $width = get_option('vgs_thumbnail_gallery_width');
      $width = empty($width) ? "210" : $width;
      if(empty($height)) $height = intval($width * $ratio[1] / $ratio[0]);
      
      return thumbnail_gallery_output($album_id, $width, $height);
    }
  
  }
  
  
  
  
  
  
}

add_shortcode('vimeo', 'vgs_shortcode');






?>