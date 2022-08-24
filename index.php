<?php
/**
 * Plugin Name: HS Matrimony
 * Plugin URI: https://haysky.com/
 * Description: Matrimony plugin by Haysky. This plugin is originally made for BharatAlliance.com
 * Version: 1.0
 * Author: Haysky
 * Author URI: https://haysky.com/
 * License: GPLv2 or later
 */
// $wpdb->show_errors(); $wpdb->print_error();
error_reporting(E_ERROR | E_PARSE);
// add_action('wp_head',function(){
// wp_enqueue_style( 'semantic-css', 'https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.css', false, '2.3.3', 'all' );
// });

add_action( 'user_register', 'myplugin_registration_save', 10, 1 );
function myplugin_registration_save( $user_id ) {
  wp_update_user( array( 'ID' => $user_id, 'display_name' => '' ) );
}

add_action( 'pre_user_query', 'my_random_user_query' );
function my_random_user_query( $class ) {
  if( 'rand' == $class->query_vars['orderby'] )
    $class->query_orderby = str_replace( 'user_login', 'RAND()', $class->query_orderby );
  return $class;
}

function cc_wpse_278096_disable_admin_bar() {
   if (current_user_can('administrator') ) {
   // user can view admin bar
   show_admin_bar(true); // this line isn't essentially needed by default...
   } else {
   // hide admin bar
   show_admin_bar(false);
   }
}
add_action('after_setup_theme', 'cc_wpse_278096_disable_admin_bar');

// add_action( 'admin_init', 'restrict_wpadmin_access' );
if ( ! function_exists( 'restrict_wpadmin_access' ) ) {
  function restrict_wpadmin_access() {
    if ( wp_doing_ajax() || current_user_can( 'administrator' ) ) {
      return;
    } else {
      header( 'Refresh: 2; ' . esc_url( home_url() ) );
      $args = array(
        'back_link' => true,
      );
      wp_die( 'Restricted access.', 'Error', $args );
    };
  };
};

add_filter( 'ajax_query_attachments_args','wpb_show_current_user_attachments' );

function wpb_show_current_user_attachments( $query ) {
$user_id = get_current_user_id();
if ( $user_id &&
!current_user_can('activate_plugins') &&
!current_user_can('edit_others_posts') ) {
$query['author'] = $user_id;
}
return $query;
}

add_action( 'admin_init', function() {
  $subscriber = get_role( 'subscriber' );
  $subscriber->add_cap( 'edit_posts' );
  $subscriber->add_cap( 'delete_posts' );
  $subscriber->add_cap( 'read' );
  $subscriber->add_cap( 'upload_files' );
});
register_activation_hook( __FILE__, function() {
    add_role( 'agent', 'Agent', array( 'delete_posts' => true, 'edit_posts' => true, 'upload_files' => true ) );
});


include 'add_filter_to_wp_users.php';

add_shortcode('display_profiles',function(){ include 'dynamic_profiles.php'; });

function matrimony_admin_menu(){
  add_submenu_page('edit.php?post_type=matrimony_field','Settings','Settings','manage_options','matrimony_settings','matrimony_settings');
}
add_action('admin_menu' , 'matrimony_admin_menu');
function matrimony_settings(){ include 'settings.php'; }

// For custom post type

add_action( "init",function(){
  // Set labels for matrimony_field
  $labels = array(
    "name" => "Matrimony Fields",
    "singular_name" => "Matrimony Field",
    "add_new"  => "Add Matrimony Field",
    "add_new_item" => "Add New Matrimony Field",
    "all_items" => "All Matrimony Fields",
  );
  // Set Options for matrimony_field
  $args = array(  
    "public" => true,
    "label"     => "Matrimony Fields",
    "labels"    => $labels,
    "description" => "Matrimony Fields custom post type.",
    "menu_icon"    => "dashicons-id-alt",  
    "supports"   => array( "title"),
    "capability_type" => "page",
    "publicly_queryable"  => false,
  );
  register_post_type("matrimony_field", $args);
  
});

add_action( "init",function(){
  // Set labels for community
  $labels = array(
    "name" => "Communities",
    "singular_name" => "Community",
    "add_new"  => "Add Community",
    "add_new_item" => "Add New Community",
    "all_items" => "All Communities",
  );
  // Set Options for community
  $args = array(  
    "labels"    => $labels,
    "hierarchical"         => true,
    "public"           => true,
    "show_ui"          => true,
    "show_admin_column"      => true,
    "show_in_nav_menus"      => true,
    "show_tagcloud"        => true,
    "show_in_rest"         => true,
  );
  register_taxonomy("community", array("matrimony_field"), $args);
  
});


add_action( "init",function(){
  // Set labels for matrimony_option
  $labels = array(
    "name" => "Matrimony Options",
    "singular_name" => "Matrimony Option",
    "add_new"  => "Add Matrimony Option",
    "add_new_item" => "Add New Matrimony Option",
    "all_items" => "All Matrimony Options",
  );
  // Set Options for matrimony_option
  $args = array(  
    "labels"       => $labels,
    "hierarchical"     => false,
    "public"       => false,
    "show_ui"      => true,
    "show_admin_column"  => true,
    "show_in_nav_menus"  => false,
    "show_tagcloud"    => false,
    "show_in_rest"     => false,
  );
  register_taxonomy("matrimony_option", array("matrimony_field"), $args);
  
});

add_action( "add_meta_boxes",function(){
  add_meta_box(
    "diwp-post-read-timer",
    "Matrimony Field Type", 
// Creates Metabox Callback Function
function(){
  global $post;
  $new_slug = sanitize_title( $post->post_title );
  $meta = get_post_meta($post->ID);
  wp_update_post(array ('ID'=> $post->ID,'post_name' => $new_slug));
  update_post_meta($post->ID, "matrimony_field_type", $meta["matrimony_field_type"][0]);
  update_post_meta($post->ID, "public_visibility", $meta["public_visibility"][0]);
  ?>
  <table>
    <tr>
      <td>Matrimony Field Type</td>
      <td>
        <select name="matrimony_field_type" id="matrimony_field_type">
          <option>text</option>
          <option>select</option>
          <option>textarea</option>
          <option>image</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Public Visibility</td>
      <td>
        <select name="public_visibility" id="public_visibility">
          <option>yes</option>
          <option>no</option>
        </select>
      </td>
    </tr>
  </table>
  <script type="text/javascript">
    <?php
    if ($meta["matrimony_field_type"][0]) {
      echo 'document.getElementById("matrimony_field_type").value="'.$meta["matrimony_field_type"][0].'";
';
    }
    if ($meta["public_visibility"][0]) {
      echo 'document.getElementById("public_visibility").value="'.$meta["public_visibility"][0].'";
';
    }
    ?>
  </script>
  <?php
},
    "matrimony_field",
    "side",
    "high"
  );
});

add_action( "save_post",function(){
  global $post;
  update_post_meta($post->ID, "matrimony_field_type", $_POST["matrimony_field_type"]);
  update_post_meta($post->ID, "public_visibility", $_POST["public_visibility"]);
});