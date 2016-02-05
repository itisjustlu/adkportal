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

// Portal
function template_home(){
	
	//I wanna load the column center only
	createColumn('center');
}

// Index.template
function template_Adk_blocks_above()
{
	global $adkportal, $context, $modSettings;

	if (function_exists('loadCTop')) {
		if ($context['user']['is_logged'])
			loadCTop();
		
		elseif (($context['user']['is_guest']) && (!empty($adkportal['adk_guest_view_post'])) && (empty($modSettings['allow_guestAccess'])) && (empty($_REQUEST['action'])))
			loadCTop();	
		
		elseif (($context['user']['is_guest']) && (!empty($modSettings['allow_guestAccess'])))
			loadCTop();	
	}

}
function template_Adk_blocks_below()
{
	global $adkportal, $context, $modSettings;

	if (function_exists('loadCBottom')) {
		if ($context['user']['is_logged'])
			loadCBottom();
		
		elseif (($context['user']['is_guest']) && (!empty($adkportal['adk_guest_view_post'])) && (empty($modSettings['allow_guestAccess'])) && (empty($_REQUEST['action'])))
			loadCBottom();	
		
		elseif (($context['user']['is_guest']) && (!empty($modSettings['allow_guestAccess'])))
			loadCBottom();	
	}

}

?>