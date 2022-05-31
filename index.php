<?php
/*
Plugin Name: HS Matrimony
Plugin URI: https://www.haysky.com/
Description: Matrimony plugin by Haysky. This plugin is originally made for BharatAlliance.com
Version: 1.0
Author: Sufyan
Author URI: https://www.sufyan.in/
License: GPLv2 or later
Text Domain: hs-matrimony
*/
error_reporting(E_ERROR | E_PARSE);

add_filter('manage_users_columns','remove_users_columns');
function remove_users_columns($column_headers) {
    // if (current_user_can('moderator')) {
      unset($column_headers['name']);
    // }
    return $column_headers;
}

add_action( 'pre_user_query', 'my_random_user_query' );
function my_random_user_query( $class ) {
    if( 'rand' == $class->query_vars['orderby'] )
        $class->query_orderby = str_replace( 'user_login', 'RAND()', $class->query_orderby );
    return $class;
}


function add_roles_on_plugin_activation() {
    global $wpdb;
    add_role( 'hs_matrimony_user', 'HS Matrimony User', 
        array( 
            'read' => true, 
            'level_0' => true , 
        )
    );
}
register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );


add_action('admin_init', 'allow_hs_matrimony_user_uploads');
function allow_hs_matrimony_user_uploads() {
    $hs_matrimony_user = get_role('hs_matrimony_user');
    $hs_matrimony_user->add_cap('upload_files');
}

add_filter( 'ajax_query_attachments_args',
'wpb_show_current_user_attachments' );

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
    $author = get_role( 'hs_matrimony_user' );

    if ( ! $author->has_cap( 'delete_posts' ) ) {
        $author->add_cap( 'delete_posts' );
    }
});

include (dirname(__FILE__).'/user_extra_fields.php');
include (dirname(__FILE__).'/add_filter_to_wp_users.php');

function display_profiles(){
    if(is_user_logged_in()){
        include (dirname(__FILE__).'/dynamic_profiles.php');
    } else {
        echo 'please login to continue';
    }
}
function matrimony_profile(){
    include (dirname(__FILE__).'/dynamic_profile.php');
}

add_shortcode('hs_matri_show_profiles'  , 'hs_matri_show_profiles'  );
add_shortcode('matrimony_profile','matrimony_profile');
add_shortcode('display_profiles'        , 'display_profiles'   );

function home_carousel(){
    include (dirname(__FILE__).'/home_carousel.php');
}
add_shortcode('home_carousel','home_carousel');

function matrimony_admin_menu(){
    add_submenu_page('edit.php?post_type=matrimony_field','View by ID','View by ID','manage_options','disable_captcha_admin','matrimony_check_by_id');
    add_submenu_page('edit.php?post_type=matrimony_field','Settings','Settings','manage_options','matrimony_settings','matrimony_settings');
}
add_action('admin_menu' , 'matrimony_admin_menu');

function matrimony_check_by_id(){ include 'check_by_id.php'; }
function matrimony_settings(){ include 'settings.php'; }

add_shortcode('view_profile',function(){ include 'view_profile.php'; });
add_shortcode('likers',function(){ 
    $likers_page = true;
    include 'display_profiles.php'; 
});


//Disable the new user notification sent to the site admin
function smartwp_disable_new_user_notifications() {
    //Remove original use created emails
    remove_action( 'register_new_user', 'wp_send_new_user_notifications' );
    remove_action( 'edit_user_created_user', 'wp_send_new_user_notifications', 10, 2 );
    
    //Add new function to take over email creation
    add_action( 'register_new_user', 'smartwp_send_new_user_notifications' );
    add_action( 'edit_user_created_user', 'smartwp_send_new_user_notifications', 10, 2 );
}

function smartwp_send_new_user_notifications( $user_id, $notify = 'user' ) {
    if ( empty($notify) || $notify == 'admin' ) {
      return;
    }elseif( $notify == 'both' ){
        //Only send the new user their email, not the admin
        $notify = 'user';
    }
    wp_send_new_user_notifications( $user_id, $notify );
}
add_action( 'init', 'smartwp_disable_new_user_notifications' );

// For custom post type

add_action( "init",function(){
    // Set labels for matrimony_field
    $labels = array(
        "name" => "Matrimony Fields",
        "singular_name" => "Matrimony Field",
        "add_new"    => "Add Matrimony Field",
        "add_new_item" => "Add New Matrimony Field",
        "all_items" => "All Matrimony Fields",
    );
    // Set Options for matrimony_field
    $args = array(    
        "public" => true,
        "label"       => "Matrimony Fields",
        "labels"      => $labels,
        "description" => "Matrimony Fields custom post type.",
        "menu_icon"      => "dashicons-id-alt",    
        "supports"   => array( "title","page-attributes"),
        "capability_type" => "page",
        "publicly_queryable"  => false,
    );
    register_post_type("matrimony_field", $args);
    
});


add_action( "init",function(){
    // Set labels for matrimony_option
    $labels = array(
        "name" => "Matrimony Options",
        "singular_name" => "Matrimony Option",
        "add_new"    => "Add Matrimony Option",
        "add_new_item" => "Add New Matrimony Option",
        "all_items" => "All Matrimony Options",
    );
    // Set Options for matrimony_option
    $args = array(    
        "labels"             => $labels,
        "hierarchical"       => false,
        "public"             => false,
        "show_ui"            => false,
        "show_admin_column"  => true,
        "show_in_nav_menus"  => false,
        "show_tagcloud"      => false,
        "show_in_rest"       => false,
    );
    register_taxonomy("matrimony_option", array("matrimony_field"), $args);
    
});
?>