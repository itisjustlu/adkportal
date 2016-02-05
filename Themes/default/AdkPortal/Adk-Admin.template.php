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

//Las noticias
function template_view()
{
	global  $scripturl, $context, $txt, $adkportal, $boardurl, $adkFolder;
	
	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img class="adk_vertical_align" alt="" src="'.$adkFolder['images'].'/new.png" />&nbsp;'.$txt['adkadmin_news_smfp'].'
			</h3>
		</div>
		<table class="adk_100">
			<tr>
				<td class="adk_70">
					<span class="clear upperframe"><span></span></span>
					<div class="roundframe">
					<div>
						<div class="adk_padding_2">
						<div class="adk_get_news"><div class="adk_get_news_2">',getAdkNews(),'</div>
						</div></div>
					</div>
					</div>
					<span class="lowerframe"><span></span></span>
				</td>
				<td class="adk_30">
					<span class="clear upperframe"><span></span></span>
					<div class="roundframe">
					<div>
						<div class="adk_padding_2">
							<div class="adk_get_news">
								<div class="adk_get_news_2">
									',help_link('','adkhelp_help_version',false),'&nbsp;'.$txt['adkadmin_yourversion'].': '.$context['adkportal']['your_version'].'<br />
									',help_link('','adkhelp_help_current_version',false),'&nbsp;'.$txt['adkadmin_currentversion'].': '.$context['adkportal']['style_version'].'
									',$txt['adkmod_Adkportaldonate'],'
									<div class="smalltext" style="text-align: center; margin-top: 63px;">
										',help_link('','adkhelp_smf_personal',false),'
										<a href="http://www.smfpersonal.net/index.php?action=support;sa=adkportal" title="',$txt['adkmod_smf_personal'],'" target="_blank">
											',$txt['adkmod_smf_personal'],'
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					</div>
					<span class="lowerframe"><span></span></span>
				</td>
			</tr>
		</table>';

		
}

//Ajustes principales
function template_adksettings()
{
	global  $scripturl, $context, $txt, $adkportal, $boardurl, $modSettings, $maintenance, $adkFolder;
	
	echo'
	<form method="post" action="'. $scripturl .'?action=admin;area=adkadmin;sa=adksavesettings">
		<div class="cat_bar">
			<h3 class="catbg">
				<img class="adk_vertical_align" alt="" src="'.$adkFolder['images'].'/computer.png" />&nbsp;'.$txt['adkadmin_setting'].'
			</h3>
		</div>
		<span class="clear upperframe">
			<span>&nbsp;</span>	
		</span>
		<div class="roundframe">
			<div>
				<table class="size_100">
					<tr>
						<td class="size_30">
							<b>'.$txt['adkadmin_general_settings'].'</b>
						</td>
						<td class="size_70">
							<fieldset>
								<legend style="color: teal">'.$txt['adkadmin_setting'].'</legend>
								<table class="size_100">
									<tr>
										<td class="size_60">
											',help_link('adkportal_activate','adkhelp_activate'),'
										</td>
										<td class="size_40 alingright">
											<select name="adk_enable">
												<option value="0"',analizar_selected_adk('adk_enable', 0),'>'.$txt['adkadmin_disable'].'</option>
												<option value="1"',analizar_selected_adk('adk_enable', 1),'>'.$txt['adkadmin_enable'].'</option>
												<option value="2"',analizar_selected_adk('adk_enable', 2),'>'.$txt['adkadmin_stand'].'</option>
											</select>
										</td>
									</tr>
									
									<tr>
										<td class="size_60">
											',help_link('adkadmin_change_title','adkhelp_change_title'),'
										</td>
										<td class="size_40 alingright">
											<input type="text" name="change_title"  value="',!empty($adkportal['change_title']) ? $adkportal['change_title'] : '' ,'" />
										</td>
									</tr>
									<tr>
										<td class="size_60">
											',help_link('adkadmin_hide_version','adkhelp_hide_version'),'
										</td>
										<td class="size_40 alingright">
											<input type="checkbox" name="adk_hide_version"',getCheckbox('adk_hide_version'),' />
										</td>
									</tr>
	
									<tr>
										<td class="size_60">
											',help_link('adkadmin_guest_view_post','adkhelp_guest_view_post'),'
										</td>
										<td class="size_40 alingright">
											<input type="checkbox" name="adk_guest_view_post"',getCheckbox('adk_guest_view_post'),' />
										</td>
									</tr>

									<tr>
										<td class="size_60">
											',help_link('adkadmin_linktree_portal','adkhelp_linktree_portal'),'
										</td>
										<td class="size_40 alingright">
											<input type="checkbox" name="adk_linktree_portal"',getCheckbox('adk_linktree_portal'),' />
										</td>
									</tr>

									<tr>
										<td class="size_60">
											',help_link('adkadmin_include_ssi','adkhelp_include_ssi'),'
										</td>
										<td class="size_40 alingright">
											<input type="checkbox" name="adk_include_ssi"',getCheckbox('adk_include_ssi'),' />
										</td>
									</tr>

								</table>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr />
						</td>
					</tr>
					<tr>
						<td class="size_30">
							<b>'.$txt['adkadmin_columns_settings'].'</b>
						</td>
						<td class="size_70">
							<fieldset>
								<legend style="color: teal">'.$txt['adkadmin_setting'].'</legend>
								<table class="size_100">
									<tr>
										<td class="size_60">
											',help_link('adkadmin_disable_colexpand','adkhelp_disable_colexpand'),'
										</td>
										<td class="size_40 alingright">
											<input type="checkbox" name="adk_disable_colexpand"',getCheckbox('adk_disable_colexpand'),' />
										</td>
									</tr>
									<tr>
										<td class="size_60">
											',help_link('adkadmin_width_portal','adkhelp_width_portal'),'
										</td>
										<td class="size_40 alingright">
											'.$txt['adkadmin_left'].' <input type="text" size="4" name="wleft" value="',$adkportal['wleft'],'" /><br />
											'.$txt['adkadmin_right'].' <input type="text" size="4" name="wright" value="',$adkportal['wright'],'" />
										</td>
									</tr>
								</table>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr />
						</td>
					</tr>
					<tr>
						<td class="size_30">
							<b>'.$txt['adkadmin_more_settings'].'</b>
						</td>
						<td class="size_70">
							<fieldset>
								<legend style="color: teal">'.$txt['adkadmin_setting'].'</legend>
								<table class="size_100">
									<tr>
										<td class="size_60">
											',help_link('adkadmin_format_blocks','adkhelp_format_blocks'),'
										</td>
										<td class="size_40 alingright">
											<select name="title_in_blocks">
												<option value="5" ',analizar_selected_adk('title_in_blocks',5),'>'.$txt['adkadmin_alternative'].'</option>
												<option value="3" ',analizar_selected_adk('title_in_blocks',3),'>'.$txt['adkadmin_windowbg'].'</option>
												<option value="6" ',analizar_selected_adk('title_in_blocks',6),'>'.$txt['adkadmin_teu'].'</option>
												<option value="1" ',analizar_selected_adk('title_in_blocks',1),'>'.$txt['adkadmin_roundFrame'].' ('.$txt['adkadmin_catbg'].')</option>
												<option value="7" ',analizar_selected_adk('title_in_blocks',7),'>'.$txt['adkadmin_roundFrame'].' ('.$txt['adkadmin_titlebg'].')</option>
												<option value="4" ',analizar_selected_adk('title_in_blocks',4),'>'.$txt['adkadmin_windowbg'].'  + '.$txt['adkadmin_inblocks'].'</option>
												<option value="2" ',analizar_selected_adk('title_in_blocks',2),'>'.$txt['adkadmin_roundFrame'].' + '.$txt['adkadmin_inblocks'].' ('.$txt['adkadmin_catbg'].')</option>
												<option value="8" ',analizar_selected_adk('title_in_blocks',8),'>'.$txt['adkadmin_roundFrame'].' + '.$txt['adkadmin_inblocks'].' ('.$txt['adkadmin_titlebg'].')</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="size_60">
											',help_link('adkadmin_enable_img_blocks','adkhelp_enable_img_blocks'),'
										</td>
										<td class="size_40 alingright">
											'.$txt['yes'].'<input type="radio" value="1" name="enable_img_blocks" ',$adkportal['enable_img_blocks'] == 1 ? 'checked="checked"' : '' ,' /> '.$txt['no'].'<input type="radio" value="0" name="enable_img_blocks"  ',$adkportal['enable_img_blocks'] == 0 ? 'checked="checked"' : '' ,' />
										</td>
									</tr>
								</table>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr />
							<br style="clear: both;" />
							<div style="float: right;">
								<input type="hidden" name="sc" value="', $context['session_id'], '" />
								<input class="button_submit" type="submit" value="'.$txt['save'].'" />
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<span class="lowerframe">
			<span>&nbsp;</span>	
		</span>
	</form>';	
	
}

function template_view_icons()
{
	global $context, $txt, $boardurl, $scripturl, $settings, $adkFolder;
	
	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img class="adk_vertical_align" alt="" src="'.$adkFolder['images'].'/drive_add.png" />&nbsp;'.$txt['adkadmin_icons'].'
			</h3>
		</div>
		<table class="size_100">
			<tr>
				<td class="size_60">
					<span class="clear upperframe">
						<span>&nbsp;</span>	
					</span>
					<div class="roundframe">
						<div style="min-height: 280px;">
							<table class="size_100">';
						foreach ($context['load_icons'] AS $icon)
						{
							echo'
								<tr>
									<td class="size_50">
										<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/blocks/'.$icon['icon'].'" alt="" />&nbsp;'.$icon['icon'].'
									</td>
									<td class="size_50" style="text-align:right">
										<a onclick="return confirm(\'', $txt['adkportal_delete_icon'], '\');" href="'.$scripturl.'?action=admin;area=adkadmin;sa=manageicons;set=deleteicon;id='.$icon['id'].';'.$context['session_var'].'='.$context['session_id'].'">
											'.$txt['adkportal_delete'].'<img style="vertical-align: bottom; padding-left: 4px;" src="'.$settings['default_images_url'].'/icons/delete.gif" alt="" title="" />
										</a>
									</td>
								</tr>';
						}
							echo'
								<tr>
									<td colspan="2">
										<hr />
										<div class="smalltext" style="text-align:right">
												'.$txt['pages'].': '.$context['page_index'].'
										</div>					
									</td>
								</tr>
							</table>
						</div>
					</div>
					<span class="lowerframe">
						<span>&nbsp;</span>	
					</span>
				</td>
				<td class="size_40">
					<span class="clear upperframe">
						<span>&nbsp;</span>	
					</span>
					<div class="roundframe">
						<div>
							<img style="vertical-align: top; padding-right: 2px;" src="'.$adkFolder['images'].'/blocks/disk.png" alt="" />&nbsp;
							<a onclick="mostrar(\'addicons\');return false;">
								<strong>'.$txt['adkportal_add_icon'].'</strong>
							</a>
							<br /><br /><br />
							<div id="addicons">
								<form enctype="multipart/form-data" action="'.$scripturl.'?action=admin;area=adkadmin;sa=manageicons;set=saveicon" method="post">
								<div id="addicon">
									<fieldset>
										<legend style="color: teal">'.$txt['adkportal_select_a_icon'].'</legend>
											<div class="adk_padding_8 text_align_center">
												<input size="27" type="file" value="" name="file" /><br />
											</div>
									</fieldset>
									<div style="text-align:right">
										<input type="hidden" name="sc" value="', $context['session_id'], '" />
										<input class="button_submit" type="submit" value="'.$txt['save'].'" />
									</div>
									<br /><br />
								</div>
								</form>
							</div>
						</div>
					</div>
					<span class="lowerframe">
						<span>&nbsp;</span>	
					</span>
				</td>
			</tr>
		</table>';


}

function template_stand_alone_admin(){

	global $context, $txt, $scripturl, $boardurl, $adkportal, $adkFolder;

	echo'
	<form method="post" action="'. $scripturl .'?action=admin;area=adkadmin;sa=save_stand">
		<div class="cat_bar">
			<h3 class="catbg">
				<img class="adk_vertical_align" alt="" src="'.$adkFolder['images'].'/computer.png" />&nbsp;'.$txt['adkadmin_stand'].'
			</h3>
		</div>
		<span class="clear upperframe">
			<span>&nbsp;</span>	
		</span>
		<div class="roundframe">
			<div>
				<table style="width: 100%;">
					<tr>
						<td class="size_50">
							',help_link('adkadmin_url_stand','adkhelp_url_stand'),'
							<div class="smalltext">',$txt['adkadmin_mofe_info_stand'],'</div>
						</td>
						<td class="size_40 alingright">
							<input type="text" name="adk_stand_alone_url"  value="',!empty($adkportal['adk_stand_alone_url']) ? $adkportal['adk_stand_alone_url'] : '' ,'" />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr />
							<br style="clear: both;" />
							<div style="float: right;">
								<input type="hidden" name="sc" value="', $context['session_id'], '" />
								<input class="button_submit" type="submit" value="'.$txt['save'].'" />
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<span class="clear lowerframe">
			<span>&nbsp;</span>	
		</span>
	</form>';
}

?>