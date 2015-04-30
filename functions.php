<?php

/** 
 * WPMU Push (wpmupush.com)
 * Create random version key
 * @author: Jason Jersey
 * @since: 1.0
 */
function generate_version_key($length = 3) {
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
        return $randomString;
}

/** 
 * WPMU Push (wpmupush.com)
 * Make version key global
 * @author: Jason Jersey
 * @since: 1.0
 */
global $WPMUP_VERSION_KEY;
$WPMUP_VERSION_KEY = generate_version_key();

/** 
 * WPMU Push (wpmupush.com)
 * Create init url
 * @author: Jason Jersey
 * @since: 1.0
 */
function get_initMe() {

    global $WPMUP_VERSION_KEY;
    
    /** Used to create version strings. Stops file from being cached */
    $PUT_VER_STRING = time();
    
    /** Get plugin directory url */
    $WPMUPUSH_URL    = plugin_dir_url( __FILE__ );  
    $WPMUPUSH_STRING = "get_init.php?ver=$PUT_VER_STRING.$WPMUP_VERSION_KEY"; 
    
    /** Url to external js file with version string */
    $GET_INIT = "$WPMUPUSH_URL$WPMUPUSH_STRING";
    
    return $GET_INIT;
}

/** 
 * WPMU Push (wpmupush.com)
 * Add stuff to frontend header
 * @author: Jason Jersey
 * @since: 1.0
 */
function the_notifyMe() {

echo "\n<!-- Web Notifications by WPMU Push -->\n";
/** echo "<script type='text/javascript' src='http://code.jquery.com/jquery-1.11.1.min.js'></script>\n"; */
/** wp_enqueue_script("jquery"); */
echo "<script type='text/javascript' src='" . get_initMe() . "'></script>\n";
echo "<!--// WPMU Push (www.wpmupush.com) -->\n\n";

}
/** add_action('wp_head', 'the_notifyMe'); */
add_action('wp_footer', 'the_notifyMe');

/** 
 * WPMU Push (wpmupush.com)
 * Insert data into wpmupush_posts table in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_insert_posts_data( $post_ID ) {
	global $wpdb;
	
        $pub_post   = get_post($post_ID);
        $author_id  = $pub_post->post_author;
        $post_title = $pub_post->post_title;
        $postperma  = get_permalink( $post_ID );     
  	
	$wpmup_post_id    = $post_ID;
	$wpmup_msg_status = 'true';
	$wpmup_author     = $author_id;
	$wpmup_title      = $post_title;
	$wpmup_url        = $postperma;
	
	$table_name = $wpdb->prefix . 'wpmupush_posts';
	$already    = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id = $post_ID");
	
	if ( ! $already == $post_ID ) {
	
	    $wpdb->insert( 
		  $table_name, 
		  array(
			'post_id'    => $wpmup_post_id,
			'msg_status' => $wpmup_msg_status,
			'author'     => $wpmup_author,
			'title'      => $wpmup_title,
			'url'        => $wpmup_url
		  )		
	    );
	
	}
	
	wpmupush_update_posts_data();
	
}
add_action('publish_post', 'wpmupush_insert_posts_data');

/** 
 * WPMU Push (wpmupush.com)
 * Update data in wpmupush_posts row in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_update_posts_data() {
	global $wpdb;
	
	$a                = $wpdb->insert_id;
        $lastid           = bcsub($a, 1);  
	$table_name       = $wpdb->prefix . 'wpmupush_posts';
	$wpmup_id         = $lastid;
	$wpmup_msg_status = 'false';

	$data = array(
	     'msg_status' => $wpmup_msg_status
	);
	
        $where        = array( 'id' => $wpmup_id );
	$format       = array( '%s', '%d' );
	$where_format = array( '%d' );

	$wpdb->update( 
		$table_name, $data, $where, $format, $where_format 
		);
	
}

/**
 * WPMU Push (wpmupush.com)
 * Insert data into wpmupush_posts_activity table in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_insert_posts_activity_data() {
	global $wpdb;

        $user_id = get_current_user_id();
        $post_id = get_posts("numberposts=1");
	
	$wpmup_user_id        = $user_id; /** receiver user_id */
	$wpmup_ip_address     = $_SERVER['REMOTE_ADDR']; /** receiver ip */
	$wpmup_footprint      = '0000000000000000000000000'; /** receiver footprint */
	$wpmup_post_id        = $post_id[0]->ID; /** post id */
	$wpmup_post_author_id = get_post_field( 'post_author', $wpmup_post_id ); /** author */
	
	$table_name = $wpdb->prefix . 'wpmupush_posts_activity';
	
	    $wpdb->insert( 
		  $table_name, 
		  array(
			'user_id'        => $wpmup_user_id,
			'ip_address'     => $wpmup_ip_address,
			'footprint'      => $wpmup_footprint,
			'post_id'        => $wpmup_post_id,
			'author_user_id' => $wpmup_post_author_id
		  )		
	    );
	
}