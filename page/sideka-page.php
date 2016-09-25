<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 9/16/2016
 * Time: 9:16 PM
 */

add_filter( 'the_content', 'sideka_the_content' );
function sideka_the_content( $content )
{
    if ( is_page() ) {
        $page_id = get_queried_object_id();
        $sideka_page_ids = get_option("sideka_page_ids");
        foreach($sideka_page_ids as $key => $value){
            if($value == $page_id){
                ob_start();
                include dirname(__FILE__) . '/template-'.$key.'.php';
                $string = ob_get_clean();
                $content .= $string;
                return $content;
            }
        }
    }

    return $content;
}