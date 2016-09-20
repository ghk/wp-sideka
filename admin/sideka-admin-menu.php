<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/16/2016
 * Time: 8:59 PM
 */

add_action( 'admin_menu', 'sideka_register_admin_menu' );

function sideka_register_admin_menu(){
    add_menu_page( 'Keuangan', 'Keuangan', 'manage_options', 'sideka-budget', 'sideka_budget_settings_page', 'dashicons-analytics', 50);
    add_menu_page( 'Wilayah', 'Wilayah', 'manage_options', 'sideka-map', 'sideka_map_settings_page',  'dashicons-location-alt', 51);
}

function sideka_budget_settings_page() {
    ?>
    <div class="wrap">
        <h1>Keuangan</h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
            <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">URL Data Anggaran</th>
                    <td><input type="text" name="budget_file_url" value="<?php echo esc_attr( get_option('budget_file_url') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Laman Anggaran</th>
                    <td>
                        <select name="budget_page">
                            <option value="">-</option>
                            <?php
                            if( $pages = get_pages() ){
                                foreach( $pages as $page ){
                                    echo '<option value="' . $page->ID . '" ' . selected( $page->ID, get_option('budget_page') ) . '>' . $page->post_title . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <!--
            <?php submit_button(); ?>
            -->

        </form>
    </div>
<?php }

function sideka_map_settings_page() {
    ?>
    <div class="wrap">
        <h1>Wilayah</h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'map-group' ); ?>
            <?php do_settings_sections( 'map-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">URL Peta</th>
                    <td><input type="text" name="map_url" value="<?php echo esc_attr( get_option('map_url') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Laman Peta</th>
                    <td>
                        <select name="budget_page">
                            <option value="">-</option>
                            <?php
                            if( $pages = get_pages() ){
                                foreach( $pages as $page ){
                                    echo '<option value="' . $page->ID . '" ' . selected( $page->ID, get_option('map_page') ) . '>' . $page->post_title . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <!--
            <?php submit_button(); ?>
            -->

        </form>
    </div>
<?php }
