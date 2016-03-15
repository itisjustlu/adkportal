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

function AdkPageSystem()
{

	global $adkportal, $user_info, $options;

	$sa = array();
	setLinktree('pages', 'adkmod_pages');

	//Subactions
	if(!empty($adkportal['enable_pages_comments']) && !$user_info['is_guest']){
		

		$sa['addcomment'] = 'AdkPagesAddComment';

		//If admin notification is active and profile notification is active!
		if(!empty($adkportal['enable_pages_notifications']) && empty($options['adk_disable_notifications_profile'])){
			
			$sa['unread'] = 'AdkUnreadComments';
			$sa['clean'] = 'AdkCleanUnreadComments';
		}
		
	}

	if(!empty($_REQUEST['sa']) && !empty($sa[$_REQUEST['sa']]))
		$sa[$_REQUEST['sa']]();
	else
		LoadIndexPages();

}

function LoadIndexPages(){

	global $context, $txt, $adkportal, $user_info, $scripturl;

	if(empty($adkportal['enable_menu_pages']))
		fatal_lang_error('adkfatal_module_not_enable', false);

	adktemplate('Adk-echomodules');
	adkLanguage('Adk-echomodules');

	$context['sub_template'] = 'page_system';
	$context['page_title'] = $txt['adkmodules_index_pages'];

	$total = getTotal('adk_pages', '(FIND_IN_SET(' . (implode(', grupos_permitidos) != 0 OR FIND_IN_SET(', $user_info['groups'])) . ', grupos_permitidos) != 0)');
	$show = 5;
	$start = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;

	$context['pages'] = getPages($start, $show, '', 'id_page DESC', array(), true);

	if(empty($context['pages']))
		fatal_lang_error('adkfatal_module_not_enable', false);

	$context['page_index'] = constructPageIndex($scripturl . '?action=pages', $start, $total, $show);

}

function load_pages_adkportal($request = '')
{
	global $context, $smcFunc, $scripturl, $adkportal, $adkFolder, $user_info, $options;
	
	//Load template
	adktemplate('Adk-echomodules');
	
	//Load language
	adkLanguage('Adk-echomodules');

	//And set the subtemplate
	$context['sub_template']  = 'load_pages_adkportal';

	//Get css
	$context['html_headers'] .= getCss('modules');
	
	//Check if page exists
	if(empty($request) && empty($_REQUEST['page']))
		fatal_lang_error('adkfatal_page_not_exist', false);
	elseif(empty($request) && !empty($_REQUEST['page']))
		$request = CleanAdkStrings(stripslashes($_REQUEST['page']));
	
	//Update views + 1
	if(empty($_SESSION['previous_visited'][$request])){
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}adk_pages
			SET views = views + 1
			WHERE urltext = {text:page}',
			array(
				'page' => $request,
			)
		);

		$_SESSION['previous_visited'][$request] = true;
	}
		
	//Get Page Info
	$context['page_view_content'] = getPage($request, false, true);
	$context['page_view_comments'] = array();
	
	//Return error if this does not exists :)
	if(empty($context['page_view_content']))
		fatal_lang_error('adkfatal_page_not_exist', false);
		
	//Set the page_title
	$context['page_title'] = $context['page_view_content']['titlepage'];
		
	//Load Linktree
	if(!empty($adkportal['enable_menu_pages']))
		setLinktree('pages', 'adkmod_pages');

	setLinktree($scripturl.'?page='.$request, $context['page_view_content']['titlepage'], true, true);

	//Load comments if system comments is enable and the page allows comments
	if(!empty($adkportal['enable_pages_comments']) && !empty($context['page_view_content']['enable_comments'])){

		$show = 10;
		$context['start'] = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
		$total = getTotal('adk_pages_comments','id_page = {int:id_page}', array('id_page' => $context['page_view_content']['id_page']));
		
		if(empty($total))
			$total = 1;		

		//Load id_comment if anyone is requesting a comment
		if(!empty($_REQUEST['comment'])){

			$request_comment = (int)$_REQUEST['comment'];

			//Load id_comments
			$query = $smcFunc['db_query']('','
				SELECT id_comment
				FROM {db_prefix}adk_pages_comments
				WHERE id_page = {int:id_page}
				ORDER BY date ASC',
				array(
					'id_page' => $context['page_view_content']['id_page'],
				)
			);

			$pos = 0;
			$page = 0;
			$continue = true;
			$i = 0;

			while(($row = $smcFunc['db_fetch_assoc']($query)) && ($continue)){

				//Comment position
				$pos++;

				if($pos == ($show + 1)){

					$pos = 1;
					$page++;
				}

				if($row['id_comment'] == $request_comment)
					$continue = false;
			}
				
			$smcFunc['db_free_result']($query);

			$context['start'] = $show * $page;
		}


		$context['page_view_comments'] = getComments($context['page_view_content']['id_page'], $limit = array($context['start'], $show));
		$context['page_index'] = constructPageIndex($scripturl . '?page='.$request, $context['start'], $total, $show);

		//Load Editor & don't load for guet
		if(!$user_info['is_guest']){
			getEditor();

			$context['html_headers'] .= '
			<script type=\'text/JavaScript\'>
				window.onload=mymenu;
				function mymenu(id) {
					var d = document.getElementById(id);
					var img = document.getElementById(id+"2");
					
					d.style.display = (d.style.display == "none") ? "block" : "none";

					img.src = (img.src == "'.$adkFolder['images'].'/add.png") ? "'.$adkFolder['images'].'/unapprove.png" : "'.$adkFolder['images'].'/add.png";
				}

			</script>';
		}

		//Check if in this page are the comments you didn't read it
		/*
			*user notifications must be non empty
			*admin notifications is enabled
			*profile notifications is not disable
		*/
		if(!empty($user_info['adk_pages_notifications']) && !empty($adkportal['enable_pages_notifications']) && empty($options['adk_disable_notifications_profile'])){

			//Run the comments
			$id_comments = array();

			foreach(explode(',',$user_info['adk_pages_notifications']) AS $v)
				$id_comments[$v] = $v;

			//Clean notifications
			foreach($context['page_view_comments'] AS $id_comment => $comment_info){

				if(in_array($id_comment, $id_comments)){
					unset($id_comments[$id_comment]);
					$context['page_view_comments'][$id_comment]['is_new'] = true;
				}
			}

			//Update table
			updateMemberData($user_info['id'], array('adk_pages_notifications' => implode(',',$id_comments)));
		}
	}

}

function AdkPagesAddComment(){

	global $smcFunc, $context, $user_info;

	checkSession('post');
	
	cleanEditor();
	
	$comment = CleanAdkStrings($_POST['descript']);
	$id_page = (int)$_POST['id_page'];
	$idUser = $user_info['id'];
	$date = time();

	//Check if this user is allowed to view this page
	$page = getPage($id_page, true, true);

	if(empty($page))
		fatal_lang_error('adkfatal_page_not_exist', false);

	$the_array_info = array(
		'body' => 'text',
		'date' => 'int',
		'id_member' => 'int',
		'id_page' => 'int',
	);
	
	$the_array_insert = array(
		$comment, $date, $idUser, $id_page
	);

	$smcFunc['db_insert']('insert',
		'{db_prefix}adk_pages_comments',
		$the_array_info,
		$the_array_insert,
		array('id_new')
	);

	$last_id = 0;
	$last_id = $smcFunc['db_insert_id']("{db_prefix}adk_pages_comments");

	//Add notifications to members
	$members_id = array();

	$query = $smcFunc['db_query']('','
		SELECT id_member, adk_pages_notifications
		FROM {db_prefix}members
		WHERE id_group = {int:id_group}',
		array(
			'id_group' => 1,
		)
	);

	while($row = $smcFunc['db_fetch_assoc']($query))
		$members_id[$row['id_member']] = $row['adk_pages_notifications'];

	$smcFunc['db_free_result']($query);

	//Load comments user
	$sql = $smcFunc['db_query']('','
		SELECT c.id_member, m.adk_pages_notifications
		FROM {db_prefix}adk_pages_comments AS c
			LEFT JOIN {db_prefix}members AS m on (m.id_member = c.id_member)
		WHERE id_page = {int:id_page} AND c.id_member',
		array(
			'id_page' => $id_page,
		)
	);

	while($row = $smcFunc['db_fetch_assoc']($sql))
		$members_id[$row['id_member']] = $row['adk_pages_notifications'];

	$smcFunc['db_free_result']($sql);

	unset($members_id[$user_info['id']]);

	//Add notification
	if(!empty($members_id)){
		foreach($members_id AS $id => $notifications){

			$n = array();

			if(!empty($notifications))
				$n = explode(',',$notifications);

			$n[] = $last_id;

			$smcFunc['db_query']('', '
				UPDATE {db_prefix}members
				SET adk_pages_notifications = {text:notifications}
				WHERE id_member = {int:id_member}',
				array(
					'notifications' => implode(',',$n),
					'id_member' => $id,
				)
			);
		}
	}

	redirectexit('page='.$page['urltext'].';comment='.$last_id.'#'.$last_id);

}

function AdkUnreadComments(){

	global $context, $smcFunc, $user_info, $txt, $scripturl;

	adkTemplate('Adk-echomodules');
	adkLanguage('Adk-echomodules');
	
	$context['sub_template'] = 'unread_comments';
	$context['page_title'] = $txt['adkmod_pages_unread'];
	setLinktree('pages;sa=unread', 'adkmod_pages_unread');
	$context['unread_comments'] = array();
	$show = 10;
	$context['start'] = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
	$total = 0;

	if(!empty($user_info['adk_pages_notifications'])){

		//Let's load some parameters
		$pages = array();
		$first_comment_unread = array();
		$pages_info = array();
		$counter = 0 + $context['start'];

		//This variable is to check how comments are unread on this age
		$check_unread = array();

		//Load all pages
		$sql = $smcFunc['db_query']('','
			SELECT id_page, id_comment
			FROM {db_prefix}adk_pages_comments
			WHERE id_comment IN ({array_int:id_comments})
			ORDER BY id_comment DESC',
			array(
				'id_comments' => explode(',',$user_info['adk_pages_notifications']),
			)
		);

		while($row = $smcFunc['db_fetch_assoc']($sql)){

			$pages[$row['id_page']] = $row['id_page'];
			$first_comment_unread[$row['id_page']] = $row['id_comment'];
			$check_unread[$row['id_page']][] = $row['id_comment'];
		}

		$smcFunc['db_free_result']($sql);

		$total = getTotal('adk_pages','id_page IN ({array_int:id_pages})', array('id_pages' => $pages));

		$query = $smcFunc['db_query']('','
			SELECT
				p.id_page, p.urltext, p.titlepage, p.views,
				c.id_comment, c.id_member, c.date,
				m.real_name, p.urltext
			FROM {db_prefix}adk_pages AS p
				INNER JOIN {db_prefix}adk_pages_comments AS c ON (c.id_page = p.id_page)
				INNER JOIN {db_prefix}members AS m ON (c.id_member = m.id_member)
			WHERE p.id_page IN ({array_int:id_pages}) and c.id_comment IN ({array_int:id_comments})
			ORDER BY c.id_comment DESC
			LIMIT {int:start}, {int:end}',
			array(
				'id_pages' => $pages,
				'start' => $context['start'],
				'end' => $show,
				'id_comments' => $first_comment_unread,
			)
		);

		while($row = $smcFunc['db_fetch_assoc']($query)){

			$counter++;

			//Count unread coments of this page
			if(!empty($check_unread[$row['id_page']]))
				$count_comments = count($check_unread[$row['id_page']]);


			$pages_info[] = array(
				'counter' => $counter,
				'alternate' => (($counter % 2) == 0) ? 0 : 1,
				'link' => '<a href="'.$scripturl.'?page='.$row['urltext'].';comment='.$row['id_comment'].'#'.$row['id_comment'].'">'.$row['titlepage'].'</a>'.((!empty($count_comments)) ? (' <br /><span class="smalltext">'.$txt['adkmod_pages_unread'].':'.$count_comments.'</span>') : ''),
				'views' => $row['views'],
				'member_link' => '<a href="'.$scripturl.'?action=profile;u='.$row['id_member'].'">'.$row['real_name'].'</a>',
				'date' => timeformat($row['date']),
			);
		}

		$smcFunc['db_free_result']($query);

		$context['unread_comments'] = $pages_info;
	}

	$context['page_index'] = constructPageIndex($scripturl . '?action=pages;sa=unread', $context['start'], $total, $show);
}

function AdkCleanUnreadComments(){

	checkSession('get');

	global $user_info;

	updateMemberData($user_info['id'], array('adk_pages_notifications' => ''));

	redirectexit('action=pages');
}

//Well, load my shoutbox
function ShowShoutbox()
{
	global $txt, $adkportal, $context, $smcFunc, $scripturl, $user_info, $boardurl;
	
	//Load our Custom language
	adkLanguage('Adk-echomodules');

	//id_group?
	$continue = shoutboxPermissions('view');
	
	//Are you allowed to view shoutbox?
	if(!$continue)
		fatal_lang_error('adkfatal_shout_now_allowed', false);
	
	//Load Linktree
	setLinktree('adk_shoutbox', 'adkmodules_shouts');
	
	//Delete any?
	if(!empty($_REQUEST['del']) && $user_info['is_admin'])
		deleteShouts((int)$_REQUEST['del']);
	
	//Template
	adktemplate('Adk-echomodules');
	
	//Load our css
	$context['html_headers'] .= getCss('modules');
	
	//Shouts limit
	$shout_limit = 20;
	
	//Start from?
	$context['start'] = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
	
	//Load total shoutbox
	$total = getTotal('adk_shoutbox');
	
	//Well.... construct page index.
	$context['page_index'] = constructPageIndex($scripturl . '?action=adk_shoutbox', $context['start'], $total, $shout_limit);
	
	//Load shouts
	$context['shouts'] = getShouts($context['start'], $shout_limit);
	
	//Show the page_title
	$context['page_title'] = $txt['adkmodules_shouts'];
	
	//Sub_template
	$context['sub_template'] = 'load_shout';
	
}

function AddThisTopic()
{
	global $smcFunc, $adkportal, $user_info, $txt, $scripturl, $context;

	//Get The requestion action
	$request_actions = array(
		'add' => 'addNewsIntoPortal',
		'remove' => 'removeNewPortal',
	);

	//Add new?
	if(!empty($_REQUEST['add']))
		$request_actions['add']();

	//RemoveAction?
	elseif(!empty($_REQUEST['remove']))
		$request_actions['remove']();

	//Nothing else? redirect to exit;
	else
		redirectexit();
}

function addNewsIntoPortal()
{

	global $smcFunc, $user_info;

	//Only allowed to manage adkportal can do it
	isAllowedTo('adk_portal');
		
	//Set the add id
	if(empty($_REQUEST['add']))
		fatal_lang_error('adkfatal_adding_news_false', false);

	$id = (int)$_REQUEST['add'];
	
	//Load info topic
	$sql = $smcFunc['db_query']('','
		SELECT m.id_topic, m.subject, m.body, m.poster_name
		FROM {db_prefix}messages AS m
		INNER JOIN {db_prefix}topics AS t ON (t.id_first_msg = m.id_msg)
		WHERE t.id_topic = {int:topic}',
		array(
			'topic' => $id,
		)
	);
	
	//Error if this topic does not exists
	if($smcFunc['db_num_rows']($sql) == 0){
		fatal_lang_error('adkfatal_adding_news_false', false);
	}

	//Set the info topic
	$row = $smcFunc['db_fetch_assoc']($sql);
	$smcFunc['db_free_result']($sql);
		
	//$body = addslashes(un_CleanAdkStrings($smcFunc['htmlspecialchars']($row['body'])));
	$title = $row['subject'];
	$name = $user_info['name'];
	$time = time();
	
	//Insert into this table
	$smcFunc['db_insert']('insert',
		'{db_prefix}adk_news',
		array('titlepage' => 'text', 'new' => 'text', 'autor' => 'text', 'time' => 'int'),
		array($title, '', $name, $time),
		array('id')
	);

		
	$id_new = 0;
	$id_new = $smcFunc['db_insert_id']('{db_prefix}adk_news');
	
	//Update id_new form {db_prefix}topics
	$smcFunc['db_query']('','
		UPDATE {db_prefix}topics
		SET id_new = {int:new}
		WHERE id_topic = {int:topic}',
		array(
			'new' => $id_new,
			'topic' => $id,
		)
	);
		
	redirectexit('topic='.$id.'.0');
}

function removeNewPortal()
{

	global $smcFunc;

	//Managing adkportal? remove it
	isAllowedTo('adk_portal');

	//Set it idremove
	$id_topic = (int)$_REQUEST['remove'];
		
	$sql = $smcFunc['db_query']('','
		SELECT id_new
		FROM {db_prefix}topics
		WHERE id_topic = {int:topic}',
		array(
			'topic' => $id_topic,
		)
	);

	if($smcFunc['db_num_rows']($sql) == 0)
		redirectexit();
		
	list ($id_new) = $smcFunc['db_fetch_row']($sql);
	$smcFunc['db_free_result']($sql);
		
	$smcFunc['db_query']('','
		UPDATE {db_prefix}topics
		SET id_new = {int:new}
		WHERE id_topic = {int:t}',
		array(
			'new' => 0,
			't' => $id_topic,
		)
	);
	
	deleteEntry('adk_news', 'id = {int:id}', array('id' => $id_new));

	redirectexit('topic='.$id_topic.'.0');
}

function AdkCredits()
{
	global $txt, $context, $scripturl, $boardurl;
	
	//Load our Custom language
	adkLanguage('Adk-echomodules');
	
	adktemplate('Adk-echomodules');
	
	$context['sub_template'] = 'adk_credits';
	$context['page_title'] = $txt['adkmodules_credits'];
	$context['html_headers'] .= getCss('modules');

	$context['adk_staff'] = getFile('http://www.smfpersonal.net/news/staff.php');
	$context['adk_friends'] = getFile('http://www.smfpersonal.net/news/friends.php');
	//Set the linktree
	setLinktree('adk_credits', 'adkmodules_credits');
}

function AdkContact()
{
	if(!empty($_REQUEST['sa']) && $_REQUEST['sa'] == 'send')
		$function = 'AdkContactSend';
	else
		$function = 'AdkContactWrite';
	
	//Load our Custom language
	adkLanguage('Adk-echomodules');
	
	adktemplate('Adk-echomodules');
	
	if(!allowedToViewContactPage())
		fatal_lang_error('adkfatal_shout_now_allowed',false);
	
	$function();
}

function AdkContactWrite()
{
	global $txt, $context, $scripturl, $adkportal, $sourcedir, $smcFunc;
	
	$context['page_title'] = $txt['adkmod_modules_contacto'].' - '.$context['forum_name'];
	$context['sub_template'] = 'adk_contact';
	$context['html_headers'] .= getCss('modules');
	
	setLinktree('contact','adkmod_modules_contacto');

	if(empty($adkportal['adk_enable_contact']))
		fatal_lang_error('adkfatal_shout_now_allowed',false);

	//get visual verification
	getVisualverification();

	//Select admin of the site
	$sql = $smcFunc['db_query']('','
		SELECT id_member, real_name
		FROM {db_prefix}members
		WHERE id_group = {int:admin}
		ORDER BY id_member ASC',
		array(
			'admin' => 1,
		)
	);
	
	$members = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
		$members[$row['id_member']] = $row['real_name'];
	
	$context['members_admin'] = $members;
	
	$smcFunc['db_free_result']($sql);
}

function AdkContactSend()
{
	global $smcFunc, $context, $sourcedir, $user_info, $txt, $mbname;
	
	checkSession('post');
	
	//Wrong fields?
	if(empty($_POST['subject']) || empty($_POST['name']) || empty($_POST['email']) || empty($_POST['descript']))
		fatal_lang_error('adkfatal_form_error',false);
	
	//Captcha error?
	setCaptchaError();
	
	$admins = array();
	
	$id_admin = (int)$_POST['admin'];
	
	if(!empty($id_admin))
		$where = 'id_member = {int:member}';
	else
		$where = 'id_group = {int:admin}';
	
	$sql = $smcFunc['db_query']('','
		SELECT email_address
		FROM {db_prefix}members
		WHERE '.$where,
		array(
			'member' => (int)$_POST['admin'],
			'admin' => 1,
		)
	);

	while($row = $smcFunc['db_fetch_assoc']($sql))
		$admins[] = $row['email_address'];

	$smcFunc['db_free_result']($sql);
	
	$body = Adk_formclear($_REQUEST['descript']);
	$name = htmlspecialchars($_POST['name'],ENT_QUOTES);
	$email = htmlspecialchars($_POST['email'],ENT_QUOTES);
	$subject = htmlspecialchars($_POST['subject'],ENT_QUOTES);

	$subject = $subject.' ( '.$mbname.' )';
	$from = $email;
	$to = $admins;
	$message = $body;
	$message_id = null;
	$send_html = true;
	$priority = 3;
	$hotmail_fix = null;
	$is_private = false;

	$message .= '<br /><br /><hr />'.$txt['adkmodules_email'].': '.$email.'<br />'.$txt['adkmodules_name'].': '.$name;

	//Sending mail :)
	require_once($sourcedir . '/Subs-Post.php');
	sendmail($to, $subject, $message, $from, $message_id, $send_html, $priority, $hotmail_fix, $is_private);

	redirectexit('action=contact;sended');
	
}

?>