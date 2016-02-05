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

function template_htaccess()
{
	global $scripturl, $txt, $context, $boardurl, $adkportal, $adkFolder;

	echo'
		<form method="post" action="'. $scripturl .'?action=admin;area=adkseoadmin;sa=savehtaccess">
			<div class="cat_bar"><h3 class="catbg">
				<img alt="" class="adk_vertical_align" src="'.$adkFolder['images'].'/link.png" />&nbsp;'.$txt['adkmod_seo_htaccess'].'
			</h3></div>
			',empty($context['htaccess_content']) ? '
			<div class="approvebg">
				<span class="topslice">
					<span>&nbsp;</span>
				</span>
				<div>
					<table class="size_100">
						<tr>
							<td class="text_align_center">
								<img class="size_40px" alt="" src="'.$adkFolder['images'].'/stop.png" />
							</td>
							<td>
								<strong>'.$txt['adkportal_seo_htaccess_info'].'</strong>
							</td>
						</tr>
					</table>
				</div>
				<span class="botslice">
					<span>&nbsp;</span>
				</span>
			</div>' : '' ,'
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
					<div class="smalltext">
						'.$txt['adkportal_htaccess_info'].':
						<br /><br />
						<span class="clear upperframe">
							<span>&nbsp;</span>	
						</span>
						<div class="roundframe">
							<div>
								<strong>
									RewriteRule ^pages/(.*)\.html index.php?page=$1 [L]<br />
									RewriteRule ^cat/([0-9]*)-(.*).html;(.*)$ index.php?action=downloads;cat=$1;$3 [L]<br />
									RewriteRule ^cat/([0-9]*)-(.*).html$ index.php?action=downloads;cat=$1 [L]<br />
									RewriteRule ^down/([0-9]*)-(.*)\.html$ index.php?action=downloads;sa=view;down=$1 [L]<br />
								</strong>
							</div>
						</div>
						<span class="lowerframe">
							<span>&nbsp;</span>	
						</span>
						<br />
						'.$txt['adkportal_htaccess_moreinfo'].'
					</div>
					<hr />
					<div align="center">
						<textarea cols="80" rows="12" name="htaccess">'.$context['htaccess_content'].'</textarea><br />
						<hr />
						',help_link('adkportal_seo_use_path','adkhelp_seo_use_path'),'<br />
						<input size="60" name="path" value="'.$adkportal['path_seo'].'" />
						<br /><br />
						<input type="submit" class="button_submit" value="'.$txt['save'].'" />
						<input type="hidden" name="sc" value="'.$context['session_id'].'" />
						<a href="',$scripturl,'?action=admin;area=adkseoadmin;sa=deletehtaccess;',$context['session_var'],'=',$context['session_id'],'">
							<input type="button" value="',$txt['adkportal_delete_htaccess'],'" class="button_submit" />
						</a>
					</div>
				</div>
			</div>
			<span class="lowerframe">
				<span>&nbsp;</span>	
			</span>	
		</form>';

}

function template_settings_seo()
{
	global $adkportal, $txt, $context, $scripturl, $boardurl, $adkFolder;
	echo'
		<form method="post" action="'. $scripturl .'?action=admin;area=adkseoadmin;sa=savesettings">
			<div class="cat_bar">
				<h3 class="catbg">
					<img alt="" class="adk_vertical_align" src="'.$adkFolder['images'].'/admin.png" />&nbsp;'.$txt['adkadmin_setting'].'
				</h3>
			</div>
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
					<table class="size_100">
						<tr>
							<td class="size_50">
								',help_link('adkportal_seo_enable_pages','adkhelp_seo_enable_pages'),'
							</td>
							<td class="size_50">
								'.$txt['yes'].'<input type="radio" value="1" name="enable_pages_seo"',$adkportal['enable_pages_seo'] == 1 ? ' checked="checked"' : '' ,' />
								'.$txt['no'].'<input type="radio" value="0" name="enable_pages_seo"',$adkportal['enable_pages_seo'] == 0 ? ' checked="checked"' : '' ,' />
							</td>
						</tr>
						<tr>
							<td class="size_50">
								',help_link('adkportal_seo_enable_downloads','adkhelp_seo_enable_downloads'),'
							</td>
							<td class="size_50">
								'.$txt['yes'].'<input type="radio" value="1" name="enable_download_seo"',$adkportal['enable_download_seo'] == 1 ? ' checked="checked"' : '' ,' />
								'.$txt['no'].'<input type="radio" value="0" name="enable_download_seo"',$adkportal['enable_download_seo'] == 0 ? ' checked="checked"' : '' ,' />
							</td>
						</tr>
					</table>
					<br />
					<hr />
					<div align="center">
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

function template_robots_seo()
{
	global $adkportal, $txt, $context, $scripturl, $boardurl, $adkFolder;

	echo'
		<form method="post" action="'. $scripturl .'?action=admin;area=adkseoadmin;sa=saverobots">
			<div class="cat_bar"><h3 class="catbg">
				<img alt="" class="adk_vertical_align" src="'.$adkFolder['images'].'/login.png" />&nbsp;',$txt['adkportal_create_robots'],'
			</h3></div>
			<span class="clear upperframe">
				<span>&nbsp;</span>	
			</span>
			<div class="roundframe">
				<div>
					',help_link('adkportal_robots','adkhelp_robots'),'
					<hr />
					<table class="size_100">
						<tr>
							<td class="size_40">
								&nbsp;
							</td>
							<td class="size_60">
								<fieldset>
									<legend style="color: teal">robots.txt</legend>
									<div class="adk_padding_8 text_align_center">
										<textarea cols="70" rows="12" name="robots">'.$context['robots_dir'].'</textarea><br />
									</div>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<span class="clear upperframe">
									<span>&nbsp;</span>	
								</span>
								<div class="roundframe">
									<div>
										<div class="smalltext">'.$txt['adkportal_robots_info'].'</div>
									</div>
								</div>
								<span class="lowerframe">
									<span>&nbsp;</span>	
								</span>	
							</td>
						</tr>
					</table>
					<div align="center">
						<hr />
						<input type="submit" value="'.$txt['save'].'" class="button_submit" />
						<input type="hidden" name="sc" value="'.$context['session_id'].'" />
					</div>
				</div>
			</div>
			<span class="lowerframe">
				<span>&nbsp;</span>	
			</span>	
		</form>';

}

?>