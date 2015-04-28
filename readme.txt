=== Plugin Name ===
Contributors: icryptic
Donate link: http://goo.gl/E4KiBV
Tags: popup, desktop notification, web notification, push notification, notifications, chrome notification, firefox notification, html5 notification, messages, announcement, call to action, wpmu, wordpress multisite, wp multisite, multisite, wp mu, mu, pushover, roost
Requires at least: 3.9.1
Tested up to: 4.2.1
Stable tag: 1.0
License: GNU General Public License 3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Send Notifications to your site visitors and/or users.

== Description ==

Display notifications to your site visitors or logged in users when there is a new post. A proven method to increase user engagement. Works with latest Chrome and Firefox browsers along with WordPress Multisite and Standard WP sites. On Multisite you may either Network Activate this plugin or you may install it on a case by case basis within the subsites you choose.

Introduction: https://www.youtube.com/watch?v=MCteV2e5Hlk

This plugin currently works as a web notification of new posts. Great for greeting users and telling them what's new. On member sites, this can really be handy as well for real-time notifications.

== Installation ==

WordPress Standard:
1. Upload `wpmupush` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress wp-admin.
3. That's it. There are no settings to change.

WordPress Multisite:
1. Upload `wpmupush` to the `/wp-content/plugins/` directory
2. Network Activate the plugin through the 'Plugins' menu in WordPress Network Admin area.
3. That's it. There are no settings to change.

== Frequently Asked Questions ==

= What web browsers is WPMU Push compatible with? =
WPMu Push is currently compatible with all html5 web browsers, such as: Chrome, Firefox, and Opera. Internet Explorer does not support HTML5, so WPMU Push will not work with it. However, the new browser coming soon from Microsoft is supposedly HTML5 ready. Sorry, Safari is behind the times too.

= Does WPMU Push support offsite Push Notifications? =
Yes, kinda' sorta. WPMU Push can be extended beyond the current capability if used with a "Service Worker" over https (http://www.html5rocks.com/en/tutorials/service-worker/introduction/). However, that is optional and requires a few additional steps. But right out-the-box WPMU Push works with no additional setup and works as an excellent way to grab your users attention.

= What api's does WPMU Push utilize? =
WPMU Push uses the HTML5 Web Notification API. More information can be found here: https://notifications.spec.whatwg.org

= What does the future of WPMU Push look like? =
I have plans to implement comment notifications down the line, such as notifications of replies to comments and admin notifications of all new comments.

= How may I contribute to WPMU Push? =
I'd like to encourage plugin contributors. You are welcome to contribute to WPMU Push via Github at https://github.com/belkincapital/wpmupush/tree/develop

= What license requirements are there? =
If you modify this plugin or reuse any portion of it, you MUST keep all attribution within the source code including the full copyright and liability waiver text.

== Changelog ==

= 1.0 =
* Initial public release

== Upgrade Notice ==

= 1.0 =
First public release of WPMU Push. This is experimential software.
