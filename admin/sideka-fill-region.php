<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/16/2016
 * Time: 8:59 PM
 */



function sideka_get_regions()
{
    global $wpdb;
    $parent_code = $_POST["parent_code"];
    $results = $wpdb->get_results($wpdb->prepare("SELECT region_code, region_name FROM sd_all_desa where parent_code = %s order by region_name", $parent_code));
    wp_send_json($results);
}
add_action( 'wp_ajax_sideka_get_regions', 'sideka_get_regions' );

function sideka_check_region_code()
{
    global $wpdb;
    $region_code = $_POST["region_code"];
    $domain = $wpdb->get_var($wpdb->prepare("SELECT domain FROM sd_desa where kode = %s", $region_code));
    wp_send_json($domain);
}
add_action( 'wp_ajax_sideka_check_region_code', 'sideka_check_region_code' );

add_action('network_site_new_form', 'sideka_region_form');
function sideka_region_form() {
    global $wpdb;
    $previous_code = null;
    if(array_key_exists('id', $_GET)){
        $previous_code = $wpdb->get_var($wpdb->prepare("select kode from sd_desa where blog_id = %d", $_GET["id"]));
    }
    ?>
    <div class="notice region-notice" style="display:none;"><p>Silahkan isi Propinsi sampai Desa</p></div>
    <div class="notice region-exists-notice" style="display:none;"><p>Desa telah mempunyai web desa pada domain <a href="" target="_blank"></a></p></div>
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
            <script type="text/javascript" >
                    var previousCode = "<?php echo $previous_code ?>";
                    var regionExistsDomain = null;
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
                                if(jQuery(this).attr("id") == "region3"){
                                    regionExistsDomain = "loading";
                                    var data = {
                                    'action': 'sideka_check_region_code',
                                    'region_code': val
                                    };

                                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                                    var xhr = jQuery.post(ajaxurl, data, function(response) {
                                        regionExistsDomain = response;
                                        jQuery(".region-exists-notice a").html(regionExistsDomain);
                                        jQuery(".region-exists-notice a").attr("href", "http://"+regionExistsDomain);
                                        if(!regionExistsDomain){
                                            jQuery(".region-exists-notice").hide();
                                        }
                                    });
                                }
                        });
                    });
                    jQuery("#wpbody form").submit(function(){
                        jQuery(".region-notice").hide();
                        jQuery(".region-exists-notice").hide();
                        var val = jQuery("#region3").val();
                        if(!val){
                            jQuery(".region-notice").show();
                            return false;
                        }
                        if(regionExistsDomain){
                            jQuery(".region-exists-notice").show();
                            return false;
                        }
                        return true;
                    });
            </script>
    <?php
}

