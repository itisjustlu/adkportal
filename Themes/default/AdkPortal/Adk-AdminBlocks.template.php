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

function template_blocks_templates()
{
	global $context, $scripturl, $txt, $boardurl, $adkFolder;

	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/wrench_orange.png" />&nbsp;',$txt['adkblock_templates'],'
			</h3>
		</div>
		<table style="width: 100%;">';
	
	foreach($context['blocks_templates'] AS $type => $template){

		echo'
			<tr>
				<td style="width: 50%;" valign="top">
					<span class="clear upperframe">
						<span>&nbsp;</span>	
					</span>
					<div class="roundframe">
						<div>
							<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/brick_add.png" alt="'.$txt['adkblock_template_'.$type].'" />
							<strong>
								'.$txt['adkblock_template_'.$type].'
							</strong>
							<br style="margin-bottom: 8px" />
							<table style="width: 100%;" cellspacing="0">';

			foreach($template AS $id_template => $templates) {
	
				//Set the alternate blocks
				$alternate = '';

				if(!$templates['is_enabled'])
					$alternate = 'background-color: #FFEAEA;';

							echo'
								<tr class="smalltext" style="'.$alternate.'">
									<td style="width:50%; text-align:center;border-top: 1px solid #C0C0C0;padding: 5px;">';

						if($templates['place_nule'] != '#all#')
							echo'
									<a href="',$scripturl, $templates['type'] != 'default' ? '?'.$templates['place'] : '' , in_array($templates['type'], array('topic', 'board')) ? '.0' : '' ,'">';

							echo 
										$templates['place_nule'] == '#all#' ? $txt['adkblock_default_templates'] : '{'.$templates['place'].'}';
								
						if($templates['place_nule'] != '#all#')
							echo'
									</a>';

							echo'
									</td>
									<td style="width:50%; text-align: right;border-top: 1px solid #C0C0C0;padding: 5px;">
										<a href="'.$scripturl.'?action=admin;area=blocks;sa=edittemplate;id='.$id_template.';'.$context['session_var'].'='.$context['session_id'].'"><img style="vertical-align: sub;" src="'.$adkFolder['images'].'/b_edit.png" alt="'.$txt['adkblock_edit'].'" title="'.$txt['adkblock_edit'].'" /></a>&nbsp;';

						if($templates['is_not_default']){
							echo'
										<a onclick="return confirm(\'', $txt['adkblock_delete_waring_template'], '\');" href="'.$scripturl.'?action=admin;area=blocks;sa=deletetemplate;id='.$id_template.';'.$context['session_var'].'='.$context['session_id'].'"><img style="vertical-align: sub;" src="'.$adkFolder['images'].'/cancel.png"  alt="'.$txt['adkblock_delete'].'" title="'.$txt['adkblock_delete'].'" /></a>&nbsp;';

							echo'
										<a title="',$templates['is_enabled'] ? $txt['adkblock_unapprove'] : $txt['adkblock_approve'] ,'" href="',$scripturl,'?action=admin;area=blocks;sa=approve_template;id=',$id_template,';value=',$templates['is_enabled'] ? '0' : '1' ,';'.$context['session_var'].'='.$context['session_id'].'">
											<img style="vertical-align: sub;" alt="" src="'.$adkFolder['images'].'/',$templates['is_enabled'] ? 'un' : '' ,'approve.png" />
										</a>';
						}
				echo'
									</td>
								</tr>';
			}

			if(empty($template))
				echo '
								<tr class="smalltext">
									<td colspan="2" style="text-align: center;border-top: 1px solid #C0C0C0;padding: 5px;"">
										',$txt['adkblock_no_templates'],'
									</td>
								</tr>';

			if($type != 'default')
				echo'
								<tr>
									<td colspan="2" align="right" class="smalltext">
										<hr style="margin-top: 0px; margin-bottom: 15px;" />
										<a href="'.$scripturl.'?action=admin;area=blocks;sa=newtemplate;type='.$type.';'.$context['session_var'].'='.$context['session_id'].'">
											<input class="button_submit" type="button" value="',$txt['adkblock_add_template'],'" />
										</a>
									</td>
								</tr>';

			echo'
							</table>
						</div>
					</div>
					<span class="lowerframe">
						<span>&nbsp;</span>	
					</span>
				</td>';
	}

	echo'		
			</tr>
		</table>';
}

function template_create_template()
{
	global $txt, $context, $scripturl, $boardurl, $adkFolder;

	echo'
	<script type="text/javascript">
		function disableImport(type){

			if(type == "',$context['type'],'"){
				document.getElementById(\'import\').disabled = false;
				document.getElementById(\'place\').disabled = false;
			}
			else{
				document.getElementById(\'import\').disabled = true;
				document.getElementById(\'place\').disabled = true;
				document.getElementById(\'place_show\').style.display = "block";
			}
		}
		function disablePlace(id_place){

			if(id_place != 0){
				document.getElementById(\'place_show\').style.display = "none";
			}
			else{
				document.getElementById(\'place_show\').style.display = "block";
			}
		}
	</script>';

	echo'
	<form method="post" action="'. $scripturl .'?action=admin;area=blocks;sa=save_template">';

	echo'
	<span class="clear upperframe">
		<span>&nbsp;</span>	
	</span>
	<div class="roundframe">
		<div>
			<img alt="" class="adk_vertical_align" src="'.$adkFolder['images'].'/drive_key.png" />&nbsp;<strong>',$txt['adkblock_new_template'],'</strong>
			<hr />
			<table style="width: 100%;">
				<tr >
					<td style="width: 50%">
						',help_link('adkblock_template', 'adkhelp_select_template'),'
					</td>
					<td>
						'.$txt['adkblock_template_'.$context['type']].'
						<input type="hidden" name="type" value="'.$context['type'].'" />
					</td>
				</tr>
				<tr >
					<td>
						',help_link('adkblock_template_import_from', 'adkhelp_template_import_from'),'
					</td>
					<td>
						<select id="import" name="import_from">
							<option value="0">(',$txt['adkblock_none'],')</option>';

	foreach($context['previous_templates'] AS $id_template => $template)
		echo'
							<option value="'.$id_template.'">{',$template['place'],'}</option>';

	echo'
						</select>
					</td>
				</tr>
				<tr >
					<td valign="top">
						',help_link('adkblock_place', 'adkhelp_place'),'
					</td>
					<td>
						<select id="place" name="place" onchange="disablePlace(this.options[this.selectedIndex].value)">
							<option value="0">{',$txt['adkblock_other'],'}</option>
							<option value="#all#">{',$txt['adkblock_all_posicion'],'}</option>';

	if(!empty($context['places']))
		foreach($context['places'] AS $place)
			echo'
							<option value="'.$place.'">'.$place.'</option>';

	echo'
						</select>
						<div class="smalltext" id="place_show">
							<input type="text" name="place_2" value="" />
						</div>
					</td>
				</tr>
			</table>
			<hr />
			<div class="smalltext" align="right">
				<input name="sc" type="hidden" value="',$context['session_id'],'" />
				<input type="hidden" name="hidden_type" value="',$context['type'],'" />
				<input type="submit" class="button_submit" value="',$txt['save'],'" />
			</div>
		</div>
	</div>
	<span class="lowerframe">
		<span>&nbsp;</span>	
	</span>


	</form>';


}

function template_edit_the_template()
{
	global $scripturl, $context, $txt, $boardurl, $adkFolder;

	echo'
		<form method="post" action="'. $scripturl .'?action=admin;area=blocks;sa=save_edit_template">
			',template_print_blocks('top'),'
			<br />
			',template_print_blocks('left'),'
			<br />
			',template_print_blocks('center', $context['type']),'
			<br />
			',template_print_blocks('right'),'
			<br />
			',template_print_blocks('bottom'),'
			<br />
			<hr />
			<div align="right">
				<input type="hidden" name="sc" value="',$context['session_id'],'" />
				<input type="submit" value="',$txt['save'],'" class="button_submit" />
				<input type="hidden" name="id_template" value="',$context['id_template'],'" />
			</div>
			<br />
			<br />';
			//Print unuses blocks
			template_print_blocks('admin');
	echo'
		</form>';
}

function template_viewblocks()
{
	global  $scripturl, $context, $txt, $boarddir, $boardurl, $settings, $adkFolder;
	echo'
		<div class="tborder smalltext">
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
					<img class="adk_vertical_align" alt="" src="'.$adkFolder['images'].'/brick.png" />&nbsp;
					<strong>
						'.$txt['adkmod_block_title'].'
					</strong>
					<br style="margin-bottom: 8px" />
					<table style="width: 100%;" cellspacing="0">';

	foreach($context['admin'] AS $poster){

		echo'
						<tr>
							<td style="width: 35%; border-top: 1px solid #C0C0C0;padding: 5px;" valign="middle">',!empty($poster['img']) ? '<img src="'.$adkFolder['images'].'/blocks/'.$poster['img'].'" alt="" align="top" />&nbsp;' : '' , $poster['name'], '</td>
							<td style="width: 20%; border-top: 1px solid #C0C0C0;padding: 5px; text-align: center;">(',$poster['type'] == 'include' ? $txt['adkblock_include'] : $poster['type'],')</td>
							<td style="width: 20%; border-top: 1px solid #C0C0C0;padding: 5px; text-align: center;">(',$poster['id'],')</td>
							<td style="width: 25%; border-top: 1px solid #C0C0C0;padding: 5px; text-align: right;">
								<a href="'.$scripturl.'?action=admin;area=blocks;sa=editblocks;edit='.$poster['id'].';'.$context['session_var'].'='.$context['session_id'].'"><img src="'.$adkFolder['images'].'/b_edit.png" alt="'.$txt['adkblock_edit'].'" title="'.$txt['adkblock_edit'].'" /></a>&nbsp;
								<a href="'.$scripturl.'?action=admin;area=blocks;sa=previewblock;id='.$poster['id'].';'.$context['session_var'].'='.$context['session_id'].'"><img src="'.$adkFolder['images'].'/admin.png" alt="'.$txt['adkblock_pre_view'].'" title="'.$txt['adkblock_pre_view'].'" /></a>&nbsp;
								<a onclick="return confirm(\'', $txt['adkblock_delete_waring'], '\');" href="'.$scripturl.'?action=admin;area=blocks;sa=deleteblocks;delete='.$poster['id'].';'.$context['session_var'].'='.$context['session_id'].'"><img src="'.$adkFolder['images'].'/cancel.png"  alt="'.$txt['adkblock_delete'].'" title="'.$txt['adkblock_delete'].'" /></a>&nbsp;
								<a href="'.$scripturl.'?action=admin;area=blocks;sa=permissions;id='.$poster['id'].';'.$context['session_var'].'='.$context['session_id'].'"><img src="'.$adkFolder['images'].'/users.png"  alt="'.$txt['adkblock_permissions'].'" title="'.$txt['adkblock_permissions'].'" /></a>
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
		</div>';

}

function template_settings_blocks()
{
	global  $scripturl, $context, $txt, $adkportal, $boardurl, $adkFolder;

	$explode1 = explode(',',$adkportal['shout_allowed_groups_view']);
	$explode2 = explode(',',$adkportal['shout_allowed_groups']);
	
	echo'
		<form method="post" action="'. $scripturl .'?action=admin;area=blocks;sa=savesettingsblocks2">
			<table style="width: 100%;"> 
				<tr>
					<td style="width: 50%" valign="top">
						<table style="width: 100%;">
							<tr>
								<td>
									<span class="clear upperframe">
										<span>&nbsp;</span>	
									</span>
									<div class="roundframe">
										<div>
											<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/news.png" alt="" />
											<strong>'.$txt['adkblock_aporte'].'</strong>
											<hr />
											<div class="smalltext">
												<table style="width: 100%;">
													<tr>
														<td style="width: 90%;">
															',help_link('adkblock_number_news','adkhelp_number_news'),'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="text" size="2" maxlength="2" name="adk_news" value="',!empty($adkportal['adk_news']) ? $adkportal['adk_news'] : '','" />
														</td>
													</tr>
													<tr>
														<td style="width: 90%;">
															',help_link('adkblock_twitter_facebook','adkhelp_twitter_facebook'),'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="checkbox" name="adk_bookmarks_news"',getCheckbox('adk_bookmarks_news'),' />
														</td>
													</tr>
													<tr>
														<td style="width: 90%;">
															',help_link('adkblock_disable_autor','adkhelp_disable_autor'),'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="checkbox" name="adk_disable_autor"',getCheckbox('adk_disable_autor'),' />
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
									<span class="lowerframe">
										<span>&nbsp;</span>	
									</span>	
								</td>
							</tr>
							<tr>
								<td>
									<span class="clear upperframe">
										<span>&nbsp;</span>	
									</span>
									<div class="roundframe">
										<div>
											<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/news.png" alt="" />
											<strong>'.$txt['adkblock_last_post'].'</strong>
											<hr />
											<div class="smalltext">
												<table style="width: 100%;">
													<tr>
														<td style="width: 90%;">
															'.$txt['adkblock_number_post'].'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="text" size="2" maxlength="2" name="ultimos_mensajes" value="',!empty($adkportal['ultimos_mensajes']) ? $adkportal['ultimos_mensajes'] : '','" />
														</td>
													</tr>
													<tr>
														<td style="width: 90%;">
															',help_link('adkblock_two_column','adkhelp_two_column'),'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="checkbox" name="adk_two_column"',getCheckbox('adk_two_column'),' />
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
									<span class="lowerframe">
										<span>&nbsp;</span>	
									</span>	
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<span class="clear upperframe">
										<span>&nbsp;</span>	
									</span>
									<div class="roundframe">
										<div>
											<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/news.png" alt="" />
											<strong>'.$txt['adkblock_autonews'].'</strong>
											<hr />
											<div class="smalltext">
												<table style="width: 100%;">
													<tr>
														<td style="width: 90%;">
															',help_link('adkblock_auto_news_limit_body','adkhelp_auto_news_limit_body'),'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="text" size="3" name="auto_news_limit_body" value="',!empty($adkportal['auto_news_limit_body']) ? $adkportal['auto_news_limit_body'] : '0','" />
														</td>
													</tr>
													<tr>
														<td style="width: 90%;">
															',help_link('adkblock_news_limit_topics','adkhelp_news_limit_topics'),'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="text" size="3" name="auto_news_limit_topics" value="',!empty($adkportal['auto_news_limit_topics']) ? $adkportal['auto_news_limit_topics'] : '','" />
														</td>
													</tr>
													<tr>
														<td style="width: 90%;">
															',help_link('adkblock_news_size_img','adkhelp_news_size_img'),'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="text" size="3" name="auto_news_size_img" value="',!empty($adkportal['auto_news_size_img']) ? $adkportal['auto_news_size_img'] : '0','" />
														</td>
													</tr>
													<tr>
														<td style="width: 90%;">
															',help_link('adkblock_twitter_facebook','adkhelp_twitter_facebook'),'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="checkbox" name="adk_bookmarks_autonews"',getCheckbox('adk_bookmarks_autonews'),' />
														</td>
													</tr>
												</table>
												<table style="width: 100%;">
													<tr>
														<td valign="top" style="width: 30%;">
															',help_link('adkblock_autonews_boards','adkhelp_autonews_boards'),'
														</td>
														<td style="width: 70%; text-align: right;">';
															$id_boards = explode(',',$adkportal['auto_news_id_boards']);
	echo'
															<select name="auto_news_id_boards[]" size="9" multiple="multiple" style="width: 88%;">';
									foreach ($context['jump_to'] as $category)
									{
										echo '
																<option disabled="disabled">----------------------------------------------------</option>
																<option disabled="disabled">', $category['name'], '</option>
																<option disabled="disabled">----------------------------------------------------</option>';
										foreach ($category['boards'] as $board)
											echo '
																<option value="' ,$board['id'], '" ' ,isset($id_boards) ? (in_array($board['id'], $id_boards) ? 'selected="selected"' : '') : '', '> ' . str_repeat('&nbsp;&nbsp;&nbsp; ', $board['child_level']) . '|--- ' . $board['name'] . '</option>';
									}
									echo'
															</select>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
									<span class="lowerframe">
										<span>&nbsp;</span>	
									</span>
								</td>
							</tr>
						</table>
					</td>
					<td style="width: 50%;" valign="top">
						<table style="width: 100%;">
							<tr>
								<td>
									<span class="clear upperframe">
										<span>&nbsp;</span>	
									</span>
									<div class="roundframe">
										<div>
											<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/package.png" alt="" />
											<strong>'.$txt['adkblock_top_poster'].'</strong>
											<hr />
											<div class="smalltext">
												<table style="width: 100%;">
													<tr>
														<td style="width: 90%;">
															'.$txt['adkblock_top_poster_limit'].'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="text" size="2" maxlength="2" name="top_poster"  value="',!empty($adkportal['top_poster']) ? $adkportal['top_poster'] : '','" />
														</td>
													</tr>
													<tr>
														<td style="width: 90%;">
															'.$txt['adkblock_avatar_topposter'].'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="checkbox" name="noavatar_top_poster"',getCheckbox('noavatar_top_poster'),' />
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
									<span class="lowerframe">
										<span>&nbsp;</span>	
									</span>	
								</td>
							</tr>
							<tr>
								<td>
									<span class="clear upperframe">
										<span>&nbsp;</span>	
									</span>
									<div class="roundframe">
										<div>
											<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/package.png" alt="" />
											<strong>'.$txt['adkblock_users_online'].'</strong>
											<hr />
											<div class="smalltext">
												<table style="width: 100%;">
													<tr>
														<td style="width: 90%;">
															'.$txt['adkblock_vertically_who'].'
														</td>
														<td style="width: 10%; text-align: right;">
															<input type="checkbox" name="adk_vertically_who"',getCheckbox('adk_vertically_who'),' />
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
									<span class="lowerframe">
										<span>&nbsp;</span>	
									</span>
								</td>
							</tr>
							<tr>
								<td>
								<span class="clear upperframe">
										<span>&nbsp;</span>	
									</span>
									<div class="roundframe">
										<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/package.png" alt="" />
										<strong>',$txt['adkblock_shout'],'</strong>
										<hr />
										<div class="smalltext">
											<table style="width: 100%">
												<tr>
													<td style="width: 50%;">
														'.$txt['adkblock_shout_g_allowed_view'].'
													</td>
													<td style="width: 50%;">
														<fieldset>
															<legend style="color: teal">'.$txt['adkblock_select_group'].'</legend>
																<div class="adk_padding_8 text_align_center">
																	<select name="shout_allowed_groups_view[]" multiple="multiple" style="width: 88%;">
																		<option value="-1"',in_array(-1,$explode1) ? ' selected="selected"': '' ,'>'.$txt['adkblock_guests'].'</option>
																		<option value="0"',in_array(0,$explode1) ? ' selected="selected"': '' ,'>'.$txt['adkblock_regulars_users'].'</option>';
																		if(!empty($context['memberadk']))
																		foreach($context['memberadk'] AS $i => $v)
																			echo'<option value="'.$i.'"',in_array($i,$explode1) ? ' selected="selected"': '' ,'>'.$v['name'].'</option>';
							echo'
																	</select>
																</div>
														</fieldset>							
													</td>
												</tr>
												<tr>
													<td style="width: 50%;">
														'.$txt['adkblock_shout_g_allowed_topost'].'
													</td>
													<td style="width: 50%;">
														<fieldset>
															<legend style="color: teal">'.$txt['adkblock_select_group'].'</legend>
																<div class="adk_padding_8 text_align_center">
																	<select name="shout_allowed_groups[]" multiple="multiple" style="width: 88%;">
																		<option value="-1"',in_array(-1,$explode2) ? ' selected="selected"': '' ,'>'.$txt['adkblock_guests'].'</option>
																		<option value="0"',in_array(0,$explode2) ? ' selected="selected"': '' ,'>'.$txt['adkblock_regulars_users'].'</option>';
																		
																		if(!empty($context['memberadk']))
																		foreach($context['memberadk'] AS $i => $v)
																			echo'<option value="'.$i.'"',in_array($i,$explode2) ? ' selected="selected"': '' ,'>'.$v['name'].'</option>';
							echo'
																	</select>
																</div>
														</fieldset>							
													</td>
												</tr>
												<tr>
													<td colspan="2">
														<div class="text_align_center" style="margin-top: 5px;">
															<a href="'. $scripturl .'?action=admin;area=blocks;sa=shoutboxdeleteall;'.$context['session_var'].'='.$context['session_id'].'">
																<input type="button" value="'.$txt['adkblock_delete_allshoutbox'].'" class="button_submit" />
															</a>
															<input type="hidden" name="sc" value="', $context['session_id'], '" />
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
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table style="width: 100%;">
				<tr>
					<td colspan="2" align="center">
						<span class="clear upperframe">
							<span>&nbsp;</span>	
						</span>
						<div class="roundframe">
							<div>
								<input type="submit" value="'.$txt['save'].'" class="button_submit" />
								<input type="hidden" name="sc" value="'.$context['session_id'].'" />
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

function template_editblocks()
{
	global $context, $txt, $scripturl, $boardurl, $adkFolder;
		
	echo'
	<form method="post" action="'. $scripturl .'?action=admin;area=blocks;sa=saveeditblocks">
		<div class="cat_bar">
			<h3 class="catbg">
				<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/package.png" />&nbsp;'.$txt['adkblock_editing_block'].'
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
						'.$txt['adkblock_titulo'].'
						</td>
						<td>
							<input type="text" value="'.$context['edit']['title'].'" name="titulo" />
						</td>
					</tr>
				</table>';
		
		if($context['edit']['type'] == 'include'){
			echo'
				<input type="hidden" name="descript" value="'.$context['edit']['echo'].'" />';
		}
		elseif($context['edit']['type'] == 'php'){
			echo'
				<table style="width: 100%;">
					<tr>
						<td valign="top" style="width: 30%;">
							'.$txt['adkblock_editing_block_code'].'
						</td>
						<td valign="top">
							<textarea name="descript" rows="10" cols="80">'.$context['edit']['echo'].'</textarea>
						</td>
					</tr>
				</table>';
		}
		else{
			echo'
				<hr />
				<table style="width: 100%;">
					<tr>
						<td valign="top" style="width: 30%;">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2">';
				
							//Get Template EDitor()
							getTemplateEditor();
			
			echo'
						</td>
					</tr>
				</table>';
		}
	echo'
				<br />
				<div class="cat_bar">
					<h3 class="catbg">
						<img alt="" src="'.$adkFolder['images'].'/admin.png" style="vertical-align: middle;" /> ',$txt['adkblock_options'],'
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
									<legend style="color: teal">'.$txt['adkblock_options'].'</legend>
									<table style="width: 100%;">
										<tr class="smalltext">
											<td style="width: 50%;">
												'.$txt['adkblock_empty_style_block'].'
											</td>
											<td style="width: 50%; text-align: right;">
												<input type="checkbox" name="empty_body"',$context['edit']['b'] == 1 ? ' checked="checked"' : '' ,' />
											</td>
										</tr>
										<tr class="smalltext">
											<td style="width: 50%;">
												'.$txt['adkblock_empty_title'].'
											</td>
											<td style="width: 50%; text-align: right;">
												<input type="checkbox" name="empty_title"',$context['edit']['t'] == 1 ? ' checked="checked"' : '' ,' />
											</td>
										</tr>
										<tr class="smalltext">
											<td style="width: 50%;">
												'.$txt['adkblock_empty_collapse'].'
											</td>
											<td style="width: 50%; text-align: right;">
												<input type="checkbox" name="empty_collapse"',$context['edit']['c'] == 1 ? ' checked="checked"' : '' ,' />
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
					</table>
					<table style="width: 100%;">
						<tr class="smalltext">
							<td style="width: 50%;">
								&nbsp;
							</td>
							<td style="width: 50%;">
								<fieldset>
									<legend style="color: teal">'.$txt['adkblock_icons'].'</legend>
										<table style="width: 100%;">
											<tr>
												<td>
													',openDirImages($context['edit']['img']),'
												</td>
											</tr>
										</table>
								</fieldset>
							</td>
						</tr>
					</table>
				</div>
				<hr />
				<div style="text-align: center;">
					<input type="hidden" name="sc" value="'.$context['session_id'].'" />
					<input type="submit" value="'.$txt['save'].'" class="button_submit" />
					<input type="hidden" name="id" value="'.$context['edit']['id'].'" />
					<input type="hidden" name="type_" value="'.$context['edit']['type'].'" />
				</div>
			</div>
		</div>
		<span class="lowerframe">
			<span>&nbsp;</span>	
		</span>	
	</form>';

}

function template_the_new_custom_blocks()
{
	global $settings, $scripturl, $txt, $context, $boardurl, $adkFolder;
	
	
	echo'
	<div class="cat_bar">
		<h3 class="catbg">
			<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/angel.gif" alt="" />
			&nbsp;'.$txt['adkblock_select_create'].'
		</h3>
	</div>
	<div class="windowbg information">
		'.$txt['adkblock_select_tipe'].'
	</div>
	<table style="width: 100%;" cellspacing="1">
		<tr>';

	$i = 0;
	foreach($context['add_custom_blocks'] AS $act => $button)
	{
		if($i == 2)
		{
			echo'</tr><tr>';
			$i = 0;
		}
		echo'
			<td style="width: 50%;" valign="top">
				<span class="clear upperframe">
					<span>&nbsp;</span>	
				</span>
				<div class="roundframe">
					<div>
						<img style="vertical-align: middle;" src="'.$adkFolder['images'].'/'.$button['image'].'" alt="" />
						<a style="font-weight: bold;" href="',$button['url'],'">
							'.$button['title'].'
						</a>
						<hr />
						<div class="smalltext">
							',$txt['adkblock_'.$act.'_help'],'
						</div>
					</div>
				</div>
				<span class="lowerframe">
					<span>&nbsp;</span>	
				</span>	
			</td>';
		$i++;
	}
	
	echo'
		</tr>
	</table>';


}

function template_differents_blocks_styles()
{

	global $context;

	CreateAddCustomBlocks($context['block_type']);
}

function template_multi_block()
{
	global $context, $txt, $scripturl, $boardurl, $adkFolder;

	// Script
	echo'
	<script type="text/javascript"><!-- // --><![CDATA[
	function add_block(id){
		if (id != 0) {
			var capa = document.getElementById("blocks");
			var div = document.createElement("div");
			div.id = "block_" + id;
			div.innerHTML = "<table width=\"100%\"><tr><td width=\"50%\"><strong>'.$txt['adkblock_insert'].' " + id + \'</strong><input type="hidden" value="\' + id + \'" name="block[\' + id + \']" /></td><td width=\"50%\" style=\"text-align: right;\"><a href="#" style="cursor: pointer;" onClick="remove_block(\' + id + \')"><img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/Delete_blocks.png" /></a></td></tr></table>   \';
			capa.appendChild(div);
		}
	}
	function remove_block(id)
	{
		var element = document.getElementById("block_" + id);
		element.parentNode.removeChild(element);
	}
	// ]]></script>';
	
	
	echo'
	<form method="post" action="'. $scripturl .'?action=admin;area=blocks;sa=newblocks;set=multi_block_save">
		<div class="cat_bar">
			<h3 class="catbg">
				<img alt="" src="'.$adkFolder['images'].'/brick_add.png" style="vertical-align: middle;" /> ',$txt['adkblock_multi_block'],'
			</h3>
		</div>
		<div class="description">
			',$txt['adkblock_multi_block_desc'],'
		</div>
		<span class="clear upperframe">
			<span>&nbsp;</span>	
		</span>
		<div class="roundframe">
			<div>
				<fieldset>
					<legend style="color: teal">'.$txt['adkblock_multi_block_select'].'</legend>
					<div class="adk_padding_8 text_align_center">
						<select style="text-align: center;" name="blocks" onchange="add_block(this.options[this.selectedIndex].value)">
							<option value="0">'.$txt['adkblock_select_insert'].'</option>';

		foreach($context['adk_blocks'] AS $id => $name)
			echo'
							<option style="text-align: left;" value="',$id,'">(',$id,') ',$name,'</option>';

		echo'
						</select>
					</div>
				</fieldset>
				<fieldset>
					<legend style="color: teal">'.$txt['adkblock_multi_block_selected'].'</legend>
					<div class="adk_padding_8">
						<div id="blocks" class="description">
						</div>
					</div>
				</fieldset>
				<br />
				<div class="cat_bar">
					<h3 class="catbg">
						<img alt="" src="'.$adkFolder['images'].'/admin.png" style="vertical-align: middle;" /> ',$txt['adkblock_options'],'
					</h3>
				</div>
				<br />
				<table width="100%">
					<tr>
						<td width="50%">
							&nbsp;
						</td>
						<td width="50%">
							<fieldset>
								<legend style="color: teal">'.$txt['adkblock_options'].'</legend>
									<table width="100%">
										
										<tr>
											<td width="50%">
												',$txt['adkblock_titulo'],'
											</td>
											<td width="50%" style="text-align: right;">
												<input type="text" size="15" name="titulo" style="text-align: right;" />
											</td>
										</tr>
										
									</table>
							</fieldset>
						</td>
					</tr>
				</table>
				<hr />
				<div align="center">
					<input type="submit" class="button_submit" value="',$txt['save'],'" />
					<input type="hidden" name="sc" value="',$context['session_id'],'" />
				</div>
			</div>
		</div>
		<span class="lowerframe">
			<span>&nbsp;</span>	
		</span>	
	</form>';

}

function template_createnews()
{
	global  $scripturl, $context, $txt, $boardurl, $adkFolder;

	echo'
	<form method="post" action="'. $scripturl .'?action=admin;area=blocks;sa=',$context['save_action'],'">
		<div class="cat_bar">
			<h3 class="catbg">
				<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/new.png" />&nbsp;'.$txt['adkblock_body'].'
			</h3>
		</div>

		<span class="clear upperframe">
			<span>&nbsp;</span>	
		</span>
		<div class="roundframe">
			<div>
				<table cellspacing="0" border="0" style="width: 100%;">
					<tr>
						<td style="width: 20%;">
							'.$txt['adkblock_autor'].'
						</td>
						<td>
							<input size="95" type="text" name="autore" value="',$context['edit']['autor'],'" />
						</td>
					</tr>
					<tr>
						<td>
							'.$txt['adkblock_titulo'].'
						</td>
						<td>
							<input size="95" type="text" name="titlepage" value="',$context['edit']['title'],'" />
						</td>
					</tr>
					<tr>
						<td colspan="2">';
							
							//Get Template EDitor()
							getTemplateEditor();
			echo'
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2"><br />
							<input type="hidden" name="id" value="'.$context['edit']['id'].'" />
							<input type="hidden" name="sc" value="', $context['session_id'], '" />
							<input class="button_submit" type="submit" name="cmdSubmit" value="'.$txt['save'].'" />
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

function template_uploadblock()
{
	global $context, $scripturl, $txt, $boardurl, $adkFolder;

	echo'
	<form enctype="multipart/form-data" action="'.$scripturl.'?action=admin;area=blocks;sa=saveuploadblock" method="post">
		<div class="cat_bar">
			<h3 class="catbg">
				<img alt="" class="adk_vertical_align" src="'.$adkFolder['images'].'/package_add.png" />&nbsp;'.$txt['adkmod_block_upload'].'
			</h3>
		</div>
		<span class="clear upperframe">
			<span>&nbsp;</span>	
		</span>
		<div class="roundframe">
			<div>
				<table style="width: 100%;">
					<tr>
						<td style="width: 50%; text-align: left;">
							',help_link('adkblock_upload_yourBlock_info','adkhelp_upload_yourBlock_info'),'
						</td>
						<td style="width: 50%; text-align: left;">
							<fieldset>
								<legend style="color: teal">'.$txt['adkblock_upload'].'</legend>
								<div class="adk_padding_8 text_align_center">
									<input size="30" type="file" value="" name="file" />
									<br />
								</div>
							</fieldset>
							<br />
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<br />
							<hr />
							<input class="button_submit" type="submit" value="'.$txt['save'].'" />
							<input type="hidden" name="sc" value="', $context['session_id'], '" />
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

function template_preview_adkblock()
{
	global  $scripturl, $context, $txt, $boarddir, $boardurl, $adkportal, $adkFolder;
	
	$width = 100;
	
	if($context['adkportal']['blocks']['columna'] == 1)
		$width = $adkportal['wleft'];
	elseif($context['adkportal']['blocks']['columna'] == 3)
		$width = $adkportal['wright'];
	
	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img class="adk_vertical_align" alt="" src="'.$adkFolder['images'].'/search.png" />&nbsp;'.$txt['adkblock_preview'].'
			</h3>
		</div>
		<span class="clear upperframe">
			<span>&nbsp;</span>	
		</span>
		<div class="roundframe">
			<div>
				<div style="margin: 0 auto; width: '.$width.';">';
	
	adk_create_block($context['adkportal']['blocks'], false);
	
	echo'
				</div>
			</div>
		</div>
		<span class="lowerframe">
			<span>&nbsp;</span>	
		</span>';

}

function template_permissions()
{
	global $scripturl, $txt, $context, $boardurl, $adkFolder;

	$p = explode(',',$context['block_permissions']['p']);

	echo'
		<form id="lu" method="post" action="'. $scripturl .'?action=admin;area=blocks;sa=savepermissions">
		<div class="cat_bar">
			<h3 class="catbg">
				<img src="'.$adkFolder['images'].'/package.png" style="vertical-align: middle;" alt="" />&nbsp;
				'.$context['block_permissions']['name'].'
			</h3>
		</div>
		<div class="information">
			'.$txt['adkblock_permissions_desc'].'
		</div>
		<span class="clear upperframe">
			<span>&nbsp;</span>	
		</span>
		<div class="roundframe">
			<div>
				<div class="cat_bar">
					<h3 class="catbg">
						<img src="'.$adkFolder['images'].'/users.png" style="vertical-align: middle;" alt="" />&nbsp;
						'.$txt['adkblock_editing_permissions'].'
					</h3>
				</div>
				<table style="width: 100%;">
					<tr>
						<td style="width: 50%;">
							&nbsp;
						</td>
						<td style="width: 50%;">
							<fieldset>
								<legend style="color: teal">'.$txt['adkblock_select_group'].'</legend>
								<input style="vertical-align: middle;" type="checkbox" name="adk[-1]" value="-1"',in_array(-1,$p) ? ' checked="checked"' : '' ,' />&nbsp;'.$txt['adkblock_guests'].'<br />
								<input style="vertical-align: middle;" type="checkbox" name="adk[-2]" value="-2"',in_array(-2,$p) ? ' checked="checked"' : '' ,' />&nbsp;'.$txt['adkblock_regulars_users'].'<br />';
	foreach($context['adk_groups'] AS $i => $v)
		echo'
								<input style="vertical-align: middle;" type="checkbox" name="adk['.$i.']" value="'.$i.'"',in_array($i,$p) ? ' checked="checked"' : '' ,' />&nbsp;'.$v['name'].'<br />';

	echo'
								<div style="text-align: right;">
									<i>'.$txt['adkblock_check_all'].'</i> <input style="vertical-align: middle;" type="checkbox" onclick="invertAll(this, this.form, \'adk\');" />
								</div>
							</fieldset>
						</td>
					</tr>
				</table>
				<hr />
				<div style="text-align: center;">
					<input type="hidden" value="'.$context['session_id'].'" name="sc" />
					<input type="hidden" value="'.$context['block_permissions']['id'].'" name="id" />
					<input type="submit" value="'.$txt['save'].'" class="button_submit" />
				</div>
			</div>
		</div>
		<span class="lowerframe">
			<span>&nbsp;</span>	
		</span>	
	</form>';

}

function CreateAddCustomBlocks($type)
{
	global $context, $txt, $scripturl, $boardurl, $adkFolder;
		
	if($type == 'staff')
		$the_txt = $txt['adkblock_staff'];
	elseif($type == 'php' || $type == 'bbc')
		$the_txt = '';
	elseif($type == 'top_poster' || $type == 'top_karma')
		$the_txt = $txt['adkblock_limit'];
	else
		$the_txt = $txt['adkblock_select_forums'];
	
	echo'
	<form method="post" action="'. $scripturl .'?action=admin;area=blocks;sa=newblocks;set='.$type.'_save">
		<div class="cat_bar">
			<h3 class="catbg">
				<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/page.png" />&nbsp;'.$txt['adkblock_creating'].'
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
						'.$txt['adkblock_titulo'].'
						</td>
						<td>
							<input type="text" value="" name="titulo" />
						</td>
					</tr>
				</table>
				<table style="width: 100%;">
					<tr>
						<td valign="top" style="width: 30%;">
							',$type == 'staff' ? help_link('','adkhelp_staff_group',false) : '' , $the_txt.'
						</td>
						<td>';
				if($type == 'staff'){
					echo'
							<fieldset>
								<legend style="color: teal">'.$txt['adkblock_select_group'].'</legend>';
					foreach($context['g'] AS $id_group => $v)
						echo'
								<input style="vertical-align: middle;" type="checkbox" value="'.$id_group.'" name="groups_allowed['.$id_group.']" /> '.$v['name'].'<br />';
		
						echo'
								<div style="text-align: right;">
									<i>'.$txt['adkblock_check_all'].'</i> <input style="vertical-align: middle;" type="checkbox" onclick="invertAll(this, this.form, \'groups_allowed\');" />
								</div>
							</fieldset>
							'.$txt['adkblock_avatar'].': <input style="vertical-align: middle;" type="checkbox" name="avatar" />';
				}
				elseif($type == 'auto_news')
				{
					echo'
							<select name="auto_news_id_boards[]" size="10" multiple="multiple" style="width: 280px;">';
						foreach ($context['jump_to'] as $category)
						{
							echo '
								<option disabled="disabled">----------------------------------------------------</option>
								<option disabled="disabled">', $category['name'], '</option>
								<option disabled="disabled">----------------------------------------------------</option>';
							foreach ($category['boards'] as $board)
								echo '
								<option value="' ,$board['id'], '"> ' . str_repeat('&nbsp;&nbsp;&nbsp; ', $board['child_level']) . '|--- ' . $board['name'] . '</option>';
						}
					echo'
							</select>
							<br /><br />
							'.$txt['adkblock_limit_auto_news'].': <input type="text" name="int" value="" size="2" />';
				}
				elseif($type == 'bbc')
				{
					echo'
						&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>HTML</strong> <input type="checkbox" name="html" /><br />';
					
					//Get Template Editor()
					getTemplateEditor();
				}
				elseif($type == 'php')
					echo'
							<textarea name="descript" rows="10" cols="80"><?php</textarea>';
				elseif($type == 'top_poster' || $type = 'top_karma')
					echo'
							<input type="text" name="descript" value="" />';
			echo'
						</td>
					</tr>
				</table>
				<br />
				<div class="cat_bar">
					<h3 class="catbg">
						<img alt="" src="'.$adkFolder['images'].'/admin.png" style="vertical-align: middle;" /> ',$txt['adkblock_options'],'
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
									<legend style="color: teal">'.$txt['adkblock_options'].'</legend>
									<table style="width: 100%;">
								
										<tr class="smalltext">
											<td style="width: 50%;">
												'.$txt['adkblock_empty_style_block'].'
											</td>
											<td style="width: 50%; text-align: right;">
												<input type="checkbox" name="empty_body" />
											</td>
										</tr>
										<tr class="smalltext">
											<td style="width: 50%;">
												'.$txt['adkblock_empty_title'].'
											</td>
											<td style="width: 50%; text-align: right;">
												<input type="checkbox" name="empty_title" />
											</td>
										</tr>
										<tr class="smalltext">
											<td style="width: 50%;">
												'.$txt['adkblock_empty_collapse'].'
											</td>
											<td style="width: 50%; text-align: right;">
												<input type="checkbox" name="empty_collapse" />
											</td>
										</tr>
										
									</table>
								</fieldset>
							</td>
						</tr>
					</table>
					<table style="width: 100%;">
						<tr class="smalltext">
							<td style="width: 50%;">
								&nbsp;
							</td>
							<td style="width: 50%;">
								<fieldset>
									<legend style="color: teal">'.$txt['adkblock_icons'].'</legend>
										<table style="width: 100%;">
											<tr>
												<td>
													',openDirImages(),'
												</td>
											</tr>
										</table>
								</fieldset>
							</td>
						</tr>
					</table>
				</div>
				<hr />
				<div style="text-align: center;">
					<input type="hidden" name="sc" value="'.$context['session_id'].'" />
					<input class="button_submit" type="submit" value="'.$txt['save'].'" />
				</div>
			</div>
		</div>
		<span class="lowerframe">
			<span>&nbsp;</span>	
		</span>	
	</form>';

}

function template_print_blocks($position = 'left', $type = 'default')
{

	global $txt, $boardurl, $context, $adkFolder;

	echo'
	<span class="clear upperframe">
		<span>&nbsp;</span>	
	</span>
	<div class="roundframe">
		<div>
			<img alt="" class="adk_vertical_align" src="'.$adkFolder['images'].'/drive_key.png" />
			<strong>',$txt['adkblock_'.$position],'</strong>
			<hr />
			<table style="width: 100%;">';

	$posicionDelVector = 0;

	if(!empty($context['blocks_admin'][$position]))
	foreach($context['blocks_admin'][$position] AS $id_block => $block){

		echo'
				<tr >
					<td style="width: 20px;" align="center">
						',!empty($block['img']) ? '<img src="'.$adkFolder['images'].'/blocks/'.$block['img'].'" alt="" align="top" />' : '' ,'
					</td>
					<td>
						<span title="(',$block['type'] == 'include' ? $txt['adkblock_include'] : $block['type'],')">',$block['name'],'</span>
					</td>
					<td style="width: 5%;" align="center">
						<input type="text" size="2" name="orden_'.$position.'['.$posicionDelVector.']" value="'.$block['orden'].'" style="text-align: center;" />
						<input type="hidden" name="id_'.$position.'['.$posicionDelVector.']" value="',$id_block,'" />
					</td>
					<td style="width: 5%;" align="center">
						<select name="columna_'.$position.'['.$posicionDelVector.']">
							<option value="1"', $position == 'left' ? ' selected="selected"' : '' ,'>'.$txt['adkblock_left'].'</option>
							',$context['type'] == 'default' ? '<option value="2"'. ($position == 'center' ? ' selected="selected"' : '') .'>'.$txt['adkblock_center'].'</option>' : '' ,'
							<option value="3"', $position == 'right' ? ' selected="selected"' : '' ,'>'.$txt['adkblock_right'].'</option>
							<option value="4"', $position == 'top' ? ' selected="selected"' : '' ,'>'.$txt['adkblock_top'].'</option>
							<option value="5"', $position == 'bottom' ? ' selected="selected"' : '' ,'>'.$txt['adkblock_bottom'].'</option>
							<option value="6"', $position == 'admin' ? ' selected="selected"' : '' ,'>'.$txt['adkblock_none'].'</option>
						</select>
					</td>
				</tr>';

		$posicionDelVector++;
	}
	else{
		echo '<tr><td class="smalltext" align="center">',$type != 'default' ? help_link('adkblock_only_portal', 'adkhelp_only_portal') : $txt['adkblock_no_blocks'],'</td></tr>';
	}

	echo'
			</table>
		</div>
	</div>
	<span class="clear lowerframe">
		<span>&nbsp;</span>	
	</span>';
}

function template_download_new_block()
{

	global $scripturl, $context, $txt, $boardurl, $adkFolder;

	echo'
	<form method="post" action="">
		<div class="cat_bar">
			<h3 class="catbg">
				<img alt="" class="adk_vertical_align" src="'.$adkFolder['images'].'/drive_key.png" />&nbsp;',$txt['adkmod_block_download'],'
			</h3>
		</div>
		<table style="width: 100%;">
			<tr>';

	$i = 0;
	
	if(!empty($context['smf_personal_blocks'])) {
		foreach($context['smf_personal_blocks']->bloque AS $bloque) {
			if($i == 2) {
				echo'</tr><tr style="padding:; 8px;">';
				$i = 0;
			}
	
			$desc = CleanAdkStrings(utf8_decode($bloque->description));
	 		$name = CleanAdkStrings(utf8_decode($bloque->name));
	
			echo'
					<td>
						<span class="clear upperframe">
							<span>&nbsp;</span>	
						</span>
						<div class="roundframe">
							<div>
								<span style="cursor: pointer;" title="',$desc,'">
									',$name,'
								</span>
								<div style="float: right;">
									<a href="',$scripturl,'?action=admin;area=blocks;sa=add_smf_block;id=',$bloque->filename,';name=',$bloque->name,';real=',$bloque->original,';',$context['session_var'],'=',$context['session_id'],'"><img alt="',$txt['adkblock_install'],'" title="',$txt['adkblock_install'],'" src="'.$adkFolder['images'].'/add.png" /></a>
									 
									<a href="http://www.smfpersonal.net/index.php?action=downloads;sa=view;down=',$bloque->file_id,'"><img alt="',$txt['adkblock_go_to_personal'],'" title="',$txt['adkblock_go_to_personal'],'" src="'.$adkFolder['images'].'/xmag.png" /></a>
								</div>
								<br class="clear" />
							</div>
						</div>
						<span class="clear lowerframe">
							<span>&nbsp;</span>	
						</span>
					</td>';
			$i++;
		}
	}
	else {
		echo'
				<td>
					<span class="clear upperframe">
						<span>&nbsp;</span>	
					</span>
					<div class="roundframe">
						<div style="text-align: center; font-weight: bold; font-family: georgia;">
							'.$txt['adkblock_no_blocks'].'
						</div>
					</div>
					<span class="clear lowerframe">
						<span>&nbsp;</span>	
					</span>
				</td>';
	}
	echo'
			</tr>
		</table>
	</form>';
}

?>