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

function template_introAdk()
{
	global $scripturl, $context, $txt, $boardurl, $adkFolder;
	
	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img alt="" style="vertical-align: middle;" src="'.$adkFolder['images'].'/link.png" /> '.$txt['adkmodules_modules_settings'].'
			</h3>
		</div>
		<span class="clear upperframe">
			<span>&nbsp;</span>	
		</span>
		<div class="roundframe">
			<div>
				',help_link('adkmodules_disponibles_modulos','adkhelp_disponibles_modulos'),'
				<hr />
				<div class="smalltext">
					',!empty($context['file']) ? $context['file'] : '<strong>'.$txt['adkmodules_no_modules'].'</strong>','
				</div>
			</div>
		</div>
		<span class="lowerframe">
			<span>&nbsp;</span>	
		</span>';	

}

function template_viewadminpages()
{
	global  $scripturl, $context, $txt, $adkportal, $boardurl, $adkFolder;

	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/pages.png" /> 
				'.$txt['adkmod_modules_pages'].'
			</h3>
		</div>
		<table class="size_100">
			<tr>
				<td class="size_60">
					<span class="clear upperframe">
						<span>&nbsp;</span>	
					</span>
					<div class="roundframe">
						<div>
							<table class="size_100">';
				if (empty($context['total'])) { 
					echo ' 
								<tr>
									<td colspan="3" style="text-align:center">
										<strong>'.$txt['adkmodules_no_pages'].'</strong>
									</td>
								</tr>';
				}
				else {
					foreach($context['total_admin_pages'] AS $pages)
					{
						
						echo'
								<tr>
									<td class="size_35">
										<img style="vertical-align: text-top;" alt="" src="'.$adkFolder['images'].'/pages.png" />
										<strong><a href="'.$scripturl.'?page='.$pages['urltext'].'">'.$pages['titlepage'].'</a></strong>
									</td>
									<td class="size_30" style="text-align:center">
										<strong>'.$txt['adkmodules_page_view_adk'].':</strong> '.$pages['views'].'
									</td>
									<td class="size_35" style="text-align:right">
										<a href="'.$scripturl.'?action=admin;area=modules;sa=editpages;id='.$pages['id_page'].';'.$context['session_var'].'='.$context['session_id'].'" title="'.$txt['adkmodules_editar'].' '.$pages['titlepage'].'">
											<img alt="" src="'.$adkFolder['images'].'/b_edit.png" />
										</a> - 
										<a onclick="return confirm(\''.$txt['adkmodules_delete_sure'].'\');" href="'.$scripturl.'?action=admin;area=modules;sa=deletepages;id='.$pages['id_page'].';'.$context['session_var'].'='.$context['session_id'].'" title="'.$txt['adkmodules_borrar'].' '.$pages['titlepage'].'">
											<img alt="" src="'.$adkFolder['images'].'/cancel.png" />
										</a>
									</td>
								</tr>';
					}
					echo'
								<tr>
									<td colspan="3">
										<hr />
										<div class="smalltext" style="text-align:right">
												'.$txt['pages'].': '.$context['page_index'].'
										</div>					
									</td>
								</tr>';
				}
	echo'
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
						<div style="text-align: right;">
							<span>
								<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/newmsg.png" />
								<a href="'.$scripturl.'?action=admin;area=modules;sa=createpages;'.$context['session_var'].'='.$context['session_id'].'">
									<strong>'.$txt['adkmodules_admin_pages_create'].'</strong>
								</a><br />
								',help_link('','adkhelp_enable_page_menu', false),'
								<a href="',$scripturl,'?',empty($adkportal['enable_menu_pages']) ? 'set;' : '' ,'action=admin;area=modules;sa=enable_page_menu;',$context['session_var'],'=',$context['session_id'],'">
									<strong>',$txt[!empty($adkportal['enable_menu_pages']) ? 'adkmodules_disable_page_menu' : 'adkmodules_enable_page_menu'],'</strong>
								</a><br />
								<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/messages.png" />
								<a href="',$scripturl,'?',empty($adkportal['enable_pages_comments']) ? 'set;' : '' ,'action=admin;area=modules;sa=enable_comments;',$context['session_var'],'=',$context['session_id'],'">
									<strong>',$txt[!empty($adkportal['enable_pages_comments']) ? 'adkmodules_disable_comments' : 'adkmodules_enable_comments'],'</strong>
								</a><br />
								<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/computer.png" />
								<a href="',$scripturl,'?',empty($adkportal['enable_pages_notifications']) ? 'set;' : '' ,'action=admin;area=modules;sa=enable_notifications;',$context['session_var'],'=',$context['session_id'],'">
									<strong>',$txt[!empty($adkportal['enable_pages_notifications']) ? 'adkmodules_disable_notifications' : 'adkmodules_enable_notifications'],'</strong>
								</a><br />
							</span>
						</div>
					</div>
					<span class="lowerframe">
						<span>&nbsp;</span>	
					</span>
				</td>
			</tr>
		</table>';

}

function template_createpages()
{
	global  $scripturl, $context, $txt, $adkportal, $boardurl, $adkFolder;

	echo'
		<form method="post" action="'. $scripturl .'?action=admin;area=modules;sa=',$context['save_action'],'">';
	
	echo'
			<div class="cat_bar">
				<h3 class="catbg">
					<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/pages.png" /> 
					'.$context['page_title'].'
				</h3>
			</div>
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
					<table style="width:100%;">
						<tr>
							<td style="width: 30%;">
								<div class="smalltext"><strong>'.$txt['adkmodules_titulo'].':</strong></div>
							</td>
							<td style="width: 70%;" >
								<div class="smalltext" style="padding-left: 207px;"><input type="text" value="'.$context['edit_admin_page']['titlepage'].'" name="titlepage" size="30" /></div>
							</td>
						</tr>
						<tr>
							<td style="width: 30%;">
								<div class="smalltext"><strong>'.$txt['adkmodules_pages_url'].':</strong></div>
							</td>
							<td style="width: 70%;">
								<div class="smalltext">http://tuforo.com/index.php?page= <input type="text" value="'.$context['edit_admin_page']['urltext'].'" name="urltext" size="30" /> ('.$txt['adkmodules_pages_minusculas'].')</div>
							</td>
						</tr>
						<tr>
							<td style="width: 30%;">
								<div class="smalltext"><strong>'.$txt['adkmodules_pages_type'].':</strong></div>
							</td>
							<td style="width: 70%;">
								<div class="smalltext">
									PHP<input type="radio" name="type" value="php" ',$context['edit_admin_page']['type'] == 'php' ? 'checked="checked"' : '' ,' style="vertical-align: sub;"/> 
									Html<input type="radio" name="type" value="html" ',$context['edit_admin_page']['type'] == 'html' ? 'checked="checked"' : '' ,' style="vertical-align: sub;"/> 
									BBC<input type="radio" name="type" value="bbc" ',$context['edit_admin_page']['type'] == 'bbc' ? 'checked="checked"' : '' ,' style="vertical-align: sub;"/>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<hr />';
			
				//Get Template EDitor()
				getTemplateEditor();
	
				echo'
							</td>
						</tr>
					</table>
					<br />
					<div class="cat_bar">
						<h3 class="catbg">
							<img alt="" src="'.$adkFolder['images'].'/admin.png" style="vertical-align: middle;" /> ',$txt['adkmodules_options'],'
						</h3>
					</div>
					<div class="adk_padding_8">
						<table style="width: 100%;">
							<tr class="smalltext">
								<td style="width: 50%;">
									&nbsp;
								</td>
								<td style="width: 50%;">
									<fieldset>
										<legend style="color: teal">'.$txt['adkmodules_pages_design'].'</legend>
										<table style="width: 100%;">
											<tr class="smalltext">
												<td style="width: 50%;">
													'.$txt['adkmodules_titulo'].' '.$txt['adkmodules_admin_pages_in'].':
												</td>
												<td style="width: 50%; text-align: right;">
													<select name="cattitlebg" style="width: 82px;">
														<option value="catbg" ',$context['edit_admin_page']['cattitlebg'] == 'catbg' ? 'selected="selected"' : '' ,'>Catbg</option>
														<option value="titlebg" ',$context['edit_admin_page']['cattitlebg'] == 'titlebg' ? 'selected="selected"' : '' ,'>Titlebg</option>
													</select>
												</td>
											</tr>
											<tr class="smalltext">
												<td style="width: 50%;">
													'.$txt['adkmodules_pages_body'].' '.$txt['adkmodules_admin_pages_in'].':
												</td>
												<td style="width: 50%; text-align: right;">
													<select name="winbg" style="width: 82px;">
														<option value="windowbg" ',$context['edit_admin_page']['winbg'] == 'windowbg' ? 'selected="selected"' : '' ,'>Windowbg</option>
														<option value="windowbg2" ',$context['edit_admin_page']['winbg'] == 'windowbg2' ? 'selected="selected"' : '' ,'>Windowbg2</option>
													</select>
												</td>
											</tr>
											<tr class="smalltext">
												<td style="width: 50%;">
													'.$txt['adkmodules_enable_comments'].'
												</td>
												<td style="width: 50%; text-align: right;">
													<input name="enable_comments" type="checkbox" style="vertical-align: middle;"',!empty($context['edit_admin_page']['enable_comments']) ? ' checked="checked"' : '' ,' />
												</td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
						</table>
					</div>
					<div class="adk_padding_8">
						<table style="width: 100%;">
							<tr class="smalltext">
								<td style="width: 50%;">
									&nbsp;
								</td>
								<td style="width: 50%;">
									<fieldset>
										<legend style="color: teal">'.$txt['adkmodules_pages_groups'].'</legend>';
										load_membergroups_edit($context['edit_admin_page']['grupos_permitidos']);
				echo'
										<div style="text-align: right;">
											<i>'.$txt['adkmodules_check_all'].'</i>
											<input style="vertical-align: middle;" type="checkbox" onclick="invertAll(this, this.form, \'groups_allowed\');" />
										</div>
									</fieldset>
								</td>
							</tr>
						</table>
					</div>
					<hr />
					<div style="text-align: center;">
						<input type="hidden" value="'.$context['session_id'].'" name="sc" />
						<input type="submit" value="'.$txt['save'].'" class="button_submit" />
						<input type="hidden" value="'.$context['edit_admin_page']['id_page'].'" name="id_page" />
					</div>
				</div>
			</div>
			<span class="lowerframe">
				<span>&nbsp;</span>	
			</span>	
		</form>';

}

function template_adk_new_image()
{
	global  $scripturl, $context, $txt, $adkportal, $boardurl, $adkFolder;

	echo'
		<form method="post" enctype="multipart/form-data" action="'. $scripturl .'?action=admin;area=modules;sa=saveuploadimg">
			<div class="cat_bar">
				<h3 class="catbg">
					<img alt="" src="'.$adkFolder['images'].'/postscript.png" class="adk_vertical_align" />&nbsp;'.$txt['adkmod_modules_images'].'
				</h3>
			</div>
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
					<table style="width: 100%;">
						<tr>
							<td valign="top" style="width: 30%;">
								',help_link('adkmodules_select_a_format','adkhelp_select_a_format'),'
							</td>
							<td style="width: 70%;">
								<fieldset>
									<legend style="color: teal">'.$txt['adkmodules_select_a_format'].'</legend>
									<table style="width: 100%;">
										<tr>
											<td style="width: 80%;">
												<strong>'.$txt['adkmodules_none'].'</strong>
											</td>
											<td style="width: 20%; text-align: right;">
												<input type="radio" name="format" value="1" checked="checked" />
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<hr />
											</td>
										</tr>';


									if(check_if_gd()){
										echo'
									
										<tr>
											<td style="width: 80%;">
												<a onclick="mostrardesignA(\'designA\');return false;" title="'.$txt['adkmodules_preview_design'].'" style="text-decoration: none">
													<img alt="'.$txt['adkmodules_preview_design'].'" src="'.$adkFolder['images'].'/search.png" style="vertical-align: top;" />
													<strong>'.$txt['adkmodules_pages_design'].'</strong> (Cover)
												</a>
												<div id="designA">
													<div id="designAa">
														<div style="padding: 8px; text-align: right;">
															<fieldset>
																<legend style="color: teal">'.$txt['adkmodules_preview_design'].'</legend>
															<img src="'.$adkFolder['images'].'/covers/1.png" alt="" style="height: 130px; margin: -3px 5px 5px;" />
															</fieldset>
														</div>
													</div>
												</div>
											</td>
											<td style="width: 20%; text-align: right;" valign="top">
												<input type="radio" name="format" value="2" />
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<hr />
											</td>
										</tr>
										<tr>
											<td style="width: 80%;">
												<a onclick="mostrardesignB(\'designB\');return false;" title="'.$txt['adkmodules_preview_design'].'" style="text-decoration: none">
													<img alt="'.$txt['adkmodules_preview_design'].'" src="'.$adkFolder['images'].'/search.png" style="vertical-align: top;" />
													<strong>'.$txt['adkmodules_pages_design'].'</strong> (Cover Ps3)
												</a>
												<div id="designB">
													<div id="designBb">
														<div style="padding: 8px; text-align: right;">
															<fieldset>
																<legend style="color: teal">'.$txt['adkmodules_preview_design'].'</legend>
															<img src="'.$adkFolder['images'].'/covers/2.png" alt="" style="height: 130px; margin: -3px 5px 5px;" />
															</fieldset>
														</div>
													</div>
												</div>
											</td>
											<td style="width: 20%; text-align: right;" valign="top">
												<input type="radio" name="format" value="4" />
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<hr />
											</td>
										</tr>
										<tr>
											<td style="width: 80%;">
												<a onclick="mostrardesignC(\'designC\');return false;" title="'.$txt['adkmodules_preview_design'].'" style="text-decoration: none">
													<img alt="'.$txt['adkmodules_preview_design'].'" src="'.$adkFolder['images'].'/search.png" style="vertical-align: top;" />
													<strong>'.$txt['adkmodules_pages_design'].'</strong> (Cover Xbox)
												</a>
												<div id="designC">
													<div id="designCc">
														<div style="padding: 8px; text-align: right;">
															<fieldset>
																<legend style="color: teal">'.$txt['adkmodules_preview_design'].'</legend>
															<img src="'.$adkFolder['images'].'/covers/3.png" alt="" style="height: 130px; margin: -3px 5px 5px;" />
															</fieldset>
														</div>
													</div>
												</div>
											</td>
											<td style="width: 20%; text-align: right;" valign="top">
												<input type="radio" name="format" value="5" />
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<hr />
											</td>
										</tr>
										<tr>
											<td style="width: 80%;">
												<a onclick="mostrardesignD(\'designD\');return false;" title="'.$txt['adkmodules_preview_design'].'" style="text-decoration: none">
													<img alt="'.$txt['adkmodules_preview_design'].'" src="'.$adkFolder['images'].'/search.png" style="vertical-align: top;" />
													<strong>'.$txt['adkmodules_pages_design'].'</strong> (Cover Dvd)
												</a>
												<div id="designD">
													<div id="designDd">
														<div style="padding: 8px; text-align: right;">
															<fieldset>
																<legend style="color: teal">'.$txt['adkmodules_preview_design'].'</legend>
															<img src="'.$adkFolder['images'].'/covers/4.png" alt="" style="height: 130px; margin: -3px 5px 5px;" />
															</fieldset>
														</div>
													</div>
												</div>
											</td>
											<td style="width: 70%; text-align: right;" valign="top">
												<input type="radio" name="format" value="8" />
											</td>
										</tr>';
									}
	echo'
									</table>
								</fieldset>
							</td>
						</tr>
					</table>
					<br />
					<div class="cat_bar">
						<h3 class="catbg">
							<img alt="" src="'.$adkFolder['images'].'/admin.png" class="adk_vertical_align" />&nbsp;'.$txt['adkmodules_options'].'
						</h3>
					</div>
					<br />
					<table style="width: 100%;">
						<tr>
							<td>
								',help_link('adkmodules_any_url','adkhelp_any_url'),'
							</td>
							<td>
								<input type="text" name="url" value="http://" />
							</td>
						</tr>
						<tr>
							<td>
								',help_link('adkmodules_select_image','adkhelp_select_image'),'
							</td>
							<td>
								<input type="file" name="image" size="23"/>
							</td>
						</tr>
						<tr>
							<td>
								',help_link('adkmodules_select_maybe_image_url','adkhelp_select_maybe_image_url'),'
							</td>
							<td>
								<input type="text" name="image2" value="" />
							</td>
						</tr>
						<tr>
							<td>
								',help_link('adkmodules_water_mark','adkhelp_water_mark'),'
							</td>
							<td>
								<input type="text" name="wm" value="',$context['forum_name'],'" />
							</td>
						</tr>
					</table>
					<br />
					<hr />
					<div style="text-align:center">
						<input type="submit" class="button_submit" value="'.$txt['save'].'" />
						<input type="hidden" name="sc" value="'.$context['session_id'].'" />
					</div>
				</div>
			</div>
			<span class="lowerframe">
				<span>&nbsp;</span>	
			</span>	
		</form>';

}

function template_manages_images()
{
	global  $scripturl, $context, $txt, $adkportal, $boardurl, $adkFolder;

	echo'
			<div class="cat_bar">
				<h3 class="catbg">
					<img alt="" class="adk_vertical_align" src="'.$adkFolder['images'].'/photo.png" />&nbsp;'.$txt['adkmodules_opcion_img'].'
				</h3>
			</div>
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
					<div class="cat_bar">
						<h3 class="catbg">
							<img alt="" class="adk_vertical_align" src="'.$adkFolder['images'].'/link.png" />&nbsp;'.$txt['adkmodules_current_images'].'
						</h3>
					</div>
					<br />
					<div>
						<table align="center" style="width: 100%;" cellspacing="0">
							<tr>';
				if (empty($context['total'])) { 
					echo '
								<td colspan="4" style="text-align:center">
									<strong>'.$txt['adkmodules_no_images'].'</strong>
								</td>
							</tr>
						</table>
					</div>';
				}
				else {
					foreach($context['load_img'] AS $img)
					{
						echo'
								<td valign="top" style="width: 25%;" align="center">
									<a style="text-decoration: none;" onclick="return confirm(\''.$txt['adkmodules_delete_sure'].'\');" href="'.$scripturl.'?action=admin;area=modules;sa=deleteimagesadk;id='.$img['id'].';url2='.$img['image'].';'.$context['session_var'].'='.$context['session_id'].'" title="'.$txt['adkmodules_delete_image'].'">
										<img style="vertical-align: bottom;" src="'.$adkFolder['images'].'/delete.gif" alt="" />
										<strong>'.$txt['adkmodules_borrar'].'</strong>
									</a>
									<br />
									<hr />
									<a href="'.$img['url'].'">
										<img src="'.$img['image'].'" alt="" style="width: 150px;" />
									</a>
								</td>';
					}
					echo'
							</tr>
							<tr>
								<td colspan="4">
									<hr />
								</td>
							</tr>
						</table>
					</div>
					<div class="smalltext" align="right">
						'.$txt['pages'].': '.$context['page_index'].'
					</div>';
				}
			echo'
				</div>
			</div>
			<span class="lowerframe">
				<span>&nbsp;</span>	
			</span>';
			
}		
	
function template_contact_admin()
{
	global  $scripturl, $context, $txt, $adkportal, $boardurl, $adkFolder;
	$toview = array();

	if(!empty($adkportal['adk_groups_contact']))
		$toview = explode(',',$adkportal['adk_groups_contact']);

	echo'
		<form method="post" action="'. $scripturl .'?action=admin;area=modules;sa=save_contact">
			<div class="cat_bar">
				<h3 class="catbg">
					<img alt="" src="'.$adkFolder['images'].'/newmsg.png" style="vertical-align: middle;" />&nbsp;',$txt['adkmodules_options'],'
				</h3>
			</div>
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
			<table style="width: 100%;">
				<tr>
					<td style="width: 50%;">
						',$txt['adkmodules_enable_contact'],'
					</td>
					<td style="width: 50%; text-align: left;">
						<input type="checkbox" name="adk_enable_contact"',getCheckbox('adk_enable_contact'),' />
					</td>
				</tr>
			</table>
			<div class="adk_padding_8">
				<table style="width: 100%;">
					<tr class="smalltext">
						<td style="width: 50%;">
							&nbsp;
						</td>
						<td style="width: 50%;">
							<fieldset>
								<legend style="color: teal">'.$txt['adkmodules_group_view'].'</legend>';
						foreach($context['groups'] AS $id_group => $info_group)
							echo'
								<input type="checkbox" name="toview['.$id_group.']"',isset($toview) && in_array($id_group,$toview) ? ' checked="checked"' : '' ,' />'.$info_group['name'].'<br />';
	echo'
								<div style="text-align: right;">
									<i>'.$txt['adkmodules_check_all'].'</i>
									<input style="vertical-align: middle;" type="checkbox" onclick="invertAll(this, this.form, \'toview\');" />
								</div>
							</fieldset>
						</td>
					</tr>
				</table>
			</div>
			<hr />
			<div align="center">
				<input type="submit" value="',$txt['save'],'" class="button_submit" />
				<input type="hidden" name="sc" value="',$context['session_id'],'" />
			</div>
				</div>
			</div>
			<span class="lowerframe">
				<span>&nbsp;</span>	
			</span>	
		</form>';

}

?>