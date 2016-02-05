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

function load_pages_adkportal($request = '')
{
	global $context, $smcFunc, $scripturl, $adkportal;
	
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
	
	//Return error if this does not exists :)
	if(empty($context['page_view_content']))
		fatal_lang_error('adkfatal_page_not_exist', false);
		
	//Comprobamos si los visitantes pueden ver esta pagina
	//permission_groups_other_pages($context['page_view_content']['groups_allowed']);
		
	//Set the page_title
	$context['page_title'] = $context['page_view_content']['titlepage'];
		
	//Load Linktree
	if(!empty($adkportal['enable_menu_pages']))
		setLinktree('pages', 'adkmod_pages');

	setLinktree($scripturl.'?page='.$request, $context['page_view_content']['titlepage'], true, true);

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

function AdkPageSystem()
{

	global $context, $txt, $adkportal, $user_info, $scripturl;

	if(empty($adkportal['enable_menu_pages']))
		fatal_lang_error('adkfatal_module_not_enable', false);

	adktemplate('Adk-echomodules');
	adkLanguage('Adk-echomodules');

	setLinktree('pages', 'adkmod_pages');

	$context['sub_template'] = 'page_system';
	$context['page_title'] = $txt['adkmodules_index_pages'];

	$total = getTotal('adk_pages', '(FIND_IN_SET(' . (implode(', grupos_permitidos) != 0 OR FIND_IN_SET(', $user_info['groups'])) . ', grupos_permitidos) != 0)');
	$show = 5;
	$start = !empty($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;

	$context['pages'] = getPages($start, $show, '', 'titlepage ASC', array(), true);

	if(empty($context['pages']))
		fatal_lang_error('adkfatal_module_not_enable', false);

	$context['page_index'] = constructPageIndex($scripturl . '?action=pages', $start, $total, $show);

}
?>