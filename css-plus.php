<?php
/*
Plugin Name: CSS Plus
Plugin URI: http://pauloklixto.com/2012/07/16/css-plus-wordpress-plugin-2/
Description: Add CSS box in your specific pages, posts or custom posts.
Version: 1.5.1
Author: Paulo E. Calixto
Author URI: http://pauloklixto.com
License: GPL2
Text Domain: css-plus
Domain Path: /langs

	Copyright 2012  Paulo E. Calixto  (email : klixto@outlook.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
*/

class CssPlus {

	const Version = '1.5.1';

	const CodeMirrorVersion = '5.26.0';

	const metaKey = '_css_code';

	/**
	 * CssPlus constructor.
	 *
	 * This function registers the hooks and filters used within the plugin.
	 */
	public function __construct() {
		add_action('init', [$this, 'load_plugin_textdomain']);
		add_action('save_post', [$this, 'save_post'], 10, 2);
		add_action('add_meta_boxes', [$this, 'add_meta_box']);
		add_action('wp_head', [$this, 'output']);
	}

	public function load_plugin_textdomain() {
		load_plugin_textdomain('css-plus', false, basename(__DIR__) . '/langs');
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
			'css_plus_pluginid',
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
				var hlLine = editor.addLineClass(0, "background", "activeline");
				editor.on("cursorActivity", function() {
					var cur = editor.getLineHandle(editor.getCursor().line);
					if (cur != hlLine) {
						editor.removeLineClass(hlLine, "background", "activeline");
						hlLine = editor.addLineClass(cur, "background", "activeline");
					}
				});
				var number = jQuery(".CodeMirror-wrap").length;
				if(number > 1){
					jQuery(".CodeMirror-wrap").hide();
					jQuery(".CodeMirror-wrap:first").show();
				}
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

if (trim($pagenow) === 'post.php' || trim($pagenow) === 'post-new.php') {
	// CodeMirror Version 5.26.0
	// Design 1.4

	// JavaScript
	function setcss_scripts_method() {
		wp_deregister_script('setCss');
		wp_register_script('setCss', plugins_url('js/codemirror.js', __FILE__), [], CssPlus::CodeMirrorVersion);
		wp_enqueue_script('setCss');
	}
	add_action('admin_enqueue_scripts', 'setcss_scripts_method');

	function sublime_scripts_method() {
		wp_deregister_script('sublime');
		wp_register_script('sublime', plugins_url('js/sublime.js', __FILE__));
		wp_enqueue_script('sublime');
	}
	add_action('admin_enqueue_scripts', 'sublime_scripts_method');

	function dialog_scripts_method() {
		wp_deregister_script('dialog');
		wp_register_script('dialog', plugins_url('js/dialog.js', __FILE__));
		wp_enqueue_script('dialog');
	}
	add_action('admin_enqueue_scripts', 'dialog_scripts_method');

	function setcssDft_scripts_method() {
		wp_deregister_script('setcssDft');
		wp_register_script('setcssDft', plugins_url('js/search.js', __FILE__));
		wp_enqueue_script('setcssDft');
	}
	add_action('admin_enqueue_scripts', 'setcssDft_scripts_method');

	function searchcursor_scripts_method() {
		wp_deregister_script('searchcursor');
		wp_register_script('searchcursor', plugins_url('js/searchcursor.js', __FILE__));
		wp_enqueue_script('searchcursor');
	}
	add_action('admin_enqueue_scripts', 'searchcursor_scripts_method');

	function placeholder_scripts_method() {
		wp_deregister_script('placeholder');
		wp_register_script('placeholder', plugins_url('js/placeholder.js', __FILE__));
		wp_enqueue_script('placeholder');
	}
	add_action('admin_enqueue_scripts', 'placeholder_scripts_method');

	function fullscreen_scripts_method() {
		wp_deregister_script('fullscreen');
		wp_register_script('fullscreen', plugins_url('js/fullscreen.js', __FILE__));
		wp_enqueue_script('fullscreen');
	}
	add_action('admin_enqueue_scripts', 'fullscreen_scripts_method');

	function trailingspace_scripts_method() {
		wp_deregister_script('trailingspace');
		wp_register_script('trailingspace', plugins_url('js/trailingspace.js', __FILE__));
		wp_enqueue_script('trailingspace');
	}
	add_action('admin_enqueue_scripts', 'trailingspace_scripts_method');

	function setcssInput_scripts_method() {
		wp_deregister_script('setCssInput');
		wp_register_script('setCssInput', plugins_url('js/css.js', __FILE__));
		wp_enqueue_script('setCssInput');
	}
	add_action('admin_enqueue_scripts', 'setcssInput_scripts_method');

	// CSS
	function setcssSkin_styles_method() {
		wp_register_style('setCssStyleSkin', plugins_url('css/codemirror.css', __FILE__));
		wp_enqueue_style('setCssStyleSkin');
	}
	add_action('admin_enqueue_scripts', 'setcssSkin_styles_method');

	function dialog_styles_method() {
		wp_register_style('dialogStyle', plugins_url('css/dialog.css', __FILE__));
		wp_enqueue_style('dialogStyle');
	}
	add_action('admin_enqueue_scripts', 'dialog_styles_method');

	function fullscreen_styles_method() {
		wp_register_style('fullscreenStyle', plugins_url('css/fullscreen.css', __FILE__));
		wp_enqueue_style('fullscreenStyle');
	}
	add_action('admin_enqueue_scripts', 'fullscreen_styles_method');

	function ambiance_styles_method() {
		wp_register_style('ambiance', plugins_url('css/solarized.css', __FILE__));
		wp_enqueue_style('ambiance');
	}
	add_action('admin_enqueue_scripts', 'ambiance_styles_method');

	// Util
	function setcss_styles_method() {
		wp_register_style('setCssStyle', plugins_url('util/style.css', __FILE__), [], CssPlus::Version);
		wp_enqueue_style('setCssStyle');
	}
	add_action('admin_enqueue_scripts', 'setcss_styles_method');

}

