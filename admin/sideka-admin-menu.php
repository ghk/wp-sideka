<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/16/2016
 * Time: 8:59 PM
 */


class SidekaAdminMenu
{
    public $option_name = "sideka_page_ids";

    public function __construct() {
        add_action( 'admin_menu', array($this, 'admin_menu' ));
        add_action( 'admin_init', array($this, 'admin_init' ));
    }

    public function admin_menu()
    {
        add_options_page('Sideka', 'Sideka', 'manage_options', 'sideka', array($this, 'settings_page'));
    }

    public function admin_init()
    {
        register_setting('sideka_page_options', $this->option_name);
    }

    public function settings_page()
    {
        $options = get_option($this->option_name);
        ?>
        <div class="wrap">
            <h1>Sideka</h1>

            <form method="post" action="options.php">
                <?php settings_fields('sideka_page_options'); ?>
                <?php do_settings_sections('sideka_page_options'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Laman Data Anggaran</th>
                        <td>
                            <select name="<?= $this->option_name ?>[anggaran]">
                                <option value=""></option>
                                <?php
                                if ($pages = get_pages()) {
                                    foreach ($pages as $page) {
                                        echo '<option value="' . $page->ID . '" ' . selected($page->ID, $options['anggaran']) . '>' . $page->post_title . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Laman Data Kependudukan</th>
                        <td>
                            <select name="<?= $this->option_name ?>[kependudukan]">
                                <option value=""></option>
                                <?php
                                if ($pages = get_pages()) {
                                    foreach ($pages as $page) {
                                        echo '<option value="' . $page->ID . '" ' . selected($page->ID, $options['kependudukan']) . '>' . $page->post_title . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Laman Data Geospasial</th>
                        <td>
                            <select name="<?= $this->option_name ?>[geospasial]">
                                <option value=""></option>
                                <?php
                                if ($pages = get_pages()) {
                                    foreach ($pages as $page) {
                                        echo '<option value="' . $page->ID . '" ' . selected($page->ID, $options['geospasial']) . '>' . $page->post_title . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>

            </form>
        </div>
        <?php
    }
}

new SidekaAdminMenu();
