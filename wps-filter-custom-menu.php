<?php
/*
Plugin Name: WP Custom Menu Filter Plugin
Plugin URI: http://wpsmith.net/wordpress-plugins/wp-custom-menu-filter-plugin
Description: This filters the WP Custom Menu based on whether user is logged in or not.
Version: 0.1
Author: wpsmith
Author URI: http://wpsmith.net
*/

/*  Copyright 2011  Travis Smith  (email : travis@wpsmith.net)

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
$opt_val=array();
register_activation_hook(__FILE__, 'wps_install');

function wps_install() {
	global $wp_version;
	
	if (version_compare($wp_version, "3.0.5", "<")) {
		deactivate_plugins(basename(__FILE__)); //deactivate plugin
		wp_die("This plugin requires WordPress version 3.0 or higher");
	}
}

//execute our settings section function
add_action('admin_menu', 'wps_add_menu');

function wps_add_menu() {
    //create the new setting section on the Appearance menu
    add_theme_page('Custom Menu Filter Settings', 'Custom Menu Filter Settings', 'administrator', 'wps-filter-menu', 'wps_settings_page'); 
}

// settings section
function wps_settings_page() {
	global $wp_version;
	//must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      die( __('You do not have sufficient permissions to access this page.') );
    }
	
	$hide_msg=get_option('wps_hidemsg');
	if (!$hide_msg) {
		$hide_msg=$_POST['hide-msg'];
		update_option('wps_hidemsg', $hide_msg);
	}
	if (version_compare($wp_version, "3.0.5", "=")) {
		if (!$hide_msg) {
		?> <div class="error fade">
		<div id="wrap"><div id="msg" style="width:100%"><h3>Attention Required</h3>
		<p>This plugin can only be used in WordPress 3.0.5 <em><strong>if</strong></em> one change is made to a core WordPress file. However, I never advise making any changes or hacking to WordPress core on production environments. So if you make this change in production, do so at your own risk. That being said, in wp-includes folder, open the file nav-menu-template.php and add this line at line 199. This line of code is already in WordPress 3.1 (RC4) at line 201 hence why I don't mind sharing this. However, update to WordPress 3.1 as soon as possible.

<pre>	$sorted_menu_items = apply_filters( 'wp_nav_menu_objects', $sorted_menu_items, $args );</pre>
<br />
This line of code needs to appear after:

<pre>	// Set up the $menu_item variables
	_wp_menu_item_classes_by_context( $menu_items );

	$sorted_menu_items = array();
	foreach ( (array) $menu_items as $key => $menu_item )
		$sorted_menu_items[$menu_item->menu_order] = $menu_item;

	unset($menu_items);</pre>

<br />
And before:

<pre>	$items .= walk_nav_menu_tree( $sorted_menu_items, $args->depth, $args );
	unset($sorted_menu_items);</pre><br />
	</div>
	<div id="hide-msg-form" style="height: 60px;">
	<form enctype="multipart/form-data" method="post" action="<?php echo $PHP_SELF;?>" id="hide-msg" style="float: right;">
	Hide? <input type="checkbox" name="hide-msg" value="1" <?php checked(1, $hide_msg); ?> >
	<input type="submit" value="Submit" name="submit" class="button-primary hide-msg">
	</form>
	<p align="right" style="padding-top: 30px;"><em>Hiding this message will permenantly hide the message.</em></p>
	</div></div></div>
	<?php
		}	
	}
	
	$nav_menus = wp_get_nav_menus( array('orderby' => 'name') );
	
	$defaults = array (
					'hidden'=> 'wps_submit_hidden'
					
				);
	
	// variables for the field and option names 
    $opt_name = 'wps_css_class';
	
    $instance = array(
						'hidden'=> 'wps_submit_hidden'
					);
	$post_fields = array();
	$post_fields[] = 'hidden';
	for ($i=0; $i < count($nav_menus); $i++) {
		$post_fields[] = 'menu-'.$nav_menus[$i]->term_taxonomy_id;
	}
	$opt_val = get_option( $opt_name);
	
	if( isset($_POST[ $instance['hidden'] ]) && $_POST[ $instance['hidden'] ] == 'Y' ) {
        // Read their posted value
		foreach ( $post_fields as $field ) {
			$opt_val[$field] = isset( $_POST[$field] ) ? $_POST[$field] : '';
		}
		$opt_val['hidden'] = 'wps_submit_hidden';
		

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );
		print_r($opt_val);
		
		// Put an settings updated message on the screen

		?>
		<div class="updated"><p><strong><?php _e('Settings saved.', 'wps-custom-menu' ); ?></strong></p></div>
		<?php
    }
	$locations = get_registered_nav_menus();
	$menus = wp_get_nav_menus();
	$menu_locations = get_nav_menu_locations();
	$num_locations = count( array_keys($locations) );

    // Now display the settings editing screen
    echo '<div class="wrap">';
	
    // header
    echo "<h2>" . __( 'WP Custom Menu Filter Plugin Settings', 'wps-custom-menu' ) . "</h2>";

    // settings form
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $instance['hidden']; ?>" value="Y">
<?php 
	foreach ($menu_locations as $menu_location => $menu_id) {
		if ($menu_id) {
	
?>
<h3>Menu Title: <?php 
			foreach ($menus as $menu) {
				if ($menu->term_id == $menu_id) {
					echo $menu->name; //$nav_menus[$i]->name; 
				}
			} ?></h3>
ID: <?php echo $menu_id; ?><br />
Location: <?php 
			foreach ($locations as $location => $location_name) {
				if ($menu_location == $location) {
					echo $location_name; //$nav_menus[$i]->term_taxonomy_id; 
				}
			}

?><br />
<p><?php _e("CSS Class Name:", 'wps-custom-menu' ); ?> 
<input type="text" name="<?php echo 'menu-'.$menu_id; ?>" value="<?php echo $opt_val['menu-'.$menu_id]; ?>" size="20"><br />
<?php _e("<em>Enter the CSS class name for items that you want to be hidden from users not logged in.</em>", 'wps-custom-menu' ); ?> 
</p>
<?php }
		?> <hr /> <?php
	}
	if (count($nav_menus)==0) { ?>
		<p><em>You first need to create a <?php echo '<a href="'.get_option('siteurl').'/wp-admin/nav-menus.php">' ?> custom WordPress menu</a></p></em>
<?php }

?>

<hr />
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>
<?php

}

function wps_custom_nav_menu_items($args){

$option_value = get_option('wps_css_class');
$nav_item_db_id = $args[1]->ID;
$nav_menu=wp_get_object_terms($nav_item_db_id, 'nav_menu');
$menu_id=$nav_menu[0]->term_id;
if ( !is_user_logged_in() ) {
	$exclusionClass = $option_value['menu-'.$menu_id];
}

$nav_items = wp_get_nav_menu_items($menu_id);

foreach ($nav_items as $nav_item)
{	
	for ($i=0; $i<count($nav_item->classes); $i++)
	{
		if (strlen ($nav_item->classes[$i]) < 1) {
			$modified_nav_items[]=$nav_item;
		}
		else
		{
			if ($nav_item->classes[$i] == $exclusionClass) {
				$excluded_nav_items[]=$nav_item; 
			}
			else {
				$modified_nav_items[]=$nav_item;
			}
		}
	}
}

return $modified_nav_items;
}

add_filter( 'wp_nav_menu_objects', 'wps_custom_nav_menu_items',10,3);

?>