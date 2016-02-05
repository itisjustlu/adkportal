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

/*		This file contains the main settings of adk portal
		
		void AdkAdmin()
			- Set the main subActions of this file
		
		void view()
			- Get version and news of SMF Personal

		void adksettings()
			- Initialize the $adkportal settings

		void adksavesettings
			- save the main $adkportal settings on smf_adk_settingsd

		void manageicons()
			- Initialize the set $_REQUEST
		
		void view_icons()
			- Get all icons of adk portal

		void deleteicon
			- delete a custom icon of adk

		void save_icon()
			- Save a new icon on smf_adk_icons

		void SettingsStandAlone()
			- Initialize the settings of stand alone mode

		void SaveSettingsStandAlone
			- Save stand alone mod settings

*/		

function AdkAdmin()
{
	global $txt, $context, $settings, $adkportal, $boardurl, $adkFolder;
	
	//Is allowed to manage adkportal
	isAllowedTo('adk_portal');
	
	//Load my template
	adktemplate('Adk-Admin');
	
	//Load Adk Language
	adkLanguage('Adk-Admin');
			
	$subActions = array(
		'view' => 'view',
		'adksettings' => 'adksettings',
		'adksavesettings' => 'adksavesettings',
		'manageicons' => 'manageicons',
	);

	//Set subactions for standalone mode
	if($adkportal['adk_enable'] == 2){
		$subActions += array(
			'standalone' => 'SettingsStandAlone',
			'save_stand' => 'SaveSettingsStandAlone',
		);
	}
	
	$context['html_headers'] .= getCss('admin_adkportal');
	$context['html_headers'] .= getJs('admin');
		
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['adkadmin_settings'],
		'description' => $txt['adkadmin_news_desc'],
		'tabs' => array(
			'view' => array(
				'description' => $txt['adkadmin_news_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/news.png" />&nbsp;'.$txt['adkadmin_news'],
			),
			'adksettings' => array(
				'description' => $txt['adkadmin_setting_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/settings.png" />&nbsp;'.$txt['adkadmin_setting'],
			),
			'manageicons' => array(
				'description' => $txt['adkadmin_icons_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/icons.png" />&nbsp;'.$txt['adkadmin_icons'],
			),	
		),
	);

	//The last thing... print the stand alone menu
	if($adkportal['adk_enable'] == 2){
		$context[$context['admin_menu_name']]['tab_data']['tabs']['standalone'] = array(
			'description' => $txt['adkadmin_stand_desc'],
			'label' => '<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/php.png" />&nbsp;'.$txt['adkadmin_stand'],
		);
	}

	// Follow the sa or just go to View function
	if (!empty($_GET['sa']) && !empty($subActions[$_GET['sa']]))
		$subActions[@$_GET['sa']]();
	else
		$subActions['view']();
}

function view()
{
	global $context, $txt;

	//Load main trader template.
	$context['sub_template']  = 'view';

	//Set the page title
	$context['page_title'] = $txt['adkadmin_name'].': '.$txt['adkadmin_news'];
	
	$context['adkportal']['current_version'] = getCurrentversion();
	$context['adkportal']['your_version'] = getYourversion();
	
	if($context['adkportal']['your_version'] == $context['adkportal']['current_version'])
		$context['adkportal']['style_version'] = '<b style="color: green;">'.$context['adkportal']['your_version'].'</b><br /><hr />';
	else
		$context['adkportal']['style_version'] = '<b style="color: red;">'.$context['adkportal']['current_version'].'</b><br /><hr /><div align="center"><a href="http://www.smfpersonal.net/index.php?action=downloads;cat=1" target="_blank"><strong>'.$txt['adkadmin_download_now'].'</strong></a></div>';
}

function adksettings()
{
	global $context, $txt, $adkportal;
	
	checkSession('get');
	
	//Load main trader template.
	$context['sub_template']  = 'adksettings';

	//Set the page title
	$context['page_title'] = $txt['adkadmin_name'].': '.$txt['adkadmin_setting'];
	
}

function adksavesettings()
{
	global $context, $smcFunc;
	checkSession('post');
	
	$adk_enable = !empty($_POST['adk_enable']) ? (int)$_POST['adk_enable'] : 0;
	
	$change_title = CleanAdkStrings($_POST['change_title']);
	$adk_hide_version = !empty($_POST['adk_hide_version']) ? 1 : 0;
	$adk_guest_view_post = !empty($_POST['adk_guest_view_post']) ? 1 : 0;
	$wleft = CleanAdkStrings($_POST['wleft']);
	$wright = CleanAdkStrings($_POST['wright']);
	$title_in_blocks = (int)$_POST['title_in_blocks'];
	$enable_img_blocks = (int)$_POST['enable_img_blocks'];
	$adk_disable_colexpand = !empty($_POST['adk_disable_colexpand']) ? 1 : 0;
	$adk_linktree_portal = !empty($_POST['adk_linktree_portal']) ? 1 : 0;
	$adk_include_ssi = !empty($_POST['adk_include_ssi']) ? 1 : 0;


	updateSettingsAdkPortal(
		array(
			'adk_enable' => $adk_enable,
			'change_title' => $change_title,
			'adk_hide_version' => $adk_hide_version,
			'adk_guest_view_post' => $adk_guest_view_post,
			'wleft' => $wleft,
			'wright' => $wright,
			'title_in_blocks' => $title_in_blocks,
			'enable_img_blocks' => $enable_img_blocks,
			'adk_disable_colexpand' => $adk_disable_colexpand,
			'adk_linktree_portal' => $adk_linktree_portal,
			'adk_include_ssi' => $adk_include_ssi,
		)
	);
	
	
	redirectexit('action=admin;area=adkadmin;sa=adksettings;'.$context['session_var'].'=' . $context['session_id']);
	
}

function manageicons()
{
	$set = array(
		'view_icons' => 'view_icons',
		'saveicon' => 'saveicon',
		'deleteicon' => 'deleteicon',
	);
	
	if (!empty($_GET['set']) && !empty($set[$_GET['set']]))
		$set[@$_GET['set']]();
	else
		$set['view_icons']();
}

function view_icons()
{
	global $context, $txt, $smcFunc, $scripturl;
	
	checkSession('get');
	
	$context['sub_template']  = 'view_icons';
	$context['page_title'] = $txt['adkadmin_name'].': '.$txt['adkadmin_icons'];

	$context['start'] = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
	$limit = 10;
	
	$total = getTotal('adk_icons');

	$context['load_icons'] = getIcons('', array(), 'id_icon ASC', $context['start'], $limit);

	$context['page_index'] = constructPageIndex($scripturl . '?action=admin;area=adkadmin;sa=manageicons;'.$context['session_var'].'='.$context['session_id'], $context['start'], $total, $limit);
}

function deleteicon()
{
	global $smcFunc, $context, $boarddir, $adkFolder;
	
	checkSession('get');
	
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_wrong_icon_id',false);
	
	$sql = $smcFunc['db_query']('','
		SELECT icon 
		FROM {db_prefix}adk_icons
		WHERE id_icon = {int:icon}',
		array(
			'icon' => $id,
		)
	);
	
	$row = $smcFunc['db_fetch_assoc']($sql);
	$smcFunc['db_free_result']($sql);
	
	if(file_exists($adkFolder['main'].'/images/blocks/'.$row['icon']))
		@unlink($adkFolder['main'].'/images/blocks/'.$row['icon']);
	
	//Now DELETE DB
	deleteEntry('adk_icons', 'id_icon = {int:icon}', array('icon' => $id));
	
	redirectexit('action=admin;area=adkadmin;sa=manageicons;'.$context['session_var'].'='.$context['session_id']);
}

function saveicon()
{
	global $context, $boarddir, $smcFunc, $adkFolder;
	
	checkSession('post');
	
	if(empty($_FILES['file']['name']))
		fatal_lang_error('adkfatal_not_select_image_icon',false);
	
	$maxfilesize = 1*1024*512;
	
	if($_FILES['file']['size'] > $maxfilesize)
		fatal_lang_error('adkfatal_not_select_image_icon',false);
	
	$filename = str_replace(' ','',$_FILES['file']['name']);
	
	$filename = time().$filename;
	
	if($_FILES['file']['type'] == "image/gif" || $_FILES['file']['type'] == "image/png")
	{
		@chmod($adkFolder['main'].'/images/blocks',0755);
		move_uploaded_file($_FILES['file']['tmp_name'], $adkFolder['main'].'/images/blocks/' .   $filename);
		
		@chmod($adkFolder['main'].'/images/blocks/'.$filename,0644);
		
		$smcFunc['db_insert']('insert', '{db_prefix}adk_icons', array('icon' => 'text'), array($filename), array('id_icon'));
	}
	else
		fatal_lang_error('adkfatal_not_select_image_icon',false);
	
	redirectexit('action=admin;area=adkadmin;sa=manageicons;'.$context['session_var'].'='.$context['session_id']);
	
}

function SettingsStandAlone(){

	global $context, $txt;

	$context['page_title'] = $txt['adkadmin_name'].': '.$txt['adkadmin_stand'];
	$context['sub_template'] = 'stand_alone_admin';
}

function SaveSettingsStandAlone(){

	checkSession('post');

	$adk_stand_alone_url = !empty($_POST['adk_stand_alone_url']) ? CleanAdkStrings($_POST['adk_stand_alone_url']) : '';

	updateSettingsAdkPortal(array('adk_stand_alone_url' => $adk_stand_alone_url));

	global $context;
	redirectexit('action=admin;area=adkadmin;sa=standalone;'.$context['session_var'].'=' . $context['session_id']);
}


?>