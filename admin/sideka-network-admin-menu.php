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
            <form id="sideka_synchronize" method="post" action="settings.php?page=sideka">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Sinkronisasi Category &amp; Role</th>
                        <td>
                            <input name="start" type="number" style="width: 80px;" value="0"/>
                            <input name="submit" id="submit" class="button button-primary" value="Sinkronisasi" type="submit">
                        </td>
                    </tr>
                </table>
                <div id="sideka_command_output">
                </div>
            </form>
            <script type="text/javascript" >
                    var isSynchronizing = false;
                    function synchronize(){
                        var start = parseInt(jQuery("#sideka_synchronize [name='start']").val());
                        isSynchronizing = true;
                        jQuery("#sideka_synchronize [name='submit']").val("Stop Sinkronisasi");
                        var data = {
                            'action': 'sideka_synchronize',
                            'start': start
                        };

                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        var xhr = jQuery.post(ajaxurl, data, function(response) {
                            var results = response.results;
                            for(var i = 0; i < results.length; i++){
                                jQuery("#sideka_command_output").prepend(results[i] + "<br />");
                            }
                            start = response.next;
                            jQuery("#sideka_synchronize [name='start']").val(start);
                            if(start && isSynchronizing)
                                synchronize();
                            else {
                                if (!start)
                                    jQuery("#sideka_command_output").prepend("Sinkronisasi selesai!<br />");
                                stopSynchronize();
                            }
                        });
                    }
                    function stopSynchronize(){
                        isSynchronizing = false;
                        jQuery("#sideka_synchronize [name='submit']").val("Sinkronisasi");
                    }
                    jQuery("#sideka_synchronize").submit(function($) {
                        if(!isSynchronizing){
                            jQuery("#sideka_command_output").html("");
                            synchronize();
                        } else {
                            stopSynchronize();
                        }
                        return false;
                    });
            </script>
        </div>
        <?php
    }
}

new SidekaNetworkAdminMenu();

function synchronize_site($site, $category_configs){
    switch_to_blog( $site->blog_id );
    $result = "Site ".$site->blog_id." Name: ".$site->blogname;

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

/*
    foreach($role_configs as $config){
        $role = get_role($config[0]);
        if(!$role){
           add_role($config[0], $config[1], $config[2]);
            $result .= " ".$config[0];
        }
    }
*/

    restore_current_blog();
    return $result;
}

function sideka_synchronize()
{
    if(is_super_admin()){
            $start = intval($_POST["start"]);
            $limit = 5;
            $output = array();
            $output["results"] = [];
            $sites = get_sites(array( "offset" => $start, "number" => $limit));
            $category_configs = sideka_get_category_configs();
            foreach ($sites as $site) {
                $output["results"][] = synchronize_site($site, $category_configs);
            }
            $output["next"] = count($sites) == $limit ? ($start + $limit) : 0;
            wp_send_json($output);
    }
}
add_action( 'wp_ajax_sideka_synchronize', 'sideka_synchronize' );
