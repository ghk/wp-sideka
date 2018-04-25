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

    /*
    $result .= " R: ";
    foreach($role_configs as $config){
        $role = get_role($config[0]);
        if(!$role){
           add_role($config[0], $config[1], $config[2]);
            $result .= " ".$config[0];
        }
    }
    */

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

            $master_options = sideka_get_sitewide_options(1);

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
            $users = $wpdb->get_results("SELECT ID, user_login, display_name, user_email FROM ".$wpdb->base_prefix."users limit ".$limit." offset ".$start);
            $user_meta = sideka_get_initial_user_meta();
            foreach ($users as $user) {
                $output["results"][] = sideka_user_synchronize($user, $user_meta);
            }
            $output["next"] = count($users) == $limit ? ($start + $limit) : 0;
            wp_send_json($output);
    }
}
add_action( 'wp_ajax_sideka_users_synchronize', 'sideka_users_synchronize' );

function sideka_get_regions()
{
    if(is_super_admin()){
            global $wpdb;
            $parent_code = $_POST["parent_code"];
            $results = $wpdb->get_results("SELECT region_code, region_name FROM sd_all_desa where parent_code = '$parent_code' order by region_name");
            wp_send_json($results);
    }
}
add_action( 'wp_ajax_sideka_get_regions', 'sideka_get_regions' );

add_action('network_site_new_form', 'sideka_network_site_new_form');
function sideka_network_site_new_form() {
    global $wpdb;
    $previous_code = $wpdb->get_var($wpdb->prepare("select kode from sd_desa where blog_id = %d", $_GET["id"]));
    ?>
    <div class="notice region-notice" style="display:none;"><p>Silahkan isi Propinsi sampai Desa</p></div>'
	<table class="form-table">
		<tr class="form-field form-required field-region field-region0">
			<th scope="row"><label for="region0">Propinsi</label></th>
			<td><select style="max-width: 25em;" name="region0" id="region0" /></td>
		</tr>
		<tr class="form-field form-required field-region field-region1">
			<th scope="row"><label for="region1">Kabupaten</label></th>
			<td><select style="max-width: 25em;" name="region1" id="region1" /></td>
		</tr>
		<tr class="form-field form-required field-region field-region2">
			<th scope="row"><label for="region2">Kecamatan</label></th>
			<td><select style="max-width: 25em;" name="region2" id="region2" /></td>
		</tr>
		<tr class="form-field form-required field-region field-region3">
			<th scope="row"><label for="region3">Desa</label></th>
			<td><select style="max-width: 25em;" name="region3" id="region3" /></td>
		</tr>
    </table>
            <?php echo $_REQUEST["region3"] ?>
            <script type="text/javascript" >
                    var previousCode = "<?php echo $previous_code ?>";
                    var loadedCode = null;
                    function loadRegions(parentCode){
                        loadedCode = parentCode;
                        var level = parentCode === "0" ? 0 : parentCode.split(".").length;
                        if(level >= 4){
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
                                    if(previousCode){
                                        if(level >= 3){
                                            previousCode = null;
                                            return;
                                        }
                                        var currentVal = previousCode.split(".").slice(0, level +1).join(".");
                                        console.log(currentVal);
                                        jQuery("#region"+level).val(currentVal);
                                        loadRegions(currentVal);
                                    }
                        });
                    }
                    loadRegions("0");
                    jQuery(".field-region select").each(function(){
                        jQuery(this).change(function(){
                                var val = jQuery(this).val();
                                loadRegions(val);
                        });
                    });
                    jQuery("#wpbody form").submit(function(){
                        jQuery(".region-notice").hide();
                        var val = jQuery("#region3").val();
                        if(!val){
                            jQuery(".region-notice").show();
                            return false;
                        }
                        return true;
                    });
            </script>
    <?php
}

