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


/****************************************************************************
** Here... You need to add the folder of your forum.... 
** For example... If your forum is located in www.yourforum.com/forumsmf
** Use: $forum_dir = 'forumsmf';
** If your forum is located in www.yourforum.com/
** Use $forum_dir = ''; 
** Any question... please go to Smfpersonal.net
** Regards ;)
****************************************************************************/
$forum_dir = 'forum';
$forum_version = 'SMF 2.0.7';

//Wrong dir?
if (!file_exists($forum_dir.'/SSI.php'))
	die('Wrong $forum_dir value. Please... modify the $forum_dir value ;).');

//I love SSI :D
require_once($forum_dir.'/SSI.php');

//Now, Load Adk Portal File
require_once($sourcedir . '/AdkPortal/Adkportal.php');

if (WIRELESS)
	redirectexit();

Adkportal();

obExit(null, null, true);

?>