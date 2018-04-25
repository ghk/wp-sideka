<?php

defined( 'ABSPATH' ) || exit;

function sideka_ms_site_not_found($current_site, $domain, $path) { 
    $new_domain = $domain;
	$domain_splits = explode(".", $domain);
	if($domain_splits[0].".sideka.id" == $domain) { 
	    $new_domain = $domain_splits[0].".desa.id";
	} else if($domain_splits[0].".desa.id" == $domain) {
	    $new_domain = $domain_splits[0].".sideka.id";
	}
	global $wpdb;
	$id = $wpdb->get_var($wpdb->prepare('select blog_id from sd_desa where domain = %s', $new_domain));
    if($id){
        $url = "http://". $new_domain . $_SERVER['REQUEST_URI'];
        header("Location: $url");
        exit;
    }
} 
add_action( 'ms_site_not_found', 'sideka_ms_site_not_found', 1, 3 );

