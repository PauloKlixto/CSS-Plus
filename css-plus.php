<?php
/*
Plugin Name: CSS Plus
Plugin URI: http://pauloklixto.com/2012/07/16/css-plus-wordpress-plugin-2/
Description: Add CSS box in your specific pages, posts or custom posts.
Version: 1.5
Author: Paulo E. Calixto
Author URI: http://pauloklixto.com
License: GPL2
Text Domain: css-plus

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
	
	public function __construct()
    {
        add_action('init', array($this, 'load_plugin_textdomain'));
        $text = __('I will not be translated!', 'css-plus');
    }
     
    public function load_plugin_textdomain()
    {
       load_plugin_textdomain('css-plus', FALSE, dirname(plugin_basename(__FILE__)).'/langs/');        
    }
}
 
$CssPlus = new CssPlus; {

	function auto_update_cssplus_plugin ( $update, $item ) {
	    // Array of plugin slugs to always auto-update
	    $plugins = array ( 
	        'css-plus',
	    );
	    if ( in_array( $item->slug, $plugins ) ) {
	        return true; // Always update plugins in this array
	    } else {
	        return $update; // Else, use the normal API response to decide whether to update or not
	    }
	}
	add_filter( 'auto_update_plugin', 'auto_update_cssplus_plugin', 10, 2 );

	function css_plus_key_get_var()
	{
	    global $pagenow;
	    return $pagenow;
	}
 
	if ( trim(css_plus_key_get_var()) == 'post.php' || trim(css_plus_key_get_var()) == 'post-new.php') {
		
		##
		# CodeMirror Version 5.26.0
		# Design 1.4
		##

		//JavaScript
		function setcss_scripts_method() {
		    wp_deregister_script( 'setCss' );
		    wp_register_script( 'setCss', plugins_url( 'js/codemirror.js' , __FILE__ ));
		    wp_enqueue_script( 'setCss' );
		}    
		add_action('admin_enqueue_scripts', 'setcss_scripts_method');

		function sublime_scripts_method() {
		    wp_deregister_script( 'sublime' );
		    wp_register_script( 'sublime', plugins_url( 'js/sublime.js' , __FILE__ ));
		    wp_enqueue_script( 'sublime' );
		}    
		add_action('admin_enqueue_scripts', 'sublime_scripts_method');

		function dialog_scripts_method() {
		    wp_deregister_script( 'dialog' );
		    wp_register_script( 'dialog', plugins_url( 'js/dialog.js' , __FILE__ ));
		    wp_enqueue_script( 'dialog' );
		}    
		add_action('admin_enqueue_scripts', 'dialog_scripts_method');

		function setcssDft_scripts_method() {
		    wp_deregister_script( 'setcssDft' );
		    wp_register_script( 'setcssDft', plugins_url( 'js/search.js' , __FILE__ ));
		    wp_enqueue_script( 'setcssDft' );
		}    
		add_action('admin_enqueue_scripts', 'setcssDft_scripts_method');

		function searchcursor_scripts_method() {
		    wp_deregister_script( 'searchcursor' );
		    wp_register_script( 'searchcursor', plugins_url( 'js/searchcursor.js' , __FILE__ ));
		    wp_enqueue_script( 'searchcursor' );
		}    
		add_action('admin_enqueue_scripts', 'searchcursor_scripts_method');

		function placeholder_scripts_method() {
		    wp_deregister_script( 'placeholder' );
		    wp_register_script( 'placeholder', plugins_url( 'js/placeholder.js' , __FILE__ ));
		    wp_enqueue_script( 'placeholder' );
		}    
		add_action('admin_enqueue_scripts', 'placeholder_scripts_method');

		function fullscreen_scripts_method() {
		    wp_deregister_script( 'fullscreen' );
		    wp_register_script( 'fullscreen', plugins_url( 'js/fullscreen.js' , __FILE__ ));
		    wp_enqueue_script( 'fullscreen' );
		}    
		add_action('admin_enqueue_scripts', 'fullscreen_scripts_method');

		function trailingspace_scripts_method() {
		    wp_deregister_script( 'trailingspace' );
		    wp_register_script( 'trailingspace', plugins_url( 'js/trailingspace.js' , __FILE__ ));
		    wp_enqueue_script( 'trailingspace' );
		}    
		add_action('admin_enqueue_scripts', 'trailingspace_scripts_method');

		function setcssInput_scripts_method() {
		    wp_deregister_script( 'setCssInput' );
		    wp_register_script( 'setCssInput', plugins_url( 'js/css.js' , __FILE__ ));
		    wp_enqueue_script( 'setCssInput' );
		}    
		add_action('admin_enqueue_scripts', 'setcssInput_scripts_method');

		//CSS
		function setcssSkin_styles_method() {
			wp_register_style('setCssStyleSkin', plugins_url( 'css/codemirror.css' , __FILE__ ));
			wp_enqueue_style( 'setCssStyleSkin' );
		}
		add_action('admin_enqueue_scripts','setcssSkin_styles_method');

		function dialog_styles_method() {
			wp_register_style('dialogStyle', plugins_url( 'css/dialog.css' , __FILE__ ));
			wp_enqueue_style( 'dialogStyle' );
		}
		add_action('admin_enqueue_scripts','dialog_styles_method');

		function fullscreen_styles_method() {
			wp_register_style('fullscreenStyle', plugins_url( 'css/fullscreen.css' , __FILE__ ));
			wp_enqueue_style( 'fullscreenStyle' );
		}
		add_action('admin_enqueue_scripts','fullscreen_styles_method');
		
		function ambiance_styles_method() {
			wp_register_style('ambiance', plugins_url( 'css/solarized.css' , __FILE__ ));
			wp_enqueue_style( 'ambiance' );
		}
		add_action('admin_enqueue_scripts','ambiance_styles_method');

		//Util
		function setcss_styles_method() {
			wp_register_style('setCssStyle', plugins_url( 'util/style.css' , __FILE__ ));
			wp_enqueue_style( 'setCssStyle' );
		}
		add_action('admin_enqueue_scripts','setcss_styles_method');

		function default_js_scripts_method() {
		    wp_deregister_script( 'default_js' );
		    wp_register_script( 'default_js', plugins_url( 'util/default.js' , __FILE__ ));
		    wp_enqueue_script( 'default_js' );
		}    
		add_action('admin_enqueue_scripts', 'default_js_scripts_method');

	}

	add_action( 'add_meta_boxes', 'css_plus_plugin' );
	 
	function css_plus_plugin() {
	    add_meta_box(
	        'css_plus_pluginid',
	        __( 'CSS Editor', 'css-plus' ),
	        'css_plus_plugin_func',
	        '',
	        'advanced',
	        'high'
	    );
	}
	function css_plus_plugin_func( $post_type ) { if ( ! current_user_can( 'administrator' ) ) return;
	?>
		<p>
	  		<textarea id="code" name="css_code" placeholder="<?php echo __('Insert your CSS code here', 'css-plus'); ?>"><?php echo get_post_meta( $post_type->ID, '_css_code', true ); ?></textarea>
	  		<script type="text/javascript">
				  var editor = CodeMirror.fromTextArea(
				  	document.getElementById("code"), {  
				  		keyMap: 'sublime',
				  		lineNumbers: true,
				  		fullscreen: true,
                        mode: "text/x-scss", 
				  		theme: "solarized dark", 
				  		lineWrapping: true,  
				  		onCursorActivity: function() {    
				  			editor.setLineClass(hlLine, null, null);    
				  			hlLine = editor.setLineClass(editor.getCursor().line, null, "activeline");  }
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
			</script>
			<p class="more"><small><?php echo __( 'Press Crtl+F to search anything.', 'css-plus' ); ?></small></p>
		</p>
		<?php }
	
	add_action( 'save_post', 'css_plus_plugin_save_post',10,2);
	 
	function css_plus_plugin_save_post( $post_id, $post_type ) {
	
	   	
		if (isset($_POST['css_code'])) {
			update_post_meta( $post_id, '_css_code', strip_tags( $_POST['css_code'] ) );
		   	return true;
		}
	 
	}

	add_action('wp_head', 'css_plus_engine');
	function css_plus_engine() { 
		if (trim (get_post_meta( $GLOBALS['post']->ID, '_css_code', true )) != "" ){ 
				$css 				= get_post_meta( $GLOBALS['post']->ID, '_css_code', true );
				$exp_css			= str_replace("  ","",$css);
				$exp_css_tab		= str_replace("\t","",$exp_css);
				$exp_css_compressor	= str_replace("\n","",$exp_css_tab);
				?>
	<style type="text/css"><?php echo $exp_css_compressor;  ?></style>
	<?php } }
}
?>