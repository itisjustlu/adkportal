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

/*      This file has the most important functions of Download System

		void getCatAdminDownload((String) Where condition for sql function, (array) Parameters of the same where, (int) sql start, (int) sql limit)
			- Load all categories 
			- Set all inforamtions on $context variable

		void processIconDownload((String)  icon uploaded, (string) process Type, (int) Category id)
			- You can select the type of process... If you select "size", this function will process the size of the icon.... and check if there is no error
													If you select "upload", this function will process that icon, and insert on {db_prefix}adk_down_cat
			- Set the icon name on $context variable if you select "upload" type

		void setCategoryError((int) Category ID)
			- When you delete some category... check if this category is not a father category. If this a father category and Some sub Category exists... make it the error
			- Check if some subsub categorys has the same problem

		array getInfoFileByApprove((int) is_approved = 1 - is_not_approve = 0, (int) sql start, (int) sql limit)
			- Load all files by approved/unapproved condition
		
		string processDownloadImage((string) uplaod image $_FILES)
			- Process a $_FILES image
			- Move it Adk-Downloads Folder

		int getCatByFile((int) Id file)
			- Load id_cat from {db_prefix}adk_down_file with the id_file

		int getApprovedByFile((int) Id file)
			- Load if this downlaod is approved or not, by id_file

		void getDownloadCategories((int) parent, (String) sql where condition, (array) Parameters of the same where, (String) sql order)
			- Here is the most important function to the main section of Download System index.php?action=downloads
			- This function load all categories of the Main Category with some conditions.
			- If you put parent = 0.... This function will load the main categories
			- If you put parent = some_cat_id... this function load all subcategories.

		void setTopic((array) parameters of this topic)
			- This function will create a new topic in a board.
			- It needs: subject, id_board, id_member, id_file, body, is_locked

		array getAttachments((int) Id file, (string) return type)
			- Load attachments from {db_prefix}adk_down_attachs
			- If type = download... return attachs to download it
			- If type != download... return input checkbox for edit it.

		print downloads_verify_checked((string) $adkportal var, (string) value to check)
			- If $adkportal[var] = value to check... print checked="checked"

		print verfy_select_board((int) key, (int) board)
			- If key = board... print selected="selected"

		double format_size((int) bytes, (int) round to...)
			- Process bytes and convert to MB, GB, etc...

		print download_bar_buttons((string) Active button)
			- create the bar buttons of Download system

		void checkCatParent((int) category id)
			- If category id != 0, load father category
			- Set the linktree with the father category

		array lastTenDownloads((int Limit))
			- A pesar del nombre :D... This function will load the last (limit) downloads 

		array RandomDownload()
			- Return body, subject and member by random order

		array PopularViewDownloads((int) limit)
			- Load popular downloads with the limit condition
			- PopularDownloads = views + downloads

		void TotalCategoryUpdate((int)category id)
			- Count all approved files from this category
			- Update total from {db_prefix}adk_down_cat

		void verifyCatPermissions((string) permission type, (int) category id)
			- Load permissions from this category
			- Check if this user has permissions to view/add
			- If user has not permissions.... drop a fatal_lang_error

		array MostDownloads()
			- Load most downloaded files

		array MostViewed()
			- Load most viewed files

		array returnLastDownloads(int limit)
			- we're using this function to create a new block...
			- return the last (limit) downloads of our system
**/

function getCatAdminDownload($where = '', $parameters = array(), $start = 0, $limit = 0){

	global $context, $smcFunc;

	validateWhere($where);

	if(!empty($limit))
		$final = 'LIMIT '.$start.', '.$limit;
	else
		$final = '';

	$dbresult = $smcFunc['db_query']('','
		SELECT id_cat, title, roworder, error, id_parent
		FROM {db_prefix}adk_down_cat
		'.$where.'
	 	ORDER BY roworder ASC
	 	'.$final, 
	 	$parameters
	 );

	$context['downloads_cat'] = array();
	 
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
	{
			$context['downloads_cat'][] = array(
			'id_cat' => $row['id_cat'],
			'title' => $row['title'],
			'roworder' => $row['roworder'],
			'id_parent' => $row['id_parent'],
			'has_error' => !emptY($row['error']),
			);
	}

	$smcFunc['db_free_result']($dbresult);
}

function processIconDownload($icon, $type = 'size', $cat_id = 0){

	global $boarddir, $smcFunc, $context, $adkFolder;

	if($type == 'size'){
		$sizes = @getimagesize($icon['tmp_name']);
		
		// if no size, invalid picture? :O
		if ($sizes === false)
			fatal_lang_error('adkfatal_invalid_picture',false);
		$width_height = 128; //128 * 128 px max
		
		if ($sizes[0] > $width_height || $sizes[1] > $width_height)
		{
			// Delete the temp file
			@unlink($icon['tmp_name']);
			fatal_lang_error('adkfatal_weight_height_false',false);
		}

	}
	else {
		$sizes = @getimagesize($icon['tmp_name']);

		// Move the file
		$extensions = array(
			1 => 'gif',
			2 => 'jpeg',
			3 => 'png',
			5 => 'psd',
			6 => 'bmp',
			7 => 'tiff',
			8 => 'tiff',
			9 => 'jpeg',
			14 => 'iff',
		);
			
		$extension = isset($extensions[$sizes[2]]) ? $extensions[$sizes[2]] : '.bmp';	
				
		$filename = $adkFolder['eds'].'/catimgs/'.$cat_id . '.JPEG';
		
		move_uploaded_file($icon['tmp_name'], $filename);
		@chmod($filename, 0644);

		// Update the filename for the category
		$smcFunc['db_query']('','
			UPDATE {db_prefix}adk_down_cat
			SET image = {string:image}
			WHERE id_cat = {int:cat} LIMIT 1',
			array(
				'image' => $filename,
				'cat' => $cat_id,
			)
		);

		$context['download_file_name'] = $filename;
	}
}

function setCategoryError($id_cat){

	global $smcFunc;

	//Set the error to the subcategories
	$smcFunc['db_query']('','UPDATE {db_prefix}adk_down_cat SET error = {int:error} WHERE id_parent = {int:cat}', array('error' => 1, 'cat' => $id_cat));

	//But maybe... this sub categories has a sub sub category.... please check it
	$sql = $smcFunc['db_query']('','
		SELECT id_cat FROM {db_prefix}adk_down_cat
		WHERE id_parent = {int:cat}',
		array(
			'cat' => $id_cat,
		)
	);

	while($row = $smcFunc['db_fetch_assoc']($sql)){

		//Set again
		setCategoryError($row['id_cat']);
	}

	$smcFunc['db_free_result']($sql);
}

function getInfoFileByApprove($is_approve = 1, $start = 0, $limit = 10){

	global $smcFunc, $context, $txt;

	$sql = $smcFunc['db_query']('','
		SELECT id_file, title
		FROM {db_prefix}adk_down_file
		WHERE approved = {int:a}
		ORDER BY title ASC
		LIMIT {int:uno}, {int:limit}
		',
		array(
			'a' => $is_approve,
			'limit' => $limit,
			'uno' => $start,
		)
	);

	$files = array();

	while($row = $smcFunc['db_fetch_assoc']($sql))
	{
		$files[] = array(
			'id' => $row['id_file'],
			'title' => $row['title'],
			'state' => $is_approve == 0 ? 'approvedownload' : 'unapprovedownload',
			'img' => $is_approve == 0 ? 'bullet_green.png' : 'bullet_red.png',
			'text' => $is_approve == 0 ? $txt['adkeds_approve_admin'] : $txt['adkeds_unapprove_admin'],
		);
	}

	$smcFunc['db_free_result']($sql);

	return $files;
}

function processDownloadImage($image_process){

	global $sourcedir, $boardurl, $boarddir, $adkFolder;

	//Set the size
	$sizes = @getimagesize($image_process['tmp_name']);
	
	if ($sizes === false)
		fatal_lang_error('adkfatal_invalid_picture',false);
			
	//Include grafics
	require_once($sourcedir . '/Subs-Graphics.php');
		
	$extensions = array(1 => 'gif',2 => 'jpeg',3 => 'png',5 => 'psd',6 => 'bmp',7 => 'tiff',8 => 'tiff',9 => 'jpeg',14 => 'iff',);
	$extension = isset($extensions[$sizes[2]]) ? $extensions[$sizes[2]] : '.bmp';
			
				
	$image2 = $adkFolder['eds'].'/'.$image_process['name'] . '.' . $extension;
	$image = $adkFolder['edsurl'].'/'.$image_process['name'] . '.' . $extension;
		
	move_uploaded_file($image_process['tmp_name'], $image2);
		
	//Thumb...make me
	if(check_if_gd())
		load_AvdImage('', $image2, $extension, 6, $image2);

	return $image;
}

function getCatByFile($id_file){

	global $smcFunc;

	$sql = $smcFunc['db_query']('','SELECT id_cat FROM {db_prefix}adk_down_file WHERE id_file = {int:file}',array('file' => $id_file,));

	list($id_cat) = $smcFunc['db_fetch_row']($sql);

	$smcFunc['db_free_result']($sql);

	return $id_cat;
}

function getApprovedByFile($id_file){

	global $smcFunc;

	$sql = $smcFunc['db_query']('',' SELECT approved FROM {db_prefix}adk_down_file WHERE id_file = {int:file}', array('file' => $id_file,));

	list($approved) = $smcFunc['db_fetch_row']($sql);

	$smcFunc['db_free_result']($sql);

	return $approved;
}

function getDownloadCategories($id_cat = 0, $where = '', $parameters = array(), $orderby = 'c.roworder ASC'){

	global $smcFunc, $context, $scripturl, $user_info, $adkportal, $current_load;

	//And validate where and order
	if(!empty($where))
		$where = ' AND '.$where;

	validateOrder($orderby);

	//Load categories
	$sql = $smcFunc['db_query']('','SELECT
		c.id_cat, c.title, c.description, c.roworder, c.image, c.total, c.id_parent, c.groups_can_view, c.groups_can_add, c.error
		FROM {db_prefix}adk_down_cat AS c
		WHERE 1=1
			'.(!$user_info['is_admin'] && !allowedTo('adk_downloads_manage') ? 'AND (FIND_IN_SET(' . implode(', c.groups_can_view) != 0 OR FIND_IN_SET(', $user_info['groups']) . ', c.groups_can_view) != 0)' : '').'
			'.$where.'
		'.$orderby,
		$parameters
	);
	
	$context['all_cat'] = array();
	$context['all_parent'] = array();
	
	$width = 30;

	//Set the index_cat
	$index_cat = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
	{	
		if($row['id_parent'] == $id_cat){
			$context['all_cat'][$row['id_cat']] = array(
				'post' => array(),
				'id_cat' => $row['id_cat'],
				'title' => $row['title'],
				'description' => parse_bbc($row['description']),
				'roworder' => $row['roworder'],
				'image' => $row['image'],
				'total' => !empty($context['all_cat'][$row['id_cat']]['total']) ? $context['all_cat'][$row['id_cat']]['total'] + $row['total'] : $row['total'],
				'has_error' => !empty($row['error']),
			);
				
			//Return The Last Download ;)
			$context['all_cat'][$row['id_cat']]['post'] = PleaseCheckMyLastDownload($row['id_cat']);
		
			//For RewriteUrls
			$context['rewrite_adk']['cat'][$row['id_cat']] = $row['title'];
			$context['rewrite_adk']['download'][$context['all_cat'][$row['id_cat']]['post']['id']] = $context['all_cat'][$row['id_cat']]['post']['download'];
		}
		else {
			$context['all_parent'][$row['id_parent']][$row['id_cat']] = 
				'<a href="'.$scripturl.'?action=downloads;cat='.$row['id_cat'].'">'.$row['title'].'</a>'
			;
			
			$context['all_cat'][$row['id_parent']]['total'] = !empty($context['all_cat'][$row['id_parent']]['total']) ? $context['all_cat'][$row['id_parent']]['total'] + $row['total'] : $row['total'];

			$context['all_parent_new'][$row['id_parent']][] = array(
				'has_error' => !empty($row['error']),
				'id_cat' => $row['id_cat'],
				'title' => $row['title'],
			);
			
			//For RewriteUrls
			$context['rewrite_adk']['cat'][$row['id_cat']] = $row['title'];
		}
		
	}

	//So let's clean unused categories
	if(($current_load[0] == 'action') && ($current_load[1] == 'downloads'))
	foreach($context['all_cat'] AS $id_cat => $cat_info){

		if(empty($cat_info['title']))
			unset($context['all_cat'][$id_cat]);
	}
	
	$smcFunc['db_free_result']($sql);

}

function setTopic($parameters = array()){

	global $smcFunc, $user_info, $boardurl, $scripturl, $txt, $adkFolder;

	//Create the body
	$body = '';

	if(!empty($parameters['image']))
		$body .= "[img]".$parameters['image']."[/img]\n\n\n";

	$body .= $parameters['body'];
	$body .= "\n[hr][img]".$adkFolder['images']."/stats_s_green.png[/img]  [url=".$scripturl."?action=downloads;sa=view;down=".$parameters['id_file']."]".$txt['adkdown_link_download']."[/url]";

	$msgOptions = array(
		'id' => 0,
		'subject' => $parameters['subject'],
		'body' => $body,
		'icon' => 'xx',
		'smileys_enabled' => 1,
		'attachments' => array(),
	);
	
	$topicOptions = array(
		'id' => 0,
		'board' => $parameters['id_board'],
		'poll' => null,
		'lock_mode' => $parameters['locked'],
		'sticky_mode' => null,
		'mark_as_read' => true,
	);
	
	$posterOptions = array(
		'id' => $parameters['id_member'],
		'update_post_count' => !$user_info['is_guest'] && !isset($_REQUEST['msg']),
	);
	
	preparsecode($msgOptions['body']);
	createPost($msgOptions, $topicOptions, $posterOptions);
	
	$id_topic = $topicOptions['id'];
			
	$smcFunc['db_query']('',"
		UPDATE {db_prefix}adk_down_file
		SET id_topic = {int:topic}
		WHERE id_file = {int:file}",
		array(
			'topic' => $id_topic,
			'file' => $parameters['id_file'],
		)
	);
}

function getAttachments($id_view, $return = 'download'){
	global $context, $smcFunc, $boardurl, $scripturl, $txt, $adkFolder;

	if(empty($id_view))
		return array();

	$a = $smcFunc['db_query']('','
		SELECT id_attach, filesize, orginalfilename
		FROM {db_prefix}adk_down_attachs
		WHERE id_file = {int:file}',
		array(
			'file' => $id_view,
		)
	);
	
	$attachs = array();

	while($row2 = $smcFunc['db_fetch_assoc']($a))
		if($return == 'download')
			$attachs[] = '<img class="adk_vertical" src="'.$adkFolder['images'].'/drive_add.png" alt="" />&nbsp;<a href="'.$scripturl.'?action=downloads;sa=downfile;id='.$row2['id_attach'].'">'.$row2['orginalfilename'].'</a> <span class="smalltext">('.format_size($row2['filesize']).')</span>';
		else
			$attachs[] = '<input type="checkbox" value="'.$row2['id_attach'].'" name="download2['.$row2['id_attach'].']" />&nbsp;'.$txt['adkdown_delete'].' - '.$row2['orginalfilename'];

	$smcFunc['db_free_result']($a);

	return $attachs;
}

function downloads_verify_checked($variable, $value)
{
	global $adkportal;
	
	if($adkportal[$variable] == $value)
		echo'checked="checked"';
}
function verfy_select_board($key,$board)
{
	if($board == $key)
		echo' selected="selected"';
}

function format_size(&$size, $round = 2) 
{
    //Size must be bytes!
    $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    for ($i=0; $size > 1024 && $i < count($sizes) - 1; $i++)
    	$size /= 1024;

    //Return it
    return round($size,$round).$sizes[$i];
}

function download_bar_buttons($active = 'downloads')
{
	global $scripturl, $context, $user_info;
	
	//The Menu Buttons
	if(allowedTo('adk_downloads_add'))
		$context['adk_downloads_add'] = true;
	if($context['user']['is_logged'])
		$context['adk_user_is_logged'] = true;
	if(allowedTo('adk_downloads_manage') && !empty($context['unApprove']))
		$context['view_un'] = true;
	
	
	$menu_buttons = array(
		'downloads' => array(
			'text' => 'adkdown_downloads',
			'image' => '',
			'lang' => true,
			'url' => $scripturl.'?action=downloads',
		),
		'viewstats' => array(
			'text' => 'adkdown_view_stats', 
			'image' => '', 
			'lang' => true, 
			'url' => $scripturl.'?action=downloads;sa=viewstats',
		),
		'myprofile' => array(
			'test' => 'adk_user_is_logged', 
			'text' => 'adkdown_myprofile', 
			'image' => '', 
			'lang' => true, 
			'url' => $scripturl.'?action=downloads;sa=myprofile;u='.$user_info['id'],
		)
	);
	
	$menu_buttons['search'] = array(
		'text' => 'adkdown_search', 
		'image' => '', 
		'lang' => true, 
		'url' => $scripturl.'?action=downloads;sa=search',
	);
	
	if(!empty($context['view_un'])){
		$menu_buttons[] = array(
			'test' => 'view_un',
			'text' => 'adkdown_approve_admin',
			'url' => $scripturl.'?action=admin;area=adkdownloads;sa=approvedownloads;'.$context['session_var'].'='.$context['session_id'],
			'active' => true,
		);
	}
	
	if(!empty($active))
		$menu_buttons[$active]['active'] = true;
	
	echo'
	<div class="pagesection">
		',template_button_strip($menu_buttons,'right'),'
	</div>
	<div class="height_2"></div>';
}

function CheckCatParent($id_cat)
{
	global $smcFunc, $scripturl, $context;
	
	/***
	* Load all links trees... when id_parent != 0
	***/
	if($id_cat != 0)
	{
		$sql = $smcFunc['db_query']('','
			SELECT id_parent, title FROM {db_prefix}adk_down_cat
			WHERE id_cat = {int:cat}',
			array(
				'cat' => $id_cat,
			)
		);
		
		$row = $smcFunc['db_fetch_assoc']($sql);
		$smcFunc['db_free_result']($sql);
		
		//Again?
		CheckCatParent($row['id_parent']);
		
		setLinktree('downloads;cat='.$id_cat, $row['title'], false, true);
	}
}	
	
function LastTenDownloads($limit = 10)
{
	global $smcFunc, $scripturl, $boardurl, $user_info, $adkportal, $adkFolder;

	//Set the permissions
	$allowed_to_manage = allowedTo('adk_downloads_manage') ? 1 : 0;
	
	$sql = $smcFunc['db_query']('','
		SELECT d.id_file, d.title
		FROM {db_prefix}adk_down_file AS d
		LEFT JOIN {db_prefix}adk_down_cat AS c ON (c.id_cat = d.id_cat)
		WHERE '.$adkportal['query_downloads'].'
		ORDER BY d.id_file DESC
		LIMIT '.$limit,
		array(
			'a' => 1,
			'member' => $user_info['id'],
		)
	);
	
	$context['total_downloads'] = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
		$context['total_downloads'][] = '<li><img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<a href="'.$scripturl.'?action=downloads;sa=view;down='.$row['id_file'].'">'.$row['title'].'</a></li>';
	
	$smcFunc['db_free_result']($sql);
	
	return $context['total_downloads'];

}

function RandomDownload()
{
	global $smcFunc, $scripturl;
	
	$sql = $smcFunc['db_query']('','
		SELECT d.id_file, d.title, d.description, d.id_member, m.id_member, m.real_name
		FROM {db_prefix}adk_down_file AS d, {db_prefix}members AS m
		WHERE approved = {int:a} AND d.id_member = m.id_member
		ORDER BY RAND()
		LIMIT 1',
		array(
			'a' => 1
		)
	);

	if ($smcFunc['db_num_rows'](($sql)) == 0)
		$context['total_downloads'] = '';
	else
	{
		$context['total_downloads'] = array();
		
		$row = $smcFunc['db_fetch_assoc']($sql);
		
		$context['total_downloads']['title_link'] = '<a href="'.$scripturl.'?action=downloads;sa=view;down='.$row['id_file'].'">'.$row['title'].'</a>';
		$context['total_downloads']['member_link'] = '<a href="'.$scripturl.'?action=profile;u='.$row['id_member'].'">'.$row['real_name'].'</a>';
		$context['total_downloads']['body'] = parse_bbc($row['description']);
		
		$smcFunc['db_free_result']($sql);
	}
	
	return $context['total_downloads'];

}

function PopularViewDownloads($limit = 10)
{
	global $smcFunc, $scripturl, $boardurl, $user_info, $adkportal, $adkFolder;
	
	//Set the permissions
	$allowed_to_manage = allowedTo('adk_downloads_manage') ? 1 : 0;

	$sql = $smcFunc['db_query']('','
		SELECT d.id_file, d.title
		FROM {db_prefix}adk_down_file AS d
		LEFT JOIN {db_prefix}adk_down_cat AS c ON (c.id_cat = d.id_cat)
		WHERE '.$adkportal['query_downloads'].'
		ORDER BY totaldownloads + views DESC
		LIMIT '.$limit,
		array(
			'member' => $user_info['id'],
		)
	);
	
	$context['total_downloads'] = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
		$context['total_downloads'][] = '<li><img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<a href="'.$scripturl.'?action=downloads;sa=view;down='.$row['id_file'].'">'.$row['title'].'</a></li>';
	
	$smcFunc['db_free_result']($sql);
	
	return $context['total_downloads'];
}

function TopUploadersDownload()
{
	global $smcFunc, $scripturl, $user_info, $adkportal;
	
	//Set the permissions
	$allowed_to_manage = allowedTo('adk_downloads_manage') ? 1 : 0;

	$sql = $smcFunc['db_query']('','
		SELECT d.id_file, d.title, d.id_member, m.id_member, m.real_name
		FROM {db_prefix}adk_down_file AS d
		LEFT JOIN {db_prefix}members AS m ON (m.id_member = d.id_member)
		LEFT JOIN {db_prefix}adk_down_cat AS c ON (c.id_cat = d.id_cat)
		WHERE '.$adkportal['query_downloads'].'
		ORDER by COUNT(d.id_member) DESC LIMIT 10',
		array(
			'a' => 1,
			'member' => $user_info['id'],
		)
	);
	
	$files = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
		$files[] = '<li><a href="'.$scripturl.'?action=profile;u='.$row['id_member'].'">'.$row['real_name'].'</a></li>';
	
	$smcFunc['db_free_result']($sql);
	
	return $files;
}

function TotalCategoryUpdate($ID_CAT)
{
	global $smcFunc;
	
	$dbresult = $smcFunc['db_query']('','
		SELECT
		COUNT(*) AS total
		FROM {db_prefix}adk_down_file
		WHERE id_cat = {int:cat} AND approved = {int:a}',
		array(
			'cat' => $ID_CAT,
			'a' => 1,
		)
	);
	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$total = $row['total'];
	$smcFunc['db_free_result']($dbresult);

	// Update the count
	$dbresult = $smcFunc['db_query']('','
		UPDATE {db_prefix}adk_down_cat 
		SET total = {int:t} WHERE id_cat = {int:cat} LIMIT 1',
		array(
			't' => $total,
			'cat' => $ID_CAT
		)
	);
}

function verifyCatPermissions($permission,$cat, $return = false)
{
	global $user_info, $smcFunc;

	//Load Cat Info
	$sql = $smcFunc['db_query']('','
		SELECT groups_can_view, groups_can_add, id_parent
		FROM {db_prefix}adk_down_cat
		WHERE id_cat = {int:cat}',
		array(
			'cat' => $cat,
		)
	);

	$row = $smcFunc['db_fetch_assoc']($sql);

	$smcFunc['db_free_result']($sql);

	//Check what permission we're trying to verify
	if($permission == 'view')
		$y = $row['groups_can_view'];
	else
		$y = $row['groups_can_add'];

	$x =  !empty($y) || $y == "0" ? explode(',', $y) : array();

	$valid_permission = false;
	
	//Check if this user has not permiossion
	if(!empty($x)){
		foreach($x AS $i => $v){
			if(in_array($v,$user_info['groups']))
				$valid_permission = true;
		}

		//Guest
		if($user_info['is_guest'] && in_array(-1,$x))
			$valid_permission = true;
		
	}

	if($user_info['is_admin'] || allowedTo('adk_downloads_manage'))
		$valid_permission = true;

	//.... lier
	if($return)
		return $valid_permission;
	elseif(!$valid_permission)
		fatal_lang_error('adkfatal_cannot_view',false);
	
	//Check if permissions of previous categories
	if($row['id_parent'] != 0)
		verifyCatPermissions($permission, $row['id_parent']);

}

function MostDownloads()
{
	global $smcFunc, $scripturl, $boardurl, $user_info, $adkportal, $adkFolder;
	
	//Set the permissions
	$allowed_to_manage = allowedTo('adk_downloads_manage') ? 1 : 0;

	$sql = $smcFunc['db_query']('','
		SELECT d.id_file, d.title, d.totaldownloads
		FROM {db_prefix}adk_down_file AS d
		LEFT JOIN {db_prefix}adk_down_cat AS c ON (c.id_cat = d.id_cat)
		WHERE 1 = 1
			AND '.$adkportal['query_downloads'].'
		ORDER by totaldownloads DESC LIMIT 10',
		array(
			'a' => 1,
			'member' => $user_info['id'],
		)
	);
	
	$files = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
		$files[] = '<li><img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<a href="'.$scripturl.'?action=downloads;sa=view;down='.$row['id_file'].'">'.$row['title'].'</a> ('.$row['totaldownloads'].')</li>';
	
	$smcFunc['db_free_result']($sql);
	
	return $files;
}

function MostViewed()
{
	global $smcFunc, $scripturl, $boardurl, $user_info, $adkportal, $adkFolder;
	
	//Set the permissions
	$allowed_to_manage = allowedTo('adk_downloads_manage') ? 1 : 0;

	$sql = $smcFunc['db_query']('','
		SELECT d.id_file, d.title, d.views
		FROM {db_prefix}adk_down_file AS d
		LEFT JOIN {db_prefix}adk_down_cat AS c ON (c.id_cat = d.id_cat)
		WHERE 1 = 1
			AND '.$adkportal['query_downloads'].'
		ORDER by views DESC LIMIT 10',
		array(
			'a' => 1,
			'member' => $user_info['id'],
		)
	);
	
	$files = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
		$files[] = '<li><img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<a href="'.$scripturl.'?action=downloads;sa=view;down='.$row['id_file'].'">'.$row['title'].'</a> ('.$row['views'].')</li>';
	
	$smcFunc['db_free_result']($sql);
	
	return $files;

}

function PleaseCheckMyLastDownload($id_cat)
{
	global $smcFunc, $context, $scripturl, $modSettings, $user_info, $adkportal;
	
	$sql = $smcFunc['db_query']('','
		SELECT c.id_cat, d.id_cat, d.short_desc,
		d.id_file, d.date, d.title, d.id_member, m.id_member, m.avatar, m.real_name, d.approved,
			IFNULL(a.id_attach, 0) AS id_attach, a.filename, a.attachment_type
		FROM {db_prefix}adk_down_file AS d, {db_prefix}adk_down_cat AS c, {db_prefix}members AS m
		LEFT JOIN {db_prefix}attachments AS a ON (a.id_member = m.id_member)
		WHERE
			m.id_member = d.id_member AND c.id_cat = d.id_cat AND (c.id_cat = {int:cat} OR c.id_parent = {int:cat})
			AND '.$adkportal['query_downloads'].'
		ORDER BY d.id_file DESC LIMIT 1',
		array(
			'cat' => $id_cat,
		)
	);
	
	$row = $smcFunc['db_fetch_assoc']($sql);
	
	$smcFunc['db_free_result']($sql);
	
	$context['last_download'] = array();
	
	//$width and height
	$width = 50; $height = 50;
	
	$context['last_download'] = array(
		'id' => $row['id_file'],
		'member' => '<a href="'.$scripturl.'?action=profile;u='.$row['id_member'].'">'.$row['real_name'].'</a>',
		'avatar' => $row['avatar'] == '' ? ($row['id_attach'] > 0 ? '<img width="'.$width.'" height="'.$height.'" src="' . (empty($row['attachment_type']) ? $scripturl . '?action=dlattach;attach=' . $row['id_attach'] . ';type=avatar' : $modSettings['custom_avatar_url'] . '/' . $row['filename']) . '" alt="" border="0" />' : '') : (stristr($row['avatar'], 'http://') ? '<img width="'.$width.'" height="'.$height.'"src="' . $row['avatar'] . '" alt="" border="0" />' : '<img width="'.$width.'" height="'.$height.'"src="' . $modSettings['avatar_url'] . '/' . $smcFunc['htmlspecialchars']($row['avatar']) . '" alt="" border="0" />'),
		'file' => '<a title="'.$row['short_desc'].'" href="'.$scripturl.'?action=downloads;sa=view;down='.$row['id_file'].'">'.$row['title'].'</a>',
		'date' => timeformat($row['date']),
		'download' => array(),
		'approved' => $row['approved'],
		'id_autor' => $row['id_member'],
	);
	
	//For RewriteUrls
	$context['last_download']['download'] = $row['title'];
	
	return !empty($context['last_download']) ? $context['last_download'] : '';

}

function checkTopicDownload($topic){

	global $smcFunc;
	
	if(empty($topic))
		return false;

	$sql = $smcFunc['db_query']('','SELECT id_topic FROM {db_prefix}topics WHERE id_topic = {int:topic}',array('topic' => $topic));

	if($smcFunc['db_num_rows']($sql)){

		$smcFunc['db_free_result']($sql);

		return true;
	}
	else
		return false;

}

?>