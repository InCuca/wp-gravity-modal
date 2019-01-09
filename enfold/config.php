<?php
/* Add shortcodes to Enfold */
function ic_gravity_modal_shortcodes($paths)
{
	$plugin_dir = plugin_dir_path(__FILE__);
	array_push($paths, $plugin_dir.'/shortcodes/');
	return $paths;
}
add_filter('avia_load_shortcodes', 'ic_gravity_modal_shortcodes', 12, 1);