<?php
require_once('vimeo.php');
function embedded_video_output($video_id, $width, $height){
  
  return "<object width='$width' height='$height'><param name='allowfullscreen' value='true' />".
  			 "<param name='allowscriptaccess' value='always' />".
  			 "<param name='movie' value='http://vimeo.com/moogaloop.swf?clip_id=$video_id&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1' />".
  			 "<embed src='http://vimeo.com/moogaloop.swf?clip_id=$video_id&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1' type='application/x-shockwave-flash' allowfullscreen='true' allowscriptaccess='always' width='$width' height='$height' /></object>";
}

function thumbnail_gallery_output($id, $width, $height){
  
  // vimeo api settings
  $vimeo_key = get_option('vgs_vimeo_api_key');
  $vimeo_secret = get_option('vgs_vimeo_api_secret');
  if(empty($vimeo_key) || empty($vimeo_secret)) return "<!-- Vimeo Gallery Shortcode: [error] You must enter your Vimeo API Key and Secret on the plugin settings page (under the Media tab in the admin panel).";
  
  // optional / alternating classes
  $thumbnail_wrapper_class = get_option('vgs_thumbnail_wrapper_class');
  $thumbnail_wrapper_alt_class = get_option('vgs_thumbnail_wrapper_alt_class');
  $thumbnail_wrapper_alt_frequency = (get_option('vgs_thumbnail_wrapper_alt_frequency'));
  $alt_class = array();
  for($i = 0; $i <= $thumbnail_wrapper_alt_frequency - 1; $i++ ) $alt_class[$i] = "";
  $alt_class[$thumbnail_wrapper_alt_frequency - 1] = " $thumbnail_wrapper_alt_class";
  $alt_class = array_values($alt_class);
  
  // figure out thumbnail index
  // [0] - 100px wide
  // [1] - 200px wide
  // [2] - 640px wide
  if($width <= 120) $thumbnail_index = 0;
  if($width > 120 && $width <= 220) $thumbnail_index = 1;
  if($width > 220) $thumbnail_index = 2;
  
  
  // start the Vimeo api
  $vimeo = new phpVimeo($vimeo_key, $vimeo_secret);
  $vimeo_response = $vimeo->call('vimeo.albums.getVideos', array('album_id' => $id, 'full_response' => 1));
  $videos = $vimeo_response->videos->video;
  
  // produce some html
  $output = "<div class='vgs-thumbnail-gallery'>";
  $i = 0;
  foreach($videos as $video){
    $title = $video->title;
    $thumbnail = $video->thumbnails->thumbnail[$thumbnail_index]->_content;
    $vimeo_url = $video->urls->url[0]->_content;
    $caption = $video->caption;
    $output .=  "<div class='vgs-video-thumb $thumbnail_wrapper_class". $alt_class[$i % $thumbnail_wrapper_alt_frequency] ."'>".
                  "<p class='title'>". $title ."</p>".
                  "<a title=\"$title\" rel='zoombox[album-". $id ."]' href='$vimeo_url' style='display:block; width:". $width ."px; height:". $height ."px; background: url($thumbnail) center center no-repeat;'></a>".
                  //"<p class='description'>". $caption ."</p>".
                "</div>";
    $i++;
  }
  $output .= "</div>";
  
  return $output;

}

function slide_gallery_output($id, $width, $height){
  
  // vimeo api settings
  $vimeo_key = get_option('vgs_vimeo_api_key');
  $vimeo_secret = get_option('vgs_vimeo_api_secret');
  if(empty($vimeo_key) || empty($vimeo_secret)) return "<!-- Vimeo Gallery Shortcode: [error] You must enter your Vimeo API Key and Secret on the plugin settings page (under the Media tab in the admin panel).";
  
  // optional / alternating classes
  $thumbnail_wrapper_class = get_option('vgs_thumbnail_wrapper_class');
  $thumbnail_wrapper_alt_class = get_option('vgs_thumbnail_wrapper_alt_class');
  $thumbnail_wrapper_alt_frequency = (get_option('vgs_thumbnail_wrapper_alt_frequency'));
  $alt_class = array();
  for($i = 0; $i <= $thumbnail_wrapper_alt_frequency - 1; $i++ ) $alt_class[$i] = "";
  $alt_class[$thumbnail_wrapper_alt_frequency - 1] = " $thumbnail_wrapper_alt_class";
  $alt_class = array_values($alt_class);
  
  // figure out thumbnail index
  // [0] - 100px wide
  // [1] - 200px wide
  // [2] - 640px wide
  if($width <= 120) $thumbnail_index = 0;
  if($width > 120 && $width <= 220) $thumbnail_index = 1;
  if($width > 220) $thumbnail_index = 2;
  
  
  // start the Vimeo api
  $vimeo = new phpVimeo($vimeo_key, $vimeo_secret);
  $vimeo_response = $vimeo->call('vimeo.albums.getVideos', array('album_id' => $id, 'full_response' => 1));
  $videos = $vimeo_response->videos->video;
  
  // produce some html
  $output = "<div class='vgs-slide-gallery'>".
              "<a class='prev browse left'><span>Prev</span></a>".
              "<div class='scrollable' style='height:". $height ."px; width:". $width ."px;'>".
                "<div class='items'>";
  foreach($videos as $video){
    $title = $video->title;
    $thumbnail = $video->thumbnails->thumbnail[$thumbnail_index]->_content;
    $vimeo_url = $video->urls->url[0]->_content;
    $caption = $video->caption;
    
    $output .=    "<div class='item'>".
                    
                    "<a rel='zoombox' title=\"$title\" href='$vimeo_url' style='display:block; width:". $width ."px; height:". $height ."px; background: url($thumbnail) center center no-repeat;'>".
                      "<span class='title'>$title</span>".
                    "</a>".
                    //"<div class='tooltip'>$caption</div>".          
                  "</div>";
  };
  $output .=    "</div></div>".
                "<a class='next browse right'><span>Next</span></a>".
              "</div>";
              
  return $output;    


}

?>