<?php
/**
 * Adk Portal
 * Version: 3.1
 * Official support: http://www.smfpersonal.net
 * Author: Adk Team
 * Copyright: 2009 - 2016 © SMFPersonal
 * Developers:
 * 		Juarez, Lucas Javier
 * 		Clavijo, Pablo
 *
 */

$direct_install = false;

if(file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')){
	require_once(dirname(__FILE__) . '/SSI.php');
	$direct_install = true;
}
elseif (!defined('SMF'))
	die('Adk portal wasn\'t able to conect to smf');

db_extend('packages');

foreach($hooks AS $hook => $call)
	remove_integration_function($hook,$call);

$drop_tables = array(
	'adk_blocks','adk_settings','adk_news','adk_pages',
	'adk_icons','adk_down_file','adk_down_attachs',
	'adk_down_cat','adk_advanced_images',
	'adk_shoutbox','adk_blocks_template',
	'adk_blocks_template_admin', 'adk_pages_notifications'
);

foreach($drop_tables AS $table)
	$smcFunc['db_drop_table']('{db_prefix}'.$table, array(), 'ignore');

$drops = array(
	'topics' => 'id_new',
	'members' => 'adk_notes',
	'members' => 'adk_pages_notifications'
);

foreach($drops AS $table => $column)
	$smcFunc['db_remove_column']('{db_prefix}'.$table, $column, array(), 'ignore');
	
if($direct_install)
	echo'Done... Adk portal was uninstalled correctly. Enjoy it!';

?>