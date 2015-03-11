<?php
/*
Plugin Name: Latest Post Shortcode
Description: This plugin allows you to create a dynamic content selection from your posts, pages and custom post types that can be embedded with a shortcode.
Author: Iulia Cazan
Version: 1.0.0
Author URI: https://profiles.wordpress.org/iulia-cazan
License: GPL2

Copyright (C) 2015 Iulia Cazan

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Latest_Post_Shortcode
{
	private static $instance;

	/**
	 * Get active object instance
	 *
	 * @access public
	 * @static
	 * @return object
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new Latest_Post_Shortcode();
		}
		return self::$instance;
	}

	/**
	 * Class constructor.  Includes constants, includes and init method.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Run action and filter hooks.
	 *
	 * @access private
	 * @return void
	 */
	private function init() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		/** Apply the tiles shortcodes */
		add_shortcode( 'latest-selected-content', array( $this, 'latest_selected_content' ) );

		if ( is_admin() ) {
			add_action( 'media_buttons_context', array( $this, 'add_shortcode_button' ) );
			add_action( 'admin_footer', array( $this, 'add_shortcode_popup_container' ) );
			add_action( 'admin_head', array( $this, 'load_admin_assets' ) );
		} else {
			add_action( 'wp_head', array( $this, 'load_assets' ) );
		}
	}

	/**
	 * Latest_Post_Shortcode::load_textdomain() Load text domain for internalization
	 */
	function load_textdomain() {
		load_plugin_textdomain( 'lps', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
	}

	/**
	 * Latest_Post_Shortcode::load_assets() Load the front assets
	 */
	function load_assets() {
		wp_enqueue_style( 'lps-style', plugins_url( '/assets/css/style.css', __FILE__ ), array(), '1.0', false );
	}

	/**
	 * Latest_Post_Shortcode::load_admin_assets() Load the admin assets
	 */
	function load_admin_assets() {
		wp_enqueue_style( 'lps-admin-style', plugins_url( '/assets/css/admin-style.css', __FILE__ ), array(), '1.0', false );
		wp_enqueue_script( 'lps-admin-shortcode-button', plugins_url( '/assets/js/custom.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	}

	/**
	 * Latest_Post_Shortcode::add_shortcode_button() Add a button to the content editor, next to the media button, this button will show a popup that contains inline content
	 */
	function add_shortcode_button( $context ) {
		$container_id = 'lps_shortcode_popup_container';
		$title = __( 'Content Selection', 'lps' );
		$context .= '<a class="thickbox button" title="' . $title . '"
		href="#TB_inline?width=100%25&inlineId=' . $container_id . '" id="lps_shortcode_button_open"><span class="dashicons dashicons-format-aside"></span> ' . __( 'Content Selection', 'lps' ) . '</a>';
		echo $context;
	}

	/**
	 * Latest_Post_Shortcode::add_shortcode_popup_container() Add some content to the bottom of the page. This will be shown in the inline modal
	 */
	function add_shortcode_popup_container() {
		$body = '
		<div id="lps_shortcode_popup_container" style="display:none; width:90%; height:100%">
			<h2>' . __( 'Create Your Custom Content Selection Shortcode By Combining What You Need', 'lps' ) . '</h2>
			<table width="100%" cellpadding="0" cellspacing="0" class="lps_shortcode_popup_container_table">
				<tr>
					<td>' . __( 'Number of Posts', 'lps' ) . '</td>
					<td><input type="text" name="lps_limit" id="lps_limit" value="1" onchange="lps_preview_configures_shortcode()" /></td>
					<td>' . __( 'Post Type', 'lps' ) . '</td>
					<td>
						<select name="lps_post_type" id="lps_post_type" onchange="lps_preview_configures_shortcode()">
							<option value="">' . __( 'Any', 'lps' ) . '</option>
							';
		$post_types = get_post_types( array(), 'objects' );
		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $k => $v ) {
				if ( $k != 'revision' && $k != 'nav_menu_item' ) {
					$body .= '<option value="' . esc_attr( $k ) . '">' . esc_html( $k ) . '</option>';
				}
			}
		}
		$body .= '
						</select>
					</td>
				</tr>
				<tr>
					<td>' . __( 'Display Post', 'lps' ) . '</td>
					<td>
						<select name="lps_display" id="lps_display" onchange="lps_preview_configures_shortcode()">
							<option value="title">Title</option>
							<option value="title,excerpt">Title + Excerpt</option>
							<option value="title,content">Title + Content</option>
						</select>
					</td>
					<td>' . __( 'Use Image', 'lps' ) . '</td>
					<td>
						<select name="lps_image" id="lps_image" onchange="lps_preview_configures_shortcode()">
							<option value="">No Image</option>
							<option value="thumbnail">Thumbnail</option>
							<option value="full">Full</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>' . __( 'CSS Class Selector', 'lps' ) . '</td>
					<td><input type="text" name="lps_css" id="lps_css" onchange="lps_preview_configures_shortcode()" placeholder="Ex: two-columns, three-columns" />
					</td>
					<td>' . __( 'Use Post URL', 'lps' ) . '</td>
					<td>
						<select name="lps_url" id="lps_url" onchange="lps_preview_configures_shortcode()">
							<option value="">No link to the post</option>
							<option value="yes">Link to the post</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="4"><hr /><h3>' . __( 'Select The Latest From Taxonomy', 'lps' ) . '</h3></td>
				</tr>
				<tr>
					<td>' . __( 'Taxonomy', 'lps' ) . '</td>
					<td>
						<select name="lps_taxonomy" id="lps_taxonomy" onchange="lps_preview_configures_shortcode()">
							<option value="">' . __( 'Any', 'lps' ) . '</option>
							';
		$exclude_tax = array( 'post_tag', 'nav_menu', 'link_category', 'post_format' );
		$tax = get_taxonomies( array(), 'objects' );
		if ( ! empty( $tax ) ) {
			foreach ( $tax as $k => $v ) {
				if ( ! in_array( $k, $exclude_tax ) ) {
					$body .= '<option value="' . esc_attr( $k ) . '">' . esc_html( $v->labels->name ) . '</option>';
				}
			}
		}
		$body .= '
						</select>
					<td>' . __( 'Term', 'lps' ) . '</td>
					<td><input type="text" name="lps_term" id="lps_term"  placeholder="Term slug (ex: news)" onchange="lps_preview_configures_shortcode()" /></td>
				</tr>
				<tr>
					<td colspan="4"><hr /><h3>' . __( 'OR Select The Latest Posts With The Tag', 'lps' ) . '</h3></td>
				</tr>
				<tr>
					<td>' . __( 'Tag', 'lps' ) . '</td>
					<td><input type="text" name="lps_tag" id="lps_tag" onchange="lps_preview_configures_shortcode()" /></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="4"><hr /><h3>' . __( 'OR Select', 'lps' ) . '</h3></td>
				</tr>
				<tr>
					<td colspan="2"><h3>' . __( 'The Posts By IDs', 'lps' ) . '</h3></td>
					<td colspan="2"><h3>' . __( 'The Latest Posts By Parent IDs', 'lps' ) . '</h3></td>
				</tr>
				<tr>
					<td>' . __( 'Post ID', 'lps' ) . '</td>
					<td><input type="text" name="lps_post_id" id="lps_post_id" onchange="lps_preview_configures_shortcode()" placeholder="Separate IDs with comma" /></td>
					<td>' . __( 'Parent ID', 'lps' ) . '</td>
					<td colspan="3"><input type="text" name="lps_parent_id" id="lps_parent_id" onchange="lps_preview_configures_shortcode()" placeholder="Separate IDs with comma" /></td>
				</tr>
				<tr>
					<td colspan="4">
						<hr />
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<h3>' . __( 'Shortcode Preview', 'lps' ) . '</h3>
					</td>
					<td>
						<a class="button button-primary" id="lps_button_embed_shortcode">' . __( 'Embed The Shortcode', 'lps' ) . '</a>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<hr /><div id="lps_preview_embed_shortcode">[latest-selected-content type="post" limit="1" tag="news"]</div>
					</td>
				</tr>
			</table>
		</div>
		';
		echo $body;
	}

	/**
	 * Latest_Post_Shortcode::latest_selected_content() Return the content generated by a shortcode with the specific arguments
	 */
	function latest_selected_content( $args ) {
		global $post;

		/** Get the post arguments from shortcode arguments */
		$ids = ( ! empty( $args['id'] ) ) ? explode( ',', $args['id'] ) : array();
		$parent = ( ! empty( $args['parent'] ) ) ? intval( $args['parent'] ) : 0;
		$type = ( ! empty( $args['type'] ) ) ? $args['type'] : 'post';

		$extra_display = ( ! empty( $args['display'] ) ) ? explode( ',', $args['display'] ) : array( 'title' );
		$qargs = array(
			'post_status'  => 'publish',
			'order'        => 'DESC',
			'orderby'      => 'date_publish',
			'offset'       => 0,
			'number'       => 1,
			/** Make sure we do not loop in the current page */
			'post__not_in' => array( $post->ID ),
		);
		if ( empty( $args['limit'] ) ) {
			$qargs['number'] = ( ! empty( $args['limit'] ) ) ? intval( $args['limit'] ) : 1;
		}

		$force_type = true;
		if ( ! empty( $ids ) && is_array( $ids ) ) {
			foreach ( $ids as $k => $v ) {
				$ids[$k] = intval( $v );
			}
			$qargs['post__in'] = $ids;
			$force_type = false;
		}
		if ( $force_type ) {
			$qargs['post_type'] = $type;
		} else {
			if ( ! empty( $args['type'] ) ) {
				$qargs['post_type'] = $args['type'];
			}
		}
		if ( ! empty( $parent ) ) {
			$qargs['post_parent'] = $parent;
		}
		$qargs['tax_query'] = array();
		if ( ! empty( $args['tag'] ) ) {
			array_push(
				$qargs['tax_query'],
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'terms'    => ( ! empty( $args['tag'] ) ) ? $args['tag'] : 'homenews',
				)
			);
		}
		if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			if ( ! empty( $qargs['tax_query'] ) ) {
				array_push(
					$qargs['tax_query'],
					array(
						'relation' => 'AND',
					)
				);
			}
			array_push(
				$qargs['tax_query'],
				array(
					'taxonomy' => $args['taxonomy'],
					'field'    => 'slug',
					'terms'    => $args['term'],
				)
			);
		}
		$posts = get_posts( $qargs );

		ob_start();
		if ( ! empty( $posts ) ) {
			$class = ( ! empty( $args['css'] ) ) ? ' ' . $args['css'] : '';
			echo '<section class="latest-post-selection' . esc_attr( $class ) . '">';
			foreach ( $posts as $post ) {
				echo '<article>';
				if ( ! empty( $args['url'] ) ) {
					echo '<a href="' . get_permalink( $post->ID ) . '">';
				}
				if ( ! empty( $args['image'] ) ) {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( intval( $post->ID ) ), $args['image'] );
					if ( ! empty( $image[0] ) ) {
						echo '<img src="' . esc_url( $image[0] ) . '" />';
					}
				}
				if ( in_array( 'title', $extra_display ) ) {
					echo '<h1>' . esc_html( $post->post_title ) . '</h1>';
				}
				if ( in_array( 'excerpt', $extra_display ) ) {
					echo '<p>' . apply_filters( 'the_content', strip_shortcodes( $post->post_excerpt ) ) . '</p>';
				}
				if ( in_array( 'content', $extra_display ) ) {
					echo '<p>' . apply_filters( 'the_content', strip_shortcodes( $post->post_content ) ) . '</p>';
				}
				if ( ! empty( $args['url'] ) ) {
					echo '</a>';
				}
				echo '</article>';
			}
			echo '</section>';
		}
		return ob_get_clean();
	}

}

Latest_Post_Shortcode::get_instance();
