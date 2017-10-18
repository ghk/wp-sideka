<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/16/2016
 * Time: 8:55 PM
 */

defined( 'ABSPATH' ) || exit;


function sideka_get_initial_user_meta(){
    $results = [];
    $results[] = array('metaboxhidden_nav-menus', array('add-post_tag','add-post_format'));
    $results[] = array('metaboxhidden_tribe_events', array('postexcerpt','postcustom', 'commentstatusdiv', 'commentsdiv', 'slugdiv', 'authordiv', 'sharing_meta'));
    $results[] = array('metaboxhidden_dashboard', array('dashboard_quick_press', 'wsal', 'dashboard_primary'));
    $results[] = array('meta-box-order_dashboard', array (
          'normal' => 'dashboard_quick_press,wsal,dashboard_right_now,jetpack_summary_widget',
          'side' => 'dashboard_activity,dashboard_primary',
          'column3' => '',
          'column4' => '',
    ));
    $results[] = array('metaboxhidden_post', array('postexcerpt', 'trackbacksdiv', 'postcustom', 'commentstatusdiv', 'slugdiv', 'authordiv', 'sharing_meta'));
    $results[] = array('metaboxhidden_page', array('postcustom', 'commentstatusdiv', 'slugdiv', 'authordiv', 'sharing_meta'));
    return $results;
}

add_action( 'user_register', 'sideka_user_init');
function sideka_user_init($user_id) {
    $user_meta = sideka_get_initial_user_meta();
    $results = [];
    foreach($user_meta as $meta){
        $results[] = update_user_meta( $user_id, $meta[0], $meta[1]);
    }
}

