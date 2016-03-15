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


if (!defined('SMF'))
	die('Hacking attempt...');

function AdkModules()
{
	global $context, $txt, $settings, $boardurl, $adkFolder;
	
	isAllowedTo('adk_portal');
	
	adktemplate('Adk-AdminModules');
	
	adkLanguage('Adk-AdminModules');
		
	$subActions = array(
		'intro' => 'introAdk',
		'viewadminpages' => 'viewadminpages',
		'createpages' => 'createpages',
		'savecreatedpages' => 'savecreatedpages',
		'editpages' => 'editpages',
		'saveeditpages' => 'saveeditpages',
		'deletepages' => 'deletepages',
		'uploadanyimage' => 'UploadNewImage',
		'saveuploadimg' => 'SaveUploadNewImage',
		'manageimagesadk' => 'ManageImagesAdk',
		'deleteimagesadk' => 'DeleteImagesAdk',
		'contact' => 'ContactAdmin',
		'save_contact' => 'SaveContactAdmin',
		'enable_page_menu' => 'EnablePageMenu',
		'enable_comments' => 'EnableComments',
		'enable_notifications' => 'EnableNotifications',
	);
	
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['adkmodules_modules_settings'],
		'description' => $txt['adkmodules_first_modules'],
		'tabs' => array(
			'intro' => array(
				'description' => $txt['adkmodules_first_modules'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/intro.png" /> '.$txt['adkmod_modules_intro'],
			),
			'viewadminpages' => array(
				'description' => $txt['adkmodules_second_modules'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/pages.png" /> '.$txt['adkmod_modules_pages'],
			),
			'contact' => array(
				'description' => $txt['adkmodules_desc_contacto'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/newmsg.png" /> '.$txt['adkmod_modules_contacto'],
			),
			'uploadanyimage' => array(
				'description' => $txt['adkmodules_tirth_modules'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/imagesadvanced.png" /> '.$txt['adkmod_modules_images'],
			),
			'manageimagesadk' => array(
				'description' => $txt['adkmodules_fourth_modules'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/images.png" /> '.$txt['adkmod_modules_manage_images'],
			),			
		),
	);

	// Hooks menu integrations
	call_integration_hook('integrate_modules_menu', array(&$context[$context['admin_menu_name']]['tab_data']));

	// Hooks sa integration
	call_integration_hook('modules_subactions', array(&$subActions));

	//Hooks pre includes
	adkportal_include_hooks('integrate_pre_include_modules');

	$context['html_headers'] .= getCss('admin_adkportal');
	$context['html_headers'] .= getJs('admin');

	// Follow the sa or just go to View function
	if (!empty($_GET['sa']) && !empty($subActions[$_GET['sa']]))
		$subActions[@$_GET['sa']]();
	else
		$subActions['intro']();
		
}

function introAdk()
{
	global $context, $txt;
	
	$context['sub_template']  = 'introAdk';
	$context['page_title'] = $txt['adkmod_modules_intro'];
	
	global $sourcedir;
	require_once($sourcedir .'/Subs-Package.php');
	
	$context['file'] = getFile('http://www.smfpersonal.net/xml/read_modules.php');

}

function viewadminpages()
{
	global $context, $txt, $smcFunc, $scripturl;
	
	checkSession('get');
	
	//Load main trader template.
	$context['sub_template']  = 'viewadminpages';

	//Set the page title
	$context['page_title'] = $txt['adkmod_modules_pages'];
	
	$total = getTotal('adk_pages');

	$context['total'] = $total;
	$context['start'] = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
	$limit = 10;
	
	//Load adkportalPages
	$context['total_admin_pages'] = getPages($context['start'], $limit, '', 'titlepage ASC');
	
	$context['page_index'] = constructPageIndex($scripturl . '?action=admin;area=modules;sa=viewadminpages;'.$context['session_var'].'='.$context['session_id'], $context['start'], $total, $limit);
		
}

function createpages()
{
	
	global $context, $txt;
	
	checkSession('get');

	//Load main trader template.
	$context['sub_template']  = 'createpages';

	//Set the page title
	$context['page_title'] = $txt['adkmodules_admin_pages_create'];
	
	//Load Groups
	$context['group_view_pages'] = loadAdkGroups('id_group <> {int:moderator} AND id_group <> {int:admin}', array('moderator' => 3, 'admin' => 1), 'id_group DESC');
	
	//Get editor
	getEditor();

	//Set the compatibility info
	$context += array(
		'save_action' => 'savecreatedpages',
		'edit_admin_page' => array(
			'titlepage' => '',
			'urltext' => '',
			'type' => 'bbc',
			'cattitlebg' => 'catbg',
			'winbg' => 'windowbg',
			'grupos_permitidos' => '',
			'id_page' => 0,
			'enable_comments' => true,
		)
	);
}

function savecreatedpages()
{
	global $context, $txt, $smcFunc, $sourcedir;
	
	checkSession('post');
	
	//Set the titlepage and urltext
	$titlepage = CleanAdkStrings($_POST['titlepage']);
	$urltext = CleanAdkStrings($_POST['urltext']);
	
	//Set the groups
	$groups_allowed = createArrayFromPost('groups_allowed');

	//Clean editor
	cleanEditor();
	
	$type = $_POST['type'];
	$body = CleanAdkStrings(stripslashes($_REQUEST['descript']));
	$cattitlebg = $_POST['cattitlebg'];
	$winbg = $_POST['winbg'];
	$views = 0;
	$enable_comments = !empty($_POST['enable_comments']) ? 1 : 0;
	
	//Check if previous page does not exists
	checkIfPageExists($urltext);
	
	//Empty titlepage?
	if(empty($titlepage))
		fatal_lang_error('adkfatal_empty_title',false);

	//Empty body
	if(empty($body))
		fatal_lang_error('adkfatal_empty_body', false);
	
	//Insert into
	$smcFunc['db_insert'](
		'insert',
		'{db_prefix}adk_pages',
		array(
			'urltext' => 'text',
			'titlepage' => 'text',
			'body' => 'text',
			'views' => 'int',
			'grupos_permitidos' => 'text',
			'type' => 'text',
			'winbg' => 'text',
			'cattitlebg' => 'text',
			'enable_comments' => 'int',
		),
		array($urltext, $titlepage, $body, $views, $groups_allowed, $type, $winbg, $cattitlebg, $enable_comments),
		array('id_page')
	);
	
	redirectexit('action=admin;area=modules;sa=viewadminpages;'.$context['session_var'].'=' . $context['session_id']);
}

function deletepages()
{
	global $smcFunc, $context;
	
	checkSession('get');
	
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id = (int) $_REQUEST['id'];
	else
		$id = 0;

	//Delete entry
	deleteEntry('adk_pages', 'id_page = {int:page}', array('page' => $id));
	
	redirectexit('action=admin;area=modules;sa=viewadminpages;'.$context['session_var'].'=' . $context['session_id']);
}

function editpages()
{

	global $context, $txt, $smcFunc, $sourcedir;
	checkSession('get');

	//Load main trader template.
	$context['sub_template']  = 'createpages';

	//Set the page title
	$context['page_title'] = $txt['adkmodules_admin_pages_edit'];

	//Set the save_action
	$context['save_action'] = 'saveeditpages';
	
	if(!empty($_REQUEST['id']))
		$id = (int) $_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_adk_not_page_id',FALSE);
	
	//Load page
	$context['edit_admin_page'] = getPage($id, true);	
	
	if(empty($context['edit_admin_page']))
		fatal_lang_error('adkfatal_adk_not_page_id',FALSE);

	//Load editor
	getEditor($context['edit_admin_page']['body']);
}

function saveeditpages()
{
	checkSession('post');
	
	global $context, $smcFunc;
	
	$titlepage = CleanAdkStrings($_POST['titlepage']);
	$urltext = CleanAdkStrings($_POST['urltext']);
	
	//Set memberGroups
	$groups_allowed = createArrayFromPost('groups_allowed');
	
	//Set the editor
	cleanEditor();
	
	$type = $_POST['type'];
	$body = CleanAdkStrings($_REQUEST['descript']);
	$cattitlebg = $_POST['cattitlebg'];
	$winbg = $_POST['winbg'];
	//$views = 0;
	
	$id_page = (int)$_POST['id_page'];
	$enable_comments = !empty($_POST['enable_comments']) ? 1 : 0;
	
	//Check if this page exists
	checkIfPageExists($urltext, $id_page);
	
	//Empty titlepage?
	if(empty($titlepage))
		fatal_lang_error('adkfatal_empty_title',false);

	//Empty body
	if(empty($body))
		fatal_lang_error('adkfatal_empty_body', false);
	
	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_pages
		SET titlepage = {string:titlepage}, urltext = {string:urltext},
		grupos_permitidos = {string:grupos}, type = {string:type},
		body = {string:body}, winbg = {string:winbg}, cattitlebg = {string:cat},
		enable_comments = {int:enable_comments}
		WHERE id_page = {int:page}',
		array(
			'titlepage' => $titlepage,
			'urltext' => $urltext,
			'grupos' => $groups_allowed,
			'type' => $type,
			'body' => $body,
			'winbg' => $winbg,
			'cat' => $cattitlebg,
			'page' => $id_page,
			'enable_comments' => $enable_comments,
		)
	);
		
	redirectexit('action=admin;area=modules;sa=viewadminpages;'.$context['session_var'].'=' . $context['session_id']);

}

function UploadNewImage()
{
	global $context, $txt;
	
	checkSession('get');
	
	$context['sub_template'] = 'adk_new_image';
	$context['page_title'] = $txt['adkmod_modules_images'];
}

function SaveUploadNewImage()
{
	global $context, $smcFunc, $txt, $boarddir, $boardurl, $adkFolder;
	
	checkSession('post');
	
	if(empty($_POST['url']))
		fatal_lang_error('adkfatal_require_url',false);
	
	if(empty($_FILES['image']['name']) && empty($_POST['image2']))
		fatal_lang_error('adkfatal_require_image',false);
	
	$style = !empty($_POST['format']) ? (int)$_POST['format'] : 2;
	$url = CleanAdkStrings($_POST['url']);
	$filename = CleanAdkStrings($_POST['image2']);
	
	$explode = explode('.',$filename);
	$count = count($explode) - 1;
	$extension = $explode[$count];
	
	if(!empty($filename)){
		
		$is_image = checkIfValidExtension($extension);
		
		if(!$is_image)
			fatal_lang_error('adkfatal_require_image',false);
	}
	
	if(!empty($_FILES['image']['name']))
	{
		if($_FILES['image']['type'] == "image/gif" 
			|| $_FILES['image']['type'] == "image/png" 
			|| $_FILES["image"]["type"] == "image/jpeg"
			|| $_FILES["image"]["type"] == "image/pjpeg")
		{
			$filename2 = $adkFolder['main'].'/tmp/'.$_FILES['image']['name'];
			$filename = $adkFolder['tmp'].'/'.$_FILES['image']['name'];
			$explode = explode('.',$_FILES['image']['name']);
			$count = count($explode) - 1;
			$extension = $explode[$count];
			
			
			@chmod($adkFolder['main'].'/tmp',0755);
			move_uploaded_file($_FILES['image']['tmp_name'], $style != 1 ? $filename2 : ($adkFolder['main'].'/images/'.time().'.JPG'));
		}
		else
		fatal_lang_error('adkfatal_require_image',false);
	}
	
	
	$watermark = CleanAdkStrings($_POST['wm']);
	$imagen_name = $adkFolder['main'].'/images/'.time().'.JPG';
	$imagen_name2 = $adkFolder['images'].'/'.time().'.JPG';
	
	//Generate Image ;)
	if($style != 1)
		load_AvdImage($watermark, $filename2, $extension, $style, $imagen_name);
	
	$smcFunc['db_insert']('insert',
		'{db_prefix}adk_advanced_images',
		array('image' => 'text', 'url' => 'text'),
		array($imagen_name2, $url),
		array('id')
	);

	if((!empty($_FILES['image']['name'])) && ($style != 1))
	{
		if($_FILES['image']['type'] == "image/gif" 
			|| $_FILES['image']['type'] == "image/png" 
			|| $_FILES["image"]["type"] == "image/jpeg"
			|| $_FILES["image"]["type"] == "image/pjpeg")
		{
			@chmod($adkFolder['main'].'/tmp',0755);
			@chmod($filename2, 0755);
			unlink($filename2);
		}
	}
	
	redirectexit('action=admin;area=modules;sa=manageimagesadk;'.$context['session_var'].'='.$context['session_id']);
}	

function ManageImagesAdk()
{
	checkSession('get');
	
	global $context, $smcFunc, $txt, $scripturl;
	$context['sub_template'] = 'manages_images';
	$context['page_title'] = $txt['adkmodules_opcion_img'];
	
	//Load Images
	$context['start'] = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
	$limit = 4;
	
	//Get total
	$context['total'] = getTotal('adk_advanced_images');
	
	//Load all images
	$context['load_img'] = getImages('', array(), 'id DESC', $context['start'], $limit);

	$context['page_index'] = constructPageIndex($scripturl . '?action=admin;area=modules;sa=manageimagesadk;'.$context['session_var'].'='.$context['session_id'], $context['start'], $context['total'], $limit);
}

function DeleteImagesAdk()
{
	checkSession('get');
	global $smcFunc, $context, $boarddir,$boardurl;
	
	$id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
	
	if(!empty($_REQUEST['url2']))
		$url = CleanAdkStrings($_REQUEST['url2']);
	else
		fatal_lang_error('adkfatal_require_url',false);
	
	$url = str_replace($boardurl,$boarddir,$url);
	
	@chmod($url,0755);
	@unlink($url);

	//Delete this entry
	deleteEntry('adk_advanced_images', 'id = {int:id}', array('id' => $id));

	redirectexit('action=admin;area=modules;sa=manageimagesadk;'.$context['session_var'].'='.$context['session_id']);

}

function ContactAdmin()
{
	global $smcFunc, $context, $txt;
	
	//CheckSession
	checkSession('get');

	$context['groups'] = loadAdkGroups('min_posts = {int:posts} AND id_group <> {int:admin} AND id_group <> {int:moderator}', array('posts' => -1,	'admin' => 1,	'moderator' => 3));
	
	//Adding guests and regulars users
	$context['groups'] += array(
		-1 => array('name' => $txt['adkmodules_guests']),
		0 => array('name' => $txt['adkmodules_regulars_users']),
	);
	
	//order array
	ksort($context['groups']);
	
	$context['sub_template'] = 'contact_admin';
	$context['page_title'] = $txt['adkmod_modules_contacto'];
}

function SaveContactAdmin()
{
	global $context;
	
	checkSession('post');
	
	$adk_enable_contact = !empty($_POST['adk_enable_contact']) ? 1 : 0;
	
	/*toview*/
	$toview = createArrayFromPost('toview');
	
	updateSettingsAdkPortal(
		array(
			'adk_enable_contact' => $adk_enable_contact,
			'adk_groups_contact' => $toview,
		)
	);
	
	redirectexit('action=admin;area=modules;sa=contact;'.$context['session_var'].'='.$context['session_id']);
}

function EnablePageMenu(){

	checkSession('get');

	updateSettingsAdkPortal(array('enable_menu_pages' => isset($_REQUEST['set']) ? 1 : 0));

	global $context;
	redirectexit('action=admin;area=modules;sa=viewadminpages;sa=viewadminpages;'.$context['session_var'].'=' . $context['session_id']);
}

function EnableComments(){

	checkSession('get');

	updateSettingsAdkPortal(array('enable_pages_comments' => isset($_REQUEST['set']) ? 1 : 0));

	global $context;
	redirectexit('action=admin;area=modules;sa=viewadminpages;sa=viewadminpages;'.$context['session_var'].'=' . $context['session_id']);
}

function EnableNotifications(){

	checkSession('get');

	updateSettingsAdkPortal(array('enable_pages_notifications' => isset($_REQUEST['set']) ? 1 : 0));

	global $context;
	redirectexit('action=admin;area=modules;sa=viewadminpages;sa=viewadminpages;'.$context['session_var'].'=' . $context['session_id']);
}
?>