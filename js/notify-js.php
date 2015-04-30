<?php

/**
 * WPMU Push (wpmupush.com)
 * Prepare notification script
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmup_notify_js() {

	global $wpdb;

        $user_id = get_current_user_id();
        $post_id = get_posts("numberposts=1");

	$wpmup_ip_address = $_SERVER['REMOTE_ADDR']; /** receiver ip */
	$wpmup_post_id    = $post_id[0]->ID; /** post id */
	
	$db_table  = $wpdb->prefix . 'wpmupush_posts_activity';
	$sql_one   = $wpdb->query("SELECT * FROM $db_table WHERE user_id = $user_id AND post_id = $wpmup_post_id");
	$sql_two   = $wpdb->query("SELECT * FROM $db_table WHERE ip_address = '$wpmup_ip_address' AND post_id = $wpmup_post_id");
	
	/** Get plugin directory url */
        $wpmupush_url  = plugin_dir_url( dirname(__FILE__) );
		
	if ( is_user_logged_in() ) {/** START: if logged in */

/** the code within this function (below) is javascript */

?>
function notifyMe(){

    if (Notification.permission === "granted") {  
    
    <?php if ( ! $sql_one == $user_id && $wpmup_post_id ) { ?>
    <?php query_posts('showposts=1'); ?>
    <?php while (have_posts()) : the_post(); ?>
        var options = { 
            body: "<?php the_title(); ?>",
            icon: "<?php echo $wpmupush_url; ?>image/wp-icon.png",
            dir : "ltr"
        };
        var notification = new Notification("New Post: (ID: <?php echo $wpmup_post_id; ?>)",options);     
        notification.onclick=function(){ window.location.href = "<?php the_permalink(); ?>"; };
    <?php endwhile; ?>      
        notification.onshow=function(){ var wpmupsnd = new Audio("<?php echo $wpmupush_url; ?>audio/new_post.mp3");wpmupsnd.volume = 0.2;wpmupsnd.play(); };               
    <?php wpmupush_insert_posts_activity_data(); } ?>
    
    }
  
}
<?php

/** the code within this function (above) is javascript */

	}/** END: if logged in */
	    else { /** START: if logged out */

/** the code within this function (below) is javascript */

?>
function notifyMe() {

    if (Notification.permission === "granted") {  
    
    <?php if ( ! $sql_two == $wpmup_ip_address && $wpmup_post_id ) { ?>
    <?php query_posts('showposts=1'); ?>
    <?php while (have_posts()) : the_post(); ?>
        var options = { 
            body: "<?php the_title(); ?>",
            icon: "<?php echo $wpmupush_url; ?>image/wp-icon.png",
            dir : "ltr"
        };
        var notification = new Notification("New Post: (ID: <?php echo $wpmup_post_id; ?>)",options);    
        notification.onclick=function(){ window.location.href = "<?php the_permalink(); ?>"; };
    <?php endwhile; ?>      
        notification.onshow=function(){ var wpmupsnd = new Audio("<?php echo $wpmupush_url; ?>audio/new_post.mp3");wpmupsnd.volume = 0.2;wpmupsnd.play(); };               
    <?php wpmupush_insert_posts_activity_data(); } ?>
    
    }
  
}
<?php

/** the code within this function (above) is javascript */

}/** END: if logged out */

}/** END: wpmup_notify_js() */

?>