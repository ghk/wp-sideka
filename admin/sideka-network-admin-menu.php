<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/16/2016
 * Time: 8:59 PM
 */


class SidekaNetworkAdminMenu
{
    public function __construct() {
        add_action( 'network_admin_menu', array($this, 'admin_menu' ));
    }

    public function admin_menu()
    {
        add_submenu_page("settings.php", 'Sideka', 'Sideka', 'manage_options', 'sideka', array($this, 'settings_page'));
        add_submenu_page("settings.php", 'Pengguna Supradesa', 'Pengguna Supradesa', 'manage_options', 'supradesa', array($this, 'supradesa_page'));
        add_submenu_page("settings.php", 'Jetpack Batch Connect', 'Jetpack Batch Connect', 'manage_options', 'jetpack_batch', array($this, 'jetpack_batch_page'));
    }



    public function settings_page()
    {
        ?>
        <div class="wrap">
            <h1>Sideka</h1>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row" style="width: 300px;">Sinkronisasi Situs: Category &amp; Role</th>
                        <td>
                            <form id="sideka_sites_synchronize" class="sideka_synchronize_form" method="post" action="settings.php?page=sideka">
                                <input name="start" type="number" style="width: 80px;" value="0"/>
                                <input name="submit" id="submit" class="button button-primary" value="Sinkronisasi" type="submit">
                            </form>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Sinkronisasi User: Meta</th>
                        <td>
                            <form id="sideka_users_synchronize" class="sideka_synchronize_form" method="post" action="settings.php?page=sideka">
                                <input name="start" type="number" style="width: 80px;" value="0"/>
                                <input name="submit" id="submit" class="button button-primary" value="Sinkronisasi" type="submit">
                            </form>
                        </td>
                    </tr>
                </table>
                <div id="sideka_command_output">
                </div>
            <script type="text/javascript" >
                    var isSynchronizings = {};
                    function synchronize(type){
                        var start = parseInt(jQuery("#sideka_"+type+"_synchronize [name='start']").val());
                        isSynchronizings[type]= true;
                        jQuery("#sideka_"+type+"_synchronize [name='submit']").val("Stop Sinkronisasi");
                        console.log(jQuery("#sideka_"+type+"_synchronize [name='submit']"));
                        var data = {
                            'action': 'sideka_'+type+'_synchronize',
                            'start': start
                        };

                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        var xhr = jQuery.post(ajaxurl, data, function(response) {
                            var results = response.results;
                            for(var i = 0; i < results.length; i++){
                                jQuery("#sideka_command_output").prepend(results[i] + "<br />");
                            }
                            start = response.next;
                            jQuery("#sideka_"+type+"_synchronize [name='start']").val(start);
                            if(start && isSynchronizings[type])
                                synchronize(type);
                            else {
                                if (!start)
                                    jQuery("#sideka_command_output").prepend("Sinkronisasi selesai!<br />");
                                stopSynchronize(type);
                            }
                        });
                    }
                    function stopSynchronize(type){
                        isSynchronizings[type]= false;
                        jQuery("#sideka_"+type+"_synchronize [name='submit']").val("Sinkronisasi");
                    }
                    jQuery(".sideka_synchronize_form").each(function(){
                        jQuery(this).submit(function() {
                                var type = jQuery(this).attr("id") == "sideka_sites_synchronize" ? "sites" : "users";
                                if(!isSynchronizings[type]){
                                    jQuery("#sideka_command_output").html("");
                                    synchronize(type);
                                } else {
                                    stopSynchronize(type);
                                }
                                return false;
                        });
                    });
            </script>
        </div>
        <?php
    }

    public function supradesa_page()
    {
        wp_enqueue_script( 'user-suggest' );
        ?>
        <div class="wrap">
            <h1>Tambah Akun Pengguna Supradesa ke Semua Desanya</h1>
            <form id="sideka_supradesa_add" class="sideka_supradesa_add" method="post" action="settings.php?page=supradesa">
                <table class="form-table">
                    <input name="start" id="start" value="0" type="hidden">
                    <tr class="form-field form-required field-region field-region0">
                        <th scope="row"><label for="region0">Propinsi</label></th>
                        <td><select style="max-width: 25em;" name="region0" id="region0" /></td>
                    </tr>
                    <tr class="form-field form-required field-region field-region1">
                        <th scope="row"><label for="region1">Kabupaten</label></th>
                        <td><select style="max-width: 25em;" name="region1" id="region1" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="newuser"><?php _e( 'Username' ); ?></label></th>
                        <td><input type="text" class="regular-text wp-suggest-user" name="newuser" id="newuser" /></td>
                    </tr>
                </table>
                <input name="submit" id="submit" class="button button-primary" value="Tambah" type="submit">
                <div id="sideka_command_output">
                </div>
            </form>
        </div>
        <script type="text/javascript" >
                var loadedCode = null;
                function loadRegions(parentCode){
                    loadedCode = parentCode;
                    var level = parentCode === "0" ? 0 : parentCode.split(".").length;
                    if(level >= 2){
                        return;
                    }
                    for (var i = 0; i < 4 ; i++){
                            if(i < level){
                                jQuery(".field-region"+i).show();
                            } else {
                                jQuery("#region"+level).val("");
                                jQuery(".field-region"+i).hide();
                            }
                    } 
                    var data = {
                        'action': 'sideka_get_regions',
                        'parent_code': parentCode
                    };

                    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                    var xhr = jQuery.post(ajaxurl, data, function(response) {
                                jQuery("#region"+level).html("");
                                jQuery("#region"+level).append("<option></option>");
                                jQuery(response).each(function(i, value){
                                        jQuery("#region"+level).append("<option value="+value.region_code+">"+value.region_name+"</option>");
                                });
                                jQuery(".field-region"+level).show();
                    });
                }
                loadRegions("0");
                jQuery(".field-region select").each(function(){
                    jQuery(this).change(function(){
                            var val = jQuery(this).val();
                            loadRegions(val);
                    });
                });

                var isSynchronizing = false;
                function synchronize(){
                    isSynchronizing = true;
                    var start = jQuery("#sideka_supradesa_add [name='start']").val();
                    var region1 = jQuery("#sideka_supradesa_add [name='region1']").val();
                    var newuser = jQuery("#sideka_supradesa_add [name='newuser']").val();
                    jQuery("#sideka_supradesa_add [name='submit']").val("Stop Menambah");
                    var data = {
                        'action': 'sideka_add_supradesa_user',
                        'start': start,
                        'region1': region1,
                        'newuser': newuser,
                    };

                    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                    var xhr = jQuery.post(ajaxurl, data, function(response) {
                        if(response.error){
                                jQuery("#sideka_command_output").prepend("ERROR: "+response.error);
                                stopSynchronize();
                                return;
                        }
                        var results = response.results;
                        for(var i = 0; i < results.length; i++){
                            jQuery("#sideka_command_output").prepend(results[i] + "<br />");
                        }
                        start = response.next;
                        jQuery("#sideka_supradesa_add [name='start']").val(start);
                        if(start && isSynchronizing)
                            synchronize();
                        else {
                            if (!start)
                                jQuery("#sideka_command_output").prepend("Penambahan selesai!<br />");
                            stopSynchronize();
                        }
                    });
                }
                function stopSynchronize(){
                    console.log("stop synchronizing");
                    isSynchronizing= false;
                    jQuery("#sideka_supradesa_add [name='submit']").val("Tambah");
                }
                jQuery("#wpbody form").submit(function(){
                    if(!isSynchronizing){
                        jQuery("#sideka_command_output").html("");
                        synchronize();
                    } else {
                        stopSynchronize();
                    }
                    return false;
                });
        </script>
        <?php
    }

    public function jetpack_batch_page()
    {
        ?>
        <div class="wrap">
            <h1>Connect Jetpack</h1>
            <form id="sideka_jetpack_batch" class="sideka_jetpack_batch" method="post" action="settings.php?page=jetpack_batch">
                <table class="form-table">
                    <tr class="form-field form-required field-start">
                        <th scope="row"><label for="start">Start</label></th>
                        <td><input name="start" id="start" value="0" type="number"></td>
                    </tr>
                </table>
                <input name="submit" id="submit" class="button button-primary" value="Batch Connect" type="submit">
                <div id="sideka_command_output">
                </div>
            </form>
        </div>
        <script type="text/javascript" >

                var isSynchronizing = false;
                function synchronize(){
                    isSynchronizing = true;
                    var start = jQuery("#sideka_jetpack_batch [name='start']").val();
                    jQuery("#sideka_jetpack_batch [name='submit']").val("Stop Batch Connect");
                    var data = {
                        'action': 'sideka_jetpack_batch',
                        'start': start
                    };

                    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                    var xhr = jQuery.post(ajaxurl, data, function(response) {
                        if(response.error){
                                jQuery("#sideka_command_output").prepend("ERROR: "+response.error);
                                stopSynchronize();
                                return;
                        }
                        var results = response.results;
                        for(var i = 0; i < results.length; i++){
                            jQuery("#sideka_command_output").prepend(results[i] + "<br />");
                        }
                        start = response.next;
                        jQuery("#sideka_jetpack_batch [name='start']").val(start);
                        if(start && isSynchronizing)
                            synchronize();
                        else {
                            if (!start)
                                jQuery("#sideka_command_output").prepend("Penghubungan selesai!<br />");
                            stopSynchronize();
                        }
                    });
                }
                function stopSynchronize(){
                    console.log("stop synchronizing");
                    isSynchronizing= false;
                    jQuery("#sideka_jetpack_batch [name='submit']").val("Batch Connect");
                }
                jQuery("#wpbody form").submit(function(){
                    if(!isSynchronizing){
                        jQuery("#sideka_command_output").html("");
                        synchronize();
                    } else {
                        stopSynchronize();
                    }
                    return false;
                });
        </script>
        <?php
    }
}

new SidekaNetworkAdminMenu();

function sideka_site_synchronize($site, $category_configs, $event_category_configs, $role_configs, $nav_menu_configs, $widget_configs, $master_options){
    switch_to_blog( $site->blog_id );
    $result = "Site ".$site->blog_id." Name: ".$site->blogname;

    $result .= " C: ";
    $categories = get_categories(array('hide_empty'=>false));
    foreach ($category_configs as $key => $config){
        $found = false;
        foreach($categories as $category){
            if($category->slug == $config["category_nicename"]){
                $found = true;
                break;
            }
        }
        if(!$found){
            wp_insert_category($config);
            $result .= " ".$config["category_nicename"];
        }
    }

    $result .= " EC: ";
    $event_categories = get_categories(array('hide_empty'=>false, 'taxonomy'=>"tribe_events_cat"));
    foreach ($event_category_configs as $event_config){
        $found = false;
        foreach($event_categories as $event_category){
            if($event_category->slug == $event_config["category_nicename"]){
                $found = true;
                break;
            }
        }
        if(!$found){
            wp_insert_category($event_config);
            $result .= " ".$event_config["category_nicename"];
        }
    }

    
    $result .= " R: ";
    foreach($role_configs as $config){        
        $role = get_role($config[0]);
        if(!$role){
           add_role($config[0], $config[1], $config[2]);
            $result .= " ".$config[0];
        }
    }
    

    $result .= " M: ";
    $menus = wp_get_nav_menus(array("name" => "Menu Utama"));
    if(count($menus)){
        $menu = $menus[0];
        $result .= "F";
        $nav_menus = wp_get_nav_menu_items($menu->term_id);
        foreach ($nav_menu_configs as $nav_menu_config){
            $found = false;
            foreach($nav_menus as $nav_menu){
                if($nav_menu->title == $nav_menu_config["menu-item-title"]){
                    $found = true;
                    break;
                }
            }
            if(!$found){
                wp_update_nav_menu_item($menu->term_id, 0, $nav_menu_config);
                $result .= " ".$nav_menu_config["menu-item-title"];
            }
        }
    }

    $result .= " W: ";
    $added_widgets = sideka_apply_widget_configs($widget_configs);
    foreach($added_widgets as $added_widget){
        $result .= $added_widget." ";
    }

    $result .= " O: ";
    foreach($master_options as $option_name => $option_value){
        if ($option_name == "wp_user_roles"){
            $option_name = "wp_".$site->blog_id."_user_roles";
        }
        $current_option_value = get_option($option_name);
        if($current_option_value == $option_value){
            continue;
        }
        update_option($option_name, $option_value);
        $result .= $option_name." ";
    }

    restore_current_blog();
    return $result;
}

function sideka_sites_synchronize()
{
    if(is_super_admin()){
            $start = intval($_POST["start"]);
            $limit = 5;
            $output = array();
            $output["results"] = [];
            $sites = get_sites(array( "offset" => $start, "number" => $limit));

            $category_configs = sideka_get_category_configs();
            $event_category_configs = sideka_get_event_category_configs();
            $role_configs = sideka_get_role_configs();
            $nav_menu_configs = sideka_get_nav_menu_configs();
            $widget_configs = sideka_get_widget_configs();

            $master_options = sideka_get_sitewide_options(1, false);

            foreach ($sites as $site) {
                if($site->blog_id == 1)
                    continue;
                $output["results"][] = sideka_site_synchronize($site, $category_configs, $event_category_configs, $role_configs, $nav_menu_configs, $widget_configs, $master_options);
            }
            $output["next"] = count($sites) == $limit ? ($start + $limit) : 0;
            wp_send_json($output);
    }
}
add_action( 'wp_ajax_sideka_sites_synchronize', 'sideka_sites_synchronize' );

function sideka_user_synchronize($user, $user_meta){
    $result = "User ".$user->ID." Login: ".$user->user_login;
    foreach($user_meta as $meta){
        $success = update_user_meta( $user->ID, $meta[0], $meta[1]);
        if($success){
            $result .= " ".$meta[0];
        }
    }
    return $result;
}

function sideka_users_synchronize()
{
    if(is_super_admin()){
            global $wpdb;
            $start = intval($_POST["start"]);
            $limit = 5;
            $output = array();
            $output["results"] = [];
            $users = $wpdb->get_results($wpdb->prepare("SELECT ID, user_login, display_name, user_email FROM ".$wpdb->base_prefix."users limit %d offset %d", $limit, $start));
            $user_meta = sideka_get_initial_user_meta();
            foreach ($users as $user) {
                $output["results"][] = sideka_user_synchronize($user, $user_meta);
            }
            $output["next"] = count($users) == $limit ? ($start + $limit) : 0;
            wp_send_json($output);
    }
}
add_action( 'wp_ajax_sideka_users_synchronize', 'sideka_users_synchronize' );


function sideka_add_supradesa_user()
{
    if(is_super_admin()){
            error_reporting(1);
            global $wpdb;
            $newuser = $_POST['newuser'];
            $user = get_user_by( 'login', $newuser );
            if ( !$user || !$user->exists() ) {
                wp_send_json(array("error"=>"User not found: ".$newuser));
                return;
            }

            if ( substr_count($_POST["region1"], ".") != 1) {
                wp_send_json(array("error"=>"Invalid region: ".$_POST["region1"]));
                return;
            }

            $prefix = $_POST["region1"].".%";
            $start = intval($_POST["start"]);
            $limit = 5;
            $output = array();
            $output["results"] = [];
            $desas = $wpdb->get_results($wpdb->prepare("SELECT blog_id, domain, kode FROM sd_desa where kode like %s order by kode limit %d offset %d", $prefix, $limit, $start));
            foreach ($desas as $desa) {
                $result = $desa->kode . " " . $desa->domain . " ";
                if ( is_user_member_of_blog( $user->ID, $desa->blog_id ) ) {
                    $result = $result . "is already a member";
                } else {
                    add_user_to_blog( $desa->blog_id, $user->ID, "administrator" );
                    $result = $result . "added";
                }
                $output["results"][] = $result;
            }
            $output["next"] = count($desas) == $limit ? ($start + $limit) : 0;
            wp_send_json($output);
    }
}
add_action( 'wp_ajax_sideka_add_supradesa_user', 'sideka_add_supradesa_user' );

function sideka_jetpack_batch()
{
    if(is_super_admin()){
           error_reporting(1);
	   $jp = Jetpack::init();
	   $jpms = Jetpack_Network::init();


            global $wpdb;
            $start = intval($_POST["start"]);
            $limit = 5;
            $output = array();
            $output["results"] = [];
            $desas = $wpdb->get_results($wpdb->prepare("SELECT blog_id, domain, kode FROM sd_desa order by blog_id limit %d offset %d", $limit, $start));
            foreach ($desas as $desa) {
                $result = $desa->kode . " " . $desa->domain . " ";

                switch_to_blog( $desa->blog_id );
		$is_active =  $jp->is_active();
		restore_current_blog();

                if ($is_active){
                    $result = $result . "is already connected";
                } else {
                    $error = $jpms->do_subsiteregister($desa->blog_id);
		    if ( is_wp_error( $error ) ) {
                     $result = $result . " error on connecting";
		    } else {
                     $result = $result . "connected";
		    }
                }
                $output["results"][] = $result;
            }
            $output["next"] = count($desas) == $limit ? ($start + $limit) : 0;
            wp_send_json($output);
    }
}
add_action( 'wp_ajax_sideka_jetpack_batch', 'sideka_jetpack_batch' );
