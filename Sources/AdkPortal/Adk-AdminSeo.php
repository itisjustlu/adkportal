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


if (!defined('SMF'))
	die('Hacking attempt...');


function AdkSeoMain()
{
	global $context, $txt, $scripturl, $settings, $boardurl;
	
	//Set css
	$context['html_headers'] .= getCss('admin_adkportal');
	
	//Load Basic Info
	isAllowedTo('adk_portal');
	adktemplate('Adk-AdminSeo');
	
	//Load Adk Language
	adkLanguage('Adk-Admin');
		
	//What are my subactions?
	$subActions = array(
		'htaccess' => 'AdkCreateHtaccess',
		'savehtaccess' => 'AdkSaveHtaccess',
		'deletehtaccess' => 'AdkDeleteHtaccess',
		'settings' => 'AdkSeoSettings',
		'savesettings' => 'AdkSaveSettings',
		'robotstxt' => 'AdkCreateRobotstxt',
		'saverobots' => 'AdkSaveRobotstxt',
	);	
		
	//Set icons
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['adkmod_seo_manage'],
		'description' => $txt['adkportal_seo_manage_desc'],
		'tabs' => array(
			'htaccess' => array(
				'description' => '',
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/htaccess.png" />'.$txt['adkmod_seo_htaccess'],
			),
			'settings' => array(
				'description' => '',
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/settings.png" />'.$txt['adkadmin_setting'],
			),
			'robotstxt' => array(
				'description' => '',					
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/robot.png" />'.$txt['adkmod_seo_robots'],
			),
		),
	);	
	
	
	// Follow the sa or just go to View function
	if (!empty($_GET['sa']) && !empty($subActions[$_GET['sa']]))
		$subActions[@$_GET['sa']]();
	else
		$subActions['htaccess']();	
}
		
function AdkCreateHtaccess()
{
	global $context, $txt, $boarddir;
	
	checkSession('get');
	
	$context['sub_template'] = 'htaccess';
	$context['page_title'] = $txt['adkmod_seo_htaccess'];
	
	//Get My Custom File
	$context['htaccess_content'] = getSimpleFile($boarddir.'/.htaccess');
}
		
function AdkSaveHtaccess()
{
	global $context, $adkportal, $boarddir;
	
	checkSession('post');
	
	//Set the htacchess dir
	$dir = $boarddir.'/.htaccess';
	
	//Update settings if it's necessary
	if(!empty($_POST['path']))
	{
		$adkportal['path_seo'] = htmlspecialchars(stripslashes($_POST['path']),ENT_QUOTES);
		
		updateSettingsAdkPortal(
			array(
				'path_seo' => $adkportal['path_seo'],
			)
		);
	}
	
	//If I trying to create a new htaccess.... create it
	if(!empty($_POST['htaccess']))
		$htaccess = stripslashes($_POST['htaccess']);
	else
		$htaccess = '
RewriteEngine on
RewriteBase /'.$adkportal['path_seo'].'
RewriteRule ^pages/(.*)\.html index.php?page=$1 [L]
RewriteRule ^cat/([0-9]*)-(.*)\.html$ index.php?action=downloads;cat=$1 [L]
RewriteRule ^down/([0-9]*)-(.*)\.html$ index.php?action=downloads;sa=view;down=$1 [L]';
		
	//Setter
	file_put_contents($dir,$htaccess);
	
	redirectexit('action=admin;area=adkseoadmin;sa=htaccess;'.$context['session_var'].'='.$context['session_id']);

}

function AdkDeleteHtaccess(){

	checkSession('get');

	global $boarddir, $context;

	//Clean htaccess
	file_put_contents($boarddir.'/.htaccess', '');

	redirectexit('action=admin;area=adkseoadmin;sa=htaccess;'.$context['session_var'].'='.$context['session_id']);

}

function AdkSeoSettings()
{
	global $txt, $context, $boarddir;
	
	checkSession('get');
	
	//Do i have a htaccess?..... if not, return me to the main section please
	if(!file_exists($boarddir.'/.htaccess'))
		redirectexit('action=admin;area=adkseoadmin;sa=htaccess;'.$context['session_var'].'='.$context['session_id']);
	
	$context['sub_template'] = 'settings_seo';
	$context['page_title'] = $txt['adkadmin_setting'];
}

function AdkSaveSettings()
{
	global $context, $boarddir;
	
	checkSession('post');
	
	//Do i have a htaccess?..... if not, return me to the main section please
	if(!file_exists($boarddir.'/.htaccess'))
		redirectexit('action=admin;area=adkseoadmin;sa=htaccess;'.$context['session_var'].'='.$context['session_id']);
	
	//Update settings please :)
	updateSettingsAdkPortal(
		array(
			'enable_pages_seo' => (int)$_POST['enable_pages_seo'],
			'enable_download_seo' => (int)$_POST['enable_download_seo'],
		)
	);
	
	redirectexit('action=admin;area=adkseoadmin;sa=settings;'.$context['session_var'].'='.$context['session_id']);
}

function AdkCreateRobotstxt()
{
	global $boarddir, $context, $txt, $adkportal;
	
	checkSession('get');
		
	$context['sub_template'] = 'robots_seo';
	$context['page_title'] = $txt['adkmod_seo_robots'];
	

	$context['robots_dir'] = getSimpleFile($boarddir.'/robots.txt');

}
		
		
function AdkSaveRobotstxt()
{
	global $boarddir, $context;
	
	checkSession('post');
	
	if(!empty($_POST['robots']))
	{	
		$dir = $boarddir.'/robots.txt';
		$r = stripslashes($_POST['robots']);
		
		file_put_contents($dir,$r);
	}
	
	
	redirectexit('action=admin;area=adkseoadmin;sa=robotstxt;'.$context['session_var'].'='.$context['session_id']);
}	
		
		
		
?>