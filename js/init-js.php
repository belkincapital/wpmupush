<?php

/** 
 * WPMU Push (wpmupush.com)
 * Create get_notifier.php url
 * @author: Jason Jersey
 * @since: 1.0
 */
function get_notifyMe() {

    global $WPMUP_VERSION_KEY;
    
    /** Used to create version strings. Stops file from being cached */
    $PUT_VER_STRING = time();
    
    /** Get plugin directory url */   
    $plugins_url = plugins_url();
    
    /** Url to external js file with version string */
    $GET_NOTIFY = "$plugins_url/wpmupush/get_notifier.php?ver=$WPMUP_VERSION_KEY.$PUT_VER_STRING";
    
    return $GET_NOTIFY;
}

/** 
 * WPMU Push (wpmupush.com)
 * Init notifications
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmup_init_js() { 	

/** the code within this function (below) is javascript */

?>
    /** $(document).ready( */
    jQuery(document).ready(
        function() {
            setInterval(function() {                

            /** $.ajax({ */
            jQuery.ajax({
                url: "<?php echo get_notifyMe(); ?>",
                context: document.body
            }).done(function() { 
                notifyMe();
            });
                                                          
            }, 3000); /** 3 seconds */
        }
       
    );
<?php

/** the code within this function (above) is javascript */
} 

?>