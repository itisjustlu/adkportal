<?php
/**
 * Adk Portal
 * Version: 3.0
 * Official support: http://www.smfpersonal.net
 * Author: Adk Team
 * Copyright: 2009 - 2014 © SMFPersonal
 * Developers:
 * 		Juarez, Lucas Javier
 * 		Clavijo, Pablo
 *
 * version smf 2.0*
 */


$direct_install = false;

if(file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')){
	require_once(dirname(__FILE__) . '/SSI.php');
	$direct_install = true;
}
elseif (!defined('SMF'))
	die('Adk portal wasn\'t able to conect to smf');

db_extend('packages');

$drop_tables = array(
	'adk_blocks','adk_settings','adk_news','adk_pages',
	'adk_icons','adk_down_file','adk_down_attachs',
	'adk_down_cat','adk_down_permissions','adk_advanced_images',
	'adk_shoutbox',
);

foreach($drop_tables AS $table)
	$smcFunc['db_drop_table']('{db_prefix}'.$table, array(), 'ignore');

$drops = array(
	'topics' => 'id_new',
);

foreach($drops AS $table => $column)
	$smcFunc['db_remove_column']('{db_prefix}'.$table, $column, array(), 'ignore');
	
//Hooks Integration
$hooks = array(
	'integrate_actions' => 'Adk_portal_add_index_actions',
	'integrate_admin_areas' => 'Adk_portal_add_admin_areas',
	'integrate_menu_buttons' => 'Adk_portal_add_menu_buttons',
	'integrate_display_buttons' => 'Adk_portal_display_buttons',
);

foreach($hooks AS $hook => $call)
	remove_integration_function($hook,$call);
	
if($direct_install)
	echo'Done... Adk portal was upgraded correctly. Enjoy it!';

?>