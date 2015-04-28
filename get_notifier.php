<?php

    /** 
     * WPMU Push (wpmupush.com)
     * Lets make the javascript needed for notifications
     * @author: Jason Jersey
     * @since: 1.0
     */
    define('WP_USE_THEMES', false);
    require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-blog-header.php');
    include('js/notify-js.php');
    wpmup_notify_js();
    http_response_code(200);
    header("content-type: application/x-javascript");
    
?>
if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
        if (!('permission' in Notification)) {
            Notification.permission = permission;
        }
    });
}