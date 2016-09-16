<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/16/2016
 * Time: 8:59 PM
 */

add_action( 'admin_menu', 'sideka_register_admin_menu' );

function sideka_register_admin_menu(){
    add_menu_page( 'Keuangan', 'Keuangan', 'manage_options', 'sideka', '', 'dashicons-analytics', 50);
    global $submenu;
    $submenu['sideka'][0] = array( 'Impor', 'manage_options' , '/sideka/admin' );
    $submenu['sideka'][1] = array( 'Lihat', 'manage_options' , 'http://go.espn.com' );

    add_menu_page( 'Wilayah', 'Wilayah', 'manage_options', 'sideka_map', '', 'dashicons-location-alt', 51);
}

