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

//Hooks Integration
$hooks = array(
	'integrate_actions' => 'Adk_portal_add_index_actions',
	'integrate_admin_areas' => 'Adk_portal_add_admin_areas',
	'integrate_menu_buttons' => 'Adk_portal_add_menu_buttons',
	'integrate_display_buttons' => 'Adk_portal_display_buttons',
	'integrate_load_permissions' => 'Adk_portal_Permissions',
	'integrate_whos_online' =>	'Adk_portal_who',
	'integrate_buffer' => 'Adk_portal_change_buffer',
	'integrate_load_theme' => 'Adk_portal_load_from_theme',
	'integrate_pre_include' => '$sourcedir/AdkPortal/Subs-adkfunction.php',
	'integrate_pre_load' => 'Adk_portal_pre_load',
	'integrate_redirect' => 'Adk_portal_redirect',
);

foreach($hooks AS $hook => $call)
	add_integration_function($hook,$call);

if($direct_install)
	exit('Hooks added');
/*elseif(!$direct_install && empty($context['uninstalling'])){

	//Load Adk portal post-installation
	global $txt, $context, $sourcedir;
	require_once($sourcedir.'/AdkPortal/Subs-adkfunction.php');

	adkTemplate('Adk-Admin');
	adkLanguage('Adk-Admin');

	$context['template_layers'][] = 'adk_post_install';
	$context['page_title'] = $txt['adk_post_install'];
	$context['sub_template'] = 'adk_post_install';
	$context['hooks_added'] = $hooks;
}*/

?>