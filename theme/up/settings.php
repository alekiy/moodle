<?php

/**
 * Settings for the up theme
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

	// Foot note setting
	$name = 'theme_up/footnote';
	$title = get_string('footnote','theme_up');
	$description = get_string('footnotedesc', 'theme_up');
	$setting = new admin_setting_confightmleditor($name, $title, $description, '');
	$settings->add($setting);
	
	
	// Block region width
	$name = 'theme_up/regionwidth';
	$title = get_string('regionwidth','theme_up');
	$description = get_string('regionwidthdesc', 'theme_up');
	$default = 200;
	$choices = array(180=>'180px', 200=>'200px', 240=>'240px', 290=>'290px', 350=>'350px', 420=>'420px');
	$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
	$settings->add($setting);
}
?>