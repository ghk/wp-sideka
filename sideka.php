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

include_once( dirname(__FILE__) . '/admin/sideka-admin-menu.php' );
include_once(dirname(__FILE__) . '/admin/sideka-admin-nav-menu-meta-box.php');
include_once( dirname(__FILE__) . '/page/sideka-page.php' );

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

?>
