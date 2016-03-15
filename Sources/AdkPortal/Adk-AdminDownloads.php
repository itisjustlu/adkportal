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

/*		This file has all function to create, edit, delete, or modify download system

		void ShowDownloadsMainAdmin()
			- Set the initial subActions for admin section
			- Load template and Main language
			- Set the beautiful menu

		void AdkDownloadSettings()
			- Set the initial settings

		void AdkDownloadSaveSettings()
			- Get the $adkportal[var] settings
			- Save it on {db_prefix}adk_settings

		void AdkDownloadSaveCategory()
			- Sets up the main info to create a new category
			- Set the permission to add/view cat

		void AdkDownloadSaveCategory()
			- Get all info about the new category
			- Save it on {db_prefix}adk_down_cat

		void AdkDownloadAllCategories()
			- Show all categories of download 
			- If there is some error... show it too
			- You can edit, delete or view this category

		void AdkDownloadEditCategory()
			- Get all info about this category
			- Get member groups permissions

		void AdkDownloadSaveEditCategory()
			- Get all info about the edited category
			- Update it

		void AdkDownloadDeleteCategory()
			- Delete all attachs of this category
			- Delete all files of this category
			- If exists some subcategory of it... make an error.

		void ApproveDownloadsAdmin()
			- Show approved files
			- Show unapprroved files
*/

function ShowDownloadsMainAdmin()
{
	global $context, $txt, $smcFunc, $settings, $sourcedir;

	//Load AdkDownloads Sources file
	require_once($sourcedir.'/AdkPortal/Subs-adkdownloads.php');

	//Set the subactions
	$subActions = array(
		'settings' => 'AdkDownloadSettings',
		'savesettings' => 'AdkDownloadSaveSettings',
		'addcategory' => 'AdkDownloadAddCategory',
		'savecategory' => 'AdkDownloadSaveCategory',
		'allcategories' => 'AdkDownloadAllCategories',
		'editcategory' => 'AdkDownloadEditCategory',
		'saveeditcategory' => 'AdkDownloadSaveEditCategory',
		'deletecategory' => 'AdkDownloadDeleteCategory',
		'approvedownloads' => 'ApproveDownloadsAdmin',
	);
	
	//Unnaproved downloads
	$TotalUnApproved = getTotal('adk_down_file', 'approved = {int:cero}', array('cero' => 0));
	
	//Permisos
	isAllowedTo('adk_downloads_manage');

	//Load Template and language
	adktemplate('Adk-AdminDownloads');
	adkLanguage('Adk-AdminDownloads');
	
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['adkeds_main_title'],
		'description' => $txt['adkeds_main_desc'],
		'tabs' => array(
			'settings' => array(
				'description' => '',
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/settings.png" />'.$txt['adkeds_settings'],
			),
			'addcategory' => array(
				'description' => $txt['adkeds_add_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/addcategory.png" />'.$txt['adkmod_eds_add'],
			),
			'allcategories' => array(
				'description' => $txt['adkeds_current_cat_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/editcategory.png" />'.$txt['adkmod_eds_categories'],
			),
			'approvedownloads' => array(
				'description' => $txt['adkeds_approve_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/approve.png" />'.$txt['adkeds_approve'].' ('.$TotalUnApproved.')',
			),
		),
	);	
	
	
	//print the subaction
	if (!empty($_GET['sa']) && !empty($subActions[$_GET['sa']]))
		$subActions[@$_GET['sa']]();
	else
		$subActions['settings']();

}

function AdkDownloadSettings()
{
	global $context, $txt;
	
	//Load main trader template.
	$context['sub_template']  = 'downloadssettings';
	$context['page_title'] = $txt['adkeds_settings'];
}	

function AdkDownloadSaveSettings()
{
	//Check the session
	checkSession('post');

	$download_enable = !empty($_POST['download_enable']) ? 1 : 0;	
	$download_max_filesize = (int)$_POST['download_max_filesize'];
	$download_images_size = (int)$_POST['download_images_size'];
	$download_set_files_per_page = (int)$_POST['download_set_files_per_page'];
	$download_enable_sendpmApprove = !empty($_POST['download_enable_sendpmApprove']) ? 1 : 0;	
	$download_sendpm_body = CleanAdkStrings($_POST['download_sendpm_body']);
	$download_sendpm_userId = (int)$_POST['download_sendpm_userId'];
	$download_max_attach_download = (int)$_POST['download_max_attach_download'];
	$adkcolor_border = CleanAdkStrings($_POST['adkcolor_border']);
	$adkcolor_fondo = CleanAdkStrings($_POST['adkcolor_fondo']);
	$adkcolor_fonttitle = CleanAdkStrings($_POST['adkcolor_fonttitle']);
	$adkcolor_font = CleanAdkStrings($_POST['adkcolor_font']);
	$adkcolor_link = CleanAdkStrings($_POST['adkcolor_link']);
	$adkcolor_attach = CleanAdkStrings($_POST['adkcolor_attach']);

	//update it
	updateSettingsAdkPortal(
		array(
			'download_enable' => $download_enable,
			'download_max_filesize' => $download_max_filesize,
			'download_images_size' => $download_images_size,
			'download_set_files_per_page' => $download_set_files_per_page,
			'download_enable_sendpmApprove' => $download_enable_sendpmApprove,
			'download_sendpm_body' => $download_sendpm_body,
			'download_sendpm_userId' => $download_sendpm_userId,
			'download_max_attach_download' => $download_max_attach_download,
			'adkcolor_border' => $adkcolor_border,
			'adkcolor_fondo' => $adkcolor_fondo,
			'adkcolor_fonttitle' => $adkcolor_fonttitle,
			'adkcolor_font' => $adkcolor_font,
			'adkcolor_link' => $adkcolor_link,
			'adkcolor_attach' => $adkcolor_attach,
		)
	);
	
	redirectexit('action=admin;area=adkdownloads');

}

function AdkDownloadAddCategory()
{
	global $context, $mbname, $txt, $modSettings, $smcFunc, $boarddir, $adkFolder;

	
	checkSession('get');

	//Save action
	$context['save_action'] = 'savecategory';

	//Category array
	$context['adk_cat']['id_cat'] = 0;
	$context['adk_cat']['title'] = '';
	$context['adk_cat']['description'] = '';
	$context['adk_cat']['sortby'] = '';
	$context['adk_cat']['orderby'] = 'DESC';
	$context['adk_cat']['id_board'] = 0;
	$context['adk_cat']['locktopic'] = 0;
	$context['adk_cat']['id_parent'] = 0;
	$context['adk_cat']['image2'] = '';

	//Load the boards where the user can post in it
	getBoardsAdminDownload();

	//Load categorys
	getCatAdminDownload('id_parent = {int:parent}', array('parent' => 0));

	//Check if we're trying to create a subcategory
	if (isset($_REQUEST['cat']))
		$parent  = (int) $_REQUEST['cat'];
	else
		$parent = 0;

	$context['cat_parent'] = $parent;

	//Set the pagetitle and sub_template
	$context['page_title'] = $txt['adkmod_eds_add'];
	$context['sub_template']  = 'add_category';

	//Directory is writable
	$context['is_not_writable_download_path'] = !is_writable($adkFolder['eds'] . '/catimgs');

	//It's for compatibility with edit section
	$context['groups_can_view'] = array();
	$context['groups_can_add'] = array();
	$context['groups_can_view_parent'] = array();
	$context['groups_can_add_parent'] = array();

	//Set memberGroups
	$context['memberGroups_view'] = loadAdkGroups('min_posts = {int:min} AND id_group NOT IN ({array_int:group})', array('min' => -1, 'group' => array(1,3)));
	$context['memberGroups_add'] = loadAdkGroups('min_posts = {int:min} AND id_group NOT IN ({array_int:group})', array('min' => -1, 'group' => array(1,3)));

	$context['memberGroups_view'] += array(
		0 => array('name' => $txt['adkeds_regulars_users']),
		-1 => array('name' => $txt['adkeds_guests']),
	);

	$context['memberGroups_add'] += array(
		0 => array('name' => $txt['adkeds_regulars_users']),
	);
}

function AdkDownloadSaveCategory()
{
	global $context, $modSettings, $sourcedir, $txt, $boarddir, $smcFunc;
	
	//Ckech the session
	checkSession('post');
	
	//And get $_POST info
	$title = CleanAdkStrings($_POST['title']);
	$parent = (int)$_POST['parent'];
	$description = CleanAdkStrings($_POST['description']);
	$boardselect = (int)$_POST['boardselect'];
	$locktopic = (int)$_POST['locktopic'];
	$cant_view = createArrayFromPost('view');
	$cant_add = createArrayFromPost('add');
	
	//Invalid cateory without a file
	if(empty($title))
		fatal_lang_error('adkfatal_cat_title_false',false);
	
	$sortby = '';
	$orderby = '';
	if(!empty($_POST['sortby']))
	{
		switch($_POST['sortby'])
		{
			case 'date':
				$sortby = 'id_file';
			break;
			case 'title':
				$sortby = 'title';
			break;
			case 'mostview':
				$sortby = 'views';
			break;
			case 'mostdowns':
				$sortby = 'totaldownloads';
			break;
		}
	}
	else
		$sortby = 'id_file';
	
	if(!empty($_POST['orderby']))
	{
		switch($_POST['orderby'])
		{
			case 'asc':
				$orderby = 'ASC';
			break;
			case 'desc':
				$orderby = 'DESC';
			break;
		}
	}
	else
		$orderby = 'DESC';
	
	// Select
	$new = $smcFunc['db_query']('','
		SELECT MAX(roworder) as cat_order
		FROM {db_prefix}adk_down_cat
		WHERE id_parent = {int:p}
		ORDER BY roworder DESC',
		array(
			'p' => $parent,
		)
	);
	
	$row = $smcFunc['db_fetch_assoc']($new);

	if ($smcFunc['db_num_rows']($new) == 0)
		$order = 0;
	else
		$order = $row['cat_order'];
	
	$order++;

	$smcFunc['db_free_result']($new);

	/**
	* Check if it is id_parent....
	* IF it's a subcategory, load permissions of previous cateogory and process it
	**/
	if($parent != 0){
		$sql = $smcFunc['db_query']('','SELECT groups_can_view AS cant_view, groups_can_add AS cant_add FROM {db_prefix}adk_down_cat WHERE id_cat = {int:parent}', array('parent' => $parent));

		list($cant_view, $cant_add) = $smcFunc['db_fetch_row']($sql);

		$smcFunc['db_free_result']($sql);
	}
	
	//Process it
	$image = '';
	if (!empty($_FILES['picture']['name']) && $_FILES['picture']['name'] != '')
		processIconDownload($_FILES['picture'], 'size');
	
	//Insert in database
	$smcFunc['db_insert'](
		'insert',
		'{db_prefix}adk_down_cat',
		array(
			'title' => 'text',
			'description' => 'text',
			'roworder' => 'int', 
			'image' => 'text',
			'id_board' => 'int',
			'id_parent' => 'int',
			'locktopic' => 'int',
			'sortby' => 'text',
			'orderby' => 'text',
			'groups_can_view' => 'text',
			'groups_can_add' => 'text',
		),
		array($title, $description, $order, $image, $boardselect, $parent, $locktopic, $sortby, $orderby, $cant_view, $cant_add),
		array('id_cat')
	);
	
	//Category ID
	$cat_id = $smcFunc['db_insert_id']('{db_prefix}adk_down_cat', 'id_cat');
	
	//Update and upload
	if (!empty($_FILES['picture']['name']) && $_FILES['picture']['name'] != '')
		processIconDownload($_FILES['picture'], 'update', $cat_id);

	redirectexit('action=admin;area=adkdownloads;sa=allcategories;'.$context['session_var'].'='.$context['session_id']);
	
}

function AdkDownloadEditCategory()
{
	global $smcFunc, $txt, $context, $scripturl, $boarddir, $adkFolder;
	
	checkSession('get');
	
	//Set the initial context
	$context['sub_template']  = 'add_category';
	$context['save_action'] = 'saveeditcategory';
	
	//Check id_category
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id_cat = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_invalid_id_category',false);
	
	//Load boards
	getBoardsAdminDownload();

	//Load Cats
	getCatAdminDownload('id_parent = {int:parent} AND id_cat <> {int:cat}', array('parent' => 0, 'cat' => $id_cat));
	
	//Load this category
	$context['adk_cat'] = array();
	$sql = $smcFunc['db_query']('','
		SELECT title,description,id_board,id_parent,locktopic,sortby,orderby,image, groups_can_view, groups_can_add, roworder
		FROM {db_prefix}adk_down_cat
		WHERE id_cat = {int:cat} LIMIT 1',
		array(
			'cat' => $id_cat,
		)
	);
	
	//Invalid category?
	if($smcFunc['db_num_rows']($sql) == 0)
		fatal_lang_error('adkfatal_invalid_id_category', false);

	$new = $smcFunc['db_fetch_assoc']($sql);

	//Set the array
	$context['adk_cat'] = array(
		'id_cat' => $id_cat,
		'id_board' => $new['id_board'],
		'title' => $new['title'],
		'description' => $new['description'],
		'id_parent' => $new['id_parent'],
		'locktopic' => $new['locktopic'],
		'sortby' => $new['sortby'],
		'orderby' => $new['orderby'],
		'image2' => $new['image'],
		'roworder' => $new['roworder'],
	);

	//Compatibility
	$context['cat_parent'] = $context['adk_cat']['id_parent'];

	//Cant view. cant add
	$context['groups_can_view'] = !empty($new['groups_can_view']) || $new['groups_can_view'] == "0" ? explode(',',$new['groups_can_view']) : array();
	$context['groups_can_add'] = !empty($new['groups_can_add']) || $new['groups_can_add'] == "0" ? explode(',',$new['groups_can_add']) : array();

	//Set memberGroups
	$context['memberGroups_view'] = loadAdkGroups('min_posts = {int:min} AND id_group NOT IN ({array_int:group})', array('min' => -1, 'group' => array(1,3)));
	$context['memberGroups_add'] = loadAdkGroups('min_posts = {int:min} AND id_group NOT IN ({array_int:group})', array('min' => -1, 'group' => array(1,3)));

	$context['memberGroups_view'] += array(
		0 => array('name' => $txt['adkeds_regulars_users']),
		-1 => array('name' => $txt['adkeds_guests']),
	);

	$context['memberGroups_add'] += array(
		0 => array('name' => $txt['adkeds_regulars_users']),
	);

	$smcFunc['db_free_result']($sql);

	//Get previos permissions
	$context['groups_can_view_parent'] = array();
	$context['groups_can_add_parent'] = array();

	if($context['cat_parent'] != 0){
		$sql = $smcFunc['db_query']('','SELECT groups_can_view AS cant_view, groups_can_add AS cant_add FROM {db_prefix}adk_down_cat WHERE id_cat = {int:parent}', array('parent' => $context['cat_parent']));

		list($cant_view, $cant_add) = $smcFunc['db_fetch_row']($sql);

		$smcFunc['db_free_result']($sql);

		$context['groups_can_view_parent'] = !empty($cant_view) || $cant_view == "0" ? explode(',', $cant_view) : array();
		$context['groups_can_add_parent'] = !empty($cant_add) || $cant_add == "0" ? explode(',', $cant_add) : array();
	}

	//Directory is writable
	$context['is_not_writable_download_path'] = !is_writable($adkFolder['eds'] . '/catimgs');

	//Set the page_title
	$context['page_title'] = $txt['adkeds_edit_cat_title'].' ('.$context['adk_cat']['title'].')';

}

function AdkDownloadSaveEditCategory()
{
	global $context, $modSettings, $sourcedir, $txt, $boarddir, $smcFunc;
	
	checkSession('post');
	
	$title = CleanAdkStrings($_POST['title']);
	$parent = (int)$_POST['parent'];
	$description = CleanAdkStrings($_POST['description']);
	$boardselect = (int)$_POST['boardselect'];
	$locktopic = (int)$_POST['locktopic'];
	$id_cat = !empty($_POST['id_cat']) ? (int)$_POST['id_cat'] : 0;
	$filename = $_POST['picture2'];
	$cant_view = createArrayFromPost('view');
	$cant_add = createArrayFromPost('add');
	$roworder = (int)$_POST['roworder'];
	
	if(empty($title))
		fatal_lang_error('adkfatal_cat_title_false',false);
	
	$sortby = '';
	$orderby = '';
	if(!empty($_POST['sortby']))
	{
		switch($_POST['sortby'])
		{
			case 'date':
				$sortby = 'id_file';
			break;
			case 'title':
				$sortby = 'title';
			break;
			case 'mostview':
				$sortby = 'views';
			break;
			case 'mostdowns':
				$sortby = 'totaldownloads';
			break;
		}
	}
	else
		$sortby = 'id_file';
	
	if(!empty($_POST['orderby']))
	{
		switch($_POST['orderby'])
		{
			case 'asc':
				$orderby = 'ASC';
			break;
			case 'desc':
				$orderby = 'DESC';
			break;
		}
	}
	else
		$orderby = 'DESC';
	
	$context['download_file_name'] = '';

	if (!empty($_FILES['picture']['name']) && $_FILES['picture']['name'] != ''){
		processIconDownload($_FILES['picture'], 'size');
		processIconDownload($_FILES['picture'], 'update', $id_cat);
	}	

	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_down_cat
		SET
			title = {string:title}, description = {string:description},
			image = {string:filename}, id_board = {int:idb},
			id_parent = {int:idp}, locktopic = {int:locktopic},
			sortby = {string:sortby}, orderby = {string:orderby},
			groups_can_add = {string:cant_add}, groups_can_view = {string:cant_view},
			error = {int:error}, roworder = {int:roworder}
		WHERE id_cat = {int:id_cat}',
		array(
			'title' => $title,
			'description' => $description,
			'filename' => $context['download_file_name'],
			'idb' => $boardselect,
			'idp' => $parent,
			'locktopic' => $locktopic,
			'sortby' => $sortby,
			'orderby' => $orderby,
			'id_cat' => $id_cat,
			'cant_add' => $cant_add,
			'cant_view' => $cant_view,
			'error' => 0,
			'roworder' => $roworder,
		)
	);

	redirectexit('action=admin;area=adkdownloads;sa=allcategories;'.$context['session_var'].'='.$context['session_id']);
	
}

function AdkDownloadAllCategories()
{
	global $smcFunc, $context, $txt, $total_cat, $scripturl;
	
	checkSession('get');
	
	//Get categories
	getDownloadCategories();

	//Compatibility
	//$context['all_categories'] = $context['downloads_cat'];
	
	$context['page_title'] = $txt['adkmod_eds_categories'];
	$context['sub_template']  = 'all_categories';
}

function AdkDownloadDeleteCategory()
{
	global $context, $smcFunc, $boarddir, $adkFolder;
	
	//Check the session
	checkSession('get');
	
	//Get id_cat
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id_cat = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_invalid_id_category',false);
	
	//Get All id_files of this category
	$sql = $smcFunc['db_query']('','
		SELECT id_file FROM {db_prefix}adk_down_file
		WHERE id_cat = {int:cat}',
		array(
			'cat' => $id_cat,
		)
	);

	while($row = $smcFunc['db_fetch_assoc']($sql))
		$files[] = $row['id_file'];
	
	$smcFunc['db_free_result']($sql);

	//Get all attachments... and delete it (if this category has a download
	if(!empty($files)){
		$get = $smcFunc['db_query']('','SELECT filename FROM {db_prefix}adk_down_attachs WHERE id_file IN ({array_int:file})', array('file' => $files));

		while($row = $smcFunc['db_fetch_assoc']($get))
			if(file_exists($adkFolder['eds'].'/'.$row['filename']))
				@unlink($adkFolder['eds'].'/'.$row['filename']);

		$smcFunc['db_free_result']($get);

		//Delete attachs
		deleteEntry('adk_down_attachs', 'id_file IN ({array_int:files})', array('files' => $files));

		//Delete downloads
		deleteEntry('adk_down_file', 'id_cat = {int:cat}', array('cat' => $id_cat));
	}

	//Update row orders
	$sql = $smcFunc['db_query']('','SELECT roworder FROM {db_prefix}adk_down_cat WHERE id_cat = {int:cat}',array('cat' => $id_cat));
	list($roworder) = $smcFunc['db_fetch_row']($sql);
	$smcFunc['db_free_result']($sql);

	$smcFunc['db_query']('','UPDATE {db_prefix}adk_down_cat SET roworder = roworder - 1 WHERE roworder > {int:roworder}', array('roworder' => $roworder));

	//Finally... delete category
	deleteEntry('adk_down_cat', 'id_cat = {int:cat}', array('cat' => $id_cat));
	
	//Set an error of this categorys
	setCategoryError($id_cat);
	
	redirectexit('action=admin;area=adkdownloads;sa=allcategories;'.$context['session_var'].'='.$context['session_id']);

}

function ApproveDownloadsAdmin()
{
	global $smcFunc, $txt, $context,$scripturl, $total_Unapprove, $total_Approve;
	
	checkSession('get');

	//Basic information
	$context['page_title'] = $txt['adkeds_approve'];
	$context['sub_template']  = 'approve_d';

	//Request info
	$context['start_unapproved'] = isset($_REQUEST['adk_unapproved']) ? !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0 : 0;
	$context['start_approved'] = isset($_REQUEST['adk_approved']) ? !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0 : 0;
	$limit = 15;
	
	//Get total approved and unapproved
	$total_Unapprove = getTotal('adk_down_file', 'approved = {int:cero}', array('cero' => 0));
	$total_Approve = getTotal('adk_down_file', 'approved = {int:cero}', array('cero' => 1));

	//Get UnApproved && approved Files
	$context['unapproved'] = getInfoFileByApprove(0, $context['start_unapproved'], $limit);
	$context['approved'] = getInfoFileByApprove(1, $context['start_approved'], $limit);

	//Set the page index.
	$context['page_index_unapproved'] = constructPageIndex($scripturl . '?action=admin;area=adkdownloads;sa=approvedownloads;adk_unapproved;'.$context['session_var'].'='.$context['session_id'].'', $context['start_unapproved'], $total_Unapprove, $limit);
	$context['page_index_approved'] = constructPageIndex($scripturl . '?action=admin;area=adkdownloads;sa=approvedownloads;adk_approved;'.$context['session_var'].'='.$context['session_id'], $context['start_approved'], $total_Approve, $limit);

	//Set the total Row
	$context['total_dow'] = $total_Approve + $total_Unapprove;
}

?>