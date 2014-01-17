<?php
/*
  Plugin Name: Custom Editor Styles
  Plugin URI: http://wordpress.org/extend/plugins/custom-editor-styles/
  Description: Allows the user to easily apply classes to text within the visual editor
  Version: 1.1
  Author: Konstantinos Kouratoras
  Author URI: http://www.kouratoras.gr
  Author Email: kouratoras@gmail.com
  License: GPL v2

  Copyright 2012 Konstantinos Kouratoras (kouratoras@gmail.com)

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

class CustomEditorStyles {

	public function __construct() {

		//Plugin scripts
		if (is_admin()) {
			add_action('admin_init', array(&$this, 'editor_styles_init'));
		}

		//Plugin menu
		add_action('admin_menu', array(&$this, 'setup_theme_admin_menus'));

		//Add custom styles into editor
		add_action('tiny_mce_before_init', array(&$this, 'editor_styles'));
	}

	function editor_styles_init() {

		//jQuery Sortable
		wp_enqueue_script('jquery-ui-sortable');

		//Plugin Script
		wp_register_script('editor-styles-script', plugins_url('js/script.js', __FILE__), NULL, NULL, TRUE);
		wp_enqueue_script('editor-styles-script');
	}

	function setup_theme_admin_menus() {
		add_menu_page('Editor Styles', 'Editor Styles', 'manage_options', 'editor_styles', array(&$this, 'editor_styles_settings'), plugins_url('custom-editor-styles/images/custom-editor-styles.png'));
		add_submenu_page('editor_styles_settings', 'Styles', 'Styles', 'manage_options', 'editor_styles', array(&$this, 'editor_styles_settings'));
	}

	function editor_styles_settings() {

		if (isset($_POST["update_settings"])) {
			$elements = array();
			$max_id = esc_attr($_POST["element-max-id"]);
			for ($i = 0; $i < $max_id; $i++) {

				$style_name = "style-id-" . $i;
				$class_name = "class-id-" . $i;

				if (isset($_POST[$style_name])) {
					$elements[esc_attr($_POST[$style_name])] = esc_attr($_POST[$class_name]);
				}
			}
			update_option("custom_editor_styles", $elements);
			?>  
			<div id="setting-error-settings_updated" class="updated settings-error"> 
				<p><strong>Styles saved.</strong></p>
			</div>
			<?php
		}

		$styles = get_option("custom_editor_styles");
		$styles_counter = sizeof($styles);
		?>  
		<div class="wrap">  
			<?php screen_icon('themes'); ?> <h2>Editor Styles</h2>  
			<form method="POST" action="">  
				<ul id="styles-list">  

					<?php
					$styles = get_option("custom_editor_styles");
					$style_counter = 0;
					
					if($styles):
						foreach ($styles as $style => $class) :
							?>
							<li class="styles-element" id="styles-element-<?php echo $style_counter; ?>">  

								<label for="style-id-<?php echo $style_counter; ?>">Style:</label>
								<input name="style-id-<?php echo $style_counter; ?>" type="text" value="<?php echo $style; ?>" />
								<label for="class-id-<?php echo $style_counter; ?>">Class:</label>
								<input name="class-id-<?php echo $style_counter; ?>" type="text" value="<?php echo $class; ?>" />
								<a href="#" onclick="removeElement(jQuery(this).closest('.styles-element'));">Remove</a>
							</li>  
							<?php
							$style_counter++;
						endforeach;
					endif;
					?>

				</ul>  
				<a href="#" id="add-style">Add style</a>  

				<li class="styles-element" id="styles-element-placeholder" style="display:none;">
					<label for="style-id">Style:</label>
					<input name="style-id" type="text" />
					<label for="class-id">Class:</label>  
					<input name="class-id" type="text" />
					<a href="#">Remove</a>
				</li>  

				<input type="hidden" name="element-max-id" value="<?php echo $style_counter; ?>" />
				<input type="hidden" name="update_settings" value="Y" />  

				<p>  
					<input type="submit" value="Save settings" class="button-primary"/>  
				</p> 
			</form>  
		</div>    
		<?php
	}
	
	function editor_styles($styles_options) {

		$styles = get_option("custom_editor_styles");
		
		$styles_str = "";
		foreach($styles as $style => $class){
			$styles_str .= $style."=".$class.";";
		}
		$styles_str = substr($styles_str,0,-1);

		$styles_options['theme_advanced_styles'] = $styles_str;
		$styles_options['theme_advanced_buttons2_add_before'] = "styleselect";
		return $styles_options;
	}

}

new CustomEditorStyles();
?>