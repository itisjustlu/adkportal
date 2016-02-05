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

function template_downloadssettings()
{
	global $context, $txt, $scripturl, $modSettings, $adkportal, $boardurl, $adkFolder;

	echo'
		<form method="post" action="'. $scripturl .'?action=admin;area=adkdownloads;sa=savesettings">
			<div class="cat_bar">
				<h3 class="catbg">
					<img alt="" style="vertical-align: middle;" src="'.$adkFolder['images'].'/admin.png" /> '.$txt['adkeds_settings'].'
				</h3>
			</div>
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
					<table style="width: 100%;">
						<tr>
							<td style="width: 35%;">
								'.$txt['adkeds_enable'].'
							</td>
							<td style="width: 65%; text-align: right;">
								<input type="checkbox" name="download_enable" ',downloads_verify_checked('download_enable',1),' /> 
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<hr />
							</td>
						</tr>
						<tr>
							<td style="width: 25%;" valign="top">
								<fieldset>
									<legend style="color: teal">'.$txt['adkeds_change_eds'].'</legend>
								<table style="width: 100%">
									<tr>
										<td style="width: 70%">
											'.$txt['adkeds_color_border'].'
										</td>
										<td style="width: 30%">
											<input type="text" size="7" value="'.$adkportal['adkcolor_border'].'" name="adkcolor_border"  />
										</td>
									</tr>
									<tr>
										<td style="width: 70%">
											'.$txt['adkeds_color_fondo'].'
										</td>
										<td style="width: 30%">
											<input type="text" size="7" value="'.$adkportal['adkcolor_fondo'].'" name="adkcolor_fondo"  />
										</td>
									</tr>
									<tr>
										<td style="width: 70%">
											'.$txt['adkeds_color_fonttitle'].'
										</td>
										<td style="width: 30%">
											<input type="text" size="7" value="'.$adkportal['adkcolor_fonttitle'].'" name="adkcolor_fonttitle"  />
										</td>
									</tr>
									<tr>
										<td style="width: 70%">
											'.$txt['adkeds_color_font'].'
										</td>
										<td style="width: 30%">
											<input type="text" size="7" value="'.$adkportal['adkcolor_font'].'" name="adkcolor_font"  />
										</td>
									</tr>
									<tr>
										<td style="width: 70%">
											'.$txt['adkeds_color_link'].'
										</td>
										<td style="width: 30%">
											<input type="text" size="7" value="'.$adkportal['adkcolor_link'].'" name="adkcolor_link"  />
										</td>
									</tr>
									<tr>
										<td style="width: 70%">
											'.$txt['adkeds_color_attach'].'
										</td>
										<td style="width: 30%">
											<input type="text" size="7" value="'.$adkportal['adkcolor_attach'].'" name="adkcolor_attach"  />
										</td>
									</tr>
								</table>
								</fieldset>
							</td>
							<td style="width: 75%;">
								<fieldset>
									<legend style="color: teal">'.$txt['adkeds_main_title'].'</legend>
									<table style="width: 100%;">
										<tr>
											<td style="width: 60%;">
												'.$txt['adkeds_maxfilesize'].'
											</td>
											<td style="width: 40%; text-align: right;">
												<input type="text" size="10" value="'.$adkportal['download_max_filesize'].'" name="download_max_filesize"  /> (Bytes)
											</td>
										</tr>
										<tr>
											<td style="width: 60%;">
												'.$txt['adkeds_maxfilesize_img'].'
											</td>
											<td style="width: 40%; text-align: right;">
												<input type="text" size="10" value="'.$adkportal['download_images_size'].'" name="download_images_size"  /> (Bytes)
											</td>
										</tr>
										<tr>
											<td style="width: 60%;">
												'.$txt['adkeds_files'].' 
											</td>
											<td style="width: 40%; text-align: right;">
												<input type="text" size="10" value="'.$adkportal['download_set_files_per_page'].'" name="download_set_files_per_page" style="margin-right: 49px;" />
											</td>
										</tr>
										<tr>
											<td style="width: 60%;">
												'.$txt['adkeds_maxattachs'].' 
											</td>
											<td style="width: 40%; text-align: right;">
												<input type="text" size="10" value="'.$adkportal['download_max_attach_download'].'" name="download_max_attach_download" style="margin-right: 49px;" />
											</td>
										</tr>
									</table>
								</fieldset>
								<fieldset>
									<legend style="color: teal">'.$txt['adkeds_mail'].'</legend>
									<table style="width: 100%;">
										<tr>
											<td style="width: 50%;">
												'.$txt['adkeds_sendPm_Approve'].' 
											</td>
											<td style="width: 50%; text-align: right;">
												<input type="checkbox" name="download_enable_sendpmApprove" ',downloads_verify_checked('download_enable_sendpmApprove',1),' /> 
											</td>
										</tr>
										<tr>
											<td style="width: 50%;">
												'.$txt['adkeds_sendPm_ID'].' 
											</td>
											<td style="width: 50%; text-align: right;">
												<input type="text" size="10" value="'.$adkportal['download_sendpm_userId'].'" name="download_sendpm_userId"  />
											</td>
										</tr>
										<tr>
											<td style="width: 50%;" valign="top">
												'.$txt['adkeds_sendPm_body'].'
												<div class="smalltext">',$txt['adkeds_sendPm_body_desc'],'</div>
											</td>
											<td style="width: 50%; text-align: right;">
												<textarea name="download_sendpm_body" rows="6" cols="30">'.$adkportal['download_sendpm_body'].'</textarea>
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
						<tr align="center">
							<td colspan="2">
								<hr />
								<input type="submit" value="'.$txt['save'].'" class="button_submit" />
								<input type="hidden" name="sc" value="'.$context['session_id'].'" />
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

function template_add_category()
{
	global $scripturl, $txt, $context, $settings, $modSettings, $boarddir, $boardurl, $adkFolder;

	//Set the initial script :D
	$enabled = '';
	$disabled = '';
	foreach($context['memberGroups_view'] AS $id_group => $group_info){
		$disabled .= '
		document.getElementById(\'view_'.$id_group.'\').disabled = true;
		document.getElementById(\'add_'.$id_group.'\').disabled = true;';

		$enabled .= '
		document.getElementById(\'view_'.$id_group.'\').disabled = false;
		document.getElementById(\'add_'.$id_group.'\').disabled = false;';
	}

	echo'
	<script type="text/javascript">
		function disablePermissions(id_parent){

			if(id_parent  != 0){
				document.getElementById(\'permissions_error\').style.display = "block";
				'.$disabled.'
				
			}
			else{
				'.$enabled.'
			}
		}
	</script>';
	
	echo '
	<div class="information" style="color: red; font-weight: bold; display: none;" id="permissions_error">
		<img alt="" src="'.$adkFolder['images'].'/warning_mute.gif" class="adk_vertical" />&nbsp;',$txt['adkeds_parent_permissions'],'
	</div>
	<br />
		<form method="post" enctype="multipart/form-data" name="catform" id="catform" action="' . $scripturl . '?action=admin;area=adkdownloads;sa=',$context['save_action'],'">
			<div class="cat_bar">
				<h3 class="catbg">
					<img alt="" style="vertical-align: middle;" src="'.$adkFolder['images'].'/adk_add_category.png" /> ', $context['page_title'], '
				</h3>
			</div>
			<table width="100%">
				<tr>
					<td>
						<span class="clear upperframe">
							<span>&nbsp;</span>	
						</span>
						<div class="roundframe">
							<div>
								<table width="100%">
									<tr>
										<td width="35%">
											' . $txt['adkeds_title'] .'
										</td>
										<td width="65%" style="text-align: right;">
											<input value="',$context['adk_cat']['title'],'" type="text" name="title" size="25" maxlength="100" />
										</td>
									</tr>
									<tr>
										<td width="35%">
											' . $txt['adkeds_cat_subforo'] .'
										</td>
										<td width="65%" style="text-align: right;">
											<select name="parent" style="width: 195px;" onchange="disablePermissions(this.options[this.selectedIndex].value)">
												<option value="0">---- ',$txt['adkeds_none_sub'],' ----</option>';
										foreach ($context['downloads_cat'] as $i => $category)
											echo '
												<option value="' . $category['id_cat']  . '" ' . (($context['cat_parent'] == $category['id_cat']) ? ' selected="selected"' : '') .'>' . $category['title'] . '</option>';

	echo'
											</select>
										</td>
									</tr>
									<tr>
										<td width="35%" valign="top">
											' .$txt['adkeds_cat_desc'] . '<br /><span class="smalltext">'. $txt['adkeds_sendPm_body_desc'] .'</span>
										</td>
										<td width="65%" style="text-align: right;">
											<textarea rows="6" name="description" cols="47">',$context['adk_cat']['description'],'</textarea>
										</td>
									</tr>
									<tr>
										<td width="35%">
											' . $txt['adkeds_icon'] . '
										</td>
										<td width="65%" style="text-align: right;">
											<input type="file" size="44" name="picture" />
											<input type="hidden" name="picture2" value="'.$context['adk_cat']['image2'].'" />
										</td>
									</tr>';
							// Warn the user if the category image path is not writable
							if ($context['is_not_writable_download_path'])
								echo '
									<tr>
										<td colspan="2" align="center">
											<span class="smalltext" style="color: red;">
												<img alt="" style="vertical-align: bottom;" src="'.$adkFolder['images'].'/warning_mute.gif" />
												' . $txt['adkeds_writable']  . $adkFolder['eds'] . '/catimgs/catimgs
											</span>
										</td>
									</tr>';

	echo '
									<tr>
										<td colspan="2">
										<hr />
										</td>
									</tr>
									<tr>
										<td width="35%" valign="top">
											&nbsp;
										</td>
										<td width="65%" style="text-align: right;">
											<fieldset>
												<legend style="color: teal">'.$txt['adkeds_create_topic'].'</legend>
												<table width="100%">
													<tr>
														<td width="70%" style="text-align: left;">
															',$txt['adkeds_create_Board'],'
														</td>
														<td width="30%" style="text-align: right;">
															<select name="boardselect" style="width: 195px;">
																<option value="0">---------- ',$txt['adkeds_none'],' ----------</option>';
	
													foreach ($context['downloads_boards'] as $key => $option) {
														if (!empty($key))
															echo '
																<option value="' . $key . '"',verfy_select_board($key, $context['adk_cat']['id_board']),'>' . $option . '</option>';
													}
	echo '
														</select>
														</td>
													</tr>
													<tr>
														<td colspan="2" style="text-align: left;">
															<hr />
															'.$txt['adkeds_lock_toopic'].'
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<table width="100%" >
																<tr>
																	<td width="15%" style="text-align: right;">
																		',$txt['yes'],'
																	</td>
																	<td width="85%" style="text-align: right;">
																		<input type="radio" value="1" name="locktopic"',$context['adk_cat']['locktopic'] == 1 ?' checked="checked"' : '' ,' />
																	</td>
																</tr>
																<tr>
																	<td width="15%" style="text-align: right;">
																		',$txt['no'],'
																	</td>
																	<td width="85%" style="text-align: right;">
																		<input type="radio" value="0" name="locktopic"',$context['adk_cat']['locktopic'] == 0 ? ' checked="checked"' : '' ,' />
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td colspan="2" style="text-align: left;">
															<hr />
															<strong>'.$txt['adkeds_note'].'</strong> <span class="smalltext">'.$txt['adkeds_post_info'].'</span>
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
										<td width="35%" valign="top">
											' . $txt['adkeds_Orderfilter'] .'
										</td>
										<td width="65%" style="text-align: right;">
											<fieldset>
												<legend style="color: teal">'.$txt['adkeds_Orderby'].'</legend>
												<table width="100%">
													<tr>
														<td width="50%" style="text-align: left;">
															',$txt['adkeds_date'],'
														</td>
														<td width="50%" style="text-align: right;">
															<input type="radio" value="date" name="sortby"',empty($context['adk_cat']['sortby']) || $context['adk_cat']['sortby'] == 'id_file' ? ' checked="checked"' : '' ,'  />
														</td>
													</tr>
													<tr>
														<td width="50%" style="text-align: left;">
															',$txt['adkeds_title'],'
														</td>
														<td width="50%" style="text-align: right;">
															<input type="radio" value="title" name="sortby"',$context['adk_cat']['sortby'] == 'title' ? ' checked="checked"' : '' ,' />
														</td>
													</tr>
													<tr>
														<td width="50%" style="text-align: left;">
															',$txt['adkeds_topview'],'
														</td>
														<td width="50%" style="text-align: right;">
															<input type="radio" value="mostview" name="sortby"',$context['adk_cat']['sortby'] == 'views' ? ' checked="checked"' : '' ,' />
														</td>
													</tr>
													<tr>
														<td width="50%" style="text-align: left;">
															',$txt['adkeds_topdownload'],'
														</td>
														<td width="50%" style="text-align: right;">
															<input type="radio" value="mostdowns" name="sortby"',$context['adk_cat']['sortby'] == 'totaldownloads' ? ' checked="checked"' : '' ,' />
														</td>
													</tr>
													<tr>
														<td colspan="2" style="text-align: left;">
															<hr />
															<strong>'.$txt['adkeds_note'].'</strong> <span class="smalltext">'.$txt['adkeds_asc_info'].'</span>
														</td>
													</tr>
												</table>
											</fieldset>
											<fieldset>
												<legend style="color: teal">'.$txt['adkeds_Ordertipe'].'</legend>
												<table width="100%">
													<tr>
														<td width="50%" style="text-align: left;">
															',$txt['adkeds_desc'],'
														</td>
														<td width="50%" style="text-align: right;">
															<input type="radio" value="desc" name="orderby" ',$context['adk_cat']['orderby'] == 'DESC' ? ' checked="checked"' : '' ,' />
														</td>
													</tr>
													<tr>
														<td width="50%" style="text-align: left;">
															',$txt['adkeds_asc'],'
														</td>
														<td width="50%" style="text-align: right;">
															<input type="radio" value="asc" name="orderby"',$context['adk_cat']['orderby'] == 'ASC' ? ' checked="checked"' : '' ,' />
														</td>
													</tr>';

											if($context['save_action'] == 'saveeditcategory')
												echo'
													<tr>
														<td width="50%" style="text-align: left;">
															',$txt['adkeds_order'],'
														</td>
														<td width="50%" style="text-align: right;">
															<input type="text" size="1" name="roworder" value="',$context['adk_cat']['roworder'],'" />
														</td>
													</tr>';
		echo'
													
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
										<td width="35%" valign="top">
											' . $txt['adkeds_permissions'] .'
										</td>
										<td width="65%" style="text-align: right;">
											<fieldset>
												<legend style="color: teal">'.$txt['adkeds_view_permissions'].'</legend>
												<table width="100%">';

												foreach($context['memberGroups_view'] AS $id_group => $group_info){

													echo'
													<tr>
														<td width="50%" style="text-align: left;">
															',$group_info['name'],'
														</td>
														<td width="50%" style="text-align: right;">
															<input',(!empty($context['groups_can_view_parent']) || $context['groups_can_view_parent'] == "0") && !in_array($id_group, $context['groups_can_view_parent']) ? ' disabled="disabled"' : '' ,' type="checkbox" id="view_'.$id_group.'" name="view[',$id_group,']"',(!empty($context['groups_can_view']) || $context['groups_can_view'] == "0") && in_array($id_group, $context['groups_can_view']) ? ' checked="checked"' : '' ,' />
														</td>
													</tr>';
												}


	echo'												
													<tr>
														<td width="50%" style="text-align: left;">
															<i>'.$txt['adkeds_check_all'].'</i>
														</td>
														<td width="50%" style="text-align: right;">
															<input style="vertical-align: middle;" type="checkbox" onclick="invertAll(this, this.form, \'view\');" />
														</td>
													</tr>
												</table>
											</fieldset>
											<fieldset>
												<legend style="color: teal">'.$txt['adkeds_add_permissions'].'</legend>
												<table width="100%">';

												foreach($context['memberGroups_add'] AS $id_group => $group_info){

													echo'
													<tr>
														<td width="50%" style="text-align: left;">
															',$group_info['name'],'
														</td>
														<td width="50%" style="text-align: right;">
															<input',(!empty($context['groups_can_add_parent']) || $context['groups_can_add_parent'] == "0") && !in_array($id_group, $context['groups_can_add_parent']) ? ' disabled="disabled"' : '' ,' type="checkbox" id="add_'.$id_group.'" name="add[',$id_group,']"',(!empty($context['groups_can_add']) || $context['groups_can_add'] == "0") && in_array($id_group, $context['groups_can_add']) ? ' checked="checked"' : '' ,' />
														</td>
													</tr>';
												}

	echo'										
													<tr>
														<td width="50%" style="text-align: left;">
															<i>'.$txt['adkeds_check_all'].'</i>
														</td>
														<td width="50%" style="text-align: right;">
															<input style="vertical-align: middle;" type="checkbox" onclick="invertAll(this, this.form, \'add\');" />
														</td>
													</tr>		
												</table>
											</fieldset>
										</td>
									</tr>
									<tr>
										<td colspan="2"  align="center" >
											<hr />
											<input type="submit" value="', $txt['save'], '" name="submit" />
											<input type="hidden" name="sc" value="'.$context['session_id'].'" />
											<input type="hidden" name="id_cat" value="'.$context['adk_cat']['id_cat'].'" />
										</td>
									</tr>
								</table>
							</div>
						</div>
						<span class="lowerframe">
							<span>&nbsp;</span>	
						</span>	
					</td>
				</tr>
			</table>
		</form>';

}

function template_all_categories()
{
	global $context, $txt, $scripturl, $boardurl, $total_cat, $adkFolder;

	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img alt="" src="'.$adkFolder['images'].'/adk_category.png" style="vertical-align: middle;" />&nbsp;'.$txt['adkeds_current_cat'].'
			</h3>
		</div>
		<table width="100%">
			<tr>
				<td>
					<span class="clear upperframe">
						<span>&nbsp;</span>	
					</span>
					<div class="roundframe">
						<div>
							<div class="smalltext">
								<table width="100%">';

								foreach($context['all_cat'] AS $id_cat => $cat_info) {
									echo'
									<tr>
										<td align="left">
											<img alt="" style="vertical-align: baseline; margin-left: -5px; margin-bottom: 1px;" src="'.$adkFolder['images'].'/flechita.gif" />';
											if(!empty($cat_info['title']))
												echo'
												<a href="'.$scripturl.'?action=downloads;cat='.$id_cat.'">
													<strong>'.$cat_info['title'].'</strong>
												</a>';
											else
												echo'<span class="smalltext">'.$txt['adkfatal_invalid_id_category'].'</span>';

	echo'
										</td>';

										if(!empty($cat_info['title']))
											echo'
										<td align="right">
											<a style="text-decoration: none;" onclick="return confirm(\'', $txt['adkeds_delcat'], '\');" title="'.$txt['adkeds_delcat'].'" href="'.$scripturl.'?action=admin;area=adkdownloads;sa=deletecategory;id='.$id_cat.';'.$context['session_var'].'='.$context['session_id'].'">
												<strong>'.$txt['adkeds_delete'].'</strong>
												<img style="vertical-align: middle;" alt="'.$txt['adkeds_delete'].'" src="'.$adkFolder['images'].'/cancel.png" />
											</a>
											<a style="text-decoration: none;" title="'.$txt['adkeds_edit'].'" href="'.$scripturl.'?action=admin;area=adkdownloads;sa=editcategory;id='.$id_cat.';'.$context['session_var'].'='.$context['session_id'].'">
												<strong>'.$txt['adkeds_edit'].'</strong>
												<img style="vertical-align: middle;" alt="'.$txt['adkeds_edit'].'" src="'.$adkFolder['images'].'/b_edit.png" />
											</a>
											',!empty($cat_info['has_error']) ? '<div align="center" style="text-decoration: none;">'.help_link('adkeds_general_error', 'adkhelp_general_error').'</div>' : '' ,'
										</td>';
	echo'
									</tr>';

									if(!empty($context['all_parent_new'][$id_cat]))
										foreach($context['all_parent_new'][$id_cat] AS $info){

											echo'
										<tr>
											<td align="left">
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img alt="" style="vertical-align: baseline; margin-left: -5px; margin-bottom: 1px;" src="'.$adkFolder['images'].'/flechita.gif" />
												<a href="'.$scripturl.'?action=downloads;cat='.$info['id_cat'].'">
													<strong>'.$info['title'].'</strong>
												</a>
											</td>
											<td align="right">
												<a style="text-decoration: none;" onclick="return confirm(\'', $txt['adkeds_delcat'], '\');" title="'.$txt['adkeds_delcat'].'" href="'.$scripturl.'?action=admin;area=adkdownloads;sa=deletecategory;id='.$info['id_cat'].';'.$context['session_var'].'='.$context['session_id'].'">
													<strong>'.$txt['adkeds_delete'].'</strong>
													<img style="vertical-align: middle;" alt="'.$txt['adkeds_delete'].'" src="'.$adkFolder['images'].'/cancel.png" />
												</a>
												<a style="text-decoration: none;" title="'.$txt['adkeds_edit'].'" href="'.$scripturl.'?action=admin;area=adkdownloads;sa=editcategory;id='.$info['id_cat'].';'.$context['session_var'].'='.$context['session_id'].'">
													<strong>'.$txt['adkeds_edit'].'</strong>
													<img style="vertical-align: middle;" alt="'.$txt['adkeds_edit'].'" src="'.$adkFolder['images'].'/b_edit.png" />
												</a>
												',$info['has_error'] ? '<div align="center" style="text-decoration: none;">'.help_link('adkeds_general_error', 'adkhelp_general_error').'</div>' : '' ,'
											</td>
										</tr>';

										}

								}
								if (empty($context['all_cat'])) {
									echo'
										<tr>
											<td align="center">
												<strong>'.$txt['adkeds_nocategory'].'</strong>
											</td>
										</tr>';
								}
							echo'
									</table>
								</div>';
	echo'
						</div>
					</div>
					<span class="lowerframe">
						<span>&nbsp;</span>	
					</span>	
				</td>
			</tr>
		</table>';

}

function template_approve_d()
{
	global $txt, $scripturl, $context, $boardurl, $total_Unapprove, $total_Approve, $adkFolder;

	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img alt="" style="vertical-align: middle;" src="'.$adkFolder['images'].'/approve.png" /> '.$txt['adkeds_approve'].'
			</h3>
		</div>
		<table style="width: 100%;">
			<tr>
				<td colspan="2">
					<span class="clear upperframe">
						<span>&nbsp;</span>	
					</span>
					<div class="roundframe">
						<div>
							<img alt="" style="vertical-align: text-bottom; margin-right: -1px;" src="'.$adkFolder['images'].'/stats_dow.png" />
							<strong>'.$txt['adkeds_stats'].'</strong>
							<hr />
							<img alt="" style="vertical-align: text-top; margin-right: -3px;" src="'.$adkFolder['images'].'/tree_dow.png" />
							<strong>'.$txt['adkeds_stats_total'].':</strong> '.$context['total_dow'].'
							<br />
							<img alt="" style="vertical-align: text-top; margin-right: -3px;" src="'.$adkFolder['images'].'/tree_dow_u.png" />
							<strong>'.$txt['adkeds_stats_unapproved'].':</strong> '.$total_Unapprove.'
							<br />
							<img alt="" style="vertical-align: text-top; margin-right: -3px;" src="'.$adkFolder['images'].'/tree_dow_a.png" />
							<strong>'.$txt['adkeds_stats_approved'].':</strong> '.$total_Approve.'
							<br />
						</div>
					</div>
					<span class="lowerframe">
						<span>&nbsp;</span>	
					</span>	
				</td>
			</tr>
			<tr>
				<td style="width: 50%;" valign="top">
					<span class="clear upperframe">
						<span>&nbsp;</span>	
					</span>
					<div class="roundframe">
						<div>
							<img alt="" style="vertical-align: middle; margin-right: -3px;" src="'.$adkFolder['images'].'/unapprove.png" />
							<strong>'.$txt['adkeds_unapproved'].'</strong>
							<hr />
							<div class="smalltext">
								<table style="width: 100%;">';
					if(!empty($total_Unapprove)) {
						foreach($context['unapproved'] AS $file) {
							echo'
									<tr>
										<td style="width: 50%; text-align: left; padding-right: 8px;">
											<img alt="" style="vertical-align: baseline;" src="'.$adkFolder['images'].'/flechita.gif" />
											<a href="'.$scripturl.'?action=downloads;sa=view;down='.$file['id'].'">
												<strong>'.$file['title'].'</strong>
											</a>
										</td>
										<td style="width: 50%; text-align: right; padding-left: 8px; font-weight: bold;">
											<a href="'.$scripturl.'?action=downloads;sa='.$file['state'].';id='.$file['id'].';return=admin;sesc='.$context['session_id'].'">'.$file['text'].'</a>
											<img alt="" style="vertical-align: middle; margin-left: -6px;" src="'.$adkFolder['images'].'/'.$file['img'].'" />
										</td>
									</tr>';
						}
						echo'
									<tr>
										<td colspan="2">
											<br />
											<hr />
											<div style="text-align: right;">
												'.$txt['pages'].': '.$context['page_index_unapproved'].'
											</div>
										</td>
									</tr>';
					}
					else {
							echo'
									<tr>
										<td colspan="2">
											<div style="text-align: center;">
												<strong>'.$txt['adkeds_no_unapproved'].'</strong>
											</div>
										</td>
									</tr>';
					}
	echo'
								</table>
							</div>
						</div>
					</div>
					<span class="lowerframe">
						<span>&nbsp;</span>	
					</span>	
				</td>
				<td style="width: 50%;" valign="top">
					<span class="clear upperframe">
						<span>&nbsp;</span>	
					</span>
					<div class="roundframe">
						<div>
							<img alt="" style="vertical-align: middle; margin-right: -3px;" src="'.$adkFolder['images'].'/approve.png" />
							<strong>'.$txt['adkeds_approved'].'</strong>
							<hr />
							<div class="smalltext">
								<table style="width: 100%;">';
					if(!empty($total_Approve)) {
						foreach($context['approved'] AS $file) {
							echo'
									<tr>
										<td style="width: 50%; text-align: left; padding-right: 8px;">
											<img alt="" style="vertical-align: baseline;" src="'.$adkFolder['images'].'/flechita.gif" />
											<a href="'.$scripturl.'?action=downloads;sa=view;down='.$file['id'].'">
												<strong>'.$file['title'].'</strong>
											</a>
										</td>
										<td style="width: 50%; text-align: right; padding-left: 8px; font-weight: bold;">
											<a href="'.$scripturl.'?action=downloads;sa='.$file['state'].';id='.$file['id'].';return=admin;sesc='.$context['session_id'].'">'.$file['text'].'</a>
											<img alt="" style="vertical-align: middle; margin-left: -6px;" src="'.$adkFolder['images'].'/'.$file['img'].'" />
										</td>
									</tr>';
						}
						echo'
									<tr>
										<td colspan="2">
											<br />
											<hr />
											<div style="text-align: right;">
												'.$txt['pages'].': '.$context['page_index_approved'].'
											</div>
										</td>
									</tr>';
					}
					else {
							echo'
									<tr>
										<td colspan="2">
											<div style="text-align: center;">
												<strong>'.$txt['adkeds_no_approved'].'</strong>
											</div>
										</td>
									</tr>';
					}
	echo'
								</table>
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

?>