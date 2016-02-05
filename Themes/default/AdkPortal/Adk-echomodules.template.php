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

function template_load_pages_adkportal()
{
	global  $scripturl, $context, $txt, $adkportal, $boardurl;
	
	//cat_bar or titlebar?
	$cat = $context['page_view_content']['catbg'] == 'catbg' ? 'cat' : 'title';
	
	echo'
		<table border="0" class="m_100">
			<tr>
				<td valign="top">
					<table class="m_100">
						<tr>
							<td>
								<div class="'.$cat.'_bar">
									<h3 class="'.$context['page_view_content']['catbg'].'">
									'.$context['page_view_content']['titlepage'].'
									</h3>
								</div>
								<div class="'.$context['page_view_content']['winbg'].'"><span class="topslice"><span></span></span>
									<div class="content">
										',analizar_type($context['page_view_content']['type'], $context['page_view_content']['body']),'
									</div>
								<span class="botslice"><span></span></span></div>
							</td>
						</tr>
					</table>
				</td>';
	
	echo'
			</tr>			
		</table>';
			
}

function template_load_shout()
{
	global $scripturl, $context, $txt, $adkFolder, $user_info;

	if (!empty($context['shouts']))
	echo'
		<div class="smalltext">
			'.$txt['pages'].': '.$context['page_index'].'
		</div>
		<br />';

	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img class="adk_vertical" style="vertical-align: middle;" alt="*" src="'.$adkFolder['images'].'/time.png" />&nbsp;'.$txt['adkmodules_shouts'].'
			</h3>
		</div>
		<span class="upperframe"><span>&nbsp;</span></span>
		<div class="roundframe">
			<div style="padding: 3px;">';


	if (!empty($context['shouts'])) {
		echo'
				<table class="table_grid" cellspacing="0" style="width: 100%;">
					<thead>
						<tr class="catbg">
							<th scope="col" class="smalltext first_th">
								'.$txt['adkmod_block_posts'].'
							</th>
							<th scope="col" class="smalltext" width="15%">
								', $txt['adkmodules_autor'] ,'
							</th>
							<th scope="col" class="smalltext',!$user_info['is_admin'] ? ' last_th' : '' ,'" width="15%">
								', $txt['adkmodules_date'] ,'
							</th>
							',$user_info['is_admin'] ? '
							<th scope="col" class="smalltext last_th" width="4%">
								&nbsp;
							</th>' : '' ,'
						</tr>
					</thead>
					<tbody>';
	
		foreach($context['shouts'] AS $shout)
		{
			echo'
						<tr class="'.$shout['alternate'].' whos_viewing adk_padding_5">
							<td>
								'.$shout['message'].'
							</td>
							<td>
								'.$shout['user'].'
							</td>
							<td>
								'.$shout['date'].'
							</td>';
		
			if($user_info['is_admin'])
			echo'
							<td align="center">
								<a href="'.$scripturl.'?action=adk_shoutbox;del='.$shout['id'].'" onclick="return confirm(\'', $txt['adkmodules_remove_message'], '?\');">
									<img alt="" src="'.$adkFolder['images'].'/cancel.png" />
								</a>
							</td>';
			echo'				
				
						</tr>';
		}
	
		echo'	
					</tbody>
				</table>';
	}
	else 
	echo $txt['adkmod_block_nopost'];

	echo'
				</div>
			</div>
			<span class="lowerframe"><span>&nbsp;</span></span>
			<br />';

	if (!empty($context['shouts']))
	echo'
		<div class="smalltext">
			'.$txt['pages'].': '.$context['page_index'].'
		</div>';
		
}

function template_adk_credits()
{
	global $txt, $scripturl, $context, $boardurl, $settings, $adkFolder;

	//A partir de aca el codigo de los creditos.
	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/award_star_gold_1.png" alt="" /> 
				'.$txt['adkmodules_credits_01'].'
			</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span>&nbsp;</span></span>
			<div class="content">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$txt['adkmodules_credits_02'].'<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$txt['adkmodules_credits_03'].'<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$txt['adkmodules_credits_04'].'<br />
			</div>
			<span class="botslice"><span>&nbsp;</span></span>
		</div>
		<div class="cat_bar">
			<h3 class="catbg">
				<img class="m_vertical_middle" src="'.$adkFolder['images'].'/award_star_gold_1.png" alt="" /> 
				'.$txt['adkmodules_credits_05'].'
			</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span>&nbsp;</span></span>
			<div class="content">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$txt['adkmodules_credits_06'].'<br /> 
				<br />&nbsp;&nbsp;<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/bullet_green.png" alt="" /> '.$txt['adkmodules_credits_07'].'
				<br />&nbsp;&nbsp;<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/bullet_green.png" alt="" /> '.$txt['adkmodules_credits_08'].'
				<br />&nbsp;&nbsp;<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/bullet_green.png" alt="" /> '.$txt['adkmodules_credits_09'].'
				<br />&nbsp;&nbsp;<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/bullet_green.png" alt="" /> '.$txt['adkmodules_credits_10'].'
			</div>
			<span class="botslice"><span>&nbsp;</span></span>
		</div>
		<div class="cat_bar">
			<h3 class="catbg">
				<img class="m_vertical_middle" src="'.$adkFolder['images'].'/award_star_gold_1.png" alt="" /> 
				'.$txt['adkmodules_credits_11'].'
			</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span>&nbsp;</span></span>
			<div class="content">
				',$context['adk_staff'],'
			</div>
			<span class="botslice"><span>&nbsp;</span></span>
		</div>
		<div class="cat_bar">
			<h3 class="catbg">
				<img class="m_vertical_middle" src="'.$adkFolder['images'].'/award_star_gold_1.png" alt="" /> 
				'.$txt['adkmodules_credits_12'].'
			</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span>&nbsp;</span></span>
			<div class="content">
				',$context['adk_friends'],'
			</div>
			<span class="botslice"><span>&nbsp;</span></span>
		</div>';
}

function template_adk_contact()
{
	global $txt, $scripturl, $context, $boardurl, $user_info, $adkFolder;
	
	if(isset($_REQUEST['sended'])){
		echo'
			<br /><br /><br />
			<div class="information Adk_contact_send_message">
				',$txt['adkmodules_form_sendeded'],'
			</div>
			<script type="text/javascript">
				function redirection(){  
					window.location ="'.$scripturl.'";
				}
				setTimeout ("redirection()", 2000);
			</script>';
	}
	else {
		echo '
			<br />
			<form method="post" action="'. $scripturl .'?action=contact;sa=send">
				<div class="cat_bar">
					<h3 class="catbg">
						<img alt="',$txt['adkmodules_form_contact'],'" style="vertical-align: middle;" src="'.$adkFolder['images'].'/contact/postscript.png" /> ',$txt['adkmodules_form_contact'],'
					</h3>
				</div>
				<span class="clear upperframe"><span>&nbsp;</span></span>
				<div class="roundframe">
					<div>
						<dl id="post_header">';
				if(!$context['user']['is_guest'])
					echo'
							<dt><input type="hidden" name="name" value="',$user_info['name'],'" /></dt>
							<dd><input type="hidden" name="email" value="',$user_info['email'],'" /></dd>';
		echo'					
							<dt>
								<img alt="" src="'.$adkFolder['images'].'/contact/agt.png" />&nbsp;',$txt['subject'],'
							</dt>
							<dd>
								<input type="text" name="subject" value="" size="80" maxlength="80" class="input_text" />
							</dd>';
				if($context['user']['is_guest'])
					echo'
							<dt>
								<img alt="" src="'.$adkFolder['images'].'/contact/user_suit.png" />&nbsp;',$txt['adkmodules_name'],'
							</dt>
							<dd>
								<input type="text" name="name" value="" size="80" maxlength="80" class="input_text" />
							</dd>
							<dt>
								<img alt="" src="'.$adkFolder['images'].'/postscript.png" />&nbsp;',$txt['adkmodules_email'],'
							</dt>
							<dd>
								<input type="text" name="email" value="" size="80" maxlength="80" class="input_text" />
							</dd>';
		echo'
							<dt>
								<img alt="" src="'.$adkFolder['images'].'/contact/users.png" />&nbsp;',$txt['adkmodules_form_select_admin'],'
							</dt>
							<dd>
								<select name="admin">
									<option value="0">',$txt['adkmodules_form_send_all'],'</option>';
				foreach($context['members_admin'] AS $id_member => $name)
					echo'
									<option value="',$id_member,'">',$name,'</option>';

		echo'
								</select>&nbsp;',help_link('','adkhelp_form_select_admin',false),'
							</dd>
						</dl>
						<div class="Adk_contact">
							<table style="width:100%">
								<tr>
									<td class="ae_center" colspan="2">
										<div class="ae_desc">'.$txt['adkmodules_form_send_content'].'</div>
										<textarea cols="600" rows="10" id="descript" name="descript" style="height:160px; width: 855px;"></textarea>
										<br />
									</td>
								</tr>
							</table>
						</div>
						<div id="form_contact" class="post_verification">
							', template_control_verification($context['visual_verification_id'], 'all'), '
						</div>
						<br />
						<div align="center">
							<input type="submit" value="',$txt['save'],'" class="button_submit" />
							<input type="hidden" name="sc" value="',$context['session_id'],'" />
						</div>
					</div>
				</div>
				<span class="lowerframe"><span>&nbsp;</span></span>
			</form>';
	}

}

function template_page_system(){

	global $context, $scripturl, $txt, $boardurl, $adkFolder;

	foreach($context['pages'] AS $page) {
		echo'
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
					<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/news.png" alt="'.$page['titlepage'].'" />
					<strong>
						<a href="'.$scripturl.'?page='.$page['urltext'].'">
							'.$page['titlepage'].'
						</a>
					</strong>
					<hr />
					<div class="smalltext">
						',analizar_type($page['type'], $page['body'], true),'
					</div>
					<br />
				</div>
			</div>
			<span class="lowerframe">
				<span>&nbsp;</span>	
			</span>';
	}

	echo'
			<div class="smalltext" style="text-align: right; margin: 10px 10px 0px 0px;">
				'.$txt['pages'].': '.$context['page_index'].'
			</div>';

}
?>