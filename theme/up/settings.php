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
}
?>