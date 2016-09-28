<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/29/2016
 * Time: 1:28 AM
 */
// fixes "Lost Password?" URLs on login page
add_filter("lostpassword_url", function ($url, $redirect) {

    $args = array( 'action' => 'lostpassword' );

    if ( !empty($redirect) )
        $args['redirect_to'] = $redirect;
    return add_query_arg( $args, site_url('wp-login.php') );
}, 10, 2);
// fixes other password reset related urls
add_filter( 'network_site_url', function($url, $path, $scheme) {

    if (stripos($url, "action=lostpassword") !== false)
        return site_url('wp-login.php?action=lostpassword', $scheme);

    if (stripos($url, "action=resetpass") !== false)
        return site_url('wp-login.php?action=resetpass', $scheme);

    return $url;
}, 10, 3 );
// fixes URLs in email that goes out.
add_filter("retrieve_password_message", function ($message, $key) {
    return str_replace(get_site_url(1), get_site_url(), $message);
}, 10, 2);
// fixes email title
add_filter("retrieve_password_title", function($title) {
    return "[" . wp_specialchars_decode(get_option('blogname'), ENT_QUOTES) . "] Password Reset";
});