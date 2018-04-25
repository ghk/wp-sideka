<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/16/2016
 * Time: 8:59 PM
 */


class SidekaDesaMenu
{
    private $blog_id = null;
    private $region_code = null;

    public function __construct() {
        add_action( 'admin_menu', array($this, 'admin_menu' ));
    }

    public function admin_menu()
    {
        global $wpdb;
        $this->blog_id = get_current_blog_id();
        $this->region_code = $wpdb->get_var($wpdb->prepare("SELECT kode FROM sd_desa where blog_id = %d", $this->blog_id));
        if(!$this->region_code){
            add_options_page('Desa', 'Desa', 'manage_options', 'desa', array($this, 'settings_page'));
        }
    }

    public function settings_page()
    {
        global $wpdb;
        $show_form = true;
        if(isset($_POST['region3'])){
                $region_code = $_POST['region3'];
                $is_valid_region = $wpdb->get_var($wpdb->prepare("SELECT id FROM sd_all_desa where region_code = %s and depth = 4", $region_code));
                $is_selected_region = $wpdb->get_var($wpdb->prepare("SELECT blog_id FROM sd_desa where kode = %s", $region_code));
                if($is_valid_region && !$is_selected_region){
                        $wpdb->update('sd_desa', array('kode'=>$region_code), array('blog_id'=>$this->blog_id));
                ?>
                        <div class="wrap">
                            <h1>Terima kasih</h1>
                            <div class="notice"><p>Terima kasih telah melengkapi desa.</p></div>
                        </div>
                <?php
                    $show_form = false;
                }
        }
        if($show_form) {
        ?>
        <div class="wrap">
            <h1>Lengkapi Desa</h1>
            <div class="notice"><p>Silahkan lengkapi nama propinsi, kabupaten, kecamatan, dan desa Web Desa ini.</p></div>

            <form method="post" action="">
                <?php sideka_region_form(); ?>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
        }
    }
}

new SidekaDesaMenu();

