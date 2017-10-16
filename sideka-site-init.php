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
        'post_name'     => 'profil',
        'post_title'     => 'Profil Desa',
        'post_status'    => 'publish',
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["history"] = wp_insert_post(array(
        'post_name'     => 'sejarah',
        'post_title'     => 'Sejarah Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["profile"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["lembaga"] = wp_insert_post(array(
        'post_name'     => 'lembaga',
        'post_title'     => 'Lembaga Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["profile"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["data"] = wp_insert_post(array(
        'post_name'     => 'data',
        'post_title'     => 'Data Desa',
        'post_status'    => 'publish',
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["kependudukan"] = wp_insert_post(array(
        'post_name'     => 'kependudukan',
        'post_title'     => 'Data Kependudukan',
        'post_status'    => 'publish',
        'post_parent'    => $pages["data"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["kependudukan"], '_wp_page_template', 'template-full.php' );

    $pages["anggaran"] = wp_insert_post(array(
        'post_name'     => 'anggaran',
        'post_title'     => 'Anggaran Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["data"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["anggaran"], '_wp_page_template', 'template-full.php' );

    $pages["geospasial"] = wp_insert_post(array(
        'post_name'     => 'geospasial',
        'post_title'     => 'Peta Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["data"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["geospasial"], '_wp_page_template', 'template-full.php' );

    $defaultPage = get_page_by_title( 'Laman Contoh' );
    wp_delete_post( $defaultPage->ID );

    update_option('sideka_page_ids', array(
        'kependudukan' => $pages['kependudukan'],
        'anggaran' => $pages['anggaran'],
        'geospasial' => $pages['geospasial'],
    ));
    return $pages;
}

function sideka_get_category_configs(){
    $configs = array();
    $configs['news']  =array('cat_name' => 'Kabar Desa',  'category_nicename' => 'kabar');
    $configs['product']  =array('cat_name' => 'Produk Desa',  'category_nicename' => 'produk');
    $configs['potential']  =array('cat_name' => 'Potensi Desa',  'category_nicename' => 'potensi');
    $configs['dana-desa']  =array('cat_name' => 'Penggunaan Dana Desa',  'category_nicename' => 'dana-desa');
    $configs['seni-kebudayaan']  =array('cat_name' => 'Seni dan Kebudayaan',  'category_nicename' => 'seni-kebudayaan');
    $configs['tokoh']  = array('cat_name' => 'Tokoh Masyarakat',  'category_nicename' => 'tokoh');
    $configs['lingkungan']  = array('cat_name' => 'Lingkungan',  'category_nicename' => 'lingkungan');
    return $configs;
}

function sideka_site_init_categories() {
    $categories = array();
    $configs = sideka_get_category_configs();
    foreach ($configs as $key => $config){
        $categories[$key] = wp_insert_category($config);
    }
    return $categories;
}

function sideka_get_role_configs(){
    $configs = array();
    $configs[] = array("penduduk", "Admin Kependudukan", array('edit_penduduk'=>true));
    $configs[] = array("keuangan", "Admin Keuangan", array('edit_keuangan'=>true));
    $configs[] = array("pemetaan", "Admin Pemetaan", array('edit_pemetaan'=>true));
    return $configs;
}

function sideka_site_init_roles() {
    $configs = sideka_get_role_configs();
    foreach ($configs as $config){
        add_role($config[0], $config[1], $config[2]);
    }
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
        'menu-item-title' => 'Produk',
        'menu-item-object-id' => $categories['product'],
        'menu-item-object' => 'category',
        'menu-item-type' => 'taxonomy',
        'menu-item-url' => get_category_link($categories['product']),
        'menu-item-status' => 'publish',));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Potensi',
        'menu-item-object-id' => $categories['potential'],
        'menu-item-object' => 'category',
        'menu-item-type' => 'taxonomy',
        'menu-item-url' => get_category_link($categories['potential']),
        'menu-item-status' => 'publish',));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Kependudukan',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["kependudukan"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    /*
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Peta',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["geospasial"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Anggaran',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["anggaran"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));
    */

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
        2 => array(
            'title' => '',
        ),
        '_multiwidget' => 1
    ));
    update_option('widget_recent-comments', array(
        1 => array(
            'title' => '',
            'number' => 5,
        ),
        2 => array(
            'title' => '',
            'number' => 5,
        ),
        '_multiwidget' => 1
    ));
    update_option('widget_mh_magazine_lite_posts_large', array(
        1 => array(
            'category' => $categories["news"],
            'postcount' => 4,
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
        'home-6' => array(
            'search-2',
            'mh_magazine_lite_posts_stacked-1',
            'mh_magazine_lite_posts_stacked-2',
            'recent-comments-2'
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
    sideka_site_init_roles();

    update_option( 'default_category', $categories['news'] );
    update_option( 'category_base', '/kategori');

    restore_current_blog();
}
