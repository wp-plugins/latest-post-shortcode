=== Plugin Name ===
Contributors: Iulia Cazan 
Author URI: https://profiles.wordpress.org/iulia-cazan
Tags: post, latest post, taxonomy, category, tag, parent, shortcode, latest, custom, selection, post by category, post by taxonomy, post by tag, post by id, post by parent, last post, tiles from posts, tile template, short content, short excerpt, limit content, limit excerpt
Requires at least: not tested
Tested up to: wp 4.1.1
Stable tag:  2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ

== Description ==
The plugin registers a configurable shortcode that allows you to create a dynamic content selection from your posts, pages and custom post types by combining and filtering what you need. The shortcode can be generated very easy, the plugin will add a shortcode button for this in the editor area. 

== Installation ==
* Upload `Latest Post Shortcode` to the `/wp-content/plugins/` directory of your application
* Login as Admin
* Activate the plugin through the 'Plugins' menu in WordPress

== Hooks ==
admin_enqueue_scripts, init, plugins_loaded, media_buttons_context, admin_footer, admin_head, wp_head

== Screenshots ==
1. The shortcode generator button in the editor area.
2. Options to configure the shortcode.

== Frequently Asked Questions ==
None

== Changelog ==

= 2.0 =
* Allow for different tile pattern (the html tags order in the tile: post title, image, text and read more message) 
* Add visual tile pattern selector
* Add short excerpt and short content options
* Add chars limit to the excerpt or content for the tile
* Add custom "read more" message option
* Allow for the post link to wrap the entire tile or just the "read more" message if this is set

== Upgrade Notice ==
Chars limit, custom "read more" option and different tile patterns in the new version! You should upgrade, it's free! 
Donation and reviews are welcomed and will help me to continue future development.

== License ==
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 

== Version history ==
2.0 - Visual pattern selector and more features
1.0 - Development version.