<?php

defined( 'ABSPATH' ) || exit;

add_action('wpmu_new_blog', 'sideka_site_init', 10, 2);

function sideka_site_init_pages($user_id){
    $pages = array();

    $pages["home"] = wp_insert_post(array(
        'post_title'     => 'Beranda',
        'post_status'    => 'publish',
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["home"], '_wp_page_template', 'homepage.php' );

    $pages["profile"] = wp_insert_post(array(
        'post_title'     => 'Profil Desa',
        'post_status'    => 'publish',
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["history"] = wp_insert_post(array(
        'post_title'     => 'Sejarah Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["profile"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["lembaga"] = wp_insert_post(array(
        'post_title'     => 'Lembaga Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["profile"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["budget"] = wp_insert_post(array(
        'post_title'     => 'Keuangan Desa',
        'post_status'    => 'publish',
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["budget"], '_wp_page_template', 'template-full.php' );

    $pages["map"] = wp_insert_post(array(
        'post_title'     => 'Peta Desa',
        'post_status'    => 'publish',
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["map"], '_wp_page_template', 'template-full.php' );

    $defaultPage = get_page_by_title( 'Laman Contoh' );
    wp_delete_post( $defaultPage->ID );

    return $pages;
}

function sideka_site_init_categories() {
    $categories = array();
    $categories["news"] = wp_create_category('Kabar Desa');
    $categories["product"] = wp_create_category('Produk Desa');
    $categories["potential"] = wp_create_category('Potensi Desa');
    return $categories;
}

function sideka_site_init_menu($pages, $categories) {
    $menu = array();
    $menu_id = wp_create_nav_menu("Menu Utama");

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Beranda',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["home"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    $nav_profile =wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Profil Desa',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["profile"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Sejarah Desa',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["history"],
        'menu-item-type' => 'post_type',
        'menu-item-parent-id' => $nav_profile,
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Lembaga Desa',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["lembaga"],
        'menu-item-type' => 'post_type',
        'menu-item-parent-id' => $nav_profile,
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => 'Kabar Desa',
            'menu-item-object-id' => $categories['news'],
            'menu-item-object' => 'category',
            'menu-item-type' => 'taxonomy',
            'menu-item-url' => get_category_link($categories['news']),
            'menu-item-status' => 'publish',));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Produk Desa',
        'menu-item-object-id' => $categories['product'],
        'menu-item-object' => 'category',
        'menu-item-type' => 'taxonomy',
        'menu-item-url' => get_category_link($categories['product']),
        'menu-item-status' => 'publish',));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Potensi Desa',
        'menu-item-object-id' => $categories['potential'],
        'menu-item-object' => 'category',
        'menu-item-type' => 'taxonomy',
        'menu-item-url' => get_category_link($categories['potential']),
        'menu-item-status' => 'publish',));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Peta Desa',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["map"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Keuangan Desa',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["budget"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    $locations = array();
    $locations["main_nav"] = $menu_id; //main MajalahDesa nav
    set_theme_mod('nav_menu_locations', $locations);
}

function sideka_site_init_widgets($pages, $categories)
{
    update_option('widget_search', array(
        1 => array(
            'title' => '',
        ),
        '_multiwidget' => 1
    ));
    update_option('widget_recent-comments', array(
        1 => array(
            'title' => '',
            'number' => 5,
        ),
        '_multiwidget' => 1
    ));
    update_option('widget_mh_magazine_lite_posts_large', array(
        1 => array(
            'category' => $categories["news"],
            'postcount' => 2,
        ),
        '_multiwidget' => 1
    ));
    update_option('widget_mh_magazine_lite_posts_stacked', array(
        1 => array(
            'title' => "Produk Desa",
            'category' => $categories["product"],
        ),
        2 => array(
            'title' => "Potensi Desa",
            'category' => $categories["potential"],
        ),
        '_multiwidget' => 1
    ));
    $widgets = array(
        'sidebar' => array(
            'search-1',
            'recent-comments-1'
        ),
        'home-2' => array(
            'mh_magazine_lite_posts_large-1'
        ),
        'home-3' => array(
            'mh_magazine_lite_posts_stacked-1'
        ),
        'home-4' => array(
            'mh_magazine_lite_posts_stacked-2'
        ),
    );
    update_option('sidebars_widgets', $widgets);

    //Update halo dunia! post categories
    $args = array(
        'name'        => 'halo-dunia',
        'post_type'   => 'post',
        'post_status' => 'publish',
        'numberposts' => 1
    );
    $posts = get_posts($args);
    wp_set_post_categories( $posts[0]->ID, array( $categories["news"], $categories["product"], $categories["potential"] ) );
}

function sideka_site_init_theme($pages) {
    $upload = wp_upload_bits( "default_bg.jpg", null, file_get_contents(dirname(__FILE__)."/default_bg.jpg") );
    set_theme_mod('background_image', $upload["url"]);
    set_theme_mod('background_repeat', 'no-repeat');
    set_theme_mod('background_position_x', 'center');
    set_theme_mod('background_attachment', 'fixed');

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $pages['home'] );
}

function sideka_site_init($blog_id, $user_id){
    switch_to_blog($blog_id);

    $pages = sideka_site_init_pages($user_id);
    $categories = sideka_site_init_categories();
    sideka_site_init_menu($pages, $categories);
    sideka_site_init_theme($pages);
    sideka_site_init_widgets($pages, $categories);

    restore_current_blog();
}
