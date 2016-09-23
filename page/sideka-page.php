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
    if ( is_page("keuangan-desa") ) {
        ob_start();
        include dirname(__FILE__) . '/template-keuangan.php';
        $string = ob_get_clean();
        $content .= $string;
    }
    if ( is_page("data") ) {
        ob_start();
        include dirname(__FILE__) . '/template-data.php';
        $string = ob_get_clean();
        $content .= $string;
    }

    return $content;
}