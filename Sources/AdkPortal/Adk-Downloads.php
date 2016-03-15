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

function ShowDownloads()
{
	global $context, $smcFunc, $txt, $boardurl, $modSettings, $adkportal, $sourcedir;
	
	//Load your language or English Language
	adkLanguage('Adk-Downloads');

	//Load Subs-adkdownloads.php
	require_once($sourcedir.'/AdkPortal/Subs-adkdownloads.php');
	
	//Check fi adkportal is enabled or if you have permissions to manage it
	if($adkportal['download_enable'] == 0 && !allowedTo('adk_downloads_manage'))
		fatal_lang_error('adkfatal_this_module_doesnt_exist',false);

	$adkportal['Designeds'] = array(
		'borde' => !empty($adkportal['adkcolor_border']) ? $adkportal['adkcolor_border'] : '#99ABBF',
		'fondo' => !empty($adkportal['adkcolor_fondo']) ? $adkportal['adkcolor_fondo'] : '#ffffff',
		'titulo' => !empty($adkportal['adkcolor_fonttitle']) ? $adkportal['adkcolor_fonttitle'] : '#ffffff',
		'letra' => !empty($adkportal['adkcolor_font']) ? $adkportal['adkcolor_font'] : '#444444',
		'link' => !empty($adkportal['adkcolor_link']) ? $adkportal['adkcolor_link'] : '#334466',
		'att' => !empty($adkportal['adkcolor_attach']) ? $adkportal['adkcolor_attach'] : '#CEE0F4',
	);


	//Set the subations 
	$subActions = array(
		'index' => 'ShowIndexCategories',
		'view' => 'AdkViewDownload',
		'search' => 'AdkSearchDownloads',
		'search2' => 'AdkSearchDownloads2',
		'downfile' => 'AdkDownloadFile',
		'addnewfile' => 'AddaNewDownload',
		'addnewfile2' => 'AddaNewDownload2',
		'deletedownload' => 'DeleteDownload',
		'editdownload' => 'EditDownload',
		'unapprovedownload' => 'UnApproveDownload',
		'approvedownload' => 'ApproveDownload',
		'saveeditdownload' => 'EditSaveDownload',
		'viewstats' => 'AdkViewStats',
		'myprofile' => 'AdkViewMyProfile',
		'down' => 'DownCat',
		'up' => 'UpCat',
	);
	
	//Load css system
	$context['html_headers'] .= getCss('download_system');
	
	//Load Template
	adktemplate('Adk-Downloads');
	
	if (!empty($_GET['sa']) && !empty($subActions[$_GET['sa']]))
		$subActions[@$_GET['sa']]();
	elseif(!empty($_REQUEST['cat']) && is_numeric($_REQUEST['cat']))
		ShowCatDownload((int)$_REQUEST['cat']);
	else
		$subActions['index']();
}

function ShowIndexCategories()
{
	global $context, $txt, $smcFunc,$user_info, $boardurl, $scripturl, $modSettings;
	
	//Update Link Tree
	setLinktree('downloads','adkdown_downloads');
	
	//Load categories
	getDownloadCategories();
				
	//Important
	$context['unApprove'] = getTotal('adk_down_file', 'approved = {int:cero}', array('cero' => 0));
	$context['last_downloads'] = LastTenDownloads();
	$context['downloads_popular'] = PopularViewDownloads();
	
	//Load main trader template.
	$context['sub_template']  = 'main';
	$context['page_title'] = $txt['adkdown_downloads'];
}

function ShowCatDownload($id_cat)
{
	global $context, $txt, $smcFunc,$user_info, $boardurl, $scripturl, $modSettings, $adkportal;
	
	//This is rare...
	if(empty($id_cat))
		fatal_lang_error('adkfatal_require_catid',false);
	
	//check permissions
	verifyCatPermissions('view',$id_cat);

	//Get the id_group
	$id_group = getIdGroup();
	
	//Load cat info
	$sql = $smcFunc['db_query']('','
		SELECT id_cat, title, roworder, description, image, orderby, sortby, id_parent, groups_can_add, error
		FROM {db_prefix}adk_down_cat
		WHERE id_cat = {int:cat}',
		array(
			'cat' => $id_cat,
		)
	);
	
	if($smcFunc['db_num_rows']($sql) == 0)
		fatal_lang_error('adkfatal_require_catid',false);

	//Set in the row
	$row = $smcFunc['db_fetch_assoc']($sql);
	
	//Cat Info
	$context['adk_download_title'] = $row['title'];
	$context['adk_download_roworder'] = $row['roworder'];
	$context['adk_download_description'] = parse_bbc($row['description']);
	$context['adk_download_image'] = $row['image'];
	$context['adk_download_idparent'] = $row['id_parent'];
	$sortby = !empty($row['sortby']) ? $row['sortby'] : 'date';
	$orderby = !empty($row['orderby']) ? $row['orderby'] : 'ASC';
	$has_error = !empty($row['error']);
	$context['cat_id'] = $id_cat;
	$value = array_intersect($id_group,explode(',', $row['groups_can_add']));
	$context['adk_can_add_file'] = !empty($value) || $user_info['is_admin'] || allowedTo('adk_downloads_manage');
	
	$smcFunc['db_free_result']($sql);
	//End cat info

	if($has_error)
		fatal_lang_error('adkfatal_require_catid',false);
	
	//The First Link Tree
	setLinktree('downloads','adkdown_downloads');

	//Link Tree if the cat is parent
	CheckCatParent($context['adk_download_idparent']);

	//Current category
	setLinktree('downloads;cat='.$id_cat, $context['adk_download_title'], false, true);

	//Define this variable :)
	$allowed_to_manage = allowedTo('adk_downloads_manage') ? 1 : 0;
	
	//Set the start
	$context['start'] = (int) $_REQUEST['start'];

	//List Sub Categories
	getDownloadCategories($id_cat, 'c.id_parent = {int:p}', array('p' => $id_cat), '');
		
	//11/11/2010
	if($sortby == 'mostview')
		$sortby = 'views';
	elseif($sortby == 'mostdowns')
		$sortby = 'totaldownloads';
	
	$sortby = 'd.'.$sortby;
	
	$limit = $adkportal['download_set_files_per_page'];
	$start = $context['start'];
	
	//List all files ;)
	$sql = $smcFunc['db_query']('','
		SELECT d.id_file, d.id_member, d.date, d.approved, d.title, d.description, d.views, d.totaldownloads, d.main_image,
		m.id_member, m.real_name, d.short_desc
		FROM {db_prefix}adk_down_file AS d
		LEFT JOIN {db_prefix}members AS m ON (m.id_member = d.id_member)
		LEFT JOIN {db_prefix}adk_down_cat AS c ON (c.id_cat = d.id_cat)
		WHERE d.id_cat = {int:cat}
			AND '.$adkportal['query_downloads'].'
		ORDER BY '.$sortby.' '.$orderby.'
		LIMIT {int:start}, {int:limit}',
		array(
			'cat' => $id_cat,
			'start' => $start,
			'limit' => $limit,
			'a' => 1,
			'member' => $user_info['id'],
		)
	);
	
	$context['listFiles'] = array();

	while($row = $smcFunc['db_fetch_assoc']($sql))
	{
		$context['listFiles'][] = array(
			'id_member' => $row['id_member'],
			'member' => '<a href="'.$scripturl.'?action=profile;u='.$row['id_member'].'">'.$row['real_name'].'</a>',
			'file' => '<a title="'.$row['short_desc'].'" style="font-weight: bold;" href="'.$scripturl.'?action=downloads;sa=view;down='.$row['id_file'].'">'.$row['title'].'</a>',
			'id_file' => $row['id_file'],
			'date' => timeformat($row['date']),
			'description' => parse_bbc($row['description']),
			'views' => $row['views'],
			'total' => $row['totaldownloads'],
			'image' => $row['main_image'],
			'title' => $row['title'],
			'approved' => $row['approved'],
			'color' => $row['approved'] == 0 ? '#FFEAEA' : '',
			'image' => $row['approved'] == 0 ? 'unapprove' : 'approve',
			'short' => $row['short_desc'],
		);
	}
	
	$smcFunc['db_free_result']($sql);

	//Count all files we can view
	$sql = $smcFunc['db_query']('','
		SELECT COUNT(*) AS total
		FROM {db_prefix}adk_down_file AS d
		LEFT JOIN {db_prefix}adk_down_cat AS c ON (c.id_cat = d.id_cat)
		WHERE d.id_cat = {int:cat}
			AND '.$adkportal['query_downloads'],
		array(
			'cat' => $id_cat,
			'member' => $user_info['id'],
		)
	);

	list($total) = $smcFunc['db_fetch_row']($sql);
	$smcFunc['db_free_result']($sql);
	
	$context['page_index'] = constructPageIndex($scripturl . '?action=downloads;cat=' . $id_cat, $context['start'], $total, $limit);
	
	//Load main trader template.
	$context['sub_template']  = 'view_download_files';
	$context['page_title'] = $context['adk_download_title'].' - '.$txt['adkdown_downloads'];

	//The Menu Buttons
	$context['adk_downloads_add'] = verifyCatPermissions('addfile', $id_cat, true);
	$context['adk_user_is_logged'] = $context['user']['is_logged'];
	$context['adk_can_manage'] = allowedTo('adk_downloads_manage') && $context['adk_download_idparent'] == 0;
}

function AdkViewMyProfile()
{
	global $context, $scripturl, $smcFunc, $txt, $user_info, $modSettings;
	
	//Set the id_user
	if(!empty($_REQUEST['u']))
		$id = (int)$_REQUEST['u'];
	else
		$id = $user_info['id'];

	//Set the id_group
	$id_group = getIdGroup();
	
	if(empty($id))
		fatal_lang_error('adkfatal_empty_id_profile',false);
	
	$context['start'] = (int) $_REQUEST['start'];
	
	//List all files ;)
	$sql = $smcFunc['db_query']('','
		SELECT d.id_file, d.id_member, d.date, d.approved, d.title, d.description, d.views, d.totaldownloads, d.main_image, d.id_cat, c.groups_can_view,
		m.id_member, m.real_name
		FROM {db_prefix}adk_down_file AS d
		LEFT JOIN {db_prefix}members AS m ON (m.id_member = d.id_member)
		LEFT JOIN {db_prefix}adk_down_cat AS c ON (c.id_cat = d.id_cat)
		WHERE d.id_member = {int:m} '. (allowedTo('adk_downloads_manage') || $user_info['id'] == $id ? '' : ' AND d.approved = {int:approved}') .'
		ORDER BY d.id_file DESC',
		array(
			'm' => $id,
			'approved' => 1,
		)
	);
	
	$context['listFiles'] = array();
	while($row = $smcFunc['db_fetch_assoc']($sql))
	{
		$value = array_intersect($id_group,explode(',', $row['groups_can_view']));
		if ((!empty($value)) || ($user_info['is_admin'])) {
			$context['listFiles'][] = array(
				'id_member' => $row['id_member'],
				'member' => '<a href="'.$scripturl.'?action=profile;u='.$row['id_member'].'">'.$row['real_name'].'</a>',
				'file' => '<a href="'.$scripturl.'?action=downloads;sa=view;down='.$row['id_file'].'">'.$row['title'].'</a>',
				'id_file' => $row['id_file'],
				'date' => timeformat($row['date']),
				'views' => $row['views'],
				'total' => $row['totaldownloads'],
				'image' => $row['main_image'],
				'title' => $row['title'],
				'approved' => $row['approved'],
			);
			
			$context['the_real_name'] = $row['real_name'];
			$context['link_profile'] = '<a class="eds_link_profile" href="'.$scripturl.'?action=downloads;sa=myprofile;u='.$row['id_member'].'">'.$row['real_name'].'</a>';
		}
	}

	$smcFunc['db_free_result']($sql);
	
	if(empty($context['listFiles']))
		fatal_lang_error('adkfatal_user_not_have_nadanose',false);
	
	$context['sub_template'] = 'download_my_profile';
	$context['page_title'] = sprintf($txt['adkdown_profile'], $context['the_real_name']);
	
	//The First Link Tree
	setLinktree('downloads','adkdown_downloads');
	setLinktree('downloads;sa=myprofile;u='.$id, $context['page_title'], false, true);
}

function AddaNewDownload()
{
	global $smcFunc, $context, $user_info, $txt, $modSettings, $sourcedir;
	
	//Set the category
	if(!empty($_REQUEST['category']) && is_numeric($_REQUEST['category']))
		$id_cat = (int)$_REQUEST['category'];
	else
		fatal_lang_error('adkfatal_please_select_cat',false);

	//Is allowed to add?
	verifyCatPermissions('addfile',$id_cat);

	if($context['user']['is_guest'])
		falta_lang_error('adkfatal_guest_not_add',false);
	
	//Set in a context variable
	$context['id_cat_'] = $id_cat;
	
	$sql = $smcFunc['db_query']('','
		SELECT title, id_parent FROM {db_prefix}adk_down_cat
		WHERE id_cat = {int:cat}',
		array(
			'cat' => $id_cat,
		)
	);

	//List info
	list($title, $id_parent) = $smcFunc['db_fetch_row']($sql);

	//No info?
	if($smcFunc['db_num_rows']($sql) == 0)
		fatal_lang_error('adkfatal_this_category_not_exist',false);

	$smcFunc['db_free_result']($sql);

	//Set the thitle
	$context['cat_title'] = $title;
	
	//Strings for compatibility
	$context['important_info']['rest'] = 0;
	$context['save_action'] = 'addnewfile2';
	$context['important_info']['id_file'] = '';
	$context['important_info']['id_member'] = 0;
	$context['important_info']['id_cat'] = '';
	$context['important_info']['title'] = '';
	$context['important_info']['rest'] = 0;
	$context['important_info']['short_desc'] = '';
	
	//Load Important Info
	$context['sub_template'] = 'add_a_new_download';
	$context['page_title'] = $txt['adkdown_add'].' '.$context['cat_title'];
	//End
	
	// Needed for the WYSIWYG editor.
	getEditor();

	//Set linktree
	setLinktree('downloads','adkdown_downloads');
	CheckCatParent($id_parent);
	setLinktree('downloads;cat='.$id_cat, $context['cat_title'], false, true);
	setLinktree('downloads;sa=addnewfile;category='.$id_cat,'adkdown_add');
}


function AddaNewDownload2()
{
	global $modSettings, $sourcedir, $smcFunc, $context, $user_info, $boarddir, $scripturl, $txt, $boardurl, $adkportal;
	
	//Need this session
	checkSession('post');

	//isAllowedToAdd
	$id_cat = (int)$_POST['id_cat'];
	verifyCatPermissions('addfile',$id_cat);
	
	if($context['user']['is_guest'])
		falta_lang_error('adkfatal_guest_not_add',false);
	
	//Clean editor information
	cleanEditor();	
	
	//Get post info
	$title = CleanAdkStrings($_POST['title']);
	$description = CleanAdkStrings($_REQUEST['descript']);
	$image = '';
	$time = time();
	$id_member = $user_info['id'];
	$short_desc = CleanAdkStrings($_POST['short_desc']);
	
	//Get image file
	if (!empty($_FILES['screen']['name']) && $_FILES['screen']['name'] != '')
		$image = processDownloadImage($_FILES['screen']);
	
	//Set downloadsDir
	$DownloadsDir = $boarddir.'/Adk-downloads';
	
	//Check if is writable
	if(!is_writable($DownloadsDir))
		fatal_lang_error('adkfatal_not_writable_dir',false);
	
	$approved = (allowedTo('adk_downloads_autoapprove') ? 1 : 0);
	
	//Set the errors if exists
	if(empty($title))
		fatal_lang_error('adkfatal_please_add_a_title',false);

	if(empty($description))
		fatal_lang_error('adkfatal_please_add_a_body', false);
	
	//Category Info
	$sql = $smcFunc['db_query']('','
		SELECT id_board, locktopic
		FROM {db_prefix}adk_down_cat
		WHERE id_cat = {int:cat}',
		array(
			'cat' => $id_cat,
		)
	);

	list($id_board, $locktopic) = $smcFunc['db_fetch_row']($sql);
	$smcFunc['db_free_result']($sql);

	//Load All rest
	if(!empty($_FILES['download']) && $_FILES['download']['name'] != '')
	{	
		$l = 0;
		foreach ($_FILES['download']['tmp_name'] as $n => $dummy)
		{
			if($_FILES['download']['name'][$n] != '')
				$l++;
			
			$filesize = $_FILES['download']['size'][$n];
			
			if (!empty($adkportal['download_max_filesize']) && $filesize > $adkportal['download_max_filesize'])
			{
				@unlink($_FILES['download']['tmp_name'][$n]);
				fatal_lang_error('adkfatal_big_size',false);
			}
		}
		
		if(empty($l))
			fatal_lang_error('adkfatal_empty_attach',false);
		
		//Insert info
		$smcFunc['db_insert'](
			'insert',
			'{db_prefix}adk_down_file',
			array(
				'id_member' => 'int',
				'date' => 'int',
				'title' => 'text',
				'description' => 'text',
				'id_cat' => 'int',
				'main_image' => 'text',
				'approved' => 'int',
				'short_desc' => 'text',
			),
			array($id_member, $time, $title, $description, $id_cat, $image, $approved, $short_desc),
			array('id_file')
		);

		//Get this id
		$last_id = 0;
		$last_id = $smcFunc['db_insert_id']("{db_prefix}adk_down_file");
		
		$i = 0;
		foreach ($_FILES['download']['tmp_name'] as $n => $dummy)
		{
			
			$filesize = $_FILES['download']['size'][$n];
			$original =  $_FILES['download']['name'][$n];
			
			//Nosotros usamos el Download System en Smf Personal, entonces... necesitamos el mismo nombre de archivo ;)
			$filename = $user_info['id'] . '_' . date('d_m_y_g_i_s').$i;
			$i++;
			
			//Move uploaded file
			move_uploaded_file($_FILES['download']['tmp_name'][$n], $DownloadsDir .'/'.  $filename);
			@chmod($DownloadsDir .  $filename, 0644);
			
			//Insert file
			$smcFunc['db_insert'](
				'insert',
				'{db_prefix}adk_down_attachs',
				array(
					'id_file' => 'int',
					'filename' => 'text',
					'filesize' => 'text',
					'orginalfilename' => 'text',
				),
				array($last_id, $filename, $filesize, $original),
				array('id_attach')
			);
		}
		
		//Get sub-post.php and reate topic if we can
		require_once($sourcedir . '/Subs-Post.php');
		if(!empty($id_board) && !empty($approved)){

			setTopic(array(
				'subject' => $title,
				'body' => $description,
				'id_board' => $id_board,
				'locked' => $locktopic,
				'id_member' => $id_member,
				'id_file' => $last_id,
				'image' => $image,
			));
		}
		
		//Update category	
		TotalCategoryUpdate($id_cat);

	}
	
	redirectexit('action=downloads;sa=myprofile;u='.$id_member);
}

function EditDownload()
{
	global $smcFunc, $context, $user_info, $txt, $modSettings, $sourcedir;
	
	//Check session
	checkSession('get');
	
	//Get Id_file
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id_file = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_require_id_file',false);

	//Strings for compatibility
	$context['save_action'] = 'saveeditdownload';

	//Get attachments
	$context['load_attachs'] = getAttachments($id_file, 'edit');
	
	//Lets load it
	$sql = $smcFunc['db_query']('','
		SELECT a.title, a.description, a.id_cat, c.id_cat, c.title AS CAT_TITLE, a.main_image, a.id_member, a.short_desc
		FROM {db_prefix}adk_down_file AS a, {db_prefix}adk_down_cat AS c
		WHERE c.id_cat = a.id_cat AND a.id_file = {int:file}',
		array(
			'file' => $id_file,
		)
	);
	
	//No values?
	if($smcFunc['db_num_rows']($sql)== 0)
		fatal_lang_error('adkfatal_require_id_file',false);
		
	$row = $smcFunc['db_fetch_assoc']($sql);
	$smcFunc['db_free_result']($sql);

	//Set information
	$context['important_info'] = array(
		'id_cat' => $row['id_cat'],
		'description' => $row['description'],
		'title' => $row['title'],
		'image' => $row['main_image'],
		'id_file' => $id_file,
		'rest' => count($context['load_attachs']),
		'id_member' => $row['id_member'],
		'cat_title' => $row['CAT_TITLE'],
		'short_desc' => $row['short_desc'],
	);
	
	//maybe? you're wrong with this dowload
	if($user_info['id'] != $row['id_member'] && !allowedTo('adk_downloads_manage'))
		fatal_lang_error('adkfatal_not_permission',false);

	//Verify the last permission
	verifyCatPermissions('addfile',$row['id_cat']);
	
	//Get the id_group
	$id_group = getIdGroup();
	
	//Get all categories
	$sql3 = $smcFunc['db_query']('','
		SELECT c.id_cat, c.title, c.groups_can_add, c.groups_can_view
		FROM {db_prefix}adk_down_cat AS c',
		array(
			'group' => $id_group,
		)
	);
	
	$context['downloads_cat'] = array();
	while($row3 = $smcFunc['db_fetch_assoc']($sql3))
	{
		$value = array_intersect($id_group,explode(',', $row3['groups_can_view']));
		$value2 = array_intersect($id_group,explode(',', $row3['groups_can_add']));
		if (((!empty($value)) || ($user_info['is_admin'])) && ((!empty($value2)) || ($user_info['is_admin']))) {
			$context['downloads_cat'][] = array(
				'id' => $row3['id_cat'],
				'title' => $row3['title'],
			);
		}
	}
		
	$smcFunc['db_free_result']($sql3);
	
	//Set the id cat
	$context['id_cat_'] = $context['important_info']['id_cat'];

	
	//Load Important Info
	$context['sub_template'] = 'add_a_new_download';
	$context['page_title'] = $txt['adkdown_editing'].' ('.$row['title'].')';
	
	// Needed for the WYSIWYG editor.
	getEditor($context['important_info']['description']);

	//Set linktree
	setLinktree('downloads','adkdown_downloads');
	setLinktree('#', $txt['adkdown_editing'].' ('.$row['title'].')', true, true);

}

function EditSaveDownload()
{
	global $modSettings, $sourcedir, $smcFunc, $context, $user_info, $boarddir, $scripturl, $txt, $adkportal, $adkFolder;
	
	//Need this session
	checkSession('post');

	//Set the initial post
	$id_cat = (int)$_POST['cat'];	
	$ex_id_cat = (int)$_POST['ex_id_cat'];
	$id_file = (int)$_POST['id_file'];

	//isAllowedToAdd
	verifyCatPermissions('addfile',$id_cat == $ex_id_cat ? $ex_id_cat : $id_cat);
	
	//The latest
	if($context['user']['is_guest'])
		falta_lang_error('adkfatal_guest_not_add',false);
	
	cleanEditor();
	
	if($user_info['id'] != (int)$_POST['id_member'] && !allowedTo('adk_downloads_manage'))
		fatal_lang_error('adkfatal_not_permission',false);
	
	//Set the other strings
	$title = CleanAdkStrings($_POST['title']);
	$description = CleanAdkStrings($_REQUEST['descript']);
	$short_desc = CleanAdkStrings($_POST['short_desc']);
	$image = !empty($_POST['screen2']) ? CleanAdkStrings($_POST['screen2']) : '';
	
	if (!empty($_FILES['screen']['name']) && $_FILES['screen']['name'] != '')
		$image = processDownloadImage($_FILES['screen']);
	
	
	//Set tdownloads dir
	$DownloadsDir = $boarddir.'/Adk-downloads';
	
	//Can write on this?
	if(!is_writable($DownloadsDir))
		fatal_lang_error('adkfatal_not_writable_dir',false);
	
	//Your title is empty? error
	if(empty($title))
		fatal_lang_error('adkfatal_please_add_a_title',false);
	
	//Get files
	$files = !empty($_POST['download2']) ? $_POST['download2'] : '';
	$files2 = !empty($_POST['download2']) ? 1 : 0;
	$download = !empty($_FILES['download']) ? 1 : 0;
	
	if(empty($download) && empty($files2))
		fatal_lang_error('adkfatal_empty_attach',false);
	
	$smcFunc['db_query']('','
			UPDATE {db_prefix}adk_down_file
			SET title = {string:title}, description = {string:description},
			id_cat = {int:cat}, main_image = {string:image}, short_desc = {string:short}
			WHERE id_file = {int:file}',
			array(
				'title' => $title,
				'description' => $description,
				'cat' => $id_cat,
				'image' => $image,
				'file' => $id_file,
				'short' => $short_desc,
			)
		);
	
	
	if(!empty($_FILES['download']) && $_FILES['download']['name'] != '')
	{	
		$l = 0;
		foreach ($_FILES['download']['tmp_name'] as $n => $dummy)
		{
			if($_FILES['download']['name'][$n] != '')
				$l++;
			
			$filesize = $_FILES['download']['size'][$n];
			
			if (!empty($adkportal['download_max_filesize']) && $filesize > $adkportal['download_max_filesize'])
			{
				@unlink($_FILES['download']['tmp_name'][$n]);
				fatal_lang_error('adkfatal_big_size',false);
			}
		}
		$i = 0;

		if(!empty($l))
		foreach ($_FILES['download']['tmp_name'] as $n => $dummy)
		{
			
			$filesize = $_FILES['download']['size'][$n];
			$original =  $_FILES['download']['name'][$n];
			
			//Nosotros usamos el Download System en Smf Personal, entonces... necesitamos el mismo nombre de archivo ;)
			$filename = $user_info['id'] . '_' . date('d_m_y_g_i_s').$i;
			$i++;
			
			//Move uploaded file
			move_uploaded_file($_FILES['download']['tmp_name'][$n], $DownloadsDir .'/'.  $filename);
			@chmod($DownloadsDir .  $filename, 0644);
			
			//Insert file
			$smcFunc['db_insert'](
				'insert',
				'{db_prefix}adk_down_attachs',
				array(
					'id_file' => 'int',
					'filename' => 'text',
					'filesize' => 'text',
					'orginalfilename' => 'text',
				),
				array($id_file, $filename, $filesize, $original),
				array('id_attach')
			);
		}
	}
	
	//DELETE files selected
	if(!empty($files))
	{
		$delete = $_POST['download2'];
		$t = 0;
		$count = count($delete); 
		
		foreach($delete AS $n => $dummy)
		{
			$id = $n;
			$sql = $smcFunc['db_query']('','
				SELECT filename FROM {db_prefix}adk_down_attachs
				WHERE id_attach = {int:attach} LIMIT 1',
				array(
					'attach' => $id,
				)
			);

			$row = $smcFunc['db_fetch_assoc']($sql);
			$smcFunc['db_free_result']($sql);
			@unlink($adkFolder['eds'].'/'.$row['filename']);
			
			$smcFunc['db_query']('','
				DELETE FROM {db_prefix}adk_down_attachs
				WHERE id_attach = {int:attach} LIMIT 1',
				array(
					'attach' => $id,
				)
			);
			
			$t++;
		}
	}
	
	//Update Category
	if($id_cat !== $ex_id_cat) {
		TotalCategoryUpdate($id_cat);
		TotalCategoryUpdate($ex_id_cat);
	}
			
	redirectexit('action=downloads;sa=view;down='.$id_file);		
}

function AdkViewDownload()
{
	global $sourcedir, $txt, $modSettings, $context, $user_info, $scripturl, $smcFunc, $boardurl, $memberContext;
	
	if(!empty($_REQUEST['down']) && is_numeric($_REQUEST['down']))
		$id_view = (int)$_REQUEST['down'];
	else
		fatal_lang_error('adkfatal_require_id_file',false);
	
	//Get All information
	$sql = $smcFunc['db_query']('','
		SELECT
		p.id_file, p.id_cat, 
	 	p.approved, p.views, p.title, p.id_member, m.real_name, p.date, p.description, p.main_image, p.id_topic, c.error,
	   	c.title AS CAT_TITLE, c.id_parent, p.totaldownloads,  p.lastdownload, m.avatar, c.id_parent, t.id_board,
			IFNULL(a.id_attach, 0) AS id_attach, a.filename, a.attachment_type,
			IFNULL(t.id_topic, p.id_topic) AS id_topic
		FROM ({db_prefix}adk_down_file as p,  {db_prefix}adk_down_cat AS c)
		LEFT JOIN {db_prefix}members AS m ON  (p.id_member = m.id_member)
		LEFT JOIN {db_prefix}attachments AS a ON (a.id_member = m.id_member)
		LEFT JOIN {db_prefix}topics AS t ON (t.id_topic = p.id_topic)
		WHERE p.id_file = {int:id} AND p.id_cat = c.id_cat LIMIT 1',
		array(
			'id' => $id_view,
		)
	);
	
	//If no query
	if($smcFunc['db_num_rows']($sql) == 0)
		fatal_lang_error('adkfatal_this_download_not_exist',false);
	
	$row = $smcFunc['db_fetch_assoc']($sql);
	$smcFunc['db_free_result']($sql);

	if(!empty($row['error']))
		fatal_lang_error('adkfatal_this_download_not_exist',false);
	
	//Verify if we can view
	verifyCatPermissions('view',$row['id_cat']);

	//It's not approved? (dissaproved :|)
	if($row['approved'] == 0 && $user_info['id'] != $row['id_member'] && !allowedTo('adk_downloads_manage'))
		fatal_lang_error('adkfatal_this_download_not_approved',false);
	
	//$height and width
	$width = 75;
	$height = 75;
	$context['adkDownloadInformation'] = array(
		'id_file' => $row['id_file'],
		'id_cat' => $row['id_cat'],
		'views' => $row['views'],
		'file_title' => $row['title'],
		'id_member' => $row['id_member'],
		'member' => '<a href="'.$scripturl.'?action=profile;u='.$row['id_member'].'">'.$row['real_name'].'</a>',
		'date' => timeformat($row['date'], '%d/%m/%Y (%I:%M:%S %p)'),
		'description' => parse_bbc($row['description']),
		'cat' => '<a href="'.$scripturl.'?action=downloads;cat='.$row['id_cat'].'">'.$row['CAT_TITLE'].'</a>',
		'id_parent' => $row['id_parent'],
		'totaldownloads' => $row['totaldownloads'],
		'lastdownload' => empty($row['lastdownload']) ? $txt['adkdown_never'] : timeformat($row['lastdownload'], '%d/%m/%Y'),
		'approved' => $row['approved'],
		'image' => $row['main_image'],
		'id_topic' => $row['id_topic'],
		'id_board' => $row['id_board'],
		'topic_exists' => checkTopicDownload($row['id_topic']),
		'avatar' => $row['avatar'] == '' ? ($row['id_attach'] > 0 ? '<img title="'.$row['real_name'].'" style="vertical-align: middle;" width="'.$width.'" height="'.$height.'" src="' . (empty($row['attachment_type']) ? $scripturl . '?action=dlattach;attach=' . $row['id_attach'] . ';type=avatar' : $modSettings['custom_avatar_url'] . '/' . $row['filename']) . '" alt="" border="0" />' : '') : (stristr($row['avatar'], 'http://') ? '<img title="'.$row['real_name'].'" style="vertical-align: middle;" width="'.$width.'" height="'.$height.'"src="' . $row['avatar'] . '" alt="" border="0" />' : '<img title="'.$row['real_name'].'" style="vertical-align: middle;" width="'.$width.'" height="'.$height.'"src="' . $modSettings['avatar_url'] . '/' . $smcFunc['htmlspecialchars']($row['avatar']) . '" alt="" border="0" />'),
	);
	
	//Set the lintkree
	setLinktree('downloads','adkdown_downloads');
	CheckCatParent($row['id_parent']);
	setLinktree('downloads;cat=' . $row['id_cat'], $row['CAT_TITLE'], false, true);
	setLinktree('downloads;sa=view;down=' . $row['id_file'], $row['title'], false, true);
	
	$context['sub_template']  = 'adk_view_file';
	$context['page_title'] = $row['title'];
	
	//Views + 1 if i can
	if(empty($_SESSION['adk_download_view'][$id_view])){
		
		$smcFunc['db_query']('', "UPDATE {db_prefix}adk_down_file
			SET views = views + 1 WHERE id_file = {int:id} LIMIT 1",
			array(
				'id' => $id_view,
			)
		);

		$_SESSION['adk_download_view'][$id_view] = true;
	}
		
	//Load Attachments
	$context['load_attachments'] = getAttachments($id_view);
	
	//Please, load all info member.
	loadMemberData($context['adkDownloadInformation']['id_member'], false, 'profile');
	loadMemberContext($context['adkDownloadInformation']['id_member']);
	
	//Finaly, make my context string ;)
	$context['member'] = $memberContext[$context['adkDownloadInformation']['id_member']];
	
}

function AdkDownloadFile()
{
	global $modSettings, $txt, $context, $smcFunc, $user_info, $boarddir, $adkFolder;

	if(!empty($_REQUEST['id']))
		$id = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_require_id_file',false);
	
	$sql = $smcFunc['db_query']('','
		SELECT a.id_file, a.id_attach, a.filename, a.orginalfilename, d.id_cat, d.id_file, d.id_member, d.approved
		FROM {db_prefix}adk_down_attachs AS a, {db_prefix}adk_down_file AS d
		INNER JOIN {db_prefix}members AS m ON (m.id_member = d.id_member)
		WHERE id_attach = {int:a} AND a.id_file = d.id_file',
		array(
			'a' => $id,
		)
	);
	
	$row = $smcFunc['db_fetch_assoc']($sql);

	//Empty file?
	if($smcFunc['db_num_rows']($sql) == 0)
		fatal_lang_error('adkfatal_require_id_file', false);

	$smcFunc['db_free_result']($sql);
	
	if($row['approved'] == 0 && $user_info['id'] != $row['id_member'] && !allowedTo('adk_downloads_manage'))
		fatal_lang_error('adkfatal_this_download_not_approved',false);
	
	verifyCatPermissions('view',$row['id_cat']);
	
	$last = time();
	
	
	$smcFunc['db_query']('', "UPDATE {db_prefix}adk_down_file
		SET totaldownloads = totaldownloads + 1, lastdownload = {int:l} WHERE id_file = {int:id} LIMIT 1",
		array(
			'id' => $row['id_file'],
			'l' => $last,
		)
	);

		
	$real_filename = $row['orginalfilename'];
	$filename = $adkFolder['eds'].'/'.$row['filename'];
	
	$ext = explode('.',$real_filename);
	$file_ext = $ext[count($ext) -1];
	
	// This is done to clear any output that was made before now. (would use ob_clean(), but that's PHP 4.2.0+...)
	ob_end_clean();
	if (!empty($modSettings['enableCompressedOutput']) && @version_compare(PHP_VERSION, '4.2.0') >= 0 && @filesize($filename) <= 4194304 && in_array($file_ext, array('txt', 'html', 'htm', 'js', 'doc', 'pdf', 'docx', 'rtf', 'css', 'php', 'log', 'xml', 'sql', 'c', 'java')))
		@ob_start('ob_gzhandler');
	else
	{
		ob_start();
		header('Content-Encoding: none');
	}

	// No point in a nicer message, because this is supposed to be an attachment anyway...
	if (!file_exists($filename))
	{
		adkLanguage('Errors');

		header('HTTP/1.0 404 ' . $txt['attachment_not_found']);
		header('Content-Type: text/plain; charset=' . (empty($context['character_set']) ? 'ISO-8859-1' : $context['character_set']));

		// We need to die like this *before* we send any anti-caching headers as below.
		die('404 - ' . $txt['attachment_not_found']);
	}

	// If it hasn't been modified since the last time this attachement was retrieved, there's no need to display it again.
	if (!empty($_SERVER['HTTP_IF_MODIFIED_SINCE']))
	{
		list($modified_since) = explode(';', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
		if (strtotime($modified_since) >= filemtime($filename))
		{
			ob_end_clean();

			// Answer the question - no, it hasn't been modified ;).
			header('HTTP/1.1 304 Not Modified');
			exit;
		}
	}

	// Check whether the ETag was sent back, and cache based on that...
	$eTag = '"' . substr($_REQUEST['id'] . $real_filename . filemtime($filename), 0, 64) . '"';
	if (!empty($_SERVER['HTTP_IF_NONE_MATCH']) && strpos($_SERVER['HTTP_IF_NONE_MATCH'], $eTag) !== false)
	{
		ob_end_clean();

		header('HTTP/1.1 304 Not Modified');
		exit;
	}

	// Send the attachment headers.
	header('Pragma: ');
	if (!$context['browser']['is_gecko'])
		header('Content-Transfer-Encoding: binary');

	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 525600 * 60) . ' GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)) . ' GMT');
	header('Accept-Ranges: bytes');
	header('Connection: close');
	header('ETag: ' . $eTag);

	// IE 6 just doesn't play nice. As dirty as this seems, it works.
	if ($context['browser']['is_ie6'] && isset($_REQUEST['image']))
		unset($_REQUEST['image']);
		
	// Make sure the mime type warrants an inline display.
	elseif (isset($_REQUEST['image']) && !empty($mime_type) && strpos($mime_type, 'image/') !== 0)
		unset($_REQUEST['image']);
	
	// Does this have a mime type?
	elseif (!empty($mime_type) && (isset($_REQUEST['image']) || !in_array($file_ext, array('jpg', 'gif', 'jpeg', 'x-ms-bmp', 'png', 'psd', 'tiff', 'iff'))))
		header('Content-Type: ' . strtr($mime_type, array('image/bmp' => 'image/x-ms-bmp')));
		
	else
	{
		header('Content-Type: ' . ($context['browser']['is_ie'] || $context['browser']['is_opera'] ? 'application/octetstream' : 'application/octet-stream'));
		if (isset($_REQUEST['image']))
			unset($_REQUEST['image']);
	}

	// Convert the file to UTF-8, cuz most browsers dig that.
	$utf8name = !$context['utf8'] && function_exists('iconv') ? iconv($context['character_set'], 'UTF-8', $real_filename) : (!$context['utf8'] && function_exists('mb_convert_encoding') ? mb_convert_encoding($real_filename, 'UTF-8', $context['character_set']) : $real_filename);
	$fixchar = create_function('$n', '
		if ($n < 32)
			return \'\';
		elseif ($n < 128)
			return chr($n);
		elseif ($n < 2048)
			return chr(192 | $n >> 6) . chr(128 | $n & 63);
		elseif ($n < 65536)
			return chr(224 | $n >> 12) . chr(128 | $n >> 6 & 63) . chr(128 | $n & 63);
		else
			return chr(240 | $n >> 18) . chr(128 | $n >> 12 & 63) . chr(128 | $n >> 6 & 63) . chr(128 | $n & 63);');

	$disposition = !isset($_REQUEST['image']) ? 'attachment' : 'inline' ;

	// Different browsers like different standards...
	if ($context['browser']['is_firefox'])
		header('Content-Disposition: ' . $disposition . '; filename*=UTF-8\'\'' . rawurlencode(preg_replace_callback('~&#(\d{3,8});~', 'fixchar__callback', $utf8name)));

	elseif ($context['browser']['is_opera'])
		header('Content-Disposition: ' . $disposition . '; filename="' . preg_replace_callback('~&#(\d{3,8});~', 'fixchar__callback', $utf8name) . '"');

	elseif ($context['browser']['is_ie'])
		header('Content-Disposition: ' . $disposition . '; filename="' . urlencode(preg_replace_callback('~&#(\d{3,8});~', 'fixchar__callback', $utf8name)) . '"');

	else
		header('Content-Disposition: ' . $disposition . '; filename="' . $utf8name . '"');

	// If this has an "image extension" - but isn't actually an image - then ensure it isn't cached cause of silly IE.
	if (!isset($_REQUEST['image']) && in_array($file_ext, array('gif', 'jpg', 'bmp', 'png', 'jpeg', 'tiff')))
		header('Cache-Control: no-cache');
	else
		header('Cache-Control: max-age=' . (525600 * 60) . ', private');

	if (empty($modSettings['enableCompressedOutput']) || filesize($filename) > 4194304)
		header('Content-Length: ' . filesize($filename));

	// Try to buy some time...
	@set_time_limit(600);

	// Recode line endings for text files, if enabled.
	if (!empty($modSettings['attachmentRecodeLineEndings']) && !isset($_REQUEST['image']) && in_array($file_ext, array('txt', 'css', 'htm', 'html', 'php', 'xml')))
	{
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Windows') !== false)
			$callback = create_function('$buffer', 'return preg_replace(\'~[\r]?\n~\', "\r\n", $buffer);');
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') !== false)
			$callback = create_function('$buffer', 'return preg_replace(\'~[\r]?\n~\', "\r", $buffer);');
		else
			$callback = create_function('$buffer', 'return preg_replace(\'~[\r]?\n~\', "\n", $buffer);');
	}

	// Since we don't do output compression for files this large...
	if (filesize($filename) > 4194304)
	{
		// Forcibly end any output buffering going on.
		if (function_exists('ob_get_level'))
		{
			while (@ob_get_level() > 0)
				@ob_end_clean();
		}
		else
		{
			@ob_end_clean();
			@ob_end_clean();
			@ob_end_clean();
		}

		$fp = fopen($filename, 'rb');
		while (!feof($fp))
		{
			if (isset($callback))
				echo $callback(fread($fp, 8192));
			else
				echo fread($fp, 8192);
			flush();
		}
		fclose($fp);
	}
	// On some of the less-bright hosts, readfile() is disabled.  It's just a faster, more byte safe, version of what's in the if.
	elseif (isset($callback) || @readfile($filename) == null)
		echo isset($callback) ? $callback(file_get_contents($filename)) : file_get_contents($filename);

	obExit(false);
}


function DeleteDownload()
{
	global $boarddir, $user_info, $smcFunc, $sourcedir, $adkFolder;
	
	//Check the session
	checkSession('get');
	
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_require_id_file',false);
	
	//Select some important info	
	$sql = $smcFunc['db_query']('','
		SELECT id_cat, id_member, id_topic
		FROM {db_prefix}adk_down_file
		WHERE id_file = {int:file}',
		array(
			'file' => $id,
		)
	);
	
	$row = $smcFunc['db_fetch_assoc']($sql);
	$id_cat = $row['id_cat'];
	$id_topic = $row['id_topic'];

	$smcFunc['db_free_result']($sql);
	
	//mmm May be you don't have the right permissions to delete this.
	if($user_info['id'] != $row['id_member'] && !allowedTo('adk_downloads_manage'))
		fatal_lang_error('adkfatal_not_permission',false);
	
	//Delete entry from adk_down_file
	deleteEntry('adk_down_file', 'id_file = {int:file}', array('file' => $id));

	//Let's load filenames
	$sql = $smcFunc['db_query']('','
		SELECT filename FROM {db_prefix}adk_down_attachs
		WHERE id_file = {int:file}',
		array(
			'file' => $id,
		)
	);
	
	//Unlink if file_exists
	while($row = $smcFunc['db_fetch_assoc']($sql))
		if(file_exists($adkFolder['eds'].'/'.$row['filename']))
			@unlink($adkFolder['eds'].'/'.$row['filename']);
	
	
	//Delete attachs
	deleteEntry('adk_down_attachs', 'id_file = {int:file}', array('file' => $id));

	//Delete topic
	if(!empty($id_topic)){

		//Load Main File to removeTopic
		require_once($sourcedir.'/RemoveTopic.php');

		//Ajam.... it's done
		removeTopics(array($id_topic));
	}

	//Update category
	TotalCategoryUpdate($id_cat);
	
	redirectexit('action=downloads;cat='.$id_cat);
}

function UnApproveDownload()
{
	global $smcFunc, $context;
	
	//I can view this section... only if i can the right permissions
	isAllowedTo('adk_downloads_manage');
	
	//Get the session so
	checkSession('get');
	
	//Get the $id
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_require_id_file',false);

	//Get the approved
	$approved = getApprovedByFile($id);

	//If a = 0... don't make anything and redirect
	if($approved == 0)
		if(!empty($_REQUEST['return']) && $_REQUEST['return'] == 'admin')
			redirectexit('action=admin;area=adkdownloads;sa=approvedownloads;'.$context['session_var'].'='.$context['session_id']);
		else
			redirectexit('action=downloads;sa=view;down='.$id);
	
	//Update it
	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_down_file
		SET approved = {int:a}
		WHERE id_file = {int:file}',
		array(
			'a' => 0,
			'file' => $id,
		)
	);

	//Get the id cat
	$id_cat = getCatByFile($id);

	//Update category
	TotalCategoryUpdate($id_cat);

	if(!empty($_REQUEST['return']) && $_REQUEST['return'] == 'admin')
		redirectexit('action=admin;area=adkdownloads;sa=approvedownloads;'.$context['session_var'].'='.$context['session_id']);
	else
		redirectexit('action=downloads;sa=view;down='.$id);

}

function ApproveDownload()
{
	global $smcFunc, $modSettings, $sourcedir, $txt, $context, $boardurl;
	
	//Right permissions?
	isAllowedTo('adk_downloads_manage');
	
	//get the session please
	checkSession('get');

	//Get the id
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_require_id_file',false);

	$approved = getApprovedByFile($id);

	//if it's approved... don't make anything else
	if($approved == 1)
		if(!empty($_REQUEST['return']) && $_REQUEST['return'] == 'admin')
			redirectexit('action=admin;area=adkdownloads;sa=approvedownloads;'.$context['session_var'].'='.$context['session_id']);
		else
			redirectexit('action=downloads;sa=view;down='.$id);
	
	//Update it
	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_down_file
		SET approved = {int:a}
		WHERE id_file = {int:file}',
		array(
			'a' => 1,
			'file' => $id,
		)
	);

	//get id category
	$id_cat = getCatByFile($id);

	//And update category
	TotalCategoryUpdate($id_cat);
	
	$s = $smcFunc['db_query']('','
		SELECT a.id_topic, c.id_board, c.locktopic, a.title, a.description, a.id_member, a.id_cat, c.id_cat, a.main_image
		FROM {db_prefix}adk_down_file AS a, {db_prefix}adk_down_cat AS c
		WHERE a.id_file = {int:file} AND a.id_cat = c.id_cat',
		array(
			'a' => 1,
			'file' => $id,
		)
	);

	$row = $smcFunc['db_fetch_assoc']($s);
	$smcFunc['db_free_result']($s);
	$id_board = $row['id_board'];
	$id_topic = $row['id_topic'];
	$locktopic = $row['locktopic'];
	$title = $row['title'];
	$id_cat = $row['id_cat'];
	$description = $row['description'];
	$id_member = $row['id_member'];
	$image = $row['main_image'];
	
	TotalCategoryUpdate($id_cat);
	require_once($sourcedir . '/Subs-Post.php');
	
	if(!empty($id_board) && empty($id_topic))
	{
		setTopic(array(
			'body' => $description,
			'subject' => $title,
			'id_file' => $id,
			'image' => $image,
			'id_board' => $id_board,
			'locked' => $locktopic,
			'id_member' => $id_member,

		));
	}
	
	global $adkportal;
	if(!empty($adkportal['download_enable_sendpmApprove']) && !empty($adkportal['download_sendpm_body']) &&!empty($adkportal['download_sendpm_userId']))
	{
		
		//Load this members and send MP
		$select = $smcFunc['db_query']('','
			SELECT member_name, real_name 
			FROM {db_prefix}members 
			WHERE id_member = {int:member}',
			array(
				'member' => $adkportal['download_sendpm_userId'],
			)
		);
		$member = $smcFunc['db_fetch_assoc']($select);
		$smcFunc['db_free_result']($select);
		
		$from = array(
		'id' => $adkportal['download_sendpm_userId'],
		'name' => $member['real_name'],
		'username' => $member['member_name']
		);
		
		$select = $smcFunc['db_query']('','
			SELECT a.id_member, a.title, m.id_member, m.real_name, m.member_name
			FROM {db_prefix}members AS m, {db_prefix}adk_down_file AS a
			WHERE a.id_member = m.id_member AND a.id_file = {int:file}',
			array(
				'file' => $id,
			)
		);
		
		$member2 = $smcFunc['db_fetch_assoc']($select);
		$smcFunc['db_free_result']($select);
		
		
						
		$recs = array(
			'to' => array($member2['id_member']),
			'bcc' => array()
		);
		
		$subject = $member2['title'].' '.$txt['adkdown_send_pm'];
		$message = $adkportal['download_sendpm_body'];
					
						
		sendpm($recs, $subject, $message, false, $from);
	}

	//redirect and end :)
	if(!empty($_REQUEST['return']) && $_REQUEST['return'] == 'admin')
		redirectexit('action=admin;area=adkdownloads;sa=approvedownloads;'.$context['session_var'].'='.$context['session_id']);
	else
		redirectexit('action=downloads;sa=view;down='.$id);


}

function AdkViewStats()
{
	global $smcFunc, $context, $txt, $scripturl;
	
	//Load Important Info
	$context['sub_template'] = 'view_stats';
	$context['page_title'] = $txt['adkdown_view_stats'];
	//End
	
	//Load Stats
	$context['last_downloads'] = LastTenDownloads();
	$context['most_downloads'] = MostDownloads();
	$context['uploaders'] = TopUploadersDownload();
	$context['most_viewed'] = MostViewed();
	
	//The First Link Tree
	setLinktree('downloads','adkdown_downloads');
	setLinktree('downloads;sa=viewstats', 'adkdown_view_stats');
}

function UpCat()
{
	global $smcFunc, $scripturl, $context;
	
	if(!empty($_REQUEST['id']))
		$id = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_require_catid',false);

	//Check if we have permissions
	isAllowedTo('adk_downloads_manage');
	
	$sql = $smcFunc['db_query']('','
		SELECT roworder, id_parent FROM {db_prefix}adk_down_cat
		WHERE id_cat = {int:cat}',
		array(
			'cat' => $id,
		)
	);

	//Invalid cat? drop an error
	if($smcFunc['db_num_rows']($sql) == 0)
		fatal_lang_error('adkfatal_require_catid',false);
	
	//Save information
	$row = $smcFunc['db_fetch_assoc']($sql);
	
	$smcFunc['db_free_result']($sql);
	
	//If this category is in the first position? idiot -.-
	if($row['roworder'] == 1){
		if($row['id_parent'] == 0)
			redirectexit('action=downloads');
		else
			redirectexit('action=downloads;cat='.$row['id_parent']);
	}
	
	//UPDATE - 1
	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_down_cat
		SET roworder = roworder - 1
		WHERE id_cat = {int:cat}',
		array(
			'cat' => $id,
		)
	);
	
	//Rest update + 1
	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_down_cat
		SET roworder = roworder + 1
		WHERE roworder = {int:row} AND id_cat <> {int:cat} AND id_parent = {int:parent}',
		array(
			'row' => $row['roworder'] - 1,
			'cat' => $id,
			'parent' => $row['id_parent'],
		)
	);
	
	if($row['id_parent'] == 0)
		redirectexit('action=downloads');
	else
		redirectexit('action=downloads;cat='.$row['id_parent']);
	
}

function DownCat()
{
	global $smcFunc, $scripturl, $context;
	
	if(!empty($_REQUEST['id']))
		$id = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_require_catid',false);

	//Check if we have permissions
	isAllowedTo('adk_downloads_manage');
	
	$sql = $smcFunc['db_query']('','
		SELECT roworder, id_parent FROM {db_prefix}adk_down_cat
		WHERE id_cat = {int:cat}',
		array(
			'cat' => $id,
		)
	);
	
	$row = $smcFunc['db_fetch_assoc']($sql);
	
	//Invalid cat? drop an error
	if($smcFunc['db_num_rows']($sql) == 0)
		fatal_lang_error('adkfatal_require_catid',false);
		
	$smcFunc['db_free_result']($sql);
	
	//If this category is in the last position? idiot -.-
	$d = $smcFunc['db_query']('','
		SELECT id_cat 
		FROM {db_prefix}adk_down_cat
		WHERE roworder > {int:order}',
		array(
			'order' => $row['roworder'],
		)
	);
	
	$r = $smcFunc['db_fetch_assoc']($d);
	
	if(empty($r)){
		if($row['id_parent'] == 0)
			redirectexit('action=downloads');
		else
			redirectexit('action=downloads;cat='.$row['id_parent']);
	}
	
	
	//UPDATE + 1
	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_down_cat
		SET roworder = roworder + 1
		WHERE id_cat = {int:cat}',
		array(
			'cat' => $id,
		)
	);
	
	//Rest update - 1
	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_down_cat
		SET roworder = roworder - 1
		WHERE roworder = {int:row} AND id_cat <> {int:cat} AND id_parent = {int:parent}',
		array(
			'row' => $row['roworder'] + 1,
			'cat' => $id,
			'parent' => $row['id_parent'],
		)
	);
	
	if($row['id_parent'] == 0)
		redirectexit('action=downloads');
	else
		redirectexit('action=downloads;cat='.$row['id_parent']);
	
}	

function AdkSearchDownloads()
{
	global $context, $txt, $scripturl;
	
	$context['sub_template'] = 'adk_search';
	$context['page_title'] = $txt['adkdown_search'];
	
	setLinktree('downloads', 'adkdown_downloads');
	setLinktree('downloads;sa=search', 'adkdown_search');
}

function AdkSearchDownloads2()
{
	global $smcFunc, $txt, $context, $scripturl, $user_info, $adkportal;
	
	checkSession('post');
	
	setLinktree('downloads', 'adkdown_downloads');
	setLinktree('downloads;sa=search', 'adkdown_search');
	
	$body = CleanAdkStrings($_POST['search']);

	//Set the permissions
	$allowed_to_manage = allowedTo('adk_downloads_manage') ? 1 : 0;
	
	$sql = $smcFunc['db_query']('','
		SELECT 
			d.id_file, d.title, d.id_member, m.id_member, m.real_name, d.lastdownload, d.totaldownloads, d.date, d.views
			FROM {db_prefix}adk_down_file AS d
			LEFT JOIN {db_prefix}members AS m ON (m.id_member = d.id_member)
			LEFT JOIN {db_prefix}adk_down_cat AS c ON (c.id_cat = d.id_cat)
			WHERE (d.title LIKE "%'.$body.'%" OR d.description LIKE "%'.$body.'%") 
				AND '.$adkportal['query_downloads'],
		array(
			'a' => 1,
			'member' => $user_info['id'],
		)
	);
	
	$context['downloads'] = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql)){
		$context['downloads'][] = array(
			'id' => $row['id_file'],
			'title' => $row['title'],
			'id_member' => $row['id_member'],
			'name' => $row['real_name'],
			'date' => timeformat($row['date'], '%d/%m/%Y (%I:%M:%S %p)'),
			'lastd' => !empty($row['lastdownload']) ? timeformat($row['lastdownload'], '%d/%m/%Y (%I:%M:%S %p)') : $txt['adkdown_never'],
			'totald' => $row['totaldownloads'],
			'viewsd' => $row['views'],
		);
	}

	$smcFunc['db_free_result']($sql);
	
	if(count($context['downloads']) == 1){
		foreach($context['downloads'] AS $id2)
			$id = $id2['id'];
		//redirect to the only download avaiable
		redirectexit('action=downloads;sa=view;down='.$id);
	}
	elseif(count($context['downloads']) == 0){
		$context['sub_template'] = 'adk_search_not';
		$context['page_title'] = $txt['adkdown_search'];
	}
	else{
		$context['sub_template'] = 'adk_search_results';
		$context['page_title'] = $txt['adkdown_search'].': '.$body;
	}

}

?>