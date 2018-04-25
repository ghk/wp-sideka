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
include_once(dirname(__FILE__) . '/sideka-user-init.php' );
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

add_action('after_signup_form', 'sideka_after_signup_form');
function sideka_after_signup_form() {
    $new = $_GET["new"];
    echo "<div id='signup-not-found'><div>Situs tidak ditemukan. Silahkan menghubungi administrator untuk mendaftarkan situs Anda.</div></div>";
}

add_action('network_site_new_form', 'sideka_network_site_new_form');
function sideka_network_site_new_form() {
    ?>
<!--
	<table class="form-table">
		<tr class="form-field form-required">
			<th scope="row"><label for="kode">Kode Kemendagri</label></th>
			<td><input style="max-width: 25em;" name="kode" type="text" class="regular-text" id="kode" /></td>
		</tr>
    </table>
-->
    <?php
}

add_action( 'login_enqueue_scripts', 'sideka_login_logo' );
function sideka_login_logo() { 
    ?> 
    <style type="text/css"> 
    body.login div#login h1 a {
    background-image: url(https://panduan.sideka.id/_static/logo.png);  
    background-size: 200px; 
    width: 300px;
    height: 70px;
    } 
    </style>
    <?php 
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

function sideka_token_authenticate($user){
    $rest_api_slug = rest_get_url_prefix();
    $valid_api_uri = strpos($_SERVER['REQUEST_URI'], $rest_api_slug);
    if(!$valid_api_uri){
        return $user;
    }

    $token = $_SERVER["HTTP_X_AUTH_TOKEN"];
    if(isset($token) && $token){
        global $wpdb;
        $user_id = $wpdb->get_var($wpdb->prepare("select user_id from sd_tokens where token = %s", $token));
        if($user_id)
            return $user_id;
    }
    return $user;
}
add_filter('determine_current_user', "sideka_token_authenticate");
