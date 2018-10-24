<?php
/**
 * Plugin Name: Storm Slider
 * Description: Create custom slider image
 * Version: 1.0
 * Author: Storm
 */
define('STORM_DB_TABLE', 'stormslider');
define('STORM_DB_OPTION_TABLE', 'stormslider_option');
define('STORM_DIR', plugin_dir_path(__FILE__));

require_once(STORM_DIR . '/storm-import.php');
require_once(STORM_DIR . '/ajax-slider.php');
require_once(STORM_DIR . '/sliders.php');
require_once(STORM_DIR . '/frontend/shortcodes.php');
storm_shortcodes::registerShortcode();

//Add media button on page and post
// add_action('media_buttons_context', 'add_media_button');

// function add_media_button($context){
//   $img = plugins_url('image/thunder-lightning-storm-icon.png', __FILE__);
//   $container_id = 'storm_slider';
//   $title = 'Add Slider Image';

//   $context .= "<a class='button thickbox' title='{$title}' href='#TB_inline?width=200&inlineId={$container_id}'>
//     <span class='wp-media-buttons-icon' style='background: url(".$img."); background-repeat: no-repeat; background-position: left top;'>
//     </span>
//     Add Slider
//   </a>";

//   return $context;
// }

add_action('admin_enqueue_scripts', 'add_scripts');

function add_scripts(){

  $screen = get_current_screen();
  wp_enqueue_script( 'jquery' );

  if(strpos($screen->base, 'stormslider') !== false && isset($_GET['action']) && ($_GET['action'] == 'edit')) {
    wp_enqueue_media();
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'minicolor', plugins_url('js/minicolor/jquery.minicolors.css', __FILE__) );
    wp_enqueue_style( 'stormSlider-css', plugins_url('css/stormSlider-css.css', __FILE__) );
    wp_enqueue_style( 'storm-wp-media', plugins_url('/css/storm-wp-media.css', __FILE__) );

    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'jquery-ui-resizable' );
    wp_enqueue_script( 'jquery-ui-slider' );
    wp_enqueue_script( 'jquery-ui-spinner' );
    wp_enqueue_script( 'jquery-ui-tooltip' );
    wp_enqueue_script( 'jquery-effects-core' );
    wp_enqueue_script( 'storm-transiiton-data', plugins_url('config/transitionDefaultData.js', __FILE__) );
    wp_enqueue_script( 'jscrollPance', plugins_url('js/jscrollPance.js', __FILE__) );
    wp_enqueue_script( 'storm-greensock', plugins_url('js/greensock.js', __FILE__) );
    wp_enqueue_script( 'pointer-event-polyfill', plugins_url('js/pointer_events_polyfill.js', __FILE__) );
    wp_enqueue_script( 'draggable-greensock', plugins_url('js/draggable.js', __FILE__) );
    wp_enqueue_script( 'layerd', plugins_url('/js/stormSlider.js', __FILE__) );
    wp_localize_script( 'layerd', 'pluginUrl', array(
      'pluginUrl' => plugins_url( '', __FILE__ )
    ));
    wp_enqueue_script( 'storm-transition-gallery', plugins_url('js/storm-transition-gallery.js', __FILE__) );

    $upload = wp_upload_dir();
    if(file_exists($upload['basedir'] . '/storm-transition.js')) {
      wp_enqueue_script( 'storm-transition-create-custom-data', $upload['baseurl'] . '/storm-transition.js');
    }
    else {
      wp_enqueue_script( 'storm-transition-custom-data', plugins_url('config/transitionCustomData.js', __FILE__));
    }

    wp_enqueue_script( 'minicolor', plugins_url('js/minicolor/jquery.minicolors.min.js', __FILE__) );
    wp_enqueue_script( 'storm-wp-media', plugins_url('js/storm-wp-media.js', __FILE__), array('jquery'), '1.0' );

  }

  if(strpos($screen->base, 'stormslider') !== false && !isset($_GET['action'])) {
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'storm-wp-admin', plugins_url('/css/storm-wp-admin.css', __FILE__) );
    wp_enqueue_script( 'storm-wp-admin', plugins_url('js/storm-wp-admin.js', __FILE__), array('jquery') );
  }

  if(strpos($screen->base, 'transition-template') !== false) {
    wp_enqueue_style( 'dashicons' );

    wp_enqueue_style( 'stormSlider-css', plugins_url('css/stormSlider-css.css', __FILE__) );
    wp_enqueue_style( 'storm-template-build', plugins_url('/css/storm-template-build.css', __FILE__) );
    wp_enqueue_script( 'jquery-ui-tooltip' );
    wp_enqueue_script( 'jquery-effects-core' );
    wp_enqueue_script( 'storm-transiiton-data', plugins_url('config/transitionDefaultData.js', __FILE__) );
    $upload = wp_upload_dir();
    if(file_exists($upload['basedir'] . '/storm-transition.js')) {
      wp_enqueue_script( 'storm-transition-create-custom-data', $upload['baseurl'] . '/storm-transition.js');
    }
    else {
      wp_enqueue_script( 'storm-transition-custom-data', plugins_url('config/transitionCustomData.js', __FILE__));
    }
    wp_enqueue_script( 'storm-greensock', plugins_url('js/greensock.js', __FILE__));
    wp_enqueue_script( 'layerd', plugins_url('/js/stormSlider.js', __FILE__) );
    wp_enqueue_script( 'storm-transition-gallery', plugins_url('js/storm-transition-gallery.js', __FILE__) );
    wp_enqueue_script( 'storm-template-build', plugins_url('/js/storm-template-build.js', __FILE__), array('jquery') );
  }

}

add_action('wp_enqueue_scripts', 'storm_wp_shortcode_scripts');

function storm_wp_shortcode_scripts(){
  wp_enqueue_style( 'dashicons' );
  wp_enqueue_style( 'stormSlider-css', plugins_url('css/stormSlider-css.css', __FILE__) );

  wp_enqueue_script( 'storm-greensock', plugins_url('js/greensock.js', __FILE__));
  wp_enqueue_script( 'storm-transiiton-data', plugins_url('config/transitionDefaultData.js', __FILE__) );

  //Include transition data
  $upload = wp_upload_dir();
  if(file_exists($upload['basedir'] . '/storm-transition.js')) {
    wp_enqueue_script( 'storm-transition-create-custom-data', $upload['baseurl'] . '/storm-transition.js');
  }
  else {
    wp_enqueue_script( 'storm-transition-custom-data', plugins_url('config/transitionCustomData.js', __FILE__));
  }

  // wp_enqueue_script( 'storm-touch', plugins_url('js/storm-touch.js', __FILE__), array('jquery'), '1.0', 'all' );
  wp_enqueue_script( 'storm-slider', plugins_url('js/stormSlider.js', __FILE__), array('jquery') );
}

add_action( 'admin_menu', 'add_menu' );

function add_menu(){
  $page_name = add_menu_page( 'Slider Page', 'StormSlider', 'delete_pages', 'stormslider', 'stormslider_router', plugins_url('image/sidebar.icon.png', __FILE__) );

  add_submenu_page( 'stormslider', 'Sliders', 'Sliders', 'delete_pages', 'stormslider', 'stormslider_router' );

  add_submenu_page( 'stormslider', 'Sliders Transition Template', 'Transition Template', 'delete_pages', 'transition-template', 'transition_template' );

}

add_filter('contextual_help', 'storm_contextual_help', 10, 3);

function storm_contextual_help($contextual_help, $screen_id, $screen) {
    if(strpos($screen->base, 'stormslider') !== false) {
      $screen->add_help_tab(array(
        'id' => 'help',
        'title' => 'content Help',
        'content' => '<p>Please read our  <a href="https://support.liutingxie.com/dcumentation.html" target="_blank">Online Documentation</a> carefully, it will likely answer all of your questions.</p><p>You can also check the <a href="https://support.liutingxie.com/" target="_blank">FAQs</a> for additional information, including our support policies and licensing rules.</p>'
      ));
    }
}

function stormslider_router(){

  if(isset($_GET["action"]))
    $action = $_GET["action"];
  else
    $action = '';

  if(isset($_GET["id"]))
    $id = $_GET["id"];

  $screen = get_current_screen();

  if(strpos($screen->base, 'stormslider') !== false && isset($id) && $action == 'edit') {
      require_once(STORM_DIR . '/views/slider-edit.php');
  }
  else {
      require_once(STORM_DIR . '/views/slider-list.php');
  }
}

function transition_template() {
  require_once( STORM_DIR . '/views/slider-transition-template.php');
}

add_action( 'init', 'register_form_action' );

function register_form_action() {
  if(isset($_GET["id"]))
    $id = $_GET["id"];

  if(isset($_GET['page']) && $_GET['page'] == 'stormslider' && isset($_GET['action']) && $_GET['action'] == 'add') {
    $id = Storm_Sliders::add();
    header('Location: admin.php?page=stormslider&action=edit&id=' . $id);
    die();
  }

  if(isset($_GET['page']) && $_GET['page'] == 'stormslider' && isset($_GET['action']) && $_GET['action'] == 'remove') {
    Storm_Sliders::remove($id);
    header('Location: admin.php?page=stormslider');
    die();
  }

  if(isset($_POST['storm-save-transition-template'])) {
    if(check_admin_referer('save-transition-template')) {
      add_action('admin_init', 'storm_save_transition_template');
    }
  }

  if(isset($_POST['storm_export'])) {
    if(current_user_can('delete_plugins')) {

        $date = date('Y-m-d');
        $filename = 'Stormslider-'.$date.'.json';

        $stringArr = array();
        if(isset($_POST['storm-slider-arr'])) {
          foreach ($_POST['storm-slider-arr'] as $key => $value) {
            $stringArr['stormSliders'][] = Storm_Sliders::_getByid($value);
          }
        }


        if(isset($_POST['storm-transition-arr'])) {
          foreach ($_POST['storm-transition-arr'] as $key => $value) {
            $stringArr['stormSliderTransitions'][] = Storm_Sliders::getOption('preset_effect')['option_value']['transition'][$value];
          }
        }

        header("Content-Type: application/force-download; charset=" . get_option( 'blog_charset') );
        header("Content-Disposition: attachment; filename=$filename");

        exit(json_encode($stringArr));
      }
  }
}

function storm_save_transition_template() {

  //Save transition
  $transition = array();
  $transition['3d'] = isset($_POST['3d']) ? $_POST['3d'] : array();
  $transition['2d'] = isset($_POST['2d']) ? $_POST['2d'] : array();

    array_walk_recursive($transition['3d'], 'storm_array_walk_recursive');
    array_walk_recursive($transition['2d'], 'storm_array_walk_recursive');

  //Iterate over the 3d
  foreach ($transition['3d'] as $key => $value) {

    //Rows
    if(strstr($value['rows'], ',')) {

      $val = explode(',', $value['rows']);
      $val[0] = (int)trim($val[0]);
      $val[1] = (int)trim($val[1]);
      $transition['3d'][$key]['rows'] = $val;
    }

    //Cols
    if(strstr($value['cols'], ',')) {

      $val = explode(',', $value['cols']);
      $val[0] = (int)trim($val[0]);
      $val[1] = (int)trim($val[1]);
      $transition['3d'][$key]['cols'] = $val;
    }

    //Before
    if(!isset($value['before']['enabled'])) {
      unset($transition['3d'][$key]['before']);
    }

    //Before
    if(!isset($value['after']['enabled'])) {
      unset($transition['3d'][$key]['after']);
    }
  }

  //Iterate over the 2d
  foreach ($transition['2d'] as $key => $value) {

    //Rows
    if(strstr($value['rows'], ',')) {

      $val = explode(',', $value['rows']);
      $val[0] = (int)trim($val[0]);
      $val[1] = (int)trim($val[1]);
      $transition['2d'][$key]['rows'] = $val;
    }

    //Cols
    if(strstr($value['cols'], ',')) {

      $val = explode(',', $value['cols']);
      $val[0] = (int)trim($val[0]);
      $val[1] = (int)trim($val[1]);
      $transition['2d'][$key]['cols'] = $val;
    }
  }

  $custom_dir = wp_upload_dir();
  $custom_file = $custom_dir['basedir'] . '/storm-transition.js';
  $data = 'var transitionCustomData = ' . json_encode($transition) . ';';
  file_put_contents($custom_file, $data);
  die('SUCCESS');

}

register_activation_hook(__FILE__, 'stormslider_active');

function stormslider_active() {

  global $wpdb;

  if( !empty($wpdb->charset) ) {
    $charset_collaet = "DEFAULT CHARACTER SET {$wpdb->charset}";
  }
  if( !empty($wpdb->collet) ) {
    $charset_collaet .= "COLLATE {$wpdb->collet}";
  }

  $table_name = $wpdb->prefix . STORM_DB_TABLE;

  $stormSliders = "CREATE TABLE IF NOT EXISTS $table_name (
    id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    name char(50) NOT Null,
    data mediumtext NOT NULL,
    date_create datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    date_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (id)
  ) $charset_collaet";

  $wpdb->query($stormSliders);

  $optionTable_name = $wpdb->prefix . 'stormslider_option';

  $stormSlidersOption = "CREATE TABLE IF NOT EXISTS $optionTable_name (
    id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    option_name varchar(120) NOT NULL,
    option_value mediumtext NOT NULL,
    date_create datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    date_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (id)
  ) $charset_collaet";

  $wpdb->query($stormSlidersOption);
}

function storm_array_walk_recursive(&$item, $key) {
  if(is_numeric($item)) {
    $item = (float)$item;
  }
}