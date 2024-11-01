<?php
/* 
Plugin Name: WP Alert
Plugin URI: http://dmitritech.com
Description: A plugin to create sticky or floating alert messages on home, pages or posts.   
Author: Binish Prabhakar 
Author URI: http://wordpressnutsandbolts.blogspot.in/
Version: 1.0
*/
		
// add WP Alert menu in admin settings

define('WP_ALERT','wp-alert');
define('WP_ALERT_PATH',plugins_url(WP_ALERT));
define('SITE_ADMIN_URL',get_admin_url());

add_action( 'init', 'wp_alert_init' );
global $wpdb;

function wp_alert_init(){
	add_action( 'admin_menu', 'wp_alert_admin_menu' );
}

function wp_alert_admin_menu() {
	global $wpdb;
	add_menu_page('WP Alert', 'WP Alert', 'manage_options', 'wp-alert', 'wp_alert_admin', WP_ALERT_PATH.'/images/icon.png');
}

// add admin style
add_action('admin_print_styles', 'wp_alert_admin_css');
function wp_alert_admin_css(){
	wp_register_style('wp-alert-admin-style', WP_ALERT_PATH .'/css/admin-style.css');
	wp_enqueue_style('wp-alert-admin-style');
	
	wp_enqueue_style('wp-color-picker');
}

//add admin script
add_action('wp_print_scripts', 'wp_alert_admin_script');
function wp_alert_admin_script(){
	if(is_admin() && $_GET['page'] == 'wp-alert'){
	 wp_enqueue_script(array('jquery', 'editor', 'thickbox', 'media-upload' , 'wp-color-picker'));
	 wp_register_script('wp-alert-admin-script',WP_ALERT_PATH.'/js/admin-script.js', array('jquery','media-upload','thickbox'));
	 wp_enqueue_script('wp-alert-admin-script');
	}
}

// add WP Alert Admin Area
function wp_alert_admin(){
?>

<div class="wrap">
  <div class="wp-alert-icon"><br>
  </div>
  <h2>WP Alert</h2>
  <div class="wp-alert-left">
    <form method="post" action="">
      <input type="hidden" name="process_wp_alert" value="process" />
      <div class="metabox-holder" style="width: 100%;float:left;">
        <div class="postbox">
          <h3>Write your message to display:</h3>
          <p>
          <div class="alert-textarea">
            <?php 
		    $wp_alert_message = stripslashes(get_option('wp_alert_message'));
			
        	wp_editor($wp_alert_message , 'content-id', array( 'textarea_name' => 'wp_alert_message', 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) ) );
?>
          </div>
          </p>
        </div>
      </div>
      <div class="metabox-holder" style="width: 100%;float:left;">
        <div class="postbox">
          <h3>Disply Settings:</h3>
          <p>
          <div class="alert-textarea">
            <table border="0">
              <tr>
                <td>Show the WP Alert on site</td>
                <td>:</td>
                <td><input type="radio" name="alert_show_on_site" value="yes" <?php if(get_option('alert_show_on_site') == 'yes'){ echo 'checked="checked"'; } ?> />
                  Yes
                  <input type="radio" name="alert_show_on_site" value="no" <?php if(get_option('alert_show_on_site') == 'no'){ echo 'checked="checked"'; } ?> />
                  No </td>
              </tr>
              <tr>
                <td>Show on home page</td>
                <td>:</td>
                <td><input type="radio" name="alert_show_on_home" value="yes" <?php if(get_option('alert_show_on_home') == 'yes'){ echo 'checked="checked"'; } ?> />
                  Yes
                  <input type="radio" name="alert_show_on_home" value="no" <?php if(get_option('alert_show_on_home') == 'no'){ echo 'checked="checked"'; } ?> />
                  No </td>
              </tr>
              <tr>
                <td>Show on all page</td>
                <td>:</td>
                <td><input type="radio" name="alert_show_on_page" value="yes" <?php if(get_option('alert_show_on_page') == 'yes'){ echo 'checked="checked"'; } ?> />
                  Yes
                  <input type="radio" name="alert_show_on_page" value="no" <?php if(get_option('alert_show_on_page') == 'no'){ echo 'checked="checked"'; } ?> />
                  No </td>
              </tr>
              <tr>
                <td>Show on all posts</td>
                <td>:</td>
                <td><input type="radio" name="alert_show_on_post" value="yes" <?php if(get_option('alert_show_on_post') == 'yes'){ echo 'checked="checked"'; } ?> />
                  Yes
                  <input type="radio" name="alert_show_on_post" value="no" <?php if(get_option('alert_show_on_post') == 'no'){ echo 'checked="checked"'; } ?> />
                  No </td>
              </tr>
            </table>
          </div>
          </p>
        </div>
      </div>
      <div class="metabox-holder" style="width: 100%;float:left;">
        <div class="postbox">
          <h3>Color and other Settings:</h3>
          <p>
          <div class="alert-textarea">
            <table border="0">
              <tr>
                <td valign="top">Alert Box Width</td>
                <td valign="top">:</td>
                <td valign="top"><input name="wp_alert_width" type="text"  size="3" maxlength="3" value="<?php echo get_option('wp_alert_width');?>" />
                  px</td>
              </tr>
              <tr>
                <td valign="top">Font color</td>
                <td valign="top">:</td>
                <td valign="top"><input type="text" class="color" name="wp_alert_fontcolor" value="<?php echo get_option('wp_alert_fontcolor');?>" /></td>
              </tr>
              <tr>
                <td valign="top">Font size</td>
                <td valign="top">:</td>
                <td valign="top"><input name="wp_alert_fontsize" type="text"  size="3" maxlength="3" value="<?php echo get_option('wp_alert_fontsize');?>" />
                  px</td>
              </tr>
              <tr>
                <td valign="top">Background color</td>
                <td valign="top">:</td>
                <td valign="top"><input type="text" class="color" name="wp_alert_bgcolor" value="<?php echo get_option('wp_alert_bgcolor');?>" /></td>
              </tr>
            </table>
          </div>
          </p>
        </div>
      </div>
      <div class="metabox-holder" style="width: 100%;float:left;">
        <div class="postbox">
          <h3>Box position:</h3>
          <p>
          <div class="alert-textarea">
            <table border="0">
              <tr>
                <td>Choose position to display</td>
                <td>:</td>
                <td><select name="wp_alert_poition">
                    <option value="left" <?php if(get_option('wp_alert_poition') == 'left'){ echo 'selected="selected"'; } ?> >Left</option>
                    <option value="right" <?php if(get_option('wp_alert_poition') == 'right'){ echo 'selected="selected"';} ?> >Right</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          </p>
        </div>
      </div>
      <div class="metabox-holder" style="width: 100%;float:left;">
        <div class="postbox">
          <h3>Save Changes:</h3>
          <p>
          <div class="alert-textarea">
            <table border="0">
              <tr>
                <td>Save the changes</td>
              </tr>
              <tr>
                <td><input type="submit" value="Save Changes " class="button-primary" name="submit"></td>
              </tr>
            </table>
          </div>
          </p>
        </div>
      </div>
    </form>
  </div>
  <div class="wp-alert-right">
    <div class="metabox-holder" style="width: 100%;float:left;">
      <div class="postbox">
        <h3>Developer Info:</h3>
        <p>
        <div class="alert-textarea" style="text-align:center;"><a href="http://dmitritech.com/" target="_blank" title="dmitri tech solution"><img src="<?php echo WP_ALERT_PATH; ?>/images/dmitri-logo.png" alt="dmitri tech solution" /></a></div>
        </p>
      </div>
    </div>
    <div class="metabox-holder" style="width: 100%;float:left;">
      <div class="postbox">
        <h3>Join with us:</h3>
        <p><div class="alert-textarea" style="text-align:center;">
         <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fdmitritechs&amp;width=280&amp;height=500&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=true&amp;header=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:280px; height:500px;" allowTransparency="true"></iframe></div>
        </p>
      </div>
    </div>
    <div class="metabox-holder" style="width: 100%;float:left;">
      <div class="postbox">
        <h3>The Wordpress Development Company:</h3>
        <p><div class="alert-textarea" style="text-align:center;">
          <a href="http://www.hirewordpressguru.com/" target="_blank" title="Hire Wordpress Guru - A wordpress development Company"><img src="<?php echo WP_ALERT_PATH; ?>/images/hire-wordpress-guru.jpg" alt="Hire Wordpress Guru" /></a>
         </div>
        </p>
      </div>
    </div>
  </div>
</div>
<?php
}

// set the options 
register_activation_hook(__FILE__,'set_wp_alert_options');
function set_wp_alert_options(){
	add_option('wp_alert_message','Alert message goes here','');
	add_option('alert_show_on_site','yes','');
	add_option('alert_show_on_home','yes','');
	add_option('alert_show_on_page','yes','');
	add_option('alert_show_on_post','yes','');
	
	add_option('wp_alert_bgcolor','#666666','');
	add_option('wp_alert_fontcolor','#FFFFFF','');
	add_option('wp_alert_width','150','');
	add_option('wp_alert_fontsize','13','');
	
	add_option('wp_alert_poition','','');
}

// reset the options
register_uninstall_hook(__FILE__,'unset_wp_alert_options');
function unset_wp_alert_options(){ 
	delete_option('wp_alert_message');
	delete_option('alert_show_on_site');
	delete_option('alert_show_on_home');
	delete_option('alert_show_on_page');
	delete_option('alert_show_on_post');
	
	delete_option('wp_alert_bgcolor');
	delete_option('wp_alert_fontcolor');
	delete_option('wp_alert_width');
	delete_option('wp_alert_fontsize');
	
	delete_option('wp_alert_poition');
}

//add user scripta and styles
add_action('wp_head', 'wp_alert_user_script_style');
function wp_alert_user_script_style(){
	wp_enqueue_script("jquery");
	
	wp_register_style('wp-alert-style', WP_ALERT_PATH.'/css/style.css');
	wp_enqueue_style('wp-alert-style');
	?>
<style type="text/css">
	.sticky-box {
		width:<?php if(get_option('wp_alert_width') != ''){ echo get_option('wp_alert_width');}else{ echo '200';}?>px;
		background-color:<?php if(get_option('wp_alert_bgcolor') != ''){ echo get_option('wp_alert_bgcolor');}else{ echo '#000000';} ?>;
		color:<?php if(get_option('wp_alert_fontcolor') != ''){ echo get_option('wp_alert_fontcolor');}else{ echo '#FFFFFF';} ?>;
		font-size:<?php if(get_option('wp_alert_fontsize') != ''){ echo get_option('wp_alert_fontsize'); } else{ echo '13px';} ?>px;
		<?php if(get_option('wp_alert_poition') != ''){ echo get_option('wp_alert_poition');}else{ echo 'right';} ?>:13px;
	}
	.sticky-box .sticky-inner {
		width:<?php echo get_option('wp_alert_width'); ?>px;
		line-height:<?php echo (get_option('wp_alert_fontsize')+ 6); ?>px;
	}
	</style>
<?php
}

// processing the form
if($_POST['process_wp_alert'] == "process") { 
   update_option('wp_alert_message',$_REQUEST['wp_alert_message']);
   update_option('alert_show_on_site',$_REQUEST['alert_show_on_site']);
   update_option('alert_show_on_home',$_REQUEST['alert_show_on_home']);
   update_option('alert_show_on_page',$_REQUEST['alert_show_on_page']);
   update_option('alert_show_on_post',$_REQUEST['alert_show_on_post']);
   
   update_option('wp_alert_bgcolor',$_REQUEST['wp_alert_bgcolor']);
   update_option('wp_alert_fontcolor',$_REQUEST['wp_alert_fontcolor']);
   update_option('wp_alert_width',$_REQUEST['wp_alert_width']);
   update_option('wp_alert_fontsize',$_REQUEST['wp_alert_fontsize']);
   
   update_option('wp_alert_poition',$_REQUEST['wp_alert_poition']);
}

//add sticky box
add_action('wp_footer', 'wp_alert_add_sticky_alert');
function wp_alert_add_sticky_alert(){
	if(get_option('alert_show_on_site') == 'yes'){
	    $display    = true;
		$homeOption = get_option('alert_show_on_home');
		$pageOption = get_option('alert_show_on_page');
		$postOption = get_option('alert_show_on_post');
		
		$getID = get_the_ID();
		
		if($homeOption == 'yes' && is_home()){
		  $display =  true ;
		}elseif($pageOption == 'yes' && is_page()){
		  $display =  true ;
		}elseif($postOption == 'yes' && is_single()){
		  $display =  true ;
		}else{
		  $display =  false ;
		}
		if($display){
	?>
<div class="sticky-box">
  <div class="sticky-inner"><?php echo stripslashes(get_option('wp_alert_message')); ?></div>
</div>
<?php
	  }
	}
}
?>
