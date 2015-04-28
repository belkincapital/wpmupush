<?php

    /** 
     * WPMU Push (wpmupush.com)
     * Lets make the javascript needed for init
     * @author: Jason Jersey
     * @since: 1.0
     */
    define('WP_USE_THEMES', false);
    require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-blog-header.php');
    include('js/init-js.php');
    wpmup_init_js();
    http_response_code(200);
    header("content-type: application/x-javascript");

?>