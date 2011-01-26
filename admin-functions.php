<?php

add_action('admin_menu', 'vgs_admin_menu');
add_action('admin_init', 'register_vgs_settings' );

function vgs_admin_menu() {
  //add_options_page('VGS Plugin Options', 'Vimeo Gallery Shortcode', 'manage_options', 'vgs-plugin', 'vgs_plugin_options');
  //add_submenu_page( 'upload.php', 'Vimeo Gallery Shortcode', 'Vimeo Gallery Shortcode', 'manage_options', 'vgs-plugin', 'vgs_plugin_options');
  add_media_page('Vimeo Gallery Shortcode Settings', 'Vimeo Gallery Shortcode', 'manage_options', 'vgs-settings', 'vgs_plugin_options');
}

function register_vgs_settings() { // whitelist options
  register_setting( 'vgs-settings-group', 'vgs_vimeo_api_key' );
  register_setting( 'vgs-settings-group', 'vgs_vimeo_api_secret' );
  register_setting( 'vgs-settings-group', 'vgs_gallery_style' );
  register_setting( 'vgs-settings-group', 'vgs_thumbnail_ratio' );
  register_setting( 'vgs-settings-group', 'vgs_thumbnail_gallery_width');
  register_setting( 'vgs-settings-group', 'vgs_slide_gallery_width');
  register_setting( 'vgs-settings-group', 'vgs_embedded_video_width');
  register_setting( 'vgs-settings-group', 'vgs_thumbnail_wrapper_class');
  register_setting( 'vgs-settings-group', 'vgs_thumbnail_wrapper_alt_class');
  register_setting( 'vgs-settings-group', 'vgs_thumbnail_wrapper_alt_frequency');
}

function vgs_plugin_options() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
  
  // set a few defaults
  $thumbnail_gallery_width = get_option('vgs_thumbnail_gallery_width');
  $slide_gallery_width = get_option('vgs_slide_gallery_width');
  $embedded_video_width = get_option('vgs_embedded_video_width');
  $thumbnail_wrapper_class = get_option('vgs_thumbnail_wrapper_class');
  $thumbnail_wrapper_alt_class = get_option('vgs_thumbnail_wrapper_alt_class');
  $thumbnail_wrapper_alt_frequency = get_option('vgs_thumbnail_wrapper_alt_frequency');


  ?>
  
  <div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <div id="icon-vimeo" class="icon32"><br></div>
    
    <h2>Vimeo Gallery Shortcode Settings</h2>
    <p>
      Configure this plugin...
    </p>
    
    <form id="vgs-settings" method="post" action="options.php">
      <?php settings_fields( 'vgs-settings-group' ); ?>
      <h3>Vimeo API Settings</h3>
      <div class="field">
        <label for="vgs_vimeo_api_key">Your Vimeo API Key</label>
        <input type="text" id="vgs_vimeo_api_key" name="vgs_vimeo_api_key" value="<?php echo get_option('vgs_vimeo_api_key'); ?>" />
      </div>
      
      <div class="field">
        <label for="vgs_vimeo_api_secret">Your Vimeo API Secret</label>
        <input type="text" id="vgs_vimeo_api_secret" name="vgs_vimeo_api_secret" value="<?php echo get_option('vgs_vimeo_api_secret'); ?>" />
      </div>
      
      <h3>Gallery Defaults</h3>
      <div class="field defaults">
        <label for="vgs_gallery_style">Default Gallery Style</label>
        <select id="vgs_gallery_style" name="vgs_gallery_style">
          <option value="thumbnails"<?php if(get_option('vgs_gallery_style') == "thumbnails") echo " selected=selected"; ?>>Thumbnails</option>
          <option value="slide gallery"<?php if(get_option('vgs_gallery_style') == "slide gallery") echo " selected=selected"; ?>>Slide Gallery</option>
        </select>
      </div>
      
      <div class="field defaults">
        <label for="vgs_thumbnail_ratio">Thumbnail Aspect Ratio</label>
        <select id="vgs_thumbnail_ratio" name="vgs_thumbnail_ratio">
          <option value="16:9"<?php if(get_option('vgs_thumbnail_ratio') == "16:9") echo " selected=selected"; ?>>16:9</option>
          <option value="4:3"<?php if(get_option('vgs_thumbnail_ratio') == "4:3") echo " selected=selected"; ?>>4:3</option>
        </select>
        <?php //echo ?>
      </div>
      
      <div class="field defaults">
        <label for="vgs_thumbnail_gallery_width">Thumbnail Width for Thumbnail Galleries</label>
        <input type="text" id="vgs_thumbnail_gallery_width" name="vgs_thumbnail_gallery_width" value="<?php echo empty($thumbnail_gallery_width) ? "210" : $thumbnail_gallery_width ?>" />
      </div>

      <div class="field defaults">
        <label for="vgs_slide_gallery_width">Thumbnail Width for Slide Galleries</label>
        <input type="text" id="vgs_slide_gallery_width" name="vgs_slide_gallery_width" value="<?php echo empty($slide_gallery_width) ? "540" : $slide_gallery_width ?>" />
      </div>
      
      <div class="field defaults">
        <label for="vgs_embedded_video_width">Width for Single Videos</label>
        <input type="text" id="vgs_embedded_video_width" name="vgs_embedded_video_width" value="<?php echo empty($embedded_video_width) ? "540" : $embedded_video_width ?>" />
      </div>
      
      <h3>Optional HTML/CSS</h3>
      <div class="field">
        <label for="vgs_thumbnail_wrapper_class">Album Thumb Wrapper Class</label>
        <input type="text" id="vgs_thumbnail_wrapper_class" name="vgs_thumbnail_wrapper_class" value="<?php echo $thumbnail_wrapper_class ?>" />
      </div>
      
      <div class="field-group">
      <div class="field inline">
        <div class="inline">
          <label for="vgs_thumbnail_wrapper_alt_class">And use this alternating class</label>
        </div>
        <div class="inline">
          <input style="width:10em;" type="text" id="vgs_thumbnail_wrapper_alt_class" name="vgs_thumbnail_wrapper_alt_class" value="<?php echo $thumbnail_wrapper_alt_class ?>" />
        </div>
      </div>
      
      <div class="field inline">
        <div class="inline">
          <label for="vgs_thumbnail_wrapper_alt_frequency">every</label>
        </div>
        <div class="inline">
          <input style="width:3em;" type="text" id="vgs_thumbnail_wrapper_alt_frequency" name="vgs_thumbnail_wrapper_alt_frequency" value="<?php echo $thumbnail_wrapper_alt_frequency ?>" />
        </div>
        <div class="inline">
          <p class="post-label"> iterations</p>
        </div>
        
      </div>
      <p class="hint">albums displayed as a thumbnail gallery will have each individual video thumb wrapped in a div. Use this field to specify one or more classes for that div. Separate each class with a single space.</p>
      </div>

      
      <div class="field submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </div>
      
    </form>
    
    <div id="vgs-instructions">
      <h3>Shortcode API</h3>
      <h4>Method</h4>
      <code>
        [vimeo (video_id=integer OR album_id=integer OR user_id=string), style=string, width=integer, height=integer]
      </code>
      
      <h4>Arguments</h4>
      <table id="vgs-arguments">
        <tr>
          <td><span class="argument-title">video_id</span></td>
          <td>
            <p>The <em>Vimeo Clip ID Number</em> for a single video clip hosted on Vimeo. The arguments "video_id", "album_id", and "user_id" are mutually exclusive. If this argument is passed, the shortcode will produce a single embeded video.</p>
          </td>
        </tr>
        <tr>
          <td><span class="argument-title">album_id</span></td>
          <td>
            <p>The <em>Vimeo Album ID Number</em> for an album of videos hosted on Vimeo. The arguments "video_id", "album_id", and "user_id" are mutually exclusive. If this argument is passed, the shortcode will display a gallery of video thumbnails for the specified album in the specified style.</p>
          </td>
        </tr>
        <tr>
          <td><span class="argument-title">user_id</span></td>
          <td>
            <p>The <em>Vimeo User ID</em> for a single Vimeo member. The arguments "video_id", "album_id", and "user_id" are mutually exclusive. If this argument is passed, the shortcode will display a gallery of video thumbnails for the specified user's activity feed in the specified style.</p>
          </td>
        </tr>
        
        <tr>
          <td><span class="argument-title">style</span></td>
          <td>
            <p>One of: "thumbnails" or "slide gallery".</p>
          </td>
        </tr>
        <tr>
          <td><span class="argument-title">width</span></td>
          <td>
            <p>An integer representing the desired width of the video or gallery thumbnails</p>
          </td>
        </tr>
        <tr>
          <td><span class="argument-title">height</span></td>
          <td>
            <p>An integer representing the desired height of the video or gallery thumbnails</p>
          </td>
        </tr>
      </table>

      <h4>Examples</h4>
      <p>Display an album as a sliding gallery with thumbnails 600px wide.</p>
      <code>
        [vimeo album_id="12345", style="slide gallery", width="600"]
      </code>

    </div>
    
  </div>
  
  
  <style type="text/css">
    @import url("<?php echo plugins_url('admin-styles.css', __FILE__) ?>");

  </style>
  
  <?php
}
?>