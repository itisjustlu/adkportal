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
	
function Adkportal()
{
	global $context, $txt, $adkportal;

	adktemplate('Adkportal');
	
	//Load main trader template.
	$context['sub_template']  = 'home';

	//Set the page title
	$context['page_title'] = !empty($adkportal['change_title']) ? $adkportal['change_title'] : $context['forum_name'] . ' - '. $txt['adkmod_portal'];

}

?>