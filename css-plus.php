<?php
/*
Plugin Name: CSS Plus
Plugin URI: http://pauloklixto.com/2012/07/16/css-plus-wordpress-plugin-2/
Description: Add CSS box in your specific pages, posts or custom posts.
Version: 1.5.2
Author: Paulo E. Calixto
Author URI: http://pauloklixto.com
License: GPL2
Text Domain: css-plus
Domain Path: /langs
*/

// Copyright 2012  Paulo E. Calixto  (email : klixto@outlook.com)
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License, version 2, as
// published by the Free Software Foundation.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software

class CssPlus {

	const Version = '1.5.2';

	const CodeMirrorVersion = '5.26.0';

	const metaKey = '_css_code';

	/**
	 * CssPlus constructor.
	 *
	 * This function registers the hooks and filters used within the plugin.
	 */
	public function __construct() {
		add_action('init', [$this, 'init']);
		add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
		add_action('save_post', [$this, 'save_post'], 10, 2);
		add_action('add_meta_boxes', [$this, 'add_meta_box']);
		add_action('wp_head', [$this, 'output']);
	}

	public function init() {
		load_plugin_textdomain('css-plus', false, basename(__DIR__) . '/langs');
	}

	public function admin_enqueue_scripts() {
		wp_enqueue_script('css-plus-js', plugins_url('js/codemirror.js', __FILE__), [], CssPlus::CodeMirrorVersion);
		wp_enqueue_script('css-plus-css', plugins_url('js/css.js', __FILE__));
		wp_enqueue_script('css-plus-dialog', plugins_url('js/dialog.js', __FILE__));
		wp_enqueue_script('css-plus-fullscreen', plugins_url('js/fullscreen.js', __FILE__));
		wp_enqueue_script('css-plus-placeholder', plugins_url('js/placeholder.js', __FILE__));
		wp_enqueue_script('css-plus-dft', plugins_url('js/search.js', __FILE__));
		wp_enqueue_script('css-plus-searchcursor', plugins_url('js/searchcursor.js', __FILE__));
		wp_enqueue_script('css-plus-sublime', plugins_url('js/sublime.js', __FILE__));
		wp_enqueue_script('css-plus-trailingspace', plugins_url('js/trailingspace.js', __FILE__));

		wp_enqueue_style('css-plus-codemirror', plugins_url('css/codemirror.css', __FILE__));
		wp_enqueue_style('css-plus-dialog-style', plugins_url('css/dialog.css', __FILE__));
		wp_enqueue_style('css-plus-fullscreen-style', plugins_url('css/fullscreen.css', __FILE__));
		wp_enqueue_style('css-plus-solarized', plugins_url('css/solarized.css', __FILE__));
		wp_enqueue_style('css-plus-style', plugins_url('util/style.css', __FILE__), [], CssPlus::Version);
	}

	/**
	 * Save the CSS code for the post being saved.
	 *
	 * @param $post_id int
	 * @param $post WP_Post
	 */
	public function save_post($post_id, $post) {
		// If the function is called by the WP auto-save feature, nothing must be saved.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// Verify that this request came properly from the WP Admin dashboard.
		if (!isset($_POST['css_code']) || !wp_verify_nonce($_POST['css_code_nonce'], plugin_basename(__FILE__))) {
			return;
		}

		// Check for authorization.
		if ($post->post_type === 'page') {
			if (!current_user_can('edit_page', $post_id)) {
				return;
			}
		} else {
			if (!current_user_can('edit_post', $post_id)) {
				return;
			}
		}

		if (isset($_POST['css_code'])) {
			update_post_meta($post_id, CssPlus::metaKey, strip_tags($_POST['css_code']));
		}
	}

	public function add_meta_box() {
		add_meta_box(
			'css_plus_plugin',
			__('CSS Editor', 'css-plus'),
			[$this, 'meta_box'],
			'',
			'advanced',
			'high'
		);
	}

	/**
	 * Construct the meta box with the code editor.
	 *
	 * @param $post WP_Post object
	 */
	public function meta_box($post) {
		wp_nonce_field(plugin_basename(__FILE__), 'css_code_nonce');
		$postCSS = get_post_meta($post->ID, CssPlus::metaKey, true); ?>
		<textarea id="css-plus-code" name="css_code" placeholder="<?php _e('Insert your CSS code here', 'css-plus'); ?>"><?php echo $postCSS; ?></textarea>
		<script>
			(function() {
				const editor = CodeMirror.fromTextArea(document.getElementById("css-plus-code"), {
					keyMap: "sublime",
					lineNumbers: true,
					fullscreen: true,
					mode: "text/x-scss",
					theme: "solarized dark",
					lineWrapping: true,
					onCursorActivity: function() {
						editor.setLineClass(hlLine, null, null);
						hlLine = editor.setLineClass(editor.getCursor().line, null, "activeline");
					}
				});
				editor.addLineClass(0, "background", "activeline");
			})();
		</script>
		<p class="more"><?php echo __('Press Crtl+f or CMD+f to search.', 'css-plus'); ?></p>
		<?php
	}

	/**
	 * Print the CSS to the <head> if there is anything.
	 */
	public function output() {
		global $post;
		if (!empty($post)) {
			$css = get_post_meta($post->ID, CssPlus::metaKey, true);
			if ($css) {
				echo '<style>' . $css . '</style>';
			}
		}
	}

}

$CssPlus = new CssPlus;

