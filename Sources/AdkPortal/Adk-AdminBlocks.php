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

function AdkBlocksGeneral()
{
	global $txt, $context, $sourcedir, $boardurl, $settings, $adkFolder;
	
	//Permisos
	isAllowedTo('adk_portal');
	
	//Load my template
	adktemplate('Adk-AdminBlocks');
	
	//Load my language
	adkLanguage('Adk-AdminBlocks');
	
	$subActions = array(
		'checktemplates' => 'LoadBlocksTemplates',
		'newtemplate' => 'createNewTemplate',
		'save_template' => 'saveNewTemplate',
		'edittemplate' => 'editTemplate',
		'save_edit_template' => 'saveEditTemplate',
		'deletetemplate' => 'deleteTemplate',
		'approve_template' => 'approveTemplate',
		'viewblocks' => 'viewblocks',
		'settingsblocks' => 'SettingsBlocks',
		'savesettingsblocks2' => 'SaveSettingsBlocks2',
		'deleteblocks' => 'deleteblocks',
		'editblocks' => 'editblocks',
		'saveeditblocks' => 'saveeditblocks',
		'newblocks' => 'LoadTheNewBlocksToCreate',
		'savenewblocks' => 'savenewblocks',
		'showeditnews' => 'showeditnews',
		'showdeletenews' => 'showdeletenews',
		'showsaveeditnews' => 'showsaveeditnews',
		'createnews' => 'createnews',
		'savecreatenews' => 'savecreatenews',
		'uploadblock' => 'uploadblock',
		'saveuploadblock' => 'saveuploadblock',
		'previewblock' => 'PreviewBlockAdKPortal',
		'permissions' => 'PermissionBlock',
		'savepermissions' => 'SavePermissionBlock',
		'download' => 'DownloadNewBlock',
		'add_smf_block' => 'AddSMFPersonalBlock',
		'shoutboxdeleteall' => 'DeleteShoutboxMessages',
	);
	
	//Load CSS
	$context['html_headers'] .= getCss('admin_adkportal');
	$context['html_headers'] .= javaScript_blocks();
	$context['html_headers'] .= getJs('admin');
	
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['adkmod_block_manage'],
		'description' => $txt['adkblock_first_descrip'],
		'tabs' => array(
			'checktemplates' => array(
				'description' => $txt['adkblock_templates_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/wrench_orange.png" />&nbsp;'.$txt['adkblock_templates'],
			),
			'viewblocks' => array(
				'description' => '',					
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/blocks.png" />&nbsp;'.$txt['adkmod_block_title'],
			),
			'settingsblocks' => array(
				'description' => $txt['adkblock_settings_desc'],					
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/admin.png" />&nbsp;'.$txt['adkmod_block_settings'],
			),
			'newblocks' => array(
				'description' => $txt['adkblock_newblocks_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/createblock.png" />&nbsp;'.$txt['adkmod_block_add'],
			),
			'createnews' => array(
				'description' => $txt['adkblock_news_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$settings['default_theme_url'].'/images/admin/createnews.png" />&nbsp;'.$txt['adkmod_block_add_news'],
			),
			'download' => array(
				'description' => $txt['adkblock_download_personal_desc'],
				'label' => '<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/drive_add.png" />&nbsp;'.$txt['adkmod_block_download'],
			),
				
		),
	);	
	
	
	// Follow the sa or just go to View function
	if (!empty($_GET['sa']) && !empty($subActions[$_GET['sa']]))
		$subActions[@$_GET['sa']]();
	else
		$subActions['checktemplates']();

}

function LoadBlocksTemplates(){

	global $smcFunc, $context, $txt, $modSettings;

	//Set the subtemplate and page_title
	$context['sub_template'] = 'blocks_templates';
	$context['page_title'] = $txt['adkblock_templates'];

	//check the session
	checkSession('get');

	//Load templates
	$sql = $smcFunc['db_query']('','
		SELECT id_template, type, place, enabled
		FROM {db_prefix}adk_blocks_template_admin
		ORDER BY place ASC
	');

	$context['blocks_templates'] = array('default' => array(), 'action' => array(), 'topic' => array(), 'board' => array(), 'page' => array());

	if(!empty($modSettings['blog_enable']))
		$context['blocks_templates']['blog'] = array();

	while($row = $smcFunc['db_fetch_assoc']($sql)){

		$context['blocks_templates'][$row['type']][$row['id_template']] = array(
			'type_string' => $txt['adkblock_template_'.$row['type']],
			'type' => $row['type'],
			'place' => $row['place'] != '' ? ($row['type'].'='.$row['place']) : 'index.php',
			'place_nule' => $row['place'],
			'is_not_default' => $row['type'] != 'default',
			'is_enabled' => !empty($row['enabled']),
		);
	}

	$smcFunc['db_free_result']($sql);
}

function createNewTemplate(){

	global $context, $smcFunc, $txt, $modSettings;

	$context['sub_template'] = 'create_template';
	$context['page_title'] = $txt['adkblock_new_template'];

	//check the session
	checkSession('get');

	//valid styles
	$types = array('action', 'topic', 'board', 'page');

	$type = CleanAdkStrings($_REQUEST['type']);

	if(!empty($modSettings['blog_enable'])){
		$types[] = 'blog';
	}
	elseif(empty($modSettings['blog_enable']) && $type == 'blog')
		fatal_lang_error('adkfatal_enable_blog_please', false);

	//Check if this is the right thing
	if(empty($_REQUEST['type']) || (!empty($_REQUEST['type']) && !in_array($_REQUEST['type'], $types)))
		fatal_lang_error('adkfatal_invalid_type', false);

	//Load previous templates
	$sql = $smcFunc['db_query']('','
		SELECT id_template, type, place
		FROM {db_prefix}adk_blocks_template_admin
		WHERE type = {string:type}',
		array(
			'type' => $type,
		)
	);

	$context['previous_templates'] = array();

	while($row = $smcFunc['db_fetch_assoc']($sql)){
		$context['previous_templates'][$row['id_template']] = array(
			'type' => $row['type'],
			'place' => $row['type'].'='.$row['place'],
		);
	}

	//If type = pages... load pages... if type load actions... load actions :P
	if($type == 'page'){

		$set = $smcFunc['db_query']('','
			SELECT urltext FROM {db_prefix}adk_pages
			ORDER BY urltext'
		);

		while($row = $smcFunc['db_fetch_assoc']($set))
			$context['places'][] = $row['urltext'];

		$smcFunc['db_free_result']($set);
	}
	elseif($type == 'action'){

		$context['places'] = array(
			'forum', 'help', 'search', 'downloads', 'contactus',
			'adk_shoutbox', 'moderate', 'profile', 'blogs',
			'pm', 'mlist', 'logout', 'login', 'calendar',
			'register'
		);
	}

	$smcFunc['db_free_result']($sql);

	$context['type'] = $type;
	$context['types'] = $types;
}

function saveNewTemplate(){

	global $context, $smcFunc;

	checkSession('post');

	//The request type
	$sa_type = CleanAdkStrings($_POST['hidden_type']);

	//If youselected other type
	$type = CleanAdkStrings($_POST['type']);

	//Do you wanna import from a previous template?
	$import_id_template = (int)$_POST['import_from'];

	//Set the place
	$place = CleanAdkStrings($_POST['place']);

	if(empty($place))
		$place = CleanAdkStrings($_POST['place_2']);

	//still empty... error
	if(empty($place))
		fatal_lang_error('adkfatal_empty_place', false);

	//Check if you are trying to create with the same place
	$query = $smcFunc['db_query']('','
		SELECT id_template
		FROM {db_prefix}adk_blocks_template_admin
		WHERE type = {string:type} AND place = {string:place}',
		array(
			'type' => $type, 
			'place' => $place,
		)
	);

	if($smcFunc['db_num_rows']($query) > 0)
		fatal_lang_error('adkfatal_exists_this_template', false);

	$smcFunc['db_free_result']($query);

	//Create the template
	$smcFunc['db_insert'](
		'insert',
		'{db_prefix}adk_blocks_template_admin',
		array('type' => 'text', 'place' => 'text'),
		array($type, $place),
		array('id_template')
	);

	//Get the last id
	$last_id = $smcFunc['db_insert_id']('{db_prefix}adk_blocks_template_admin');

	//If you wanna import the template :D do it
	if(!empty($import_id_template) && $sa_type == $type){

		$sql = $smcFunc['db_query']('','
			SELECT id_template, id_block, columna, orden
			FROM {db_prefix}adk_blocks_template
			WHERE id_template = {int:import_template}',
			array(
				'import_template' => $import_id_template
			)
		);

		while($row = $smcFunc['db_fetch_assoc']($sql)){
			$smcFunc['db_insert']('insert', '{db_prefix}adk_blocks_template',
				array('id_template' => 'int', 'id_block' => 'int', 'columna' => 'int', 'orden' => 'int'),
				array($last_id, $row['id_block'], $row['columna'], $row['orden']),
				array()
			);
		}

		$smcFunc['db_free_result']($sql);
	}

	redirectexit('action=admin;area=blocks;sa=edittemplate;id='.$last_id.';'.$context['session_var'].'='.$context['session_id']);
}

function editTemplate(){

	global $smcFunc, $context, $txt;

	checkSession('get');

	if(empty($_REQUEST['id']))
		fatal_lang_error('adkfatal_template_invalid_id', false);

	//Get the id_template
	$id_template = (int)$_REQUEST['id'];

	//Load Template
	$set = $smcFunc['db_query']('','
		SELECT id_template, type, place
		FROM {db_prefix}adk_blocks_template_admin
		WHERE id_template = {int:id_template}',
		array(
			'id_template' => $id_template
		)
	);

	list($id_template, $type, $place) = $smcFunc['db_fetch_row']($set);

	$smcFunc['db_free_result']($set);

	//Set in a context variable
	$context += array(
		'id_template' => $id_template,
		'type' => $type,
		'place' => $place
	);

	//Load Blocks
	$sql = $smcFunc['db_query']('','
		SELECT b.id, b.name, b.type AS block_type, b.img, 
			IFNULL(t.id_block, 0) AS id_block_template, t.columna, t.orden
		FROM {db_prefix}adk_blocks AS b
		LEFT JOIN {db_prefix}adk_blocks_template AS t ON (t.id_block = b.id AND t.id_template = {int:template})
		ORDER BY t.orden',
		array(
			'template' => $id_template,
		)
	);

	$context['blocks_admin'] = array();

	while($row = $smcFunc['db_fetch_assoc']($sql)){
		$context['blocks_admin'][getPosition($row['columna'])][$row['id']] = array(
			'id' => $row['id'],
			'name' => $row['name'],
			'type' => $row['block_type'],
			'img' => $row['img'],
			'columna' => !empty($row['columna']) ? $row['columna'] : 6,
			'orden' => !empty($row['orden']) ? $row['orden'] : 1,
		);
	}

	$smcFunc['db_free_result']($sql);

		//Set title and tmeplate
	$context['sub_template'] = 'edit_the_template';
	$context['page_title'] = $txt['adkblock_edit_template'].' {'.$type. (!empty($place) ? '='.$place : '').'}';
}


function saveEditTemplate(){

	global $smcFunc, $context;

	checkSession('post');

	$id_template = (int)$_POST['id_template'];

	//Delete previous entry
	deleteEntry('adk_blocks_template', 'id_template = {int:template}', array('template' => $id_template));

	saveBlockSettings('left', $id_template);
	saveBlockSettings('center', $id_template);
	saveBlockSettings('right', $id_template);
	saveBlockSettings('top', $id_template);
	saveBlockSettings('bottom', $id_template);
	saveBlockSettings('admin', $id_template);

	redirectexit('action=admin;area=blocks;'.$context['session_var'].'='.$context['session_id']);
}

function deleteTemplate(){

	global $context;

	checkSession('get');

	if(empty($_REQUEST['id']))
		fatal_lang_error('adkfatal_template_invalid_id', false);

	if(templateIsPortal((int)$_REQUEST['id']))
		fatal_lang_error('adkfatal_you_can_not_modify_portal_template', false);

	$id_template = (int)$_REQUEST['id'];

	deleteEntry('adk_blocks_template_admin', 'id_template = {int:template}', array('template' => $id_template));
	deleteEntry('adk_blocks_template', 'id_template = {int:template}', array('template' => $id_template));


	redirectexit('action=admin;area=blocks;'.$context['session_var'].'='.$context['session_id']);
}

function approveTemplate(){

	global $context, $smcFunc;

	checkSession('get');

	//Set error if empty id
	if(empty($_REQUEST['id']))
		fatal_lang_error('adkfatal_template_invalid_id', false);

	if(templateIsPortal((int)$_REQUEST['id']))
		fatal_lang_error('adkfatal_you_can_not_modify_portal_template', false);

	$value = !empty($_REQUEST['value']) ? 1 : 0;

	$id_template = (int)$_REQUEST['id'];

	$smcFunc['db_query']('','UPDATE {db_prefix}adk_blocks_template_admin SET enabled = {int:enabled} WHERE id_template = {int:id_template}', array('enabled' => $value, 'id_template' => $id_template));

	redirectexit('action=admin;area=blocks;'.$context['session_var'].'='.$context['session_id']);

}

function viewblocks()
{
	global $context, $txt, $adkportal;

	checkSession('get');
	$context['sub_template']  = 'viewblocks';

	//Set the page title
	$context['page_title'] = $txt['adkmod_block_title'];
	
	//Load my blocks
	$context += loadBlocks();

	$context['can_use_template_blocks'] = true;

}

function SettingsBlocks()
{
	global $context, $txt;
	
	checkSession('get');
	
	$context['sub_template'] = 'settings_blocks';
	$context['page_title'] = $txt['adkmod_block_settings'];
	
	//Load Jump To
	loadJumpTosmf1ByAlper();

	$context['memberadk'] = loadAdkGroups("min_posts = '-1' AND id_group <> '1'");
}
function DeleteShoutboxMessages()
{
	checkSession('get');
	
	global $smcFunc, $context;
	
	$smcFunc['db_query']('','DELETE FROM {db_prefix}adk_shoutbox');
	
	redirectexit('action=admin;area=blocks;sa=settingsblocks;'.$context['session_var'].'='.$context['session_id']);
}


function SaveSettingsBlocks2()
{
	global $adkFolder;

	checkSession('post');
	
	//A custom function :D
	if (isset($_POST['auto_news_id_boards'])){
		foreach ($_POST['auto_news_id_boards'] as $i => $v)
		{
			 if (!is_numeric($_POST['auto_news_id_boards'][$i])) 
				unset($_POST['auto_news_id_boards'][$i]);
			else
				$_POST['auto_news_id_boards'][$i] = (int)$_POST['auto_news_id_boards'][$i];
		}
	$auto_news_id_boards = implode(',', $_POST['auto_news_id_boards']);
	}
	else
		$auto_news_id_boards = 0;
	
	$adk_news = (int)$_POST['adk_news'];
	$auto_news_limit_body = (int)$_POST['auto_news_limit_body'];
	$auto_news_limit_topics = (int)$_POST['auto_news_limit_topics'];
	$auto_news_size_img = (int)$_POST['auto_news_size_img'];
	$top_poster = (int)$_POST['top_poster'];
	$ultimos_mensajes = (int)$_POST['ultimos_mensajes'];
	$adk_vertically_who = !empty($_POST['adk_vertically_who']) ? 1 : 0;
	$adk_bookmarks_autonews = !empty($_POST['adk_bookmarks_autonews']) ? 1 : 0;
	$adk_bookmarks_news = !empty($_POST['adk_bookmarks_news']) ? 1 : 0;
	$adk_disable_autor = !empty($_POST['adk_disable_autor']) ? 1 : 0;
	$noavatar_top_poster = !empty($_POST['noavatar_top_poster']) ? 1 : 0;
	$adk_two_column = !empty($_POST['adk_two_column']) ? 1 : 0;


	if ((empty($adk_news)) || (empty($ultimos_mensajes)) || (empty($auto_news_limit_topics)) || (empty($top_poster)))
		fatal_lang_error('adkfatal_not_zero_data',false);

	
	if (isset($_POST['shout_allowed_groups_view'])){
		foreach ($_POST['shout_allowed_groups_view'] as $i => $v)
		{
			 if (!is_numeric($_POST['shout_allowed_groups_view'][$i])) 
				unset($_POST['shout_allowed_groups_view'][$i]);
			else
				$_POST['shout_allowed_groups_view'][$i] = (int)$_POST['shout_allowed_groups_view'][$i];
		}
	$shout_allowed_groups_view = implode(',', $_POST['shout_allowed_groups_view']);
	}
	else
		$shout_allowed_groups_view = 1;
	
	if (isset($_POST['shout_allowed_groups'])){
		foreach ($_POST['shout_allowed_groups'] as $i => $v)
		{
			 if (!is_numeric($_POST['shout_allowed_groups'][$i])) 
				unset($_POST['shout_allowed_groups'][$i]);
			else
				$_POST['shout_allowed_groups'][$i] = (int)$_POST['shout_allowed_groups'][$i];
		}
	$shout_allowed_groups = implode(',', $_POST['shout_allowed_groups']);
	}
	else
		$shout_allowed_groups = 1;
	
	updateSettingsAdkPortal( 
		array(
			'shout_allowed_groups' => $shout_allowed_groups,
			'shout_allowed_groups_view' => $shout_allowed_groups_view,
		)
	);
	
	//Permissions, For some errors ;)
	global $boarddir;
	@chmod($adkFolder['main'].'/shoutbox', 0755);
	@chmod($adkFolder['main'].'/shoutbox/shoutbox.php', 0644);
	@chmod($adkFolder['main'].'/shoutbox/shoutbox.js', 0644);

	updateSettingsAdkPortal( 
		array(
			'adk_news' => $adk_news,
			'auto_news_limit_body' => $auto_news_limit_body,
			'auto_news_limit_topics' => $auto_news_limit_topics,
			'auto_news_size_img' => $auto_news_size_img,
			'top_poster' => $top_poster,
			'ultimos_mensajes' => $ultimos_mensajes,
			'adk_vertically_who' => $adk_vertically_who,
			'auto_news_id_boards' => $auto_news_id_boards,
			'adk_bookmarks_news' => $adk_bookmarks_news,
			'adk_bookmarks_autonews' => $adk_bookmarks_autonews,
			'adk_disable_autor' => $adk_disable_autor,
			'noavatar_top_poster' => $noavatar_top_poster,
			'adk_two_column' => $adk_two_column,
		)
	);
	
	global $context;
	
	redirectexit('action=admin;area=blocks;sa=settingsblocks;'.$context['session_var'].'='.$context['session_id']);
}

function deleteblocks()
{

	global $scripturl, $smcFunc, $context, $boarddir, $adkFolder;
	
	checkSession('get');
	
	if(!empty($_REQUEST['delete']) && is_numeric($_REQUEST['delete']))
		$id_delete = (int) $_REQUEST['delete'];
	else
		fatal_lang_error('adkfatal_empty_block_id',false);
   
   //Load my block
	$row = loadBlock($id_delete);
	
	if(empty($row['id']))
		fatal_lang_error('adkfatal_empty_block_id',false);
	
	//DELETE FILE
	if($row['type'] == 'include' && file_exists($adkFolder['blocks'].'/'.$row['echo']))
	{
		$echo = $adkFolder['blocks'].'/'.$row['echo'];
		@unlink($echo);
	}
	
	//Delete entry
	deleteEntry('adk_blocks', 'id = {int:id}', array('id' => $id_delete));
	deleteEntry('adk_blocks_template', 'id_block = {int:id}', array('id' => $id_delete));
	
	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'=' . $context['session_id']);
}

function editblocks()
{
	global $context;
	
	checkSession('get');

	if(!empty($_REQUEST['edit']) && is_numeric($_REQUEST['edit']))
		$id_block = (int)$_REQUEST['edit'];
	else
		fatal_lang_error('adkfatal_empty_block_id',false);
	
	$context['sub_template'] = 'editblocks';
	
	//Load block information
	$context['edit'] = loadBlock($id_block);
	
	//error_block
	if($context['edit']['num_rows'] == 0)
		fatal_lang_error('adkfatal_empty_block_id', false);
	
	//Get editor
	getEditor($context['edit']['echo']);

	$context['page_title'] = $context['edit']['title'];
	
}

function saveeditblocks()
{
	global $smcFunc, $context;

	checkSession('post');
	
	cleanEditor();
	
	$type = CleanAdkStrings($_POST['type_']);
	$echo = CleanAdkStrings($_POST['descript']);
	
	$title = CleanAdkStrings($_POST['titulo']);
	$id = (int)$_POST['id'];
	$img = CleanAdkStrings($_POST['img']);
	
	$empty_body = !empty($_POST['empty_body']) ? 1 : 0;
	$empty_title = !empty($_POST['empty_title']) ? 1 : 0;
	$empty_collapse = !empty($_POST['empty_collapse']) ? 1 : 0;

	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_blocks 
		SET name = {string:title},
		echo = {string:echo},
		img = {string:img},
		empty_body = {int:b},
		empty_collapse = {int:c},
		empty_title = {int:t}
		WHERE id = {int:id}',
		array(
			'title' => $title,
			'echo' => $echo,
			'img' => $img,
			'id' => $id,
			'b' => $empty_body,
			't' => $empty_title,
			'c' => $empty_collapse,
		)
	);

	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'=' . $context['session_id']);
}

function LoadTheNewBlocksToCreate()
{
	global $context, $txt, $scripturl;

	$context['add_custom_blocks'] = array(
		'bbc' => array(
			'title' => $txt['adkblock_bbc'].' / Html',
			'image' => 'page.png',
		),
		'php' => array(
			'title' => $txt['adkblock_php'],
			'image' => 'php.png',
		),
		'top_poster' => array(
			'title' => $txt['adkblock_topposter'],
			'image' => 'users.png',
		),
		'auto_news' => array(
			'title' => $txt['adkblock_auto_news'],
			'image' => 'new.png',
		),
		'top_karma' => array(
			'title' => $txt['adkblock_top_karma'],
			'image' => 'group_add.png',
		),
		'staff' => array(
			'title' => $txt['adkblock_staff'],
			'image' => 'register.png',
		),
		'multi_block' => array(
			'title' => $txt['adkblock_multi_block'],
			'image' => 'brick_add.png',
		),
		'uploadblock' => array(
			'title' => $txt['adkmod_block_upload'],
			'image' => 'upload.png',
			'custom_url' => $scripturl.'?action=admin;area=blocks;sa=uploadblock',
		)
	);
	
	$set = array();

	foreach($context['add_custom_blocks'] AS $act => $button_finally)
	{	

		if(!empty($button_finally['custom_url']))
			$context['add_custom_blocks'][$act]['url'] = $button_finally['custom_url'].';'.$context['session_var'].'='.$context['session_id'];
		else
			$context['add_custom_blocks'][$act]['url'] = $scripturl.'?action=admin;area=blocks;sa=newblocks;set='.$act.';'.$context['session_var'].'='.$context['session_id'];


		//Action
		$set[$act] = 'AdkAddBlock_'.$act;
		//The Saves
		$set[$act.'_save'] = 'AdkAddBlock_'.$act.'_save';
	}
	
	
	
	if (!empty($_GET['set']) && !empty($set[$_GET['set']]))
		$set[@$_GET['set']]();
	else
	{
		checkSession('get');
		
		$context['sub_template'] = 'the_new_custom_blocks';
		$context['page_title'] = $txt['adkblock_create_custom_block'];
	}
	
}

function AdkAddBlock_staff()
{
	global $context, $txt, $smcFunc;
	
	$context['page_title'] = $txt['adkblock_staff'];
	$context['sub_template'] = 'differents_blocks_styles';
	$context['block_type'] = 'staff';
	
	$context['g'] = loadAdkGroups('min_posts = -1');
}

function AdkAddBlock_staff_save()
{
	global $context;
	
	checkSession('post');

	//Basic information
	$groups_allowed = createArrayFromPost('groups_allowed');
	$show_avatar = !empty($_POST['avatar']) ? 1 : 0;

	createBlock('php', "<?php adkportal_staff(' $groups_allowed', $show_avatar); ?>");

	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'='.$context['session_id']);
}

function AdkAddBlock_top_karma()
{
	global $context, $txt;
	
	$context['page_title'] = $txt['adkblock_top_karma'];
	$context['sub_template'] = 'differents_blocks_styles';
	$context['block_type'] = 'top_karma';
}

function AdkAddBlock_top_karma_save()
{
	global $context;
	
	checkSession('post');

	//Basic information and create it
	$echo = (int)$_POST['descript'];

	if(empty($echo))
		fatal_lang_error('adkfatal_top_karma_error',false);
	
	createBlock('php', "<?php adk_topkarma10('$echo'); ?>");
	
	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'='.$context['session_id']);
	
}

function AdkAddBlock_auto_news()
{
	global $context, $txt;

	$context['page_title'] = $txt['adkblock_auto_news'];
	$context['sub_template'] = 'differents_blocks_styles';
	$context['block_type'] = 'auto_news';
	
	//Load Boards
	loadJumpTosmf1ByAlper();
	
}
function AdkAddBlock_auto_news_save()
{
	global $context;
	
	checkSession('post');
	
	if (isset($_POST['auto_news_id_boards'])){
		foreach ($_POST['auto_news_id_boards'] as $i => $v)
		{
			 if (!is_numeric($_POST['auto_news_id_boards'][$i])) 
				unset($_POST['auto_news_id_boards'][$i]);
			else
				$_POST['auto_news_id_boards'][$i] = (int)$_POST['auto_news_id_boards'][$i];
		}
	$echo = implode(',', $_POST['auto_news_id_boards']);
	}
	else
		$echo = 0;
	
	$int = (int)$_POST['int'];

	//This is an error?
	if(empty($echo))
		fatal_lang_error('adkfatal_auto_news_error',false);

	//Create myu block please
	createBlock('php', "<?php adk_aportes_automaticos('$echo', '', $int); ?>");
	
	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'='.$context['session_id']);
}

function AdkAddBlock_top_poster()
{
	global $context, $txt;

	$context['page_title'] = $txt['adkblock_topposter'];
	$context['sub_template'] = 'differents_blocks_styles';
	$context['block_type'] = 'top_poster';
	
}

function AdkAddBlock_top_poster_save()
{
	global $context;
	
	checkSession('post');
	
	$echo = (int)$_POST['descript'];

	if(empty($echo))
		fatal_lang_error('adkfatal_top_karma_error',false);

	createBlock('php', "<?php adk_topposter10('$echo'); ?>");

	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'='.$context['session_id']);
}

function AdkAddBlock_php()
{
	global $context, $txt;

	$context['page_title'] = $txt['adkblock_php'];
	$context['sub_template'] = 'differents_blocks_styles';
	$context['block_type'] = 'php';
}

function AdkAddBlock_php_save()
{
	global $context;
	
	checkSession('post');

	//Empty body?
	if(empty($_POST['descript']))
		fatal_lang_error('adkfatal_please_add_a_body_message',false);

	createBlock('php', CleanAdkStrings($_POST['descript']));
	
	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'='.$context['session_id']);
	
}

function AdkAddBlock_bbc()
{
	global $context, $sourcedir, $txt;

	$context['page_title'] = $txt['adkblock_bbc'];
	$context['sub_template'] = 'differents_blocks_styles';
	$context['block_type'] = 'bbc';
	
	//Get Editor
	getEditor();
}

function AdkAddBlock_bbc_save()
{
	global $context;
	
	checkSession('post');
	
	//Clean editor
	cleanEditor();
	
	$echo = CleanAdkStrings($_REQUEST['descript']);
	$type = !empty($_POST['html']) ? 'html' : 'bbc';

	//Emptybody
	if(empty($echo))
		fatal_lang_error('adkfatal_please_add_a_body_message',false);

	createBlock($type, $echo);

	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'='.$context['session_id']);
		
}

function AdkAddBlock_multi_block()
{
	global $smcFunc, $context, $txt;
	
	$context['page_title'] = $txt['adkblock_multi_block'];
	$context['sub_template'] = 'multi_block';

	//Loading blocks
	$sql = $smcFunc['db_query']('','
		SELECT id, name
		FROM {db_prefix}adk_blocks
		WHERE type <> {string:type} AND id NOT IN ({array_int:not_id})
		ORDER by id ASC',
		array(
			'type' => 'multi_block',
			'not_id' => array(11,12),
		)
	);
	
	$blocks = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
		$blocks[$row['id']] = $row['name'];
	
	$smcFunc['db_free_result']($sql);
	
	$context['adk_blocks'] = $blocks;
	
}

function AdkAddBlock_multi_block_save()
{
	checkSession('post');
	
	global $context, $smcFunc;
	
	$block = createArrayFromPost('block');
	
	if(empty($block))
		fatal_lang_error('adkfatal_insert_multi_id',false);

	createBlock('multi_block', $block);
	
	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'='.$context['session_id']);
}
	
function showeditnews()
{
	global $context, $smcFunc;
	
	checkSession('get');
	
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id_new = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_empty_news_id',FALSE);

	$context['sub_template']  = 'createnews';
	
	$edit = $smcFunc['db_query']('','
		SELECT n.titlepage, n.new, n.autor, n.id,
			IFNULL(t.id_new, 0) AS id_new, t.id_topic
		FROM {db_prefix}adk_news AS n
		LEFT JOIN {db_prefix}topics AS t ON (t.id_new = n.id)
		WHERE n.id = {int:id}',
		array(
			'id' => $id_new,
		)
	);

	//no results?
	if($smcFunc['db_num_rows']($edit) == 0)
		fatal_lang_error('adkfatal_empty_news_id', false);
	
	$fila = $smcFunc['db_fetch_assoc']($edit);

	if(!empty($fila['id_topic']))
		fatal_lang_error('adkfatal_empty_news_id', false);
	
	$context['edit'] = array (
		'title' => un_CleanAdkStrings($fila['titlepage']),
		'new' => un_CleanAdkStrings($fila['new']),
		'autor' => un_CleanAdkStrings($fila['autor']),
		'id' => $fila['id']
	);
	
	$context['page_title'] = $context['edit']['title'];
	$context['save_action'] = 'showsaveeditnews';
	
	$smcFunc['db_free_result']($edit);
	
	// Needed for the WYSIWYG editor.
	getEditor($context['edit']['new']);
	
	$options['wysiwyg_default'] = true;
	
}

function createnews()
{
	global $context, $txt, $boardurl, $sourcedir;
	
	checkSession('get');

	//Load main trader template.
	$context['sub_template']  = 'createnews';
	$context['page_title'] = $txt['adkmod_block_add_news'];
	$context['save_action'] = 'savecreatenews';
	
	//Editor
	getEditor();

	//Compatibility template
	$context['edit'] = array(
		'autor' => '',
		'title' => '',
		'id' => '',
	);
	
}

function savecreatenews()
{
	global $context, $scripturl, $smcFunc, $sourcedir;

	checkSession('post');
	
	//Clean the editor
	cleanEditor();
	
	$autore = CleanAdkStrings($_POST['autore']);
	$titlepage = CleanAdkStrings($_POST['titlepage']);	
	$quest = CleanAdkStrings($_REQUEST['descript']);
	$time = time();
	
	$quest = $quest;
	
	$the_array_info = array(
		'titlepage' => 'text',
		'new' => 'text',
		'autor' => 'text',
		'time' => 'int',
	);
	
	$the_array_insert = array(
		$titlepage,$quest,$autore,$time
	);

	$smcFunc['db_insert']('insert',
		'{db_prefix}adk_news',
		$the_array_info,
		$the_array_insert,
		array('id_new')
	);
	
	redirectexit('action=admin;area=blocks;'.$context['session_var'].'='.$context['session_id']);


}

function showsaveeditnews()
{
	global $smcFunc, $context;
	
	checkSession('post');
	
	//Clean it
	cleanEditor();
	
	$id_new = (int)$_POST['id'];
	$autor = CleanAdkStrings($_POST['autore']);
	$title = CleanAdkStrings($_POST['titlepage']);
	$insert = CleanAdkStrings($_REQUEST['descript']);
	
	$insert = $insert;

	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_news
		SET autor = {string:autor},
		titlepage = {string:title},
		new = {string:insert}
		WHERE id = {int:id}',
		array(
			'autor' => $autor,
			'title' => $title,
			'insert' => $insert,
			'id' => $id_new,
		)
	);
	

	redirectexit('portal');
}

function showdeletenews()
{
	global $db_prefix,$scripturl, $smcFunc;
	
	checkSession('get');
	
	if(!empty($_REQUEST['del']) && is_numeric($_REQUEST['del']))
		$id_new = (int)$_REQUEST['del'];
	else
		$id_new = 0;
   	

	$smcFunc['db_query']('','
		UPDATE {db_prefix}topics
		SET id_new = {int:zero}
		WHERE id_new = {int:id_new}',
		array(
			'zero' => 0,
			'id_new' => $id_new,
		)
	);

	//Delete 
	deleteEntry('adk_news', 'id = {int:id}', array('id' => $id_new));
	
	redirectexit('portal');
}

function uploadblock()
{
	global $context, $txt;
	
	checkSession('get');
	
	$context['sub_template']  = 'uploadblock';

	$context['page_title'] = $txt['adkmod_block_upload'];
}

function saveuploadblock()
{
	global $context, $boarddir, $smcFunc, $adkFolder;
	
	checkSession('post');
	
	if(empty($_FILES['file']['name']))
		fatal_lang_error('adkfatal_lang_error_not_block',false);
	
	$explode = explode('.',$_FILES['file']['name']);
	$count = count($explode) - 1;
	$extension = $explode[$count];
	
	if($extension != 'php')
		fatal_lang_error('adkfatal_extension',false);
	else
	{
		$name = $_FILES['file']['name'];
		$name2 = str_replace('.php','',$_FILES['file']['name']);
		
		@chmod($adkFolder['blocks'],0755);
		move_uploaded_file($_FILES['file']['tmp_name'], $adkFolder['blocks'].'/' .   $_FILES['file']['name']);
		@chmod($adkFolder['blocks'].'/'.$_FILES['file']['name'],0644);
		
		$smcFunc['db_insert'](
			'insert',
			'{db_prefix}adk_blocks',
			array(
				'name' => 'text',
				'echo' => 'text',
				'img' => 'text',
				'type' => 'text',
				'empty_body' => 'int',
				'empty_title' => 'int',
				'empty_collapse' => 'int',
				'other_style' => 'int',
				'permissions' => 'text',
			),
			array($name2, $name, '', 'include', 0, 0, 0, 0, ''),
			array('id')
		);

	}
	
	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'=' . $context['session_id']);

}

function PreviewBlockAdKPortal()
{
	global $context, $sourcedir;
	
	checkSession('get');
	
	if(!empty($_REQUEST['id']))
		$id = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_empty_block_id',false);
	
	//require_subs
	require_once($sourcedir.'/AdkPortal/Subs-adkblocks.php');
	
	//Load Block information
	$context['adkportal']['blocks'] = loadBlock($id);
	
	//This block does not exists?
	if($context['adkportal']['blocks']['num_rows'] == 0)
		fatal_lang_error('adkfatal_empty_block_id',false);
	
	$context['page_title'] = $context['adkportal']['blocks']['title'];
	$context['sub_template'] = 'preview_adkblock';
	
	//javascript block
	$context['html_headers'] .= javaScript_blocks();
}

function PermissionBlock()
{
	global $smcFunc, $context, $txt;
	
	checkSession('get');
	
	if(!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		$id = (int)$_REQUEST['id'];
	else
		fatal_lang_error('adkfatal_empty_block_id',FALSE);
	
	//Load Groups
	$context['adk_groups'] = loadAdkGroups('min_posts = {int:p}', array('p' => -1));
	
	//Load my block
	$context['block_permissions'] = loadBlock($id);
	
	if($context['block_permissions']['num_rows'] == 0)
		fatal_lang_error('adkfatal_empty_block_id', false);
	
	$context['sub_template'] = 'permissions';
	$context['page_title'] = $context['block_permissions']['name'].' - '.$txt['adkblock_permissions'];
	
}

function SavePermissionBlock()
{
	global $smcFunc, $context;
	
	checkSession('post');
	
	$adk = createArrayFromPost('adk'); 
		
	$id = (int)$_POST['id'];
	
	$smcFunc['db_query']('','
		UPDATE {db_prefix}adk_blocks
		SET permissions = {text:adk}
		WHERE id = {int:id}',
		array(
			'adk' => $adk,
			'id' => $id,
		)
	);
	
	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'=' . $context['session_id']);
}

function openDirImages($image = false)
{
	global $boardir, $adkportal, $txt, $smcFunc, $boardurl, $adkFolder;
	
	echo'
	<table style="text-align: center; width: 100%;">';
	
	echo'
		<tr>
			<td colspan="5">
			'.$txt['adkblock_none'].'<input type="radio" name="img" value=""',empty($image) ? ' checked="checked"' : '',' />&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>';
	
	$icons = getIcons();
	
	$i = 1;
	foreach($icons AS $icon)
	{	
		if($i == 6)
		{
			echo'</tr><tr>';
			$i = 1;
		}
		
		echo'
			<td>
				<img src="'.$adkFolder['images'].'/blocks/'.$icon['icon'].'" alt="" /><input type="radio" name="img" value="'.$icon['icon'].'" ',!empty($image) && $image == $icon['icon'] ? ' checked="checked"' : '' ,' />&nbsp;&nbsp;&nbsp;&nbsp;
			</td>';
		
		$i++;
	
	}
	echo'
		</tr></table>';

}

function saveBlockSettings($position, $id_template){

	global $smcFunc;

	if(!empty($_POST['id_'.$position]))
	{
		$orden =  $_POST['orden_'.$position];  
		$id =  $_POST['id_'.$position];  
		$columna =  $_POST['columna_'.$position];	
			
		$i = 0;
		$n = count($id);

		while ($i < $n)
		{
			$orden[$i] = (int)$orden[$i];
			$columna[$i] = (int)$columna[$i];
			$id[$i] = (int)$id[$i];

			if($columna[$i] != 6){
				$smcFunc['db_insert'](
					'insert',
					'{db_prefix}adk_blocks_template',
					array(
						'id_template' => 'int',
						'id_block' => 'int',
						'orden' => 'int',
						'columna' => 'int',
					),
					array($id_template, $id[$i], $orden[$i], $columna[$i]),
					array()
				);
			}
			
			$i++;
		}
	}
}

function DownloadNewBlock(){

	global $context, $smcFunc, $txt;

	checkSession('get');
	
	$context['page_title'] = $txt['adkmod_block_download'];
	$context['sub_template'] = 'download_new_block';

	//Get xml information
	$context['smf_personal_blocks'] = checkUrl('http://www.smfpersonal.net/xml/get_blocks.php') ? simplexml_load_file("http://www.smfpersonal.net/xml/get_blocks.php"): '';
}

function AddSMFPersonalBlock(){

	global $boarddir, $context, $adkFolder;

	checkSession('get');

	//Set error if empty id
	if(empty($_REQUEST['id']) || empty($_REQUEST['name']) || empty($_REQUEST['real']))
		fatal_lang_error('adkfatal_smf_p_blocks_not',false);

	$id = CleanAdkStrings($_REQUEST['id']);
	$name = cleanAdkStrings($_REQUEST['name']);
	$real = CleanAdkStrings($_REQUEST['real']);

	//Set the dir blocks
	$blocks_dir = $adkFolder['blocks'].'/'.$real;

	//Get the file :D
	$block_portal = getFile('http://www.smfpersonal.net/Adk-downloads/'.$id);

	//die($block_portal);

	fopen($blocks_dir,'a');

	//die();

	//Does not exists?
	if(empty($block_portal))
		fatal_lang_error('adkfatal_smf_p_blocks_not',false);

	//Create a block
	file_put_contents($blocks_dir, $block_portal);

	//Expand variable to create a simple block
	$_POST += array(
		'titulo' => $name,
		'empty_title' => 0,
		'empty_body' => 0,
		'empty_collapse' => 0,
		'img' => '',
	);

	createBlock('include', $real);

	redirectexit('action=admin;area=blocks;sa=viewblocks;'.$context['session_var'].'='.$context['session_id']);
}
?>