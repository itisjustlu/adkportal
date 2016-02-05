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

function template_main()
{
	global $context, $txt, $scripturl, $modSettings, $boardurl, $boarddir, $user_info, $adkFolder, $adkportal;

	//The Download bar buttons
	download_bar_buttons();

	echo'
		<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
			<h3 class="eds_catbg" style="padding-top: 5px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
				<img src="'.$adkFolder['images'].'/view_refresh.png" style="vertical-align: text-bottom;" alt="'.$txt['adkdown_downloads'].'" />&nbsp;'.$txt['adkdown_downloads'].'
			</h3>
		</div>';
		template_show_categories();

	echo'
		<br />
		<table class="adk_100">
			<tr>
				<td class="adk_50" valign="top">
					<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
						<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
							&nbsp;'.$txt['adkdown_lasted_down'].'
						</h3>
					</div>
					<div class="eds_down" style="border-color: '.$adkportal['Designeds']['borde'].'; background: '.$adkportal['Designeds']['fondo'].';">
						<div class="my_back"></div>';
				if(!empty($context['last_downloads'])) {
					$link = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$context['last_downloads']);
					echo'
						<ul class="eds_list">
								',implode("",$link),'
						</ul>';
				}
				else
					echo'
						<ul class="eds_list"><li><img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;',$txt['adkdown_none'],'</li></ul>';
	echo'
					</div>
					<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
						<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
					</div>
				</td>
				<td class="adk_50" valign="top">
					<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
						<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
							&nbsp;'.$txt['adkdown_popular'].'
						</h3>
					</div>
					<div class="eds_down" style="border-color: '.$adkportal['Designeds']['borde'].'; background: '.$adkportal['Designeds']['fondo'].';">
						<div class="my_back"></div>';
			if(!empty($context['downloads_popular'])) {
					$links = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$context['downloads_popular']);
				echo'
					<ul class="eds_list">
						',implode("",$links),'
					</ul>';
			}
			else
				echo'
					<ul class="eds_list"><li><img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;',$txt['adkdown_none'],'</li></ul>';
	echo'
					</div>
					<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
						<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
					</div>
				</td>
			</tr>
		</table>';

}

function template_view_download_files()
{
	global $context, $txt, $scripturl, $modSettings,$boardurl, $boarddir, $user_info, $adkFolder, $adkportal;

	echo'
		<div class="cat_bar">
			<h3 class="catbg">
				<img src="'.$adkFolder['images'].'/stats_s_red.png" style="vertical-align: text-bottom;" alt="'.$context['adk_download_title'].'" />&nbsp;'.$context['adk_download_title'].'
			</h3>
		</div>';

		if(!empty($context['adk_download_description']))
			echo'
		<div class="description">
			<div align="left">
				'.$context['adk_download_description'].'
			</div>
		</div>';

	if(!empty($context['all_cat']))
		template_show_categories();

	$menu_buttons = array(
		'viewstats' => array(
			'text' => 'adkdown_view_stats', 
			'image' => '', 
			'lang' => true, 
			'url' => $scripturl.'?action=downloads;sa=viewstats',
		),
		'myprofile' => array(
			'test' => 'adk_user_is_logged', 
			'text' => 'adkdown_myprofile', 
			'image' => '', 
			'lang' => true, 
			'url' => $scripturl.'?action=downloads;sa=myprofile;u='.$user_info['id'],
		),
		'add_sub_category' => array(
			'test' => 'adk_can_manage',
			'text' => 'adkdown_sub_category',
			'image' => '',
			'lang' => true,
			'url' => $scripturl.'?action=admin;area=adkdownloads;sa=addcategory;cat='.$context['cat_id'].';'.$context['session_var'].'='.$context['session_id'],
		)
	);

	if($context['adk_can_add_file']){
		$menu_buttons += array(
			'addnewfile' => array(
				'test' => 'adk_downloads_add', 
				'text' => 'adkdown_add', 
				'image' => '', 
				'lang' => true, 
				'url' => $scripturl.'?action=downloads;sa=addnewfile;category='.$context['cat_id'],
				'active' => true
			)
		);
	}
	
	echo'
		<table class="adk_100">
			<tr>
				<td align="left">
					<div align="left" class="smalltext">'.$txt['pages'].': '.$context['page_index'].'</div>
				</td>
				<td align="right">
					',template_button_strip($menu_buttons,'right'),'
				</td>
			</tr>
		</table>
		<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
			<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
				&nbsp;
			</h3>
		</div>
		<div class="eds_down" style="min-height: 0px; padding: 0px;border-color: '.$adkportal['Designeds']['borde'].';">
			<div style="margin-top: -22px">
				<table class="table_eds" style="width: 100%;" cellspacing="0">
					<thead>
						<tr >
							<th width="4%" scope="col" class="first_th" style="text-align: center;">
								&nbsp;
							</th>
							<th scope="col" align="center" style="padding-bottom: 4px; color:'.$adkportal['Designeds']['titulo'].';">
								'.$txt['adkdown_subject'].'
							</th>
							<th scope="col" align="center" width="11%" style="padding-bottom: 4px; color:'.$adkportal['Designeds']['titulo'].';">
								'.$txt['adkdown_vistas'].'
							</th>
							<th scope="col" align="center" width="11%" style="padding-bottom: 4px; color:'.$adkportal['Designeds']['titulo'].';">
								'.$txt['adkdown_downloads'].'
							</th>
							<th scope="col" class="last_th" align="center" width="20%" style="padding-bottom: 4px; color:'.$adkportal['Designeds']['titulo'].';">
								'.$txt['adkdown_date'].'
							</th>
						</tr>
					</thead>';

		$i = 0;
		foreach($context['listFiles'] AS $file) {
			$background = !empty($file['color']) ? $file['color'] : $adkportal['Designeds']['fondo'];
			$desc = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$file['file']);
			$author = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$file['member']);

			echo'
				<tbody>
					<tr style="background: ',$background,';">
						<td align="center" style="border-color: '.$adkportal['Designeds']['borde'].';">
							<img src="'.$adkFolder['images'].'/',$file['image'],'.png" alt="" />
						</td>
						<td style="border-color: '.$adkportal['Designeds']['borde'].'; color:'.$adkportal['Designeds']['letra'].';">
							'.$desc.'
							<div class="smalltext">'.$txt['by'].': '.$author.'</div>
						</td>
						<td class="smalltext" align="center" style="border-color: '.$adkportal['Designeds']['borde'].'; color:'.$adkportal['Designeds']['letra'].';">
							'.$file['views'].'
						</td>
						<td class="smalltext" align="center" style="border-color: '.$adkportal['Designeds']['borde'].'; color:'.$adkportal['Designeds']['letra'].';">
							'.$file['total'].'
						</td>
						<td class="smalltext" align="center" style="border-color: '.$adkportal['Designeds']['borde'].'; color:'.$adkportal['Designeds']['letra'].';">
							'.$file['date'].'
						</td>
					</tr>
				</tbody>';
			$i++;
			
		}

		if(($i == 0)) {
		echo'
					<tbody>
						<tr>
							<td colspan="5" align="center" style="background: '.$adkportal['Designeds']['fondo'].'; border-color: '.$adkportal['Designeds']['borde'].';">
								<strong>'.$txt['adkdown_no_downloads'].'</strong>
							</td>
						</tr>
					</tbody>';
		}

	echo'
					</table>
				</div>
			</div>
			<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
				<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
			</div>
			<table style="width: 100%;">
				<tr>
					<td align="left">
						<div align="left" class="smalltext">'.$txt['pages'].': '.$context['page_index'].'</div>
					</td>
					<td align="right">
						',template_button_strip($menu_buttons,'right'),'
					</td>
				</tr>
			</table>';

}

function template_show_categories(){

	global $context, $boardurl, $scripturl, $boarddir, $txt, $user_info, $adkFolder, $adkportal;

	echo'
	<div class="eds_down" style="padding: 0px; background: '.$adkportal['Designeds']['fondo'].'; color: '.$adkportal['Designeds']['letra'].'; border-color: '.$adkportal['Designeds']['borde'].';">
			<table class="table_list eds_table" cellspacing="0" style="border-color: '.$adkportal['Designeds']['borde'].';">
				<tbody class="eds_title_td" style="border-color: '.$adkportal['Designeds']['borde'].';">
					<tr>
						<td class="adk_5" style="border-color: '.$adkportal['Designeds']['borde'].';">&nbsp;</td>
						<td style="color: '.$adkportal['Designeds']['link'].'; border-color: '.$adkportal['Designeds']['borde'].';">'.$txt['adkdown_category'].'</td>
						<td style="color: '.$adkportal['Designeds']['link'].'; border-color: '.$adkportal['Designeds']['borde'].';">'.$txt['adkdown_downloads'].'</td>
						<td style="color: '.$adkportal['Designeds']['link'].'; border-color: '.$adkportal['Designeds']['borde'].';">'.$txt['adkdown_last_down'].'</td>
						',allowedTo('adk_downloads_manage') ? '<td class="adk_5" style="border-color: '.$adkportal['Designeds']['borde'].';">&nbsp;</td>' : '' ,'
					</tr>
				</tbody>';	

	foreach($context['all_cat'] AS $cat)
	{			
		if(!empty($context['all_parent'][$cat['id_cat']])) {
			$sub_children = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$context['all_parent'][$cat['id_cat']]);
			$sub_children = implode(', ',$sub_children);
		}
			echo'
				<tbody class="eds_content_td">
					<tr>
						<td align="center" style="border-color: '.$adkportal['Designeds']['borde'].';">';

		if(!empty($cat['image']))
			$image = str_replace($boarddir,$boardurl,$cat['image']);

		$caturl = $scripturl.'?action=downloads;cat='.$cat['id_cat'];

		if(!empty($image))
		echo'
					<img src="'.$image.'" alt="'.$cat['title'].'" />';
		else
			echo'
					<img src="'.$adkFolder['images'].'/eds_download.png" alt="'.$cat['title'].'" />';

		echo'	
					</td>
					<td class="adk_40" style="border-color: '.$adkportal['Designeds']['borde'].';">';
		echo'
						<a class="subject adk_bold" href="'.$caturl.'" style="color: '.$adkportal['Designeds']['link'].';">'.$cat['title'].'</a> 
						<br />
						<div class="smalltext" style="color: '.$adkportal['Designeds']['letra'].';">'.$cat['description'].'</div>
						',!empty($context['all_parent'][$cat['id_cat']]) ? '
						<div class="smalltext eds_childreborder_td" style="color: '.$adkportal['Designeds']['letra'].';">'.$txt['adkdown_sub_cat'].': '.$sub_children.'</div>' : '' ,'
					</td>
					<td align="center" class="adk_10" style="border-color: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['letra'].';">
						('.$cat['total'].')
					</td>
					<td class="smalltext adk_40" style="border-color: '.$adkportal['Designeds']['borde'].';">';

		if(!empty($cat['post']['id'])){
			$title = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$cat['post']['file']);
			$author = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$cat['post']['member']);
			$avatar = !empty($cat['post']['avatar']) ? $cat['post']['avatar'] : '<img style="width: 50px; height: 50px;" alt="" src="'.$adkFolder['images'].'/noavatar.jpg" />';
			echo'
					<table class="adk_100">
						<tr>
							<td valign="top" style="border: none; width: 100px;">
								'.$avatar.'
							</td>
							<td valign="top" class="adk_align_left" style="border: none; color: '.$adkportal['Designeds']['letra'].';">
								<img class="adk_vertical" alt="" src="'.$adkFolder['images'].'/newmsg.png" />&nbsp;
								<strong>'.$title.'</strong> '.$txt['by'].' '.$author.'<br />
								'.$txt['on'].' '.$cat['post']['date'].'
							</td>
						</tr>
					</table>';
		}
		elseif(empty($cat['post']['id'])) {
					echo'
						<table class="adk_100">
							<tr>
								<td valign="top" style="border: none; width: 100px;">
									<img style="width: 50px; height: 50px;" alt="" src="'.$adkFolder['images'].'/stop.png" />
								</td>
								<td valign="top" class="adk_align_left" style="border: none;">
									<img class="adk_vertical" alt="" src="'.$adkFolder['images'].'/newmsg.png" />&nbsp;
									<strong style="color: '.$adkportal['Designeds']['letra'].';">'.$txt['adkdown_no_downloads'].'</strong>
								</td>
							</tr>
						</table>';
		}

	echo'
					</td>';
	
		if(allowedTo('adk_downloads_manage'))
			echo'
					<td valign="middle" class="adk_align_center" style="border-color: '.$adkportal['Designeds']['borde'].';">
						<a class="eds_img_link" href="',$scripturl,'?action=downloads;sa=up;id='.$cat['id_cat'].'">
							<img alt="" src="'.$adkFolder['images'].'/colapse.png" />
						</a>
						<a class="eds_img_link" href="',$scripturl,'?action=downloads;sa=down;id='.$cat['id_cat'].'">
							<img alt="" src="'.$adkFolder['images'].'/expand.png" />
						</a>
					</td>';
		echo'
				</tr>
			</tbody>
		';
	}

	if (empty($context['all_cat'])) {
		echo'
			<tr>
				<td ',allowedTo('adk_downloads_manage') ? 'colspan="5"' : 'colspan="4"' ,' align="center" style="height: 60px; color: '.$adkportal['Designeds']['link'].';">
					<strong>'.$txt['adkeds_nocategory'].'</strong>
				</td>
			</tr>';
	}

	echo'
			</table>
		</div>
		<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
			<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
		</div>';

}

function template_adk_search_not()
{
	global $txt, $boardurl, $adkFolder, $adkportal;

	//Menu Buttons
	download_bar_buttons('search');

	echo'
		<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
			<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
				<img src="'.$adkFolder['images'].'/unapprove.png" style="vertical-align: text-bottom;" alt="'.$txt['adkdown_find_results'].'" />&nbsp;'.$txt['adkdown_find_results'].'
			</h3>
		</div>
		<div class="eds_down" style="min-height: 0; border-color: '.$adkportal['Designeds']['borde'].'; background: '.$adkportal['Designeds']['fondo'].'; color: '.$adkportal['Designeds']['letra'].';">
			<div align="center">
				<strong>'.$txt['adkdown_search_no_results'].'</strong>
				<br />
				<span><strong>[</strong> <a style="color: '.$adkportal['Designeds']['link'].'; text-decoration:none;" href=javascript:history.go(-1)>'.$txt['adkdown_go_back'].'</a> <strong>]</strong></span>
			</div>
		</div>
		<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
			<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
		</div>';
}

function template_adk_search_results()
{
	global $scripturl, $txt, $context, $boardurl, $adkFolder, $adkportal;
	
	//Menu Buttons
	download_bar_buttons('search');

	echo'
		<table style="width: 100%;">
			<tr>
				<td>
					<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
						<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
							<img src="'.$adkFolder['images'].'/approve.png" style="vertical-align: text-bottom;" alt="'.$txt['adkdown_find_results'].'" />&nbsp;'.$txt['adkdown_find_results'].'
						</h3>
					</div>
				</td>
			</tr>
		</table>
		<table style="width: 100%;">
			<tr>';

		$i = 0;

		foreach($context['downloads'] AS $down)
		{
			if($i == 2){
				echo'</tr><tr>';
				$margin = 'margin-top: -12px;';
				$i = 0;
			}
			echo'
				<td class="adk_50">
					<div class="eds_down" style="background: '.$adkportal['Designeds']['fondo'].'; color: '.$adkportal['Designeds']['letra'].'; border-color: '.$adkportal['Designeds']['borde'].'; ',!empty($margin) ? $margin : '','">
						<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_title'].'</strong> <a style="color: '.$adkportal['Designeds']['link'].';" href="'.$scripturl.'?action=downloads;sa=view;down='.$down['id'].'">'.$down['title'].'</a><br />
						<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_author'].':</strong> <a style="color: '.$adkportal['Designeds']['link'].';" href="'.$scripturl.'?action=profile;u='.$down['id_member'].'">'.$down['name'].'</a><br />
						<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_vistas'].':</strong> '.$down['viewsd'].'<br />
						<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_downloads'].':</strong> '.$down['totald'].'<br />
						<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_date'].':</strong> '.$down['date'].'<br />
						<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_last_down'].':</strong> '.$down['lastd'].'
					</div>
					<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
						<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
					</div>
				</td>';
			$i++;
		}
	echo'
			</tr>
		</table>';
}	

function template_adk_search()
{
	global $context, $txt, $scripturl, $boardurl, $adkFolder, $adkportal;

	//Menu Buttons
	download_bar_buttons('search');

	echo'
		<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
			<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
				<img src="'.$adkFolder['images'].'/xmag.png" style="vertical-align: text-bottom;" alt="'.$txt['adkdown_search'].' - '.$txt['adkdown_downloads'].'" />&nbsp;'.$txt['adkdown_search'].' - '.$txt['downloads'].'
			</h3>
		</div>
		<div class="eds_down" style="background: '.$adkportal['Designeds']['fondo'].'; color: '.$adkportal['Designeds']['letra'].'; border-color: '.$adkportal['Designeds']['borde'].';min-height: 0;">
			<form method="post" enctype="multipart/form-data" action="',$scripturl,'?action=downloads;sa=search2">
				<div align="center">
					<strong>'.$txt['adkdown_search'].'</strong>: <input type="text" value="" name="search" />
					<input type="hidden" value="'.$context['session_id'].'" name="sc" />
					<input type="submit" value="',$txt['adkdown_send'],'" class="button_submit " />
				</div>
			</form>
		</div>
		<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
			<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
		</div>';

}

function template_adk_view_file()
{
	global $context, $txt, $scripturl, $modSettings, $user_info, $boardurl, $settings, $adkFolder, $adkportal;
	
	$width_ = 100;
	
	if($context['adkDownloadInformation']['approved'] == 0)
		echo'
		<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
			<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
				&nbsp;
			</h3>
		</div>
		<div class="eds_down_profile approvebg" style="min-height: 65px; color: '.$adkportal['Designeds']['letra'].'; border-color: '.$adkportal['Designeds']['borde'].';">
			<div class="eds_disable_post">
				<strong>'.$txt['adkdown_unnapproved_down'].'</strong> 
			</div>
		</div>
		<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
			<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
		</div>';
	
	if($context['adkDownloadInformation']['approved'] == 1)
	{
		$newtxt = $scripturl.'?action=downloads;sa=unapprovedownload;id='.$context['adkDownloadInformation']['id_file'].';sesc='.$context['session_id'];
		$newtxt_2 = 'adkdown_unapprove';
	}
	else
	{
		$newtxt = $scripturl.'?action=downloads;sa=approvedownload;id='.$context['adkDownloadInformation']['id_file'].';sesc='.$context['session_id'];
		$newtxt_2 = 'adkdown_approve';
	}
	
	if(allowedTo('adk_downloads_manage') || $user_info['id'] == $context['adkDownloadInformation']['id_member'])
		$context['you_can_edit_and_download'] = true;
	if(allowedTo('adk_downloads_manage'))
		$context['adk_downloads_manage'] = true;
	if(!empty($context['adkDownloadInformation']['id_topic']) && $context['adkDownloadInformation']['id_board'] != $modSettings['recycle_board'] && $context['adkDownloadInformation']['topic_exists'])
		$context['view_topic_you_can'] = true;
	
	$menu_buttons = array(
		'view_topic' => array(
			'test' => 'view_topic_you_can', 
			'text' => 'adkdown_comment', 
			'image' => '', 
			'lang' => true, 
			'url' => $scripturl.'?topic='.$context['adkDownloadInformation']['id_topic'].'.0',
			'active' => true
		),
		'edit' => array(
			'test' => 'you_can_edit_and_download', 
			'text' => 'adkdown_edit', 
			'image' => '', 
			'lang' => true, 
			'url' => $scripturl.'?action=downloads;sa=editdownload;id='.$context['adkDownloadInformation']['id_file'].';sesc='.$context['session_id'],
		),
		'delete' => array(
			'test' => 'you_can_edit_and_download', 
			'text' => 'adkdown_delete', 
			'image' => '', 
			'lang' => true, 
			'url' => $scripturl.'?action=downloads;sa=deletedownload;id='.$context['adkDownloadInformation']['id_file'].';sesc='.$context['session_id'],
			'custom' => 'onclick="return confirm(\''. $txt['adkdown_remove_message']. '\');"',
		),
		'approve' => array(
			'test' => 'adk_downloads_manage', 
			'text' => $newtxt_2, 
			'image' => '', 
			'lang' => true,
			'url' => $newtxt
		),
	);
	
	echo'
	<div class="pagesection">
		',template_button_strip($menu_buttons,'right'),'
	</div>';

	$att = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$context['load_attachments']);

	echo'
		<table style="width: 100%;">
			<tr>
				<td>
					<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
						<h3 class="eds_catbg" style="background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
							<img src="'.$adkFolder['images'].'/page_white_copy.png" style="vertical-align: text-bottom;" alt="" />&nbsp;'.$context['adkDownloadInformation']['file_title'].'
							<span class="eds_author">', $txt['adkdown_author_info'], '</span>
						</h3>
					</div>
				</td>
			</tr>
		</table>
		<table style="width: 100%;">
			<tr>
				<td valign="top">
					<div class="eds_desc" style="border-color: '.$adkportal['Designeds']['borde'].'; background: '.$adkportal['Designeds']['fondo'].'; color: '.$adkportal['Designeds']['letra'].';">
						<div class="eds_content">
							',!empty($context['adkDownloadInformation']['image']) ? '<div style="float: right;"><img src="'.$context['adkDownloadInformation']['image'].'" alt="" /></div>' : '' ,'
							'.$context['adkDownloadInformation']['description'].'
						</div>
						<div class="eds_files">
							<strong class="eds_title_attachments" style="color: '.$adkportal['Designeds']['link'].';">'.$txt['adkdown_attach'].':</strong>
							<div class="eds_attachments smalltext" style="border-color: '.$adkportal['Designeds']['borde'].'; background-color: '.$adkportal['Designeds']['att'].'; color: '.$adkportal['Designeds']['letra'].';">
								<div style="overflow: ', $context['browser']['is_firefox'] ? 'visible' : 'auto', ';">
									',implode('<br />',$att),'
								</div>
							</div>
						</div>
					</div>
					<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
						<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
					</div>
				</td>
				<td valign="top" style="width: 240px;">
					<div class="eds_autor" style="border-color: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['letra'].'; background: '.$adkportal['Designeds']['fondo'].';">';
						// Show avatars, images, etc.?
						if (!empty($settings['show_user_images']))
							echo '<div style="float: right;">
								'. (!empty($context['member']['avatar']['image']) ? $context['member']['avatar']['image'] : '<img src="" alt="" border="0" />') .'</div>';
	echo '
						<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/user_suit.png" />&nbsp;
						<a style="color: '.$adkportal['Designeds']['link'].';" href="'.$scripturl.'?action=profile;u='.$context['member']['id'].'">
							<strong>'. $context['member']['name'] .'</strong>
						</a><br />';

						// Show the member's primary group (like 'Administrator') if they have one.
						if (isset($context['member']['group']) && $context['member']['group'] != '')
							echo '
								<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/users.png" />&nbsp;'. $context['member']['group'] .'<br />';

						// Show the post group if and only if they have no other group or the option is on, and they are in a post group.
						if ((empty($settings['hide_post_group']) || $context['member']['group'] == '') && $context['member']['post_group'] != '')
							echo '
								<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/users.png" />&nbsp;'. $context['member']['post_group'] .'<br />';
							echo '
								&nbsp;&nbsp;&nbsp;&nbsp;'. $context['member']['group_stars'] .'<br />';
							// Show how many posts they have made.
							echo '
									<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/newmsg.png" />&nbsp;'. $txt['member_postcount'] .': '. $context['member']['posts'] .'<br />';


							// Show the member's custom title, if they have one.
							if (isset($context['member']['title']) && $context['member']['title'] != '')
								echo '
									'.$txt['adkdown_title_autor'].': '. $context['member']['title'] .'<br />';

							// Show their personal text?
							if (!empty($settings['show_blurb']) && $context['member']['blurb'] != '')
								echo '
									'.$txt['adkdown_text_autor'].': '. $context['member']['blurb'] .'<br />';

							// Show the member's gender icon?
							if (!empty($settings['show_gender']) && $context['member']['gender']['image'] != '')
								echo '
									'. $txt['gender'] .': '. $context['member']['gender']['image'] .'<br />';

							// This shows the popular messaging icons.
							echo '
								'. $context['member']['icq']['link'] .'
								'. $context['member']['msn']['link'] .'
								'. $context['member']['aim']['link'] .'
								'. $context['member']['yim']['link'] .'<br />';
						
						// Show the profile, website, email address, and personal message buttons.
						if ($settings['show_profile_buttons'])
						{
							// Show the profile button
							echo '
									<a href="'. $context['member']['href'] .'">'. ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/icons/profile_sm.gif" alt="" title="" border="0" />' : '') .'</a>';
			
							// Don't show an icon if they haven't specified a website.
							if ($context['member']['website']['url'] != '')
								echo '
									<a href="'. $context['member']['website']['url'] .'" title="' . $context['member']['website']['title'] . '" target="_blank">'. ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/www_sm.gif" alt="" border="0" />' : '') .'</a>';

							// Don't show the email address if they want it hidden and is guest.							
							if (empty($context['member']['hide_email']))					
								echo '							
									<a href="mailto:'. $context['member']['email'] .'">'. ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/email_sm.gif" alt="" title="" border="0" />' : '') .'</a>';						

							//Send PM button
								echo '
									<a href="'. $scripturl .'?action=pm;sa=send;u='. $context['member']['id'] .'" title="'. $context['member']['online']['label'] .'">'. ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/im_' . ($context['member']['online']['is_online'] ? 'on' : 'off') . '.gif" alt="' . $context['member']['online']['label'] . '" border="0" />' : $context['member']['online']['label']) .'</a>';
							
						}	

							// Show online and offline buttons?
							if (!empty($modSettings['onlineEnable']))
								echo 
									($settings['use_image_buttons'] ? '<div style="float: right;"><img src="' . $context['member']['online']['image_href'] . '" alt="' . $context['member']['online']['text'] . '" border="0" style="margin-top: 2px;" />' : $context['member']['online']['text']) .'</div> ';

						$cat = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$context['adkDownloadInformation']['cat']);

	echo '
					</div>
					<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
						<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
					</div>
					<div class="eds_topbar" style="background: '.$adkportal['Designeds']['borde'].';">
						<span>'.$txt['adkdown_data_info'].'</span>
					</div>
					<div class="eds_down smalltext" style="background: '.$adkportal['Designeds']['fondo'].'; border-color: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['letra'].';">
						<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<strong>'.$txt['adkdown_title'].': </strong> <a style="color: '.$adkportal['Designeds']['link'].';" href="'.$scripturl.'?action=downloads;sa=view;down='.$context['adkDownloadInformation']['id_file'].'">'.$context['adkDownloadInformation']['file_title'].'</a><br />
						<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<strong>'.$txt['adkdown_category'].': </strong>'.$cat.' <br />
						<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<strong>'.$txt['adkdown_date'].': </strong>'.$context['adkDownloadInformation']['date'].'<br />
						<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<strong>'.$txt['adkdown_downloads'].': </strong>'.$context['adkDownloadInformation']['totaldownloads'].'<br />
						<img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<strong>'.$txt['adkdown_vistas'].': </strong> '.$context['adkDownloadInformation']['views'].'
						',!empty($context['adkDownloadInformation']['lastdownload']) ? '<br /><img style="vertical-align: middle;" alt="" src="'.$adkFolder['images'].'/menu.png" />&nbsp;<strong>'.$txt['adkdown_last_down'].': </strong>'.$context['adkDownloadInformation']['lastdownload'] : '' ,' 	
					</div>
					<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
						<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
					</div>
				</td>
			</tr>
		</table>';
	
}

function template_download_my_profile()
{
	global $context, $txt, $scripturl, $boardurl, $user_info, $adkFolder, $adkportal;

	//Menu Buttons
	download_bar_buttons('myprofile');

	echo'
			<table style="width: 100%;" cellspacing="0">
				<tr>
					<td colspan="2">
						<div class="eds_cat_bar" style="margin-bottom: -18px; background: '.$adkportal['Designeds']['borde'].';">
							<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
								<img src="'.$adkFolder['images'].'/vcard.png" style="vertical-align: text-bottom;" alt="'.$context['page_title'].'" />&nbsp;'.$context['link_profile'].'
							</h3>
						</div>
						<br />
					</td>
				</tr>
				<tr>';
	$i = 0;
	$cant_desc = count($context['listFiles'])+1;
	foreach($context['listFiles'] AS $file)
	{	
		$cant_desc = $cant_desc-1;
		
		if($i == 2){
			echo'</tr><tr>';
			$margins = 'margin-top: -12px;';
			$i = 0;
		}
		$title = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$file['file']);
		$author = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$file['member']);

		echo'
					<td class="adk_50 smalltext">
						<div class="',$file['approved'] == 0 ? 'eds_down_profile approvebg' : 'eds_down_profile' ,'" style="',$file['approved'] == 0 ? '' : 'background: '.$adkportal['Designeds']['fondo'].';' ,' border-color: '.$adkportal['Designeds']['borde'].'; ',!empty($margins) ? $margins : '','">
							',$file['approved'] == 0 ? '<div class="eds_disable">' : '' ,'
							<table style="width: 100%;">
								<tr>
									<td class="adk_50" style="color: '.$adkportal['Designeds']['letra'].';">
										<strong>#'.$cant_desc.'</strong><br />
										<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_title'].':</strong> '.$title.'<br />
										<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_author'].':</strong> '.$author.'<br />
										<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_vistas'].':</strong> '.$file['views'].'<br />
										<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_downloads'].':</strong> '.$file['total'].'<br />
										<img src="'.$adkFolder['images'].'/menu.png" alt="" />&nbsp;<strong>'.$txt['adkdown_date'].':</strong> '.$file['date'].'
									</td>
									<td class="adk_50" style="text-align:right;">
										<strong>
											<a style="color: '.$adkportal['Designeds']['link'].';" href="'.$scripturl.'?action=downloads;sa=view;down='.$file['id_file'].'">
												'.$txt['adkdown_view'] .'
											</a>
										</strong>
									</td>
								</tr>
							</table>
							',$file['approved'] == 0 ? '</div>' : '' ,'
						</div>
						<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
							<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
						</div>
					</td>';
		$i++;
		
		
	}

	echo'
					</tr>
				</table>';
}

function template_add_a_new_download()
{
	global $context, $txt, $scripturl, $modSettings, $adkportal, $boardurl, $adkFolder;
	
	$category = $context['id_cat_'];
	$rest = $adkportal['download_max_attach_download'] - $context['important_info']['rest'];

	//Multi Files
	echo'
		<script type="text/javascript"><!-- // --><![CDATA[
			var allowed_attachments = '.$rest.';
			function addAttachment()
			{
				allowed_attachments = allowed_attachments - 1;
				if (allowed_attachments <= 0)
					return alert("'.$txt['adkdown_not_add_more'].'");
	
				setOuterHTML(document.getElementById("moreAttachments"), \'<input type="file" size="60" name="download[]" class="input_file" /><br /><dd class="smalltext" id="moreAttachments" style="color: '.$adkportal['Designeds']['letra'].'; ">( <a style="color: '.$adkportal['Designeds']['link'].'; " href="#" onclick="addAttachment(); return false;">'.$txt['adkdown_add_more'].'<\' + \'/a> )<\' + \'/dd>\');
				return true;
			}
		// ]]></script>';

	echo'
		<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
			<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
				<img src="'.$adkFolder['images'].'/stats_s_green.png" style="vertical-align: text-bottom;" alt="'.$context['page_title'].'" />&nbsp;'.$context['page_title'].'
			</h3>
		</div>
		<div class="eds_down" style="border-color: '.$adkportal['Designeds']['borde'].'; background: '.$adkportal['Designeds']['fondo'].';">
			<form method="post" enctype="multipart/form-data" action="' . $scripturl . '?action=downloads;sa=',$context['save_action'],'">
				<div class="tborder">
					<table cellspacing="0" style="width: 100%;">
						<tr>
							<td style="text-align: left; width: 100px; color: '.$adkportal['Designeds']['letra'].'; ">
								<strong>'.$txt['adkdown_title'].' </strong>
							</td>
							<td style="text-align: left;">
								<input type="text" size="50" name="title" value="',$context['important_info']['title'],'" />
							</td>
						</tr>
						<tr>
							<td style="color: '.$adkportal['Designeds']['letra'].'; ">
								<strong>',$txt['adkdown_desc'],'</strong>
							</td>
							<td>
								<input type="text" size="50" maxlength="200" name="short_desc" value="',$context['important_info']['short_desc'],'" />
							</td>
						</tr>';
		
			if($context['save_action'] == 'saveeditdownload'){
				echo'
						<tr>
							<td style="text-align: left; color: '.$adkportal['Designeds']['letra'].';">
								<strong>'.$txt['adkdown_category'].' </strong>
							</td>
							<td style="text-align: left;">
								<select name="cat">';
				foreach($context['downloads_cat'] AS $cat)
					echo'				
									<option value="'.$cat['id'].'"',$cat['id'] == $context['important_info']['id_cat'] ? ' selected="selected"' : '' ,'>'.$cat['title'].'</option>';
				echo'
								</select>
							</td>
						</tr>';
			}

	echo'
						<tr>
							<td colspan="2">
								<hr />
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table>';
			if (!function_exists('getLanguages'))
			{
				// Showing BBC?
				if ($context['show_bbc'])
					echo '
									<tr>
										<td colspan="2" align="center">
											', template_control_richedit($context['post_box_name'], 'bbc'), '
										</td>
									</tr>';
				// What about smileys?
				if (!empty($context['smileys']['postform']))
					echo '
									<tr>
										<td colspan="2" align="center">
											', template_control_richedit($context['post_box_name'], 'smileys'), '
										</td>
									</tr>';
				// Show BBC buttons, smileys and textbox.
				echo '
									<tr>
										<td colspan="2" align="center">
											', template_control_richedit($context['post_box_name'], 'message'), '
										</td>
									</tr>';
			}
			else 
			{
				echo '
									<tr>
										<td>';
				// Showing BBC?
				if ($context['show_bbc'])
					echo '
											<div id="bbcBox_message"></div>';
				// What about smileys?
				if (!empty($context['smileys']['postform']) || !empty($context['smileys']['popup']))
					echo '
											<div id="smileyBox_message"></div>';
			
				// Show BBC buttons, smileys and textbox.
				echo '
											', template_control_richedit($context['post_box_name'], 'smileyBox_message', 'bbcBox_message');
				echo '
										</td>
									</tr>';
			}
				echo '
								</table>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; color: '.$adkportal['Designeds']['letra'].'; ">
								<strong>'.$txt['adkdown_screen'].' </strong>
							</td>
							<td style="text-align: left;">
								<input type="file" size="60" name="screen" class="input_file" />
								',!empty($context['important_info']['image']) ? '
								<br />
								<input type="checkbox" name="screen2" value="'.$context['important_info']['image'].'" checked="checked" />
								<strong>
									<a href="'.$context['important_info']['image'].'">
										'.$context['important_info']['image'].'
									</a>
								</strong>' : '' ,'
							</td>
						</tr>
						<tr>
							<td colspan="2">
							<hr />
							</td>
						</tr>
						<tr>
							<td valign="top" style="text-align: left; color: '.$adkportal['Designeds']['letra'].'; ">
								<strong>'.$txt['adkdown_attach'].' </strong>
							</td>
							<td style="text-align: left; color: '.$adkportal['Designeds']['letra'].'; ">
								',$context['important_info']['rest'] < 4 ? '<input type="file" size="60" name="download[]" class="input_file" />' : '' ,'
								<dl>
									<dd class="smalltext" id="moreAttachments" style="color: '.$adkportal['Designeds']['letra'].'; ">
										(<a style="color: '.$adkportal['Designeds']['link'].'; " href="#" onclick="addAttachment(); return false;">
											'.$txt['adkdown_add_more'].'
										</a>)
									</dd>
								</dl>
								<br />
								',!empty($context['load_attachs']) ? implode('<br />',$context['load_attachs']) : '','
							</td>
						</tr>
						<tr>
							<td align="center" colspan="2">
								<hr />
								<input type="submit" value="'.$txt['save'].'" class="button_submit" />
								<input type="hidden" name="sc" value="'.$context['session_id'].'" />
								<input type="hidden" name="id_cat" value="'.$category.'" />
								<input type="hidden" name="id_file" value="'.$context['important_info']['id_file'].'" />
								<input type="hidden" name="id_member" value="'.$context['important_info']['id_member'].'" />
								<input type="hidden" name="ex_id_cat" value="'.$context['important_info']['id_cat'].'" />
							</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
			<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
		</div>';
}

function template_view_stats()
{
	global $context, $scripturl, $txt, $settings, $boardurl, $adkFolder, $adkportal;
	
	download_bar_buttons('viewstats');

	$last_downloads = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$context['last_downloads']);
	$most_viewed = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$context['most_viewed']);
	$most_downloads = str_replace('<a','<a style="color: '.$adkportal['Designeds']['link'].';"',$context['most_downloads']);

	echo'
		<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
			<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
				<img src="'.$adkFolder['images'].'/stats_dow.png" style="vertical-align: text-bottom;" alt="'.$txt['adkdown_view_stats'].'" />&nbsp;'.$txt['adkdown_view_stats'].'
			</h3>
		</div>
		<div class="eds_downs" style="padding: 0px; border-color: '.$adkportal['Designeds']['borde'].';">
			<table style="width: 100%;">
				<tr>
					<td style="width: 33%;">
						<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
							<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
								<img src="'.$adkFolder['images'].'/stats_s_green.png" style="vertical-align: top;" alt="'.$txt['adkdown_lasted_down'].'" />
								'.$txt['adkdown_lasted_down'].'
							</h3>
						</div>
						<div class="eds_down" style="border-color: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['letra'].'; background: '.$adkportal['Designeds']['fondo'].';">
							<ul class="eds_list">
								',implode("",$last_downloads),'
							</ul>
						</div>
						<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
							<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
						</div>
					</td>
					<td style="width: 34%;">
						<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
							<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
							<img src="'.$adkFolder['images'].'/stats_s_red.png" style="vertical-align: top;" alt="'.$txt['adkdown_topviewed'].'" />
								'.$txt['adkdown_topviewed'].'
							</h3>
						</div>
						<div class="eds_down" style="border-color: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['letra'].'; background: '.$adkportal['Designeds']['fondo'].';">
							<ul class="eds_list">
								',implode("",$most_viewed),'
							</ul>
						</div>
						<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
							<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
						</div>
					</td>
					<td style="width: 33%;">
						<div class="eds_cat_bar" style="background: '.$adkportal['Designeds']['borde'].';">
							<h3 class="eds_catbg" style="padding-top: 3px; background: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['titulo'].';">
							<img src="'.$adkFolder['images'].'/stats_s_blue.png" style="vertical-align: top;" alt="'.$txt['adkdown_topdown'].'" />
								'.$txt['adkdown_topdown'].'
							</h3>
						</div>
						<div class="eds_down" style="border-color: '.$adkportal['Designeds']['borde'].'; color: '.$adkportal['Designeds']['letra'].'; background: '.$adkportal['Designeds']['fondo'].';">
							<ul class="eds_list">
								',implode("",$most_downloads),'
							</ul>
						</div>
						<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
							<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="eds_botbar" style="background: '.$adkportal['Designeds']['borde'].';">
			<span style="background: '.$adkportal['Designeds']['borde'].';">&nbsp;</span>
		</div>';

}	

?>