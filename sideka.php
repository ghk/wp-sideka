<?php
/**
 * @package Sideka
 * @version 1.0
 */
/*
Plugin Name: Sideka
Plugin URI: http://www.sideka.id
Description: todo
Author: BP2DK
Version: 1.6
Author URI: http://www.bp2dk.id
*/

defined( 'ABSPATH' ) || exit;

//include_once( dirname(__FILE__) . '/admin/sideka-admin-nav-menu-meta-box.php');
include_once(dirname(__FILE__) . '/page/sideka-page.php' );
include_once(dirname(__FILE__) . '/sideka-site-init.php' );
include_once(dirname(__FILE__) . '/sideka-email-multisite.php');

if(is_admin()) {
    include_once(dirname(__FILE__) . '/admin/sideka-admin-menu.php');
}
if(is_network_admin() ||  (defined('DOING_AJAX') && DOING_AJAX)) {
    include_once(dirname(__FILE__) . '/admin/sideka-network-admin-menu.php');
}

add_action( 'init', 'sideka_rewrites_init' );
function sideka_rewrites_init(){
    add_rewrite_rule(
        'statistics/([0-9]+)/?$',
        'index.php?pagename=statistics&statistics_id=$matches[1]',
        'top' );
}

add_filter( 'query_vars', 'sideka_query_vars' );
function sideka_query_vars( $query_vars ){
    $query_vars[] = 'statistics_id';
    return $query_vars;
}

add_action('admin_head', 'sideka_admin_head');

function sideka_admin_head() {
	if(!is_network_admin()){
	  echo '<style>
	li#toplevel_page_jetpack, li#toplevel_page_wsal-auditlog {display: none;}
	#wp-admin-bar-wp-logo, .update-nag {display: none;}
	  </style>';
	}
}

function sideka_get_desa_id(){
	$desa_id = "mandalamekar";
	$server_name = $_SERVER["SERVER_NAME"];
	$server_splits = explode(".", $server_name);
	if($server_splits[0].".desa.id" == $server_name || $server_splits[0].".sideka.id" == $server_name){
	    $desa_id = $server_splits[0];
	}
	return $desa_id;
}

function sideka_is_desa_dbt(){
	global $wpdb;
	$server_name = $_SERVER["SERVER_NAME"];
	return $wpdb->get_var( 'select is_dbt from sd_desa where domain = "'.$server_name.'"');
}

function sideka_get_desa_code(){
	global $wpdb;
	$server_name = $_SERVER["SERVER_NAME"];
	return $wpdb->get_var( 'select kode from sd_desa where domain = "'.$server_name.'"');
}
?>
