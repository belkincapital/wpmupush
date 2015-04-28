<?php
/*
    Plugin Name: WPMU Push
    Plugin URI: http://www.wpmupush.com/
    Description: Display notifications to your site visitors or logged in users when there is a new post. A proven method to increase user engagement. Works with latest Chrome and Firefox browsers along with WordPress Multisite and Standard WP sites. On Multisite you may either Network Activate this plugin or you may install it on a case by case basis within the subsites you choose.
    
    Author: Jason Jersey
    Author URI: https://www.twitter.com.com/degersey
    Version: 1.0
    Text Domain: wpmupush
    Domain Path: /languages/
    License: GNU General Public License 3.0 
    License URI: http://www.gnu.org/licenses/gpl-3.0.txt
    
    Copyright 2015 Belkin Capital Ltd (contact: https://belkincapital.com/contact/)

    This plugin is opensource; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published
    by the Free Software Foundation; either version 3 of the License,
    or (at your option) any later version (if applicable).

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111 USA
*/


/** 
 * WPMU Push (wpmupush.com)
 * Dont allow direct access
 * @author: Jason Jersey
 * @since: 1.0
 */
if ( ! defined( 'ABSPATH' ) ) die("Whatcha think you're doing bub? SMH!");

/** 
 * WPMU Push (wpmupush.com)
 * Set global db version numbre
 * This should'nt be changed.
 * @author: Jason Jersey
 * @since: 1.0
 */
global $wpmup_db_version;
$wpmup_db_version = '1.0';

/** 
 * WPMU Push (wpmupush.com)
 * Include functions
 * @author: Jason Jersey
 * @since: 1.0
 */
include( 'functions.php' );

/**
 * WPMU Push (wpmupush.com)
 * Create wpmupush_posts table in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_install_posts( $wpmup_db_version ) {
	global $wpdb;
	global $wpmup_db_version;
	$table_name = $wpdb->prefix . 'wpmupush_posts';	
	$charset_collate = $wpdb->get_charset_collate();
	$sql = $wpdb->query("CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		post_id text NOT NULL,
		msg_status text NOT NULL,
		author tinytext NOT NULL,
		title text NOT NULL,
		url varchar(55) DEFAULT '' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;");
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'wpmup_db_version', $wpmup_db_version );
}

/**
 * WPMU Push (wpmupush.com)
 * Create wpmupush_posts_activity table in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_install_posts_activity( $wpmup_db_version ) {
	global $wpdb;
	global $wpmup_db_version;
	$table_name = $wpdb->prefix . 'wpmupush_posts_activity';	
	$charset_collate = $wpdb->get_charset_collate();
	$sql = $wpdb->query("CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id mediumint(9) NOT NULL,
		ip_address text NOT NULL,
		footprint text NOT NULL,
		post_id mediumint(9) NOT NULL,
		author_user_id mediumint(9) NOT NULL,	
		UNIQUE KEY id (id)
	) $charset_collate;");
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'wpmup_db_version', $wpmup_db_version );
}

/** 
 * WPMU Push (wpmupush.com)
 * Activation hook
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_activate( $network_wide ) {
    global $wpdb;
    if ( is_multisite() && $network_wide ) {
        /** store the current blog id */
        $current_blog = $wpdb->blogid;
        /** Get all blogs in the network and activate plugin on each one */
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            /** Add new tables to install below */
            wpmupush_install_posts_activity();
            wpmupush_install_posts();
            /** Add new tables to install above */
            restore_current_blog();
        }
    } else {
        /** Add new tables to install below */
        wpmupush_install_posts_activity();
        wpmupush_install_posts();
        /** Add new tables to install above */
    }
}
register_activation_hook( __FILE__, 'wpmupush_activate' );

/** 
 * WPMU Push (wpmupush.com)
 * Creating tables whenever a new blog is created on MU
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {   
    if ( is_plugin_active_for_network( 'wpmupush/wpmupush.php' ) ) {
        switch_to_blog( $blog_id );
        /** Add new tables to install below */
        wpmupush_install_posts_activity();
        wpmupush_install_posts();
        /** Add new tables to install above */
        restore_current_blog();
    }    
}
add_action( 'wpmu_new_blog', 'wpmupush_create_blog', 10, 6 );

/** 
 * WPMU Push (wpmupush.com)
 * Deleting the tables whenever a blog is deleted on MU
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_delete_blog( $tables ) {
    global $wpdb;
    /** Add new tables to delete below */
    $tables[] = $wpdb->prefix . 'wpmupush_posts_activity';
    $tables[] = $wpdb->prefix . 'wpmupush_posts';
    /** Add new tables to delete above */
    return $tables;  
}
add_filter( 'wpmu_drop_tables', 'wpmupush_delete_blog' );

/**
 * WPMU Push (wpmupush.com)
 * Delete wpmupush_activity table in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_deactivate_one() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpmupush_posts_activity';
	$wpdb->query("DROP TABLE IF EXISTS $table_name");	
}

/**
 * WPMU Push (wpmupush.com)
 * Delete wpmupush_posts table in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_deactivate_two() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpmupush_posts';
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
}

/** 
 * WPMU Push (wpmupush.com)
 * Deactivation hook
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmupush_deactivate( $network_wide ) {   
    global $wpdb;
    if ( is_multisite() && $network_wide ) {
        /** store the current blog id */
        $current_blog = $wpdb->blogid;
        /** Get all blogs in the network and deactivate plugin on each one */
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            /** Add new tables to delete below */
            wpmupush_deactivate_one();
            wpmupush_deactivate_two();
            /** Add new tables to delete above */
            restore_current_blog();
        }
    } else {
        /** Add new tables to delete below */
        wpmupush_deactivate_one();
        wpmupush_deactivate_two();
        /** Add new tables to delete above */
    }       
}
register_deactivation_hook( __FILE__, 'wpmupush_deactivate' );
