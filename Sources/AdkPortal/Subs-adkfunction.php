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

//Ultimas actualizaciones
function getAdkNews()
{
	echo getFile("http://www.smfpersonal.net/news.txt");
}

function getCurrentversion()
{
	return getFile("http://smfpersonal.net/news/adk-portal-version.txt");
}

function getYourversion()
{
	//Hey baby... What's our version?
	$version = '3.1';
	
	return $version;
}

function getCheckbox($variable)
{
	global $adkportal;
	
	if (!empty($adkportal[$variable]))
		echo ' checked="checked"';
}


function analizar_selected_adk($variable, $value)
{
	global $adkportal;
	
	if($adkportal[$variable] == $value)
		echo' selected="selected"';
}

function adkportalSettings($stand_alone_mode = false)
{
	global $context;

	$context['stand_alone_mode'] = $stand_alone_mode;

	//Set the functions to use in all adkportal
	$functions = array(

		//This function create our custom copyright for the forum
		'createCopyright' => 'bufferAdkCopyright',

		//Make a simples $adkportal variables to use in our system
		'other_adkportal_variables' => 'getCustomAdkportalVariables',

		//Load Blocks information if i'll need it
		'blocks' => 'createBlocksInformation',
	);

	//Use all functions please
	foreach($functions AS $current_function)
		$current_function();
}

function getAdkportalSettings()
{

	global $adkportal, $smcFunc;

	//Load Table Settings... smf_adk_settings ^^
	$query = $smcFunc['db_query']('','
		SELECT variable, value
		FROM {db_prefix}adk_settings'
	);
	
	while($row = $smcFunc['db_fetch_assoc']($query))
		$adkportal[$row['variable']] = $row['value'];
	
	$smcFunc['db_free_result']($query);

	//Include SSI File
	if(!empty($adkportal['adk_include_ssi'])){

		global $boarddir;
		require_once($boarddir.'/SSI.php');
	}
}

function bufferAdkCopyright()
{

	global $adkportal, $scripturl;

	$to_replace_in_version = getYourversion();
	
	$version = empty($adkportal['adk_hide_version']) ? $to_replace_in_version : '';
	
	//The copyright
	$adkportal['copy'] = '<br /><a href="http://www.smfpersonal.net" target="_blank">Adk Portal'.$version.' &copy; 2009-2013</a>';
	
	//Creating a simple variable
	$adkportal['variables_important'] = ' | <a href="{$scripturl}?action=adk_credits">Adk Portal {$version}</a> &copy; <a href="http://www.smfpersonal.net" target="_blank">SMF personal</a>';
	
	//Reeplace version?
	if(empty($adkportal['adk_hide_version']))
		$to_replace = $to_replace_in_version;
	else
		$to_replace = '';

	//Replace
	$replace = array(
		'{$scripturl}' => $scripturl,
		'{$version}' => $to_replace,
	);
	
	//Reeplace version?
	if(empty($adkportal['adk_hide_version']))
		$to_replace = $to_replace_in_version;
	else
		$to_replace = '';

	foreach($replace AS $i => $v)
		$adkportal['variables_important'] = str_replace($i,$v,$adkportal['variables_important']);
	
}

function getCustomAdkportalVariables()
{

	global $adkportal, $boarddir, $modSettings, $adkFolder, $boardurl;

	//Set use adkportal
	$adkportal['use_adkportal_now'] = false;

	//Some modSettings
	$modSettings['modules_menu'] = '';
	$modSettings['modules_subactions'] = '';

	//Create our custom variables
	$adkFolder = array(
		'main' =>  $boarddir.'/adkportal',
		'eds' =>  $boarddir.'/Adk-downloads',
		'blocks' => $boarddir.'/adkportal/blocks',
		'mainurl' =>  $boardurl.'/adkportal',
		'edsurl' =>  $boardurl.'/Adk-downloads',
		'tmp' => $boardurl.'/adkportal/tmp',
		'bbcodes' => $boardurl.'/adkportal/bbcodes',
		'css' => $boardurl.'/adkportal/css',
		'images' => $boardurl.'/adkportal/images',
		'js' => $boardurl.'/adkportal/js',
		'shoutbox' => $boardurl.'/adkportal/shoutbox',
		'smileys' => $boardurl.'/adkportal/smileys',
	);
}

function createBlocksInformation()
{

	global $adkportal, $sourcedir, $context, $smcFunc, $current_load, $board, $topic, $modSettings, $maintenance, $user_info;

	if (!empty($maintenance) && $user_info['is_guest'])
		return;

	//Set the actions and load blocks
	if($adkportal['adk_enable'] == 1)
		$current_load = array('default' ,'');
	elseif(($adkportal['adk_enable'] == 2) && ($context['adk_stand_alone']))
		$current_load = array('default','');
	else
		$current_load = array('action', 'forum');

	//Set the acttions
	if(!empty($_REQUEST['action'])){

		$current_load = array('action', CleanAdkStrings($_REQUEST['action']));

		if($_REQUEST['action'] == 'collapse')
			$current_load[1] = 'forum';

		//For profiles users
		if($_REQUEST['action'] == 'profile' && !empty($_REQUEST['u']))
			$current_load = array('action', CleanAdkStrings($_REQUEST['action']).';u='.(int)$_REQUEST['u']);
	}

	//If its a page
	elseif(!empty($_REQUEST['page']))
		$current_load = array('page', CleanAdkStrings($_REQUEST['page']));

	//If it's a board
	elseif(!empty($board) && empty($topic))
		$current_load = array('board', $board);

	//If it's a topic
	elseif(!empty($topic))
		$current_load = array('topic', $topic);

	//If it's a blog
	elseif(!empty($_REQUEST['blog']) && !empty($modSettings['blog_enable']))
		$current_load = array('blog', (int)$_REQUEST['blog']);

	//Get blocks template
	$sql = $smcFunc['db_query']('','
		SELECT b.id_block, b.columna, b.orden, t.place, t.id_template
		FROM {db_prefix}adk_blocks_template AS b
		LEFT JOIN {db_prefix}adk_blocks_template_admin AS t ON (t.id_template = b.id_template)
		WHERE t.type = {string:type} AND (t.place = {string:place} OR t.place = {string:place_2}) AND t.enabled = {int:enabled}',
		array(
			'type' => $current_load[0],
			'place' => $current_load[1],
			'place_2' => '#all#',
			'enabled' => 1,
		)
	);

	$blocks_id = array();
	$blocks_id_all = array();

	$template_id = 0;
	$template_id_all = 0;

	$settings = array();
	$settings_all = array();

	while($row = $smcFunc['db_fetch_assoc']($sql)){

		//Set the blocks id
		if($row['place'] == '#all#'){

			$template_id_all = $row['id_template'];
			$blocks_id_all[] = $row['id_block'];

			$settings_all[$row['id_block']] = array(
				'columna' => $row['columna'],
				'orden' => $row['orden']
			);

		}
		else{
			
			$template_id = $row['id_template'];
			$blocks_id[] = $row['id_block'];

			$settings[$row['id_block']] = array(
				'columna' => $row['columna'],
				'orden' => $row['orden']
			);
		}
	}

	//If you are using a default tempalte... use it
	if(empty($blocks_id) && !empty($blocks_id_all)){
		$blocks_id = $blocks_id_all;
		$settings = $settings_all;
		$template_id = $template_id_all;
	}

	//die($template_id);

	//I don't wanna show any block in admin section
	if($current_load[0] == 'action' && $current_load[1] == 'admin'){
		unset($blocks_id);
		unset($settings);
	}

	$smcFunc['db_free_result']($sql);

	//Load blocks
	if(!empty($blocks_id))
	{
		require_once($sourcedir.'/AdkPortal/Subs-adkblocks.php');
		
		//Check if your use adk portal in this moment ;) NOW!
		$adkportal['use_adkportal_now'] = true;
		
		//Get blocks
		$adkportal += loadBlocks($blocks_id, $settings, '', array('id_template' => $template_id), 't.orden ASC', false);
	}
}

function loadBlocks($block_ids = '', $settings = array(), $where = '', $parameters = array(), $orderby = '', $return_unique = false)
{
	global $smcFunc;

	if(!empty($block_ids))
		$block_ids = !is_array($block_ids) ? array($block_ids) : $block_ids;

	//$where validate and order
	if(!empty($where))
		$where = 'AND '.$where;
	
	validateOrder($orderby);

	$parameters += array(
		'uno' => 1,
		'cero' => 0,
		'block_ids' => $block_ids,
	);

	//Set the left join
	$left_join = '';

	if(!empty($parameters['id_template']))
		$left_join = '
		INNER JOIN {db_prefix}adk_blocks_template AS t ON (t.id_block = b.id AND t.id_template = {int:id_template})';
	
	//Let's load all blocks....
	$sql = $smcFunc['db_query']('','
		SELECT b.id, b.echo, b.name AS title, b.img, b.type, b.empty_body, b.empty_title, 
		b.empty_collapse, b.permissions
		FROM {db_prefix}adk_blocks AS b
		'.$left_join.'
		WHERE 1=1 '.(!empty($where) ? $where : '') . (!empty($block_ids) ? ' AND id IN ({array_int:block_ids})' : '').'
		'.$orderby,
		$parameters
	);
	
	//The Misterious array
	$columns = array(
		'left' => '',
		'center' => '',
		'right' => '',
		'bottom' => '',
		'top' => '',
	);
	
	//Set the num_rows
	$num_rows = $smcFunc['db_num_rows']($sql);
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
	{
		$row['title'] = parse_if_utf8($row['title']);

		$columna = !empty($settings[$row['id']]['columna']) ? $settings[$row['id']]['columna'] : 0;
		$orden = !empty($settings[$row['id']]['orden']) ? $settings[$row['id']]['orden'] : 0;
		
		//Set the position
		$position = getPosition($columna);
		
		$columns[$position][] = array(
			'id' => $row['id'],
			'echo' => un_htmlspecialchars($row['echo']),
			'title' => $row['title'],
			'name' => $row['title'], //For Adk-admin compatibility
			'orden' => $orden,
			'columna' => $columna,
			'img' => $row['img'],
			'type' => $row['type'],
			'b' => $row['empty_body'],
			't' => $row['empty_title'],
			'c' => $row['empty_collapse'],
			'p' => $row['permissions'],
		);
	}
	
	if($num_rows == 1 && $return_unique){
		//Create $one_block variable
		foreach($columns[$position][0] AS $unique_column => $value){
			$one_block[$unique_column] = $value;
		}
		
		$columns = $one_block;
	}

	$smcFunc['db_free_result']($sql);
	
	//Add some information
	$columns += array(
		'num_rows' => $num_rows,
	);
	
	//Returns as array the columns...
	return $columns;
}

function loadCTop()
{	
	global $user_info, $options, $adkportal, $txt, $settings, $context, $current_load, $boardurl, $adkFolder;
	
	//Declare static...
	static $level = 0;
	
	if($level >= 1)
		return false;

	//the_change
	$left_image = (($user_info['is_guest'] ? !empty($_COOKIE['adk_left_'.$current_load[0].'_'.$current_load[1]]) : !empty($options['adk_left_'.$current_load[0].'_'.$current_load[1]])) ? 'expand_left.png' : 'collapse_left.png'); 
	$right_image = (($user_info['is_guest'] ? !empty($_COOKIE['adk_right_'.$current_load[0].'_'.$current_load[1]]) : !empty($options['adk_right_'.$current_load[0].'_'.$current_load[1]])) ? 'expand_right.png' : 'collapse_right.png');
	
	$left_text = ($left_image == 'expand_left.png') ? $txt['adkmod_expand'] : $txt['adkmod_collapse'];
	$right_text = ($right_image == 'expand_right.png') ? $txt['adkmod_expand'] : $txt['adkmod_collapse'];
	
	$colapse_left = '
		<span onclick="adkcollapse(1,\'adklcolapse\')" class="adk_pointer adk_float_l colexp_l">
			<span id="adklcolapse">
				<img alt="'.$left_text.'" title="'.$left_text.'" src="'.$adkFolder['images'].'/'.$left_image.'" />
			</span>
		</span>';
			
			
	$colapse_right = '
		<span onclick="adkcollapse(2,\'adkrcolapse\')" class="adk_pointer adk_float_r colexp_r">
			<span id="adkrcolapse">	
				<img alt="'.$right_text.'" title="'.$right_text.'" src="'.$adkFolder['images'].'/'.$right_image.'" />
			</span>		
		</span>	';

	if(!empty($adkportal['top']))
		createColumn('top');

	if ($adkportal['title_in_blocks'] == 6)
		echo '<br />';

	if(!empty($adkportal['left']) || !empty($adkportal['right']))
	echo'
		<div class="smalltext adk_pointer adk_colexp" ',(!empty($adkportal['adk_disable_colexpand']) || (WIRELESS)) ? 'style="display:none;"' : '','>';


	//Print the colapses
	if(!empty($adkportal['left'])){

		//print colapse left
		echo $colapse_left;
	}
	
	if(!empty($adkportal['right'])){
		
		//print colapse right
		echo $colapse_right;
	}
	
	if(!empty($adkportal['left']) || !empty($adkportal['right']))	
	echo'	
		</div>
		<div class="adk_height_1"></div>';

	//Load if multiples if are true
	if((!empty($adkportal['right']) || !empty($adkportal['left'])))
		printOnIndexTop();
	
	$level++;
	
}

function loadCBottom()
{
	global $adkportal, $smcFunc, $user_info, $options, $current_load;	
	
	
	//Load if it's possible
	if((!empty($adkportal['right']) || !empty($adkportal['left'])))
		printOnIndexBottom();
	
	//If you wanna show the bottom column.. show it please
	if(!empty($adkportal['bottom'])){
		echo'<br />';
		createColumn('bottom');
	}
	
}

function printOnIndexTop()
{
	global $adkportal, $user_info, $options, $current_load;
	
	echo'
	<table class="adk_100">
		<tr>';
		
		if(!empty($adkportal['left']))
		echo'
			<td id="adk_left_'.$current_load[0].'_'.$current_load[1].'" valign="top" style="width:',$adkportal['wleft'],';'. (($user_info['is_guest'] ? !empty($_COOKIE['adk_left_'.$current_load[0].'_'.$current_load[1]]) : !empty($options['adk_left_'.$current_load[0].'_'.$current_load[1]])) ? ' display: none;' : '') .'" class="adk_padding_5_r">
				',createColumn('left'),'
			</td>';
		
	echo'
			<td valign="top">';
	
}
	
function printOnIndexBottom()
{
	global $adkportal, $user_info, $options, $current_load;
	
	//Static level
	static $level = 0;
	
	//If level > 0.... return false;
	if($level >= 1)
		return false;
	
	echo'
			</td>';
   
	if(!empty($adkportal['right']))
		echo'
			<td id="adk_right_'.$current_load[0].'_'.$current_load[1].'" valign="top" style="width:',$adkportal['wright'],';'. (($user_info['is_guest'] ? !empty($_COOKIE['adk_right_'.$current_load[0].'_'.$current_load[1]]) : !empty($options['adk_right_'.$current_load[0].'_'.$current_load[1]])) ? ' display: none;' : '') .'" class="adk_padding_5_l">
				',createColumn('right'),'
			</td>';
   
   
		echo'
		</tr>
	</table>';
	
	$level++;
}

//I dont need duplicate content.... just i need this once
function createColumn($position = 'center')
{
	global $adkportal;
	
	//Wrong position?
	if(!in_array($position,array('left','center','right','top','bottom')))
		return false;
	
	//If empty columns... it's not necessary to load anything else
	if(empty($adkportal[$position]))
		return false;
	
	echo
	'<div id="adkportal_',$position,'">';
	
	if($adkportal['title_in_blocks'] == 6 && !empty($adkportal[$position]))
		echo'
	<span class="clear upperframe"><span>&nbsp;</span></span>
		<div class="roundframe">';
	
	foreach ($adkportal[$position] AS $poster)
		adk_create_block($poster);
	
	if($adkportal['title_in_blocks'] == 6 && !empty($adkportal[$position]))
	echo'
		</div>
	<span class="lowerframe"><span>&nbsp;</span></span>';
	
	echo'
	</div>';
}

function adk_standAloneMode($standalone = false)
{
	global $context, $user_info, $adkportal;
	
	$context['adk_stand_alone'] = $standalone;

	//Adk Download System
	$allowed_to_manage = allowedTo('adk_downloads_manage') ? 1 : 0;

	if($user_info['is_admin'])
		$adkportal['query_downloads'] = '1=1';
	else
		$adkportal['query_downloads'] =
			'
			CASE 
				WHEN '.$allowed_to_manage.' = 1
				THEN 1=1
				ELSE d.approved = 1
			END
			AND CASE
				WHEN d.id_member = '.$user_info['id'].'
				THEN 1=1
				ELSE d.approved = 1
			END
			AND (FIND_IN_SET(' . (implode(', c.groups_can_view) != 0 OR FIND_IN_SET(', $user_info['groups'])) . ', c.groups_can_view) != 0)';

}

function updateSettingsAdkPortal($arraySettings)
{
	global $smcFunc, $adkportal;

	$replaceArray = array();
	foreach ($arraySettings as $variable => $value)
	{
		// Don't bother if it's already like that ;).
		if (isset($adkportal[$variable]) && $adkportal[$variable] == $value)
			continue;
		// If the variable isn't set, but would only be set to nothing'ness, then don't bother setting it.
		elseif (!isset($adkportal[$variable]) && empty($value))
			continue;

		$replaceArray[] = array($variable, $value);

		$adkportal[$variable] = $value;
	}

	if (empty($replaceArray))
		return;

	$smcFunc['db_insert']('replace',
		'{db_prefix}adk_settings',
		array('variable' => 'string-255', 'value' => 'string-65534'),
		$replaceArray,
		array('variable')
	);
}

//This function is to load one unique block
function loadBlock($id)
{
	//If empty id return false
	if(empty($id))
		return false;
	
	//Use loadBlocks function
	return loadBlocks(array($id), array(), '', array(), '', true);
}

function getPosition($column)
{
	//If?
	if($column == 1)
		return 'left';
	elseif($column == 2)
		return 'center';
	elseif($column == 3)
		return 'right';
	elseif($column == 4)
		return 'top';
	elseif($column == 5)
		return 'bottom';
	else
		return 'admin';
}

/*******************************************
* Replace file_put_contents()
 Aidan Lister <aidan@php.net>
********************************************/
if(!function_exists('php_compat_file_put_contents')){
	function php_compat_file_put_contents($filename, $content, $flags = null, $resource_context = null)
	{
		$oldlevel = error_reporting(0);
		// If $content is an array, convert it to a string
		if (is_array($content))
		{
			$content = implode('', $content);
		}
		 
		// If we don't have a string, throw an error
		if (!is_scalar($content))
		{
			user_error('file_put_contents() The 2nd parameter should be either a string or an array',
				E_USER_WARNING);
			return false;
		}
		 
		// Get the length of data to write
		$length = strlen($content);
		 
		// Check what mode we are using
		$mode = ($flags & FILE_APPEND) ? 'a' :
		'wb';
		 
		// Check if we're using the include path
		$use_inc_path = ($flags & FILE_USE_INCLUDE_PATH) ? true :
		false;
		 
		// Open the file for writing
		if (($fh = @fopen($filename, $mode, $use_inc_path)) === false)
		{
			user_error('file_put_contents() failed to open stream: Permission denied',
				E_USER_WARNING);
			return false;
		}
		 
		// Attempt to get an exclusive lock
		$use_lock = ($flags & LOCK_EX) ? true :
		false ;
		if ($use_lock === true)
		{
			if (!flock($fh, LOCK_EX))
			{
				return false;
			}
		}
		 
		// Write to the file
		$bytes = 0;
		if (($bytes = @fwrite($fh, $content)) === false)
		{
			$errormsg = sprintf('file_put_contents() Failed to write %d bytes to %s',
				$length,
				$filename);
			user_error($errormsg, E_USER_WARNING);
			return false;
		}
			 
		// Close the handle
		@fclose($fh);
			 
		// Check all the data was written
		if ($bytes != $length)
		{
			$errormsg = sprintf('file_put_contents() Only %d of %d bytes written, possibly out of free disk space.',
				$bytes,
				$length);
			user_error($errormsg, E_USER_WARNING);
			return false;
		}
		 
			return $bytes;
	}
}

// Define - Adk Seo uses this function
if(!function_exists('file_put_contents'))
{
    function file_put_contents($filename, $content, $flags = null, $resource_context = null)
    {
        return php_compat_file_put_contents($filename, $content, $flags, $resource_context);
	}
}

function changeurlpagesAdkportal($page)
{
	global $boardurl;
	
	//Rewrite System
	$path = 'pages/';
	
	//The perfect url
	$totalurl = $path.$page.'.html';
	
	//Return me
	return $totalurl;
}

function changeCatUrl($id)
{
	global $smcFunc, $context;
	
	if(isset($context['rewrite_adk']['cat'][$id]))
		$title = $context['rewrite_adk']['cat'][$id];
	else
	{
		$sql = $smcFunc['db_query']('','
			SELECT title
			FROM {db_prefix}adk_down_cat
			WHERE id_cat = {int:cat}',
			array(
				'cat' => $id,
			)
		);
		
		$row = $smcFunc['db_fetch_assoc']($sql);
		$smcFunc['db_free_result']($sql);
		
		$title = $row['title'];
	}
	
	if(empty($title))
		$title = 'empty';
	
	$title = strtolower(SimpleReplace($title));
	
	return 'cat/'.$id.'-'.$title.'.html';

}
		
function changeDownloadUrl($id)
{
	global $smcFunc, $smcFunc;
	
	if(isset($context['rewrite_adk']['download'][$id]))
		$title = $context['rewrite_adk']['download'][$id];
	else
	{
		$sql = $smcFunc['db_query']('','
			SELECT title
			FROM {db_prefix}adk_down_file
			WHERE id_file = {int:file}',
			array(
				'file' => $id,
			)
		);
		
		$row = $smcFunc['db_fetch_assoc']($sql);
		$smcFunc['db_free_result']($sql);
		
		$title = $row['title'];
	}
	
	if(empty($title))
		$title = 'empty';
	
	
	$title = strtolower(SimpleReplace($title));
	
	return 'down/'.$id.'-'.$title.'.html';

}

//Remove some signs....
function SimpleReplace($variable)
{
	$split = '-';

	$other = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','Ñ','ñ',);
	$other2 = array('a','e','i','o','u','A','E','I','O','U','N','n',);
	
	$var = str_replace($other,$other2,$variable);
	
	$again = array('"','!','ª','$','%','&','/','(',')','?','¿','Ç','-','.',';',',','´','+','`',"\\",'#',);
	$var = str_replace($again,"",$var);
	
	$o = array('   ','  ',);
	$var = str_replace($o,' ',$var);
	
	$var = str_replace(' ',$split,$var);
	
	return $var;
	
}
	
//This jump to is from SMF 1
function loadJumpTosmf1ByAlper()
{
	global $context, $user_info, $smcFunc;

	if (isset($context['jump_to']))
		return;

	// Find the boards/cateogories they can see.
	$request = $smcFunc['db_query']('','
		SELECT c.name AS catName, c.id_cat, b.id_board, b.name AS boardName, b.child_level
		FROM {db_prefix}boards AS b
		LEFT JOIN {db_prefix}categories AS c ON (c.id_cat = b.id_cat)
		WHERE '.$user_info['query_see_board']
	);
	
	$context['jump_to'] = array();
	$this_cat = array('id' => -1);
	
	//While....
	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		if ($this_cat['id'] != $row['id_cat'])
		{
			$this_cat = &$context['jump_to'][];
			$this_cat['id'] = $row['id_cat'];
			$this_cat['name'] = $row['catName'];
			$this_cat['boards'] = array();
		}

		$this_cat['boards'][] = array(
			'id' => $row['id_board'],
			'name' => $row['boardName'],
			'child_level' => $row['child_level'],
			'is_current' => isset($context['current_board']) && $row['id_board'] == $context['current_board']
		);
	}
	
	$smcFunc['db_free_result']($request);
}	
	
function load_AvdImage($watermark = '', $image, $extension, $style, $imagen_name)
{
	global $boarddir, $adkFolder;
	
	$font = $boarddir.'/Themes/default/fonts/Forgottb.ttf';
	
	if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG')
		$extension = 'jpg';
	
	
	$padding_left = 0;
	$padding_top = 0;
	
	//Makeme a new extension
	$size = getimagesize($image);

	switch ($size['mime']) {
		case "image/gif":
			$extension = 'gif';
			break;
		case "image/jpeg":
			$extension = 'jpg';
			break;
		case "image/png":
			$extension = 'png';
			break;
	}
	
	if($extension == 'gif')
		$create = imagecreatefromgif($image);
	if($extension == 'jpg')
		$create = imagecreatefromjpeg($image);
	if($extension == 'png')
		$create = imagecreatefrompng($image);
	
	if($style == 6)
	{
		$background = '';
		$width_image = imagesx($create);
		$height_image = imagesy($create);
		$width = 180;
		$height = 180 * $height_image / $width_image;
		$letter = 13;
		$position_watermark = 0;
		$vertical = 200;
		$horizontal = 10;
	}
	elseif($style == 7)
	{
		$background = '';
		$width_image = imagesx($create);
		$height_image = imagesy($create);
		$width = 50;
		$height = 50 * $height_image / $width_image;
		$letter = 13;
		$position_watermark = 0;
		$vertical = 200;
		$horizontal = 10;
	}
	elseif($style == 2)
	{
		$background = $adkFolder['main'].'/images/portfolioBox.png';
		$width = 177;
		$height = 188;
		$width_image = imagesx($create);
		$height_image = imagesy($create);
		$padding_left = 17;
		$padding_top = 11;
		$letter = 13;
		$position_watermark = 0;
		$vertical = 200;
		$horizontal = 10;
	}
	elseif($style == 3)
	{
		$background = $adkFolder['main'].'/images/caja.png';
		$width= 303; 
		$height = 426; 
		$width_image = imagesx($create);
		$height_image = imagesy($create);
		$padding_left = 60;
		$padding_top = 15;
		$letter = 16;
		$position_watermark = 90;
		$vertical = 150;
		$horizontal = 54;
	}
	elseif($style == 4)
	{
		$background = $adkFolder['main'].'/images/caratulaps3.png';
		$width= 370; 
		$height = 461; 
		$width_image = imagesx($create);
		$height_image = imagesy($create);
		$padding_left = 30;
		$padding_top = 0;
		$letter = 16;
		$position_watermark = 0;
		$vertical = 20;
		$horizontal = 30;
	}
	elseif($style == 5)
	{
		$background = $adkFolder['main'].'/images/caratulaxbox.png';
		$width= 400; 
		$height = 500; 
		$width_image = imagesx($create);
		$height_image = imagesy($create);
		$padding_left = 0;
		$padding_top = 64;
		$letter = 28;
		$position_watermark = 45;
		$vertical = 480;
		$horizontal = 240;
	}
	elseif($style == 8)
	{
		$background = $adkFolder['main'].'/images/dvd8.png';
		$width = 335;
		$height = 496;
		$width_image = imagesx($create);
		$height_image = imagesy($create);
		$padding_left = 50;
		$padding_top = 13;
		$letter = 28;
		$position_watermark = 90;
		$vertical = 508;
		$horizontal = 45;
	}
	
	
	$final = imagecreatetruecolor($width, $height);
	imagecopyresampled($final, $create, 0, 0, 0, 0, $width, $height, $width_image, $height_image);
	
	if($style == 2 || $style == 3 || $style == 4 || $style == 5 || $style == 8)
	{
		$new = imagecreatefrompng($background);
		imagecopymerge($new, $final, $padding_left, $padding_top, 0, 0, $width, $height, 100);		
		
		if(!empty($watermark))
		{
			$return = imagecolorallocate($new, 0, 0, 0);
			imagettftext($new, $letter, $position_watermark, $horizontal , $vertical , $return, $font, $watermark);
		}
		imagealphablending($new, true);
		imagesavealpha($new, true);				
		imagepng($new,$imagen_name);
		//imagejpeg($new,$imagen_name);
		imagedestroy($create);
		imagedestroy($new);
	}
	elseif($style == 1 || $style == 6 || $style == 7)
	{	
		imagealphablending($final, true);
		imagesavealpha($final, true);			
		//imagepng($final);
		imagejpeg($final,$imagen_name);
		imagedestroy($create);
	}
}

function find_modSettings_style_top($title, $img = false, $id_block = false, $b = 0, $t = 0, $c = 0)
{
	global $adkportal, $boardurl, $user_info, $options, $settings, $adkFolder;
	
	$load = !empty($img) && !empty($adkportal['enable_img_blocks']) ? '<img class="adk_vertical" src="'.$adkFolder['images'].'/blocks/'.$img.'" alt="" />&nbsp;' : '';
	
	if($adkportal['title_in_blocks'] == 6)
	{
		if($t == 0)
		{
			echo'
			<div class="cat_bar"><h3 class="catbg">
				<span class="adk_font">
					'.$load.$title.'
				</span>';
		}
		
		//Get Colapse
		getCollapse($id_block, $c);
		
		if($t == 0)
		{
			echo'
			</h3></div>';
		}
		
		echo'
			<div class="adk_7"></div>
			<div class="my_blocks" id="adk_block_'. $id_block .'" '. (($user_info['is_guest'] ? !empty($_COOKIE['adk_block_'.$id_block]) : !empty($options['adk_block_'.$id_block])) ? ' style="display: none;"' : '') .'>';
	}
	
	if($adkportal['title_in_blocks'] == 1 || $adkportal['title_in_blocks'] == 7)
	{
		$define = $adkportal['title_in_blocks'] == 1 ? 'cat' : 'title';
		
		if($t == 0)
		{
			echo'
			<div class="'.$define.'_bar"><h3 class="'.$define.'bg">
				<span class="adk_font">
					'.$load.$title.'
				</span>';
		}
		
		//Get Colapse
		getCollapse($id_block, $c);
		
		if($t == 0)
		{
			echo'
			</h3></div>';
		}

		getBlockFirst($id_block);

		echo
			$b == 0 ? '<span class="clear upperframe"><span>&nbsp;</span></span>
					<div class="roundframe">
						<div class="adk_min_height">' : '';
	}
	
	elseif($adkportal['title_in_blocks'] == 2 || $adkportal['title_in_blocks'] == 8)
	{
		$define = $adkportal['title_in_blocks'] == 2 ? 'cat' : 'title';
		
		echo'
		
				',$b == 0 ? '<span class="clear upperframe"><span>&nbsp;</span></span>
					<div class="roundframe">
						<div class="adk_min_height">' : '';
		
		if($t == 0)
			echo'
			<div class="'.$define.'_bar"><h3 class="'.$define.'bg">
				<span class="adk_font">
					'.$load.$title.'
				</span>';
		
		
		//Get Colapse
		getCollapse($id_block, $c);
		
		if($t == 0)
			echo'
			</h3></div>
			<div class="adk_7"></div>';
		
		
		getBlockFirst($id_block);
	}
		
	elseif($adkportal['title_in_blocks'] == 3)
	{
		if($t == 0)
		{
			echo'
			<div class="cat_bar"><h3 class="catbg">
				<span class="adk_font">
					'.$load.$title.'
				</span>';
		}
		
		//Get Colapse
		getCollapse($id_block, $c);
		
		if($t == 0)
		{
			echo'
			</h3></div>';
		}
		
		getBlockFirst($id_block);
	
		echo
			$b == 0 ? '<div class="windowbg adk_min_height"><span class="topslice"><span>&nbsp;</span></span>' : '' ,'
					<div class="allpadding_simple">';
	}
	elseif($adkportal['title_in_blocks'] == 4)
	{
		echo'
		
				',$b == 0 ? '<div class="windowbg"><span class="topslice"><span>&nbsp;</span></span>' : '' ,'
					<div class="allpadding adk_min_height">';
		
		if($t == 0)
		{
			echo'
			<div class="cat_bar"><h3 class="catbg">
				<span class="adk_font">
					'.$load.$title.'
				</span>';
		}
		
		//Get Colapse
		getCollapse($id_block, $c);
		
		if($t == 0)
		{
			echo'
			</h3></div>';
		}
		
		getBlockFirst($id_block);
	}	
	elseif($adkportal['title_in_blocks'] == 5)
	{
		
		
		if($t == 0)
		{
			echo'
			<div class="cat_bar adk_little_round">
				<h3 class="catbg">
				<span class="adk_font">
						'.$load.$title.'
				</span>';
		}
		
		//Get Colapse
		getCollapse($id_block, $c);
		
		if($t == 0)
		{
			echo'
				</h3>
			</div>';
		}
		
		echo'
		',$b == 0 ? '
					<div class="roundframe adk_padding_7">
					<div class="adk_7"></div>
						' : '';
		
		getBlockFirst($id_block);
	}

}

function find_modSettings_style_bot($b = 0)
{
	global $adkportal;
	
	if($adkportal['title_in_blocks'] == 6)
		echo'
	</div>
	<div class="adk_7"></div>';
	if($adkportal['title_in_blocks'] == 1 || $adkportal['title_in_blocks'] == 7)
		echo'
		
				',$b == 0	? '	
						</div>
					</div>
				<span class="lowerframe"><span>&nbsp;</span></span>' : '','
				</div>';
		
	elseif($adkportal['title_in_blocks'] == 2 || $adkportal['title_in_blocks'] == 8)
		echo'
				
							</div>
				',$b == 0	? '			</div>
					</div>
				<span class="lowerframe"><span>&nbsp;</span></span>' : '' ,'';
		
	elseif($adkportal['title_in_blocks'] == 3)
		echo'	
		
					</div>
				',$b == 0	? '	<span class="botslice"><span>&nbsp;</span></span></div>' : '' ,'
				</div>';
		
	elseif($adkportal['title_in_blocks'] == 4)
		echo'	
					</div>
					</div>
				',$b == 0	? '	
				<span class="botslice"><span>&nbsp;</span></span></div>' : '' ,'';	
	elseif($adkportal['title_in_blocks'] == 5)
		echo'
					</div>
					',$b == 0	? '	
					</div>
					<span class="lowerframe"><span>&nbsp;</span></span>
					' : '' ,'';
			
	echo '<br />';

}

function ILoveAdkPortal(){
	echo '<br /><div align="center" class="smalltext"><a href="http://www.smfpersonal.net" target="_blank">Adk Portal by SMF Personal</a></div>';
}

function rewrite_context_html_headers()
{
	global $adkportal;
	
	if($adkportal['use_adkportal_now'])
		return javaScript_blocks();
	else
		return '';
}

function loadRandom5News()
{
	global $smcFunc, $context;
	
	//Load Random News
	$sql = $smcFunc['db_query']('','
		SELECT titlepage, new, autor, time, id
		FROM {db_prefix}adk_news
		ORDER BY RAND()
	');
	
	$context['adk_new']['random'] = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql)){
		$context['adk_new']['random'][] = array(
			'id' => $row['id'],
			'title' => $row['titlepage'],
			'body' => un_htmlspecialchars($row['new']),
			'time' => timeformat($row['time']),
			'autor' => $row['autor'],
		);
	}
	
	$smcFunc['db_free_result']($sql);
}
function limpiarurl($coincidencias)
{
  return "";
}
function adk_getContentShout($limit = 25)
{
	global $smcFunc, $context, $txt;

	$shouts = getShouts(0, $limit);
	
	if (!empty($shouts)) {
		foreach($shouts AS $shout) {

			$message = preg_replace_callback('/\[url=(.*?)(?::\w+)?\]/', 'limpiarurl', $shout['message']);
			$message = preg_replace_callback('/\[\/url(?::\w+)?\]/', 'limpiarurl', $message);
			$message = preg_replace_callback('/\[url(?::\w+)?\]/', 'limpiarurl', $message);

			echo  '
				<div>
					<div class="smalltext" style="font-weight: bold;">
						'.$txt['date'].': <span class="date">'.$shout['date'].'</span><br /> 
						'.$txt['author'].': '.$shout['user'].'
						<hr />
					'.$txt['adkmod_block_posts'].':
					</div>

					',$message,'
					<br /><hr />
				</div>';
		}
	}
	else {
	 	echo $txt['adkmod_block_notext'];
	}
}

function adk_insertMessageShout($user, $message, $id_member = 0)
{
	global $smcFunc;
	
	$time = time();
	
	//I need this one
	$array_info = array(
		'date' => 'int',
		'user' => 'text',
		'message' => 'text',
		'id_member' => 'int',
	);
	
	
	//Make me happy :D
	$array_insert = array(
		$time,
		$user,
		$message,
		$id_member,
	);
	
	$smcFunc['db_insert']('insert',
		'{db_prefix}adk_shoutbox',
		//Load The Array Info
		$array_info,
		//Insert Now;)
		$array_insert,
		array('id')
	);
	
}

function loadShoutboxwi()
{
	global $user_info, $scripturl, $context;

	if(!empty($_REQUEST['sa']) && $_REQUEST['sa'] == 'update')
		adk_getContentShout();
	elseif(!empty($_REQUEST['sa']) && $_REQUEST['sa'] == 'insert'){

		$nick = CleanAdkStrings($_REQUEST['nick']);
		$message = CleanAdkStrings($_REQUEST['message']);
		
		if((!empty($user_info['name'])) && ($user_info['name'] != $nick))
			die();
		
		$id_user = $user_info['id'];
			
		adk_insertMessageShout($nick, $message, $id_user);
	}

	return exit();
}

//Function parse_buttons
function parseAdk_buttons($buttons)
{
	global $boardurl, $adkFolder;
	
	if(!is_array($buttons) || empty($buttons))
		return;
	
	$return = array();
	
	foreach($buttons AS $button)
	{
		if(!isset($button['href']))
			$title = $button['title'];
		else
			$title = '<a href="'.$button['href'].'">
					'.$button['title'].'
				</a>';


		if($button['show'])
			$return[] = '
			<div class="smalltext" style="padding:3px;">
				<img style="'.$button['style'].'" alt="'.$button['title'].'" src="'.$adkFolder['images'].'/'.$button['icon'].'" /> 
				'.$title.'
			</div>';
	}
	
	echo implode('',$return);
}

//Load Menus
function load_menu_principal()
{
	global $scripturl, $txt, $adkportal, $modSettings, $context;
	
	//Url's
	$adk_stand_alone_url = isset($adkportal['adk_stand_alone_url']) ? $adkportal['adk_stand_alone_url'] : $scripturl;
	$home_url = $adkportal['adk_enable'] == 2 ? $adk_stand_alone_url : $scripturl;
	
	$buttons = array();
	
	//Array
	$buttons = array(
		'home' => array(
			'title' => $txt['home'],
			'style' => 'vertical-align: middle;',
			'href' => $home_url,
			'show' => true,
			'icon' => 'gohome.png',
		),
		'forum' => array(
			'title' => $txt['adkmod_forum'],
			'style' => 'vertical-align: middle;',
			'href' => $adkportal['adk_enable'] == 2 ? $scripturl : $scripturl . '?action=forum',
			'show' => !empty($adkportal['adk_enable']),
			'icon' => 'agt.png',
		),
		'users' => array(
			'title' => $txt['users'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=mlist',
			'show' => allowedTo('view_mlist'),
			'icon' => 'users.png',
		),
		'search' => array(
			'title' => $txt['search'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=search',
			'show' => allowedTo('search_posts'),
			'icon' => 'search.png',
		),
		'faq' => array(
			'title' => $txt['help'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=help',
			'show' => true,
			'icon' => 'help.png',
		),
	);
	
	if(!empty($modSettings['blog_enable']) && allowedTo_viewBlog()){

		if(!isset($txt['blog']))
			$txt['blog'] = 'Blog';

		$buttons['blog'] = array(
			'title' => $txt['blog'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=blogs',
			'show' => true,
			'icon' => 'AllPages.png',
		);
	}
	
	//Return buttons
	return $buttons;
}

function load_menu_personal()
{
	global $scripturl, $txt, $context, $modSettings, $user_info;
	
	$buttons = array();
	
	//Array
	$buttons = array(
		'profile' => array(
			'title' => $txt['profile'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=profile',
			'show' => $context['user']['is_logged'],
			'icon' => 'link.png',
		),
		'pm' => array(
			'title' => $txt['pm_short'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=pm',
			'show' => $context['user']['is_logged'] && $context['allow_pm'],
			'icon' => 'messages.png',
		),
		'admin' => array(
			'title' => $txt['admin'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=admin',
			'show' => $context['user']['is_logged'] && $context['allow_admin'],
			'icon' => 'admin.png',
		),
		'logout' => array(
			'title' => $txt['logout'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=logout;'.$context['session_var'].'='. $context['session_id'],
			'show' => $context['user']['is_logged'],
			'icon' => 'logout.png',
		),
		'register' => array(
			'title' => $txt['register'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=register',
			'show' => !$context['user']['is_logged'],
			'icon' => 'register.png',
		),
		'login' => array(
			'title' => $txt['login'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?action=login',
			'show' => !$context['user']['is_logged'],
			'icon' => 'login.png',
		),
	);
	
	if(!empty($modSettings['blog_enable']) && allowedTo_viewMyBlog()){

		//Load Adk Blog
		if(!isset($txt['adk_another_string']))
			$txt['adk_another_string'] = 'My blog';

		array_unshift($buttons, array(
			'title' => $txt['adk_another_string'],
			'style' => 'vertical-align: middle;',
			'href' => $scripturl.'?blog='.$user_info['id'],
			'show' => true,
			'icon' => 'AllPages.png',
		));
	}
	
	//Unread Messages ;)
	$buttons['pm']['title'] .= ' ('.$user_info['unread_messages'].'/'.$user_info['messages'].')';
			
	
	return $buttons;
}

function allowedTo_viewBlog()
{
	global $user_info, $modSettings, $context;
	
	if($context['user']['is_guest'] && in_array(-1,explode(',',$modSettings['blog_allowed_toview'])) && !empty($modSettings['blog_enable']))
		return true;
	elseif(!$user_info['is_guest'] && in_array($user_info['groups'][0],explode(',',$modSettings['blog_allowed_toview'])) && !empty($modSettings['blog_enable']))
		return true;
	else
		return false;
}

function allowedTo_viewMyBlog()
{
	global $user_info, $modSettings;
	
	if(in_array($user_info['groups'][0],explode(',',$modSettings['blog_allowed_your_blog'])))
		return true;
	else
		return false;
}

//Do you have GD library? :|
function check_if_gd()
{
	if(function_exists('imagecreate'))
		return true;
	else
		return false;
}

//Some errors with russian languages (for example)
function parse_if_utf8($title)
{
	//Heracles was here :P
	if(empty($title) || empty($context['character_set']))
		return empty($title) ? '' : $title;

	global $context;
	
	if($context['character_set'] == 'UTF-8')
		$title = htmlentities($title,ENT_QUOTES,'cp1251');
	
	return $title;	
}

//Shoutbox smileys
function parse_shoutbox($body)
{
	global $context, $boarddir, $adkportal, $adkFolder;
	
	//Found dir
	$context['shout_dir'] = $adkFolder['main'].'/smileys';
	$context['shout_dir_found'] = is_dir($context['shout_dir']);

		
	$context['filenames'] = array();
	//Load folder
	if ($context['shout_dir_found'])
	{
		if (!file_exists($context['shout_dir']))
			continue;

		$dir = dir($context['shout_dir']);
		while ($entry = $dir->read())
		{
			if (!in_array($entry, $context['filenames']) && in_array(strrchr($entry, '.'), array('.jpg', '.gif', '.jpeg', '.png')))
				$context['filenames'][strtolower($entry)] = array(
					'id' => CleanAdkStrings($entry),
			);
		}
		
		$dir->close();
		ksort($context['filenames']);
	}

	$context['filenames'] = array_values($context['filenames']);
	
	$parse_me = array(
		':)' => 'smiley.gif',
		';)' => 'wink.gif',
		':D' => 'cheesy.gif',
		':(' => 'angry.gif',
		':*' => 'kiss.gif',
		':P' => 'tongue.gif',
		':|' => 'undecided.gif',
		'8)' => 'cool.gif',
	);

	foreach($parse_me AS $button => $image)
		$body = str_replace($button, ':'.$image.':', $body);

	//Create str_replace
	foreach($context['filenames'] AS $smiley){
		$body = str_replace(':'.$smiley['id'].':','<img alt="" src="'.$adkFolder['smileys'].'/'.$smiley['id'].'" />',$body);
	}
	
	//Parse with parse_bbc
	$bbcodes = array(
		'i','b','u','s',
		'left','center','right'
	);
	
	//dont show default smileys
	$body = parse_bbc($body,false,'',$bbcodes);
	
	
	//return
	return $body;
	
}

function adk_bookmarks($align = 'left', $block = 'auto_news', $id = 0)
{
	global $boardurl, $scripturl, $adkFolder;
	
	//Not id? fuck!
	if(empty($id))
		return;
	
	//Url?
	$t = $scripturl.'?topic='.$id.'.0';
	$n = $scripturl.'?action=addthistopic;view='.$id;
	
	//final url
	$url = $block == 'auto_news' ? $t : $n;
	
	//Facebook share
	$share = array(
		'facebook' => array(
			'url' => 'http://www.facebook.com/sharer.php?u='.$url,
			'image' => 'facebook.png',
		),
		'twitter' => array(
			'url' => 'http://twitter.com/home?status='.$url,
			'image' => 'twitter.png',
		),
	);	
	
	echo'
	<div class="adk_align_'.$align.'">';
	
	foreach($share AS $b)
		echo'
		<a href="'.$b['url'].'" target="_blank"><img alt="" src="'.$adkFolder['images'].'/'.$b['image'].'" /></a>';
	
	echo'
	</div>';
		
}

function checkIfValidExtension($extension){
	
	//non extension? return false :D
	if(empty($extension))
		return false;
	
	$extensions = array(
		'jpg','jpeg','png','gif','bmp',
	);
	
	$extension = strtolower($extension);
	
	if(in_array($extension,$extensions))
		return true;
	else
		return false;
}

//Hooks integration
function Adk_portal_add_index_actions(&$actionArray)
{
	//Load Adkportal actions
	$actionArray += array(
		'forum' => array('BoardIndex.php', 'BoardIndex'),
		'portal' => array('AdkPortal/Adkportal.php', 'Adkportal'),
		'downloads' => array('AdkPortal/Adk-Downloads.php','ShowDownloads'),
		'addthistopic' => array('AdkPortal/Adk-echomodules.php','AddThisTopic'),
		'adk_shoutbox' => array('AdkPortal/Adk-echomodules.php','ShowShoutbox'),
		'adk_credits' => array('AdkPortal/Adk-echomodules.php','AdkCredits'),
		'contact' => array('AdkPortal/Adk-echomodules.php','AdkContact'),
		'pages' => array('AdkPortal/Adk-echomodules.php', 'AdkPageSystem'),
		'shoutboxAjax' => array('AdkPortal/Subs-adkfunction.php', 'loadShoutboxwi'),
	);

}

function Adk_portal_add_admin_areas(&$adminAreas)
{
	global $txt, $adkportal;
	
	//Load Menu Admin
	$find_me = 0;
	reset($adminAreas);
	
	while((list($key, $val) = each($adminAreas)) && $key != 'layout')
		$find_me++;

	$adminAreas = array_merge(
		array_slice($adminAreas, 0, $find_me),
		array(
			'adk_portal' => array(
				'title' => $txt['adkmod_adkportal'],
				'permission' => array('adk_portal'),
				'areas' => array(
					'adkadmin' => array(
						'label' => $txt['adkmod_adkportal'],
						'file' => 'AdkPortal/Adk-Admin.php',
						'function' => 'AdkAdmin',
						'icon' => 'label0.png',
						'permission' => array('adk_portal'),
						'subsections' => array(
							'view' => array($txt['adkmod_news']),
							'adksettings' => array($txt['adkmod_settings']),
							'manageicons' => array($txt['adkmod_icons']),
						),
					),
					'blocks' => array(
						'label' => $txt['adkmod_block_manage'],
						'file' => 'AdkPortal/Adk-AdminBlocks.php',
						'function' => 'AdkBlocksGeneral',
						'icon' => 'posts.gif',
						'permission' => array('adk_portal'),
						'subsections' => array(
							'checktemplates' => array($txt['adkmod_block_templates']),
							'viewblocks' => array($txt['adkmod_block_title']),
							'settingsblocks' => array($txt['adkmod_block_settings']),
							'newblocks' => array($txt['adkmod_block_add']),
							'createnews' => array($txt['adkmod_block_add_news']),
							'download' => array($txt['adkmod_block_download']),
						),
					),
					'modules' => array(
						'label' => $txt['adkmod_modules_manage'],
						'file' => 'AdkPortal/Adk-AdminModules.php',
						'function' => 'AdkModules',
						'icon' => 'label1.png',
						'permission' => array('adk_portal'),
						'subsections' => array(
							'intro' => array($txt['adkmod_modules_intro']),
							'viewadminpages' => array($txt['adkmod_modules_pages']),
							'contact' => array($txt['adkmod_modules_contacto']),
							'uploadanyimage' => array($txt['adkmod_modules_images']),
							'manageimagesadk' => array($txt['adkmod_modules_manage_images']),
						),
					),
					'adkdownloads' => array(
						'label' => $txt['adkmod_eds_manage'],
						'file' => 'AdkPortal/Adk-AdminDownloads.php',
						'function' => 'ShowDownloadsMainAdmin',
						'icon' => 'label2.png',
						'subsections' => array(
							'settings' => array($txt['adkmod_eds_settings']),
							'addcategory' => array($txt['adkmod_eds_add']),
							'allcategories' => array($txt['adkmod_eds_categories']),
							'approvedownloads' => array($txt['adkmod_eds_approve']),
						),
					),
					'adkseoadmin' => array(
						'label' => $txt['adkmod_seo_manage'],
						'file' => 'AdkPortal/Adk-AdminSeo.php',
						'function' => 'AdkSeoMain',
						'icon' => 'label3.png',
						'subsections' => array(
							'htaccess' => array($txt['adkmod_seo_htaccess']),
							'settings' => array($txt['adkmod_eds_settings']),
							'robotstxt' => array($txt['adkmod_seo_robots']),
						),
					),
				),
			),
		),
		array_slice($adminAreas, $find_me)
	);
	
	//Print standalone area if it's necessary
	if($adkportal['adk_enable'] == 2)
		$adminAreas['adk_portal']['areas']['adkadmin']['subsections']['standalone'] = array($txt['adkmod_stand']);

}

function Adk_portal_add_menu_buttons(&$buttons)
{
	global $adkportal, $scripturl, $txt, $context, $user_info, $options;
	
	//Load Menu buttons
	$adk_stand_alone_url = isset($adkportal['adk_stand_alone_url']) ? $adkportal['adk_stand_alone_url'] : $scripturl;
	$home_url = $adkportal['adk_enable'] == 2 ? $adk_stand_alone_url : $scripturl;
	$txt_unread = '';

	if(!empty($adkportal['enable_pages_notifications']))
		$txt_unread = ($user_info['has_pages_notifications'] ? (' <b>('.$user_info['adk_pages_notifications_count'].')</b>') : '');
	
	$find_me = 0;
	reset($buttons);
	
	while((list($key, $val) = each($buttons)) && $key != 'home')
		$find_me++;

	$buttons = array_merge(
		array_slice($buttons, 0, $find_me + 1),
		array(
			'forum' => array(
				'title' => $txt['adkmod_forum'],
				'href' => $adkportal['adk_enable'] == 2 ? $scripturl : $scripturl . '?action=forum',
				'show' => !empty($adkportal['adk_enable']),
				'sub_buttons' => array(
				),
			),
			'downloads' => array(
				'title' => $txt['adkmod_downloads'],
				'href' => $scripturl . '?action=downloads',
				'show' => $adkportal['download_enable'],
				'sub_buttons' => array(
				),
			),
			'contact' => array(
				'title' => $txt['adkmod_modules_contacto'],
				'href' => $scripturl.'?action=contact',
				'show' => !empty($adkportal['adk_enable_contact']) && allowedToViewContactPage(),
			),
			'pages' => array(
				'title' => $txt['adkmod_pages'] . $txt_unread,
				'href' => $scripturl.'?action=pages',
				'show' => !empty($adkportal['enable_menu_pages']),
				'sub_buttons' => array(
					'unread' => array(
						'title' => $txt['adkmod_pages_unread'] . $txt_unread,
						'show' => empty($options['adk_disable_notifications_profile']) && !empty($adkportal['enable_pages_comments']) && !empty($adkportal['enable_pages_notifications']),
						'href' => $scripturl.'?action=pages;sa=unread'
					)
				)
			),
		),
		array_slice($buttons, $find_me)
	);

	$buttons['admin']['sub_buttons'] += array(
		'adkportal' => array(
			'title' => $txt['adkmod_adkportal'],
			'href' => $scripturl . '?action=admin;area=adkadmin',
			'show' => allowedTo('adk_portal'),
			'is_last' => true,
		),
	);
	
	//rewrite main url
	$buttons['home']['href'] = $home_url;
	
	//rewrite is last...
	$buttons['admin']['sub_buttons']['permissions']['is_last'] = false;

	//Rewrite show admin
	$buttons['admin']['show'] = $context['allow_admin'] && allowedTo('adk_portal');

	//mmm i don't like this here... but it's the only way (to know (8) (?))
	if((!empty($context['sub_template'])) && ($context['sub_template'] == 'kick_guest'))
		getAdkportalMaintenance();
}

function Adk_portal_display_buttons(&$display_buttons)
{
	global $context, $scripturl, $topic, $smcFunc;
	
	if(!empty($topic)){

		$context['adk_portal'] = allowedTo('adk_portal');

		$sql = $smcFunc['db_query']('','SELECT id_new FROM {db_prefix}topics WHERE id_topic = {int:topic}', array('topic' => $topic));

		list($id_new) = $smcFunc['db_fetch_row']($sql);

		$smcFunc['db_free_result']($sql);
	
		$string = !empty($id_new) ? 'remove' : 'add';
	
		//Add Display Button
		$display_buttons += array(
			'addthistopic' => array('test' => 'adk_portal', 'text' => 'adkmod_block_'.$string.'_this_topic', 'image' => 'reply.gif', 'lang' => true, 'url' => $scripturl . '?action=addthistopic;'.$string.'=' . $context['current_topic']),
		);
	}
}

function Adk_portal_Permissions(&$permissionGroups, &$permissionList)
{
	//Simple permissions :D
	$permissionGroups['membergroup']['simple'] = array('adk_portal', 'adk_downloads_autoapprove', 'adk_downloads_manage');

	//Set the classic permissions, for old mans
	$permissionGroups['membergroup']['classic'] = array('adk_portal', 'adk_downloads_autoapprove', 'adk_downloads_manage');

	//Set the permissionList membergroup
	$permissionList['membergroup'] += array (
		'adk_portal' => array(false, 'adkportal', 'adkportal'),
		'adk_downloads_autoapprove' => array(false, 'adkportal', 'adkportal'),
		'adk_downloads_manage' => array(false, 'adkportal', 'adkportal'),
		);
}

function Adk_portal_who($actions)
{
	//Loading Adkportal actions who
	global $txt;

	$return = '';
	if (!empty($actions['action'])) {

		//Check our custom actions
		if ($actions['action'] == 'portal')
			$return = $txt['who_adk_portal'];
		elseif ($actions['action'] == 'forum')
			$return = $txt['who_adk_forum'];
		elseif ($actions['action'] == 'adk_credits')
			$return = $txt['who_adk_credits'];
		elseif ($actions['action'] == 'contact')
			$return = $txt['who_adk_contact'];
		elseif ($actions['action'] == 'adk_shoutbox')
			$return = $txt['who_adk_shoutbox'];
		elseif($actions['action'] == 'pages')
			$return = $txt['who_adk_index_pages'];
		elseif ($actions['action'] == 'downloads') {

			//IF the user is viewing download system?
			if (!empty($actions['cat']))
				$return = $txt['who_adk_down_cat'];
			elseif (!empty($actions['sa'])) {
				if ($actions['sa'] == 'view')
					$return = $txt['who_adk_down'];
				elseif ($actions['sa'] == 'myprofile')
					$return = $txt['who_adk_down_profile'];
				elseif ($actions['sa'] == 'viewstats')
					$return = $txt['who_adk_down_stats'];
				elseif ($actions['sa'] == 'search')
					$return = $txt['who_adk_down_search'];
				elseif ($actions['sa'] == 'search2')
					$return = $txt['who_adk_down_search2'];
				elseif ($actions['sa'] == 'addnewfile')
					$return = $txt['who_adk_down_add'];
				elseif ($actions['sa'] == 'editdownload')
					$return = $txt['who_adk_down_edit'];
			}
			else
				$return = $txt['who_adk_down_system'];
		}
	}
	elseif (!empty($actions['page'])) {
			$return = $txt['who_adk_page'];
	}
	elseif (empty($actions['action']))
		$return = $txt['who_adk_portal'];

	return $return;
}

function Adk_portal_change_buffer(&$buffer)
{
	global $adkportal, $boardurl, $scripturl, $settings, $forum_copyright, $context, $txt, $current_load;

	//Set the Copyright for different pages
	//Replace here to create your copyright with the action=
	$copyrights = array(
		'downloads' => 'Extreme download system by Adk Portal',
		'contact' => 'Contact module by Adk Portal',
		'pages' => 'Pages module by Adk Portal',
		'adk_shoutbox' => 'Shoutbox by Adk portal',
	);

	//Set the copyrights :)
	call_integration_hook('modules_copyright', array(&$copyrights));

	if(($current_load[0] == 'action') && (!empty($copyrights[$current_load[1]])))
		$buffer = str_replace($forum_copyright, $forum_copyright.'<br /><a href="http://www.smfpersonal.net" target="_blank">'.$copyrights[$current_load[1]].'</a>', $buffer);
	elseif($current_load[0] == 'page')
		$buffer = str_replace($forum_copyright, $forum_copyright.'<br /><a href="http://www.smfpersonal.net" target="_blank">'.$copyrights['pages'].'</a>', $buffer);
	
	//Seo pages
	if(!empty($adkportal['enable_pages_seo']))
	{
		$buffer = preg_replace_callback('~"' . preg_quote($scripturl, '/') . '\?page=([^#"]+?)?"~', 'preg_page', $buffer);
	}
	
	//Replace images url from admin
	$buffer = str_replace($settings['images_url'].'/admin', $settings['default_images_url'].'/admin', $buffer);

	//Set another things
	$load = $adkportal['variables_important'];
	
	if(empty($load))
	{
		$version = getYourversion();
		$load = $adkportal['copy'];
	}
	
	$buffer = str_replace($forum_copyright,$forum_copyright.$load,$buffer);
	
	if(!empty($context['adk_stand_alone'])){
		$re_new = $load.$load;

		$buffer = str_replace($re_new,$load,$buffer);
	}
	
	if(!empty($adkportal['enable_download_seo']))
	{
		$buffer = preg_replace_callback('~"' . preg_quote($scripturl, '/') . '\?action=downloads;cat=([^#"]+?);([^#"]+?)?"~', 'preg_down', $buffer);
		$buffer = preg_replace_callback('~"' . preg_quote($scripturl, '/') . '\?action=downloads;cat=([^#"]+?)?"~', 'preg_down2', $buffer);
		$buffer = preg_replace_callback('~"' . preg_quote($scripturl, '/') . '\?action=downloads;sa=view;down=([^#"]+?)?"~', 'preg_down3', $buffer);

	}

	//Rewrite cat url's
	if($adkportal['adk_enable'] == 1)
		$buffer = preg_replace_callback('~"' . preg_quote($scripturl, '/') . '\#c([^#"]+?)"~', 'preg_forum', $buffer);

	//Let's modify SMF Profile.php
	if(!empty($adkportal['enable_pages_comments']) && !empty($adkportal['enable_pages_notifications']) && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'profile' && !empty($_REQUEST['area']) && $_REQUEST['area'] == 'theme')
		$buffer = str_replace(
			'<ul id="theme_settings">',
			'<ul id="theme_settings">
				<li>
					<input type="hidden" name="default_options[adk_disable_notifications_profile]" value="0" />
					<label for="adk_disable_notifications_profile"><input type="checkbox" name="default_options[adk_disable_notifications_profile]" id="adk_disable_notifications_profile" value="1"'. (!empty($context['member']['options']['adk_disable_notifications_profile']) ? ' checked="checked"' : ''). ' class="input_check" /> '. $txt['adk_disable_notifications_profile'].'</label>
				</li>',
			$buffer
		);

	return $buffer;
}

function preg_forum($matches) {
	global $boardurl; 
	return $boardurl.'/index.php?action=forum#c'.$matches[1];
}
function preg_page($matches) {
	global $boardurl; 
	return $boardurl.'/'.changeurlpagesAdkportal($matches[1]);
}
function preg_down($matches) {
	global $boardurl;
	if (!empty($matches[2]))
		return $boardurl.'/index.php?action=downloads;cat='.$matches[1].';'.$matches[2];
	else
		return $boardurl.'/index.php?action=downloads;cat='.$matches[1];
}
function preg_down2($matches) {
	global $boardurl; 
	return $boardurl.'/'.changeCatUrl($matches[1]);
}
function preg_down3($matches) {
	global $boardurl; 
	return $boardurl.'/'.changeDownloadUrl($matches[1]);
}

function Adk_portal_load_from_theme()
{

	global $context, $topic, $board, $adkportal, $current_load, $scripturl, $txt, $board, $topic, $boardurl, $user_settings, $user_info;

	//StandAlone
	adk_standAloneMode(SMF == 'SSI');

	//Get Languages Help
	if ((!empty($_REQUEST['action'])) && ($_REQUEST['action'] == 'helpadmin'))
		adkLanguage('Adk-Admin+Adk-Help');

	//LoadSettings From Adk Portal
	adkportalSettings();

	$context['html_headers'] .= rewrite_context_html_headers();

	//Css Compatible
	if($context['browser']['is_ie6']){
		$context['html_headers'] .= getCss('ie6');
	}

	adktemplate('Adkportal');
	$context['template_layers'][] = 'Adk_blocks';

	//Set Linktree
	if((($current_load[0] == 'action') && (($current_load[1] == 'forum') || ($current_load[1] == 'collapse')) || (!empty($topic)) || (!empty($board))) && !empty($adkportal['adk_enable'])){
		
		$url = $adkportal['adk_enable'] == 2 ? $scripturl : $scripturl.'?action=forum';

		for($i = count($context['linktree']) - 1; $i > 0; $i--){

			$context['linktree'][$i + 1] = $context['linktree'][$i];
		}

		$context['linktree'][1] = array(
			'url' => $url,
			'name' => $txt['adkmod_forum']
		);

	}

	//Change Portal url
	if(($adkportal['adk_enable'] == 2) && (isset($adkportal['adk_stand_alone_url'])))
		$context['linktree'][0]['url'] = $adkportal['adk_stand_alone_url'];

	if ((($current_load[0] == 'default') && (!empty($adkportal['adk_enable']))) && (!empty($adkportal['adk_linktree_portal'])) && (!WIRELESS))
		unset($context['linktree']);

	//Set a user_info
	$user_info['adk_notes'] = isset($user_settings['adk_notes']) ? $user_settings['adk_notes'] : '';
	$user_info['adk_pages_notifications'] = isset($user_settings['adk_pages_notifications']) ? $user_settings['adk_pages_notifications'] : '';
	$user_info['adk_pages_notifications_count'] = !empty($user_info['adk_pages_notifications']) ? (count(explode(',', $user_info['adk_pages_notifications']))) : 0;
	$user_info['has_pages_notifications'] = !empty($user_info['adk_pages_notifications_count']);

}

function Adk_portal_pre_load()
{
	global $context;

	// Adk Portal Language is needed all the time
	adkLanguage('Adk-Modifications');

	getAdkportalSettings();
}

function Adk_portal_redirect(&$setLocation, &$refresh)
{
	global $scripturl, $adkportal;
	//News edit, redirect to the portal
	if($setLocation == $scripturl.'?portal')
		$setLocation = (($adkportal['adk_enable'] == 2) && (isset($adkportal['adk_stand_alone_url']))) ? $adkportal['adk_stand_alone_url'] : $scripturl;
}

//End Hooks

function CleanAdkStrings($string)
{
	global $smcFunc;

	//This is for a compatibility check with Adk portal 2.1.... really, this is an obsolet function on Adk 2.1.1+
	return $smcFunc['htmlspecialchars']($string, ENT_QUOTES);
}

function un_CleanAdkStrings($string)
{
	//This is for a compatibility check with Adk portal 2.1.... really, this is an obsolet function on Adk 2.1.1+
	return un_htmlspecialchars($string);
}

function adk_create_block($parameters, $load_permissions = true)
{
	global $boarddir, $user_info, $context, $adkFolder;

	//Set the block information
	$context['block'] = $parameters;
	
	//Change variable name
	$poster = $parameters;
	
	//You can view that block (for the moment :P)
	$true = true;
	
	//Load permissions
	if($load_permissions){
		$explode = explode(',',$poster['p']);
		if($user_info['is_guest'] && in_array(-1,$explode))
			$true = false;
			
		if($user_info['groups'][0] == 0 && in_array(-2,$explode))
			$true = false;
			
		if(in_array($user_info['groups'][0],$explode) && $user_info['groups'][0] != 0)
			$true = false;
	}
	
	//Load block
	if($true){
		find_modSettings_style_top($poster['title'], $poster['img'],$poster['id'], $poster['b'],$poster['t'], $poster['c']);

		if($poster['type'] == 'include'){
			if(file_exists($adkFolder['blocks'].'/'.$poster['echo']))
				require($adkFolder['blocks'].'/'.$poster['echo']);
		}
		elseif($poster['type'] == 'php'){
			$body = $poster['echo'];
			$body = trim($body);
			$body = trim($body, '<?php');
			$body = trim($body, '?>');
			eval($body);
		}
		elseif($poster['type'] == 'html'){
			$body = $poster['echo'];
			echo $body;
		}
		elseif($poster['type'] == 'bbc'){
			$body = $poster['echo'];
			echo parse_bbc($body);
		}
		elseif($poster['type'] == 'multi_block'){
			$blocks = load_multi_blocks($poster['echo'], $poster['id']);
		}
		find_modSettings_style_bot($poster['b']);
	}

}
	
//The Array :?
function load_multi_blocks($id, $initial_id)
{
	global $smcFunc, $user_info;
	
	//wrongs id?
	if(empty($id))
		return;
		
	$multi_id = explode(',',$id);
	
	$sql = $smcFunc['db_query']('','
		SELECT id, echo, name, img, type, empty_body, empty_title, empty_collapse, permissions, other_style
		FROM {db_prefix}adk_blocks 
		WHERE id IN ({array_string:settings})',
		array(
			'settings' => $multi_id,
		)
	);
	
	$blocks = array();
	
	$to_count = count($multi_id);
	
	//Algunas variables
	$i = 0;
	$to_use = '';
	
	while($row = $smcFunc['db_fetch_assoc']($sql)){
		while($i < $to_count){
			if($row['id'] == $multi_id[$i])
				$to_use = $i;
			
			$i++;
		}
		
		$blocks[$to_use] = array(
			'id' => $row['id'],
			'echo' => un_htmlspecialchars($row['echo']),
			'title' => $row['name'],
			'img' => $row['img'],
			'type' => $row['type'],
			'b' => $row['empty_body'],
			't' => $row['empty_title'],
			'c' => $row['empty_collapse'],
			'p' => $row['permissions'],
			'other_style' => $row['other_style'],
		);
		
		$i = 0;
	}
	
	ksort($blocks);
	
	$smcFunc['db_free_result']($sql);
	
	$total_blocks = count($blocks);
	
	if(empty($blocks))
		return;
	
	//Count -1 if that user does not have permissions to see this block
	foreach($blocks AS $block){
		$true = true;
		$explode = explode(',',$block['p']);
		
		if($user_info['is_guest'] && in_array(-1,$explode))
		$true = false;
		
		if($user_info['groups'][0] == 0 && in_array(-2,$explode))
			$true = false;
			
		if(in_array($user_info['groups'][0],$explode) && $user_info['groups'][0] != 0)
			$true = false;
		
		if(!$true)
			$total_blocks--;
	}
	
	if(!empty($total_blocks)){
		
		//Widths :)
		$widths = 100 / $total_blocks;
		
		echo'
		<table style="width: 100%;" align="center">
			<tr>';
		
		foreach($blocks AS $block){
			
			$true = true;
			$explode = explode(',',$block['p']);
			
			if($user_info['is_guest'] && in_array(-1,$explode))
			$true = false;
			
			if($user_info['groups'][0] == 0 && in_array(-2,$explode))
				$true = false;
				
			if(in_array($user_info['groups'][0],$explode) && $user_info['groups'][0] != 0)
				$true = false;
			
			if($true){
				$block['id'] = $block['id'].'_prima_'.$initial_id;
				
				echo'
					<td valign="top" style="width: ',$widths,'%;">';
						adk_create_block($block);
				echo'
					</td>';
			}
		}
		
		echo'
			</tr>
		</table>';
	}
	
}

function help_link($txt_string, $help_string, $print_txt = true)
{
	global $boardurl, $txt, $scripturl, $helptxt, $adkFolder;
	
	$image = 'help';
	
	//Print text
	echo'
	<a href="',$scripturl,'?action=helpadmin;help=',$help_string,'" onclick="return reqWin(this.href);"><img style="vertical-align: bottom;" alt="" src="'.$adkFolder['images'].'/',$image,'.png" /></a>';
	
	if($print_txt)
		echo'&nbsp;',$txt[$txt_string];
	
}

function javaScript_blocks()
{
	global $boardurl, $txt, $user_info, $settings, $context, $current_load, $adkFolder;
	static $count = 0;

	if($count > 0)
		return '';
	
	$js = getCss('blocks');

	$js .=
		'<script type="text/javascript"><!-- // --><![CDATA[
			var smf_adk_url = "'.$adkFolder['mainurl'].'/";
			var smf_shoutbox_text_sending = "'.$txt['adkmod_shoutbox_sending'].'";
			var smf_shoutbox_shout_it = "'.$txt['adkmod_shoutbox_shout_it'].'";
			var smf_shoutbox_fill = "'.$txt['adkmod_shoutbox_all_field'].'";
		// ]]></script>
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
			function adkcollapse(id,span)
			{
				var hide = new Array();
				hide[1] = "adk_left_'.$current_load[0].'_'.$current_load[1].'";
				hide[2] = "adk_right_'.$current_load[0].'_'.$current_load[1].'";
				if (id == 1) {var leftright = "left";}
				else {var leftright = "right";}
				mode = document.getElementById(hide[id]).style.display == "" ? 0 : 1;' . ($user_info['is_guest'] ? '
				document.cookie = hide[id] + "=" + (mode ? 0 : 1);' : '
				smf_setThemeOption(hide[id], mode ? 0 : 1, null, "' . $context['session_id'] . '");') . '
				document.getElementById(span).innerHTML = (mode ? "<img alt=\"'.$txt['adkmod_collapse'].'\" title=\"'.$txt['adkmod_collapse'].'\" src=\"'.$adkFolder['images'].'/collapse_" + leftright + ".png\" />" : "<img alt=\"'.$txt['adkmod_expand'].'\" title=\"'.$txt['adkmod_expand'].'\" src=\"'.$adkFolder['images'].'/expand_" + leftright + ".png\" />"); 						
				document.getElementById(hide[id]).style.display = mode ? "" : "none";
			}
			function adkBlock(id,img_id)
			{
				var hide = new Array();
				hide[id] = "adk_block_"+ id;
				mode = document.getElementById(hide[id]).style.display == "" ? 0 : 1;' . ($user_info['is_guest'] ? '
				document.cookie = hide[id] + "=" + (mode ? 0 : 1);' : '
				smf_setThemeOption(hide[id], mode ? 0 : 1, null, "' . $context['session_id'] . '");') . '
				document.getElementById(img_id).src = (mode ? "'.$settings['images_url'].'/collapse.gif" : "'.$settings['images_url'].'/expand.gif");
				document.getElementById(hide[id]).style.display = mode ? "" : "none";
			}		
		// ]]></script>';

	$count++;
	
	return $js;
}

function loadAdkGroups($where = '', $parameters = array(), $orderby = '')
{

	global $smcFunc;

	//Validate Where
	validateWhere($where);
	validateOrder($orderby);

	$sql = $smcFunc['db_query']('','
		SELECT id_group, group_name, description, online_color
		stars, group_type, hidden, id_parent
		FROM {db_prefix}membergroups
		'.$where.'
		'.$orderby,
		$parameters
	);
	
	$construirGrupos = array();
	while($row = $smcFunc['db_fetch_assoc']($sql)){
		$construirGrupos[$row['id_group']] = array(
			'name' => $row['group_name'],
			'description' => $row['description'],
			'stars' => $row['stars'],
			'group_type' => $row['group_type'],
			'is_hidden' => !empty($row['hidden']),
			'id_parent' => $row['id_parent'],
		);
	}

	$smcFunc['db_free_result']($sql);

	return $construirGrupos;
}

function validateWhere(&$where)
{

	if(!empty($where))
		$where = 'WHERE '.$where;
}

function validateOrder(&$order)
{

	if(!empty($order))
		$order = 'ORDER BY '.$order;
}

function createArrayFromPost($post_action)
{
	if(isset($_POST[$post_action])){	
		$matriz = array_keys($_POST[$post_action]);
		$matriz = implode(',',$matriz);
	}
	else
		$matriz = '';	

	return $matriz;
}

function setLinktree($action = '', $string, $other_url = false, $other_string = false)
{
	global $context, $txt, $scripturl;

	//Set the url
	$url = $other_url ? $action : $scripturl.'?action='.$action;

	//Set the string
	$text = $other_string ? $string : $txt[$string];

	$context['linktree'][] = array(
		'name' => $text,
		'url' => $url,
	);

}

function getIdGroup()
{
	global $user_info;

	if($user_info['is_guest']) {
		$guest = array(-1);
		return $guest;
	}
	else
		return $user_info['groups'];
}

function getCurrentAction()
{

	global $board, $adkportal, $topic;

	$current_action = '';

	if((!empty($_REQUEST['action'])) || !empty($board)  || !empty($topic) || !empty($_REQUEST['blog']))
		$current_action = 'action';
	
	if(empty($adkportal['adk_enable']))
		$current_action = 'action';

	return $current_action;
}

function shoutboxPermissions($current_permission = 'view')
{
	global $adkportal, $user_info;

	//What permissions are you trying to check?
	$permissions = array(
		'view' => 'shout_allowed_groups_view',
		'post' => 'shout_allowed_groups',
	);


	$check = $permissions[$current_permission];

	//Groups
	if(!empty($adkportal[$check])){

		$x = explode(',',$adkportal[$check]);

		foreach($x AS $i => $v){
			if(in_array($v,$user_info['groups']))
				return true;
		}

		//Guest
		if($user_info['is_guest'] && in_array(-1,$x) || $user_info['groups'][0] == 0 && in_array(0,$x) || $user_info['is_admin'])
			return true;
	}

	return false;

}

function deleteShouts($id_shouts = array())
{

	global $smcFunc;

	//Check if is an array
	$id_shouts = !empty($id_shouts) ? !is_array($id_shouts) ? array($id_shouts) : $id_shouts : '';

	//Delete Please
	$smcFunc['db_query']('','DELETE FROM {db_prefix}adk_shoutbox '.(!empty($id_shouts) ? 'WHERE id IN ({array_int:id_shouts})' : ''), array('id_shouts' => $id_shouts));
}

function adkLanguage($language = 'Adk-Modifications')
{

	$languages = explode('+', $language);

	foreach($languages AS $lang)
		if(loadLanguage('AdkPortal/'.$lang) == false)
			loadLanguage('AdkPortal/'.$lang,'english');
}
function adktemplate($template)
{
	loadTemplate('AdkPortal/'.$template);
}

function getSimpleFile($file = '')
{

	if(file_exists($file))
		return file_get_contents($file);
	else
		return '';
}

function getCss($css_file)
{

	global $adkFolder;

	return '<link rel="stylesheet" type="text/css" href="'.$adkFolder['css'].'/'.$css_file.'.css" />';
}

function getJs($js_file)
{

	global $adkFolder;

	return '<script type="text/javascript" src="'.$adkFolder['js'].'/'.$js_file.'.js"></script>';
}

//Load one page
function getPage($page_url = '', $id_exists = false, $find_in_set = false)
{

	global $smcFunc, $user_info;

	//Check this... you can load the page by id... or by url_text;
	$where = 'urltext';

	if($id_exists)
		$where = 'id_page';

	$sql = $smcFunc['db_query']('','
		SELECT * FROM 
		{db_prefix}adk_pages
		WHERE '.$where.' = {text:page}
			'.($find_in_set && !$user_info['is_admin'] ? ('AND (FIND_IN_SET(' . implode(', grupos_permitidos) != 0 OR FIND_IN_SET(', $user_info['groups']) . ', grupos_permitidos) != 0) ') : ''),
		array(
			'page' => $page_url,
		)
	);
	
	//empty information?
	if($smcFunc['db_num_rows']($sql) == 0)
		return array();
	
	$row = $smcFunc['db_fetch_assoc']($sql);

	//Set the infopage...
	$page_info = array(
		'id_page' => $row['id_page'],
		'urltext' => $row['urltext'],
		'titlepage' => un_CleanAdkStrings($row['titlepage']),
		'body' => un_CleanAdkStrings($row['body']),
		'catbg' => $row['cattitlebg'], /*Compatibility*/ 'cattitlebg' => $row['cattitlebg'], 
		'winbg' => $row['winbg'],
		'groups_allowed' => $row['grupos_permitidos'], /*compatibility*/ 'grupos_permitidos' => $row['grupos_permitidos'],
		'type' => $row['type'],
		'views' => $row['views'],
		'enable_comments' => !empty($row['enable_comments']),
	);

	$smcFunc['db_free_result']($sql);

	return $page_info;
}

//Load multiple pages
function getPages($start = 0, $limit = 10, $where, $orderby, $parameters = array(), $find_in_set = false)
{

	global $smcFunc, $user_info;

	//Validate where and order
	if(!empty($where))
		$where = ' AND '.$where;

	validateOrder($orderby);

	//update parameters
	$parameters += array('start' => $start, 'end' => $limit);

	$sql = $smcFunc['db_query']('','
		SELECT *
		FROM {db_prefix}adk_pages 
		WHERE 1=1 '.$where.'
			'.($find_in_set && !$user_info['is_admin'] ? ('AND (FIND_IN_SET(' . implode(', grupos_permitidos) != 0 OR FIND_IN_SET(', $user_info['groups']) . ', grupos_permitidos) != 0) ') : '').'
		'.$orderby.'
		LIMIT {int:start},{int:end}',
		$parameters
	);
	
	$pages = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql)){

		$short_body = $row['body'];

		$pages[] = array(
			'id_page' => $row['id_page'],
			'short_body' => $short_body,
			'titlepage' => un_CleanAdkStrings($row['titlepage']),
			'body' => un_CleanAdkStrings($row['body']),
			'urltext' => $row['urltext'],
			'views' => $row['views'],
			'catbg' => $row['cattitlebg'], /*Compatibility*/ 'cattitlebg' => $row['cattitlebg'], 
			'winbg' => $row['winbg'],
			'groups_allowed' => $row['grupos_permitidos'], /*compatibility*/ 'grupos_permitidos' => $row['grupos_permitidos'],
			'type' => $row['type'],
			'enable_comments' => !empty($row['enable_comments']),
		);
	}

	$smcFunc['db_free_result']($sql);

	return $pages;
}

function getTotal($table, $where = '', $parameters = array())
{

	global $smcFunc;

	//Validate my where please
	validateWhere($where);

	$data = $smcFunc['db_query']('','
		SELECT COUNT(*) AS total FROM {db_prefix}'.$table.'
		'.$where, 
		$parameters
	);

	list($total) = $smcFunc['db_fetch_row']($data);
	
	$smcFunc['db_free_result']($data);

	return $total;
}

function getShouts($start = 0, $end = 10)
{

	global $smcFunc, $scripturl;

	$sql = $smcFunc['db_query']('','
		SELECT id, date, user, message, id_member
		FROM {db_prefix}adk_shoutbox
		ORDER BY id DESC
		LIMIT {int:start},{int:end}',
		array(
			'start' => $start,
			'end' => $end,
		)
	);
	
	$shouts = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
	{
		
		$shouts[] = array(
			'id' => $row['id'],
			'date' => timeformat($row['date'], '%d/%m - %H:%M' ),
			'user' => (!empty($row['id_member']) ? ('<a href="'.$scripturl.'?action=profile;u='.$row['id_member'].'">'.$row['user'].'</a>') : ($row['user'])),
			'message' => parse_shoutbox($row['message']),
			'alternate' => 'windowbg',
		);
		
	}
	
	$smcFunc['db_free_result']($sql);

	return $shouts;
}

function getEditor($description = '')
{

	global $sourcedir, $context;

	// Needed for the WYSIWYG editor.
	require_once($sourcedir . '/Subs-Editor.php');

	// Now create the editor.
	$editorOptions = array(
		'id' => 'descript',
		'value' => $description,
		'width' => '97%',
		'form' => 'picform',
		'labels' => array(
			'post_button' => '',
		),
	);

	create_control_richedit($editorOptions);
	$context['post_box_name'] = $editorOptions['id'];
}

function getVisualVerification()
{

	global $sourcedir, $context;

	// Needed
	require_once($sourcedir . '/Subs-Editor.php');

	//Visual Verification
	$verificationOptions = array(
		'id' => 'post',
	);

	$context['require_verification'] = create_control_verification($verificationOptions);
	$context['visual_verification_id'] = $verificationOptions['id'];
}

function setCaptchaError()
{

	global $sourcedir, $context;

	require_once($sourcedir . '/Subs-Editor.php');
	$verificationOptions = array(
		'id' => 'post',
	);
	
	$context['require_verification'] = create_control_verification($verificationOptions, true);
	
	if (is_array($context['require_verification']))
		fatal_lang_error('adkfatal_captcha_invalid',false);
}

function cleanEditor()
{

	global $sourcedir;

	if (!empty($_REQUEST['descript_mode']) && isset($_REQUEST['descript']))
	{
		require_once($sourcedir . '/Subs-Editor.php');

		$_REQUEST['descript'] = html_to_bbc($_REQUEST['descript']);

		// We need to unhtml it now as it gets done shortly.
		$_REQUEST['descript'] = un_CleanAdkStrings($_REQUEST['descript']);

	}
}

function deleteEntry($table = '', $where = '', $parameters = array())
{

	global $smcFunc;

	validateWhere($where);

	$smcFunc['db_query']('','DELETE FROM {db_prefix}'.$table.' '.$where, $parameters);
}

function checkIfPageExists($urltext, $id_page = 0)
{

	global $smcFunc;

	$sql = $smcFunc['db_query']('','
		SELECT urltext
		FROM {db_prefix}adk_pages
		WHERE urltext = {string:url} AND id_page <> {int:id_page}',
		array(
			'url' => $urltext,
			'id_page' => $id_page,
		)
	);

	if($smcFunc['db_num_rows']($sql) > 0)
		fatal_lang_error('duplicate_adk_pages',FALSE);

	$smcFunc['db_free_result']($sql);

}

function getImages($where = '', $parameters = array(), $orderby = '', $start = 0, $limit = 10)
{

	global $smcFunc;

	validateWhere($where);
	validateOrder($orderby);

	$parameters += array('start' => $start, 'limit' => $limit);

	$sql = $smcFunc['db_query']('','
		SELECT id, image, url
		FROM {db_prefix}adk_advanced_images
		'.$where.'
		'.$orderby.'
		LIMIT {int:start}, {int:limit}',
		$parameters
	);

	$images = array();
	while($row = $smcFunc['db_fetch_assoc']($sql)){
		$images[] = array(
			'id' => $row['id'],
			'url' => $row['url'],
			'image' => $row['image'],
		);
	}
	
	$smcFunc['db_free_result']($sql);

	return $images;
}

function getIcons($where = '', $parameters = array(), $orderby = '', $start = 0, $limit = 0)
{

	global $smcFunc;

	validateWhere($where);
	validateOrder($orderby);

	$parameters += array('start' => $start, 'limit' => $limit);

	$sql = $smcFunc['db_query']('','
		SELECT id_icon, icon 
		FROM {db_prefix}adk_icons 
		'.$orderby.'
		'.$where.'
		'.(empty($limit) && empty($start) ? '' : 'LIMIT {int:start}, {int:limit}'),
		$parameters
	);
	
	$icons = array();
	
	while($row = $smcFunc['db_fetch_assoc']($sql))
	{
		$icons[] = array(
			'id' => $row['id_icon'],
			'icon' => $row['icon']
		);
	}

	$smcFunc['db_free_result']($sql);

	return $icons;
}

function createBlock($type = 'php', $echo)
{

	global $smcFunc;

	$name = CleanAdkStrings($_POST['titulo']);
	$empty_body = !empty($_POST['empty_body']) ? 1 : 0;
	$empty_title = !empty($_POST['empty_title']) ? 1 : 0;
	$empty_collapse = !empty($_POST['empty_collapse']) ? 1 : 0;
	$img = !empty($_POST['img']) ? CleanAdkStrings($_POST['img']) : '';

	if(empty($name))
		fatal_lang_error('adkfatal_empty_title',false);

	$the_array_info = array(
		'name' => 'text',
		'echo' => 'text',
		'img' => 'text',
		'type' => 'text',
		'empty_body' => 'int',
		'empty_title' => 'int',
		'empty_collapse' => 'int',
	);
	
	$the_array_insert = array(
		$name,
		$echo,
		$img,
		$type,
		$empty_body,
		$empty_title,
		$empty_collapse,
	);
	
	$smcFunc['db_insert']('insert',
		'{db_prefix}adk_blocks',
		//Load The Array Info
		$the_array_info,
		//Insert Now;)
		$the_array_insert,
		array('id')
	);
}

function getTemplateEditor()
{

	global $context;

	if (!function_exists('getLanguages'))
	{
		// Showing BBC?
		if ($context['show_bbc'])
			template_control_richedit($context['post_box_name'], 'bbc');
		// What about smileys?
		if (!empty($context['smileys']['postform']))
			template_control_richedit($context['post_box_name'], 'smileys');
		// Show BBC buttons, smileys and textbox.
		template_control_richedit($context['post_box_name'], 'message');
	}
	else 
	{
		if ($context['show_bbc'])
			echo '
			<div id="bbcBox_message"></div>';
	
			// What about smileys?
		if (!empty($context['smileys']['postform']) || !empty($context['smileys']['popup']))
			echo '
			<div id="smileyBox_message"></div>';
	
		echo '
		', template_control_richedit($context['post_box_name'], 'smileyBox_message', 'bbcBox_message');
	}
}

function getCollapse($id_block, $collapse)
{
	
	global $settings, $options, $user_info;

	if($collapse != 0)
		return false;

	echo'
		<span onclick="adkBlock(\''. $id_block .'\',\'image_collapse_'. $id_block .'\')" class="adk_pointer">
			'.  (($user_info['is_guest'] ? !empty($_COOKIE['adk_block_'. $id_block]) : !empty($options['adk_block_'. $id_block])) ? '<img id="image_collapse_'. $id_block .'" src="'.$settings['images_url'].'/expand.gif" alt="+" border="0" class="collapse2" />' : '<img id="image_collapse_'. $id_block .'" src="'.$settings['images_url'].'/collapse.gif" alt="-" border="0" class="collapse2" />') .'
		</span>';
}

function getBlockFirst($id_block)
{

	global $user_info, $options;

	echo'
			<div class="my_blocks" id="adk_block_'. $id_block .'" '. (($user_info['is_guest'] ? !empty($_COOKIE['adk_block_'.$id_block]) : !empty($options['adk_block_'.$id_block])) ? ' style="display: none;"' : '') .'>';
}

function getAdkportalMaintenance()
{

	global $sourcedir, $adkportal, $current_load;

	if((!empty($adkportal['adk_enable'])) && (!empty($adkportal['adk_guest_view_post'])) && ($current_load[0] == 'default')){
		require_once($sourcedir.'/AdkPortal/Adkportal.php');

		Adkportal();
	}

}

function loadJquery()
{

	global $adkportal, $boardurl;

	//Check it
	if(empty($adkportal['jquery_loaded'])){

		//Load and print jquery
		echo getJs('jquery');

		//Yeah! Jquery loaded
		$adkportal['jquery_loaded'] = true;
	}
			
}

function checkUrl($url)
{
	$id = @fopen($url,"r");
	if ($id) 
		$adkCheck = true;
	else 
		$adkCheck = false;

	if($adkCheck)
		fclose($id);
	
	return $adkCheck;
	
}

function getFile($adkfile = '')
{

	global $sourcedir, $context, $getFile, $txt;
	require_once($sourcedir .'/Subs-Package.php');

	if (empty($adkfile))
		$getFile = '';
	else {
		$checkUrl = checkUrl($adkfile);

		if (!$checkUrl)
			$getFile = '';
		else
			$getFile = fetch_web_data($adkfile);
	}
	return $getFile;
}

function getBoardsAdminDownload()
{

	global $context, $smcFunc;

	$context['downloads_boards'] = array();

	$request = $smcFunc['db_query']('','
		SELECT b.id_board, b.name AS bName, c.name AS cName 
		FROM {db_prefix}boards AS b, {db_prefix}categories AS c 
		WHERE b.id_cat = c.id_cat ORDER BY c.cat_order, b.board_order
	');

	while ($row = $smcFunc['db_fetch_assoc']($request))
		$context['downloads_boards'][$row['id_board']] = $row['cName'] . ' - ' . $row['bName'];
	
	$smcFunc['db_free_result']($request);
}

function templateIsPortal($id_template)
{

	global $smcFunc;

	$sql = $smcFunc['db_query']('','
		SELECT type 
		FROM {db_prefix}adk_blocks_template_admin
		WHERE id_template = {int:template}',
		array(
			'template' => $id_template,
		)
	);

	list($type) = $smcFunc['db_fetch_row']($sql);

	$smcFunc['db_free_result']($sql);

	return $type == 'default';
}

function adkportal_include_hooks($var = '')
{

	if($var == '')
		return;

	global $modSettings, $boarddir, $sourcedir;

	if (!empty($modSettings[$var]))
	{
		$pre_includes = explode(',', $modSettings[$var]);
		foreach ($pre_includes as $include)
		{
			$include = strtr(trim($include), array('$boarddir' => $boarddir, '$sourcedir' => $sourcedir, '$themedir' => $boarddir.'/Themes/default'));
			if (file_exists($include))
				require_once($include);
		}
	}
}

function load_membergroups_edit($id_array)
{
	global $smcFunc, $context, $txt;
	
	$id_array2 = explode(",",$id_array);
	
	$groups = loadAdkGroups('id_group <> {int:moderator} AND id_group <> {int:admin}', array('admin' => 1, 'moderator' => 3), 'id_group DESC');
	
	$context['all_checked'] = true;
	
	echo'<input style="vertical-align: middle;" type="checkbox" value="-1" name="groups_allowed[-1]" ',(in_array(-1,$id_array2) == 1) ? 'checked="checked"' : '' ,' /> '.$txt['adkmodules_guests'].'<br />';
	
	//mmm
	if(!(in_array(-1,$id_array2) == 1))
		$context['all_checked'] = false;
	
	foreach($groups AS $id_group => $g)
	{
		echo'<input style="vertical-align: middle;" type="checkbox" value="',$id_group,'" name="groups_allowed[',$id_group,']" ',in_array($id_group, $id_array2) ? 'checked="checked"' : '' ,' /> '.$g['name'].'<br />';
		
		if(!in_array($id_group,$id_array2))
			$context['all_checked'] = false;
	}

}

function analizar_type($type, $body, $truncate = false)
{

	if ($type == 'bbc')
		$body = parse_bbc($body);
	elseif ($type == 'html')
		$body = un_htmlspecialchars($body);
	elseif ($type == 'php')
	{
		$body = trim($body);
		$body = trim($body, '<?php');
		$body = trim($body, '?>');
		eval($body);
	}

	if($truncate)
		$body = Adk_Truncate($body, 100, '...', true, true);

	//Print if type is not php
	if($type != 'php')
		echo $body;
}

function allowedToViewContactPage()
{
	global $adkportal, $user_info;
	
	$to_view = false;
	
	if (!empty($adkportal['adk_groups_contact'])) {
		$x = explode(',',$adkportal['adk_groups_contact']);
		//Groups
		foreach($x AS $i => $v){
			if(in_array($v,$user_info['groups']))
				$to_view = true;
		}
		//Guest
		if($user_info['is_guest'] && in_array(-1,$x))
			$to_view = true;
		
		//Regular users
		if($user_info['groups'][0] == 0 && in_array(0,$x))
			$to_view = true;
	}
	if($user_info['is_admin'])
		$to_view = true;
	
	return $to_view;
}

function Adk_Truncate($text, $length = 100, $ending = "...", $exact = true, $considerHtml = false)
{

	//If this function is array...
    if (is_array($ending)) {
        extract($ending);
    }

    //Are considering html?...
    if ($considerHtml) {
        if (mb_strlen(preg_replace("/<.*?>/", "", $text)) <= $length) {
            return $text;
        }
        $totalLength = mb_strlen($ending);
        $openTags = array();
        $truncate = "";
        preg_match_all("/(<\/?([\w+]+)[^>]*>)?([^<>]*)/", $text, $tags, PREG_SET_ORDER);
        foreach ($tags as $tag) {
            if (!preg_match("/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s", $tag[2])) {
                if (preg_match("/<[\w]+[^>]*>/s", $tag[0])) {
                    array_unshift($openTags, $tag[2]);
                } else if (preg_match("/<\/([\w]+)[^>]*>/s", $tag[0], $closeTag)) {
                    $pos = array_search($closeTag[1], $openTags);
                    if ($pos !== false) {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }
            $truncate .= $tag[1];
 
            $contentLength = mb_strlen(preg_replace("/&amp;[0-9a-z]{2,8};|&amp;#[0-9]{1,7};|[0-9a-f]{1,6};/i", " ", $tag[3]));
            if ($contentLength + $totalLength > $length) {
                $left = $length - $totalLength;
                $entitiesLength = 0;
                if (preg_match_all("/&amp;[0-9a-z]{2,8};|&amp;#[0-9]{1,7};|[0-9a-f]{1,6};/i", $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entitiesLength <= $left) {
                            $left--;
                            $entitiesLength += mb_strlen($entity[0]);
                        } else {
                            break;
                        }
                    }
                }
 
                $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                break;
            } else {
                $truncate .= $tag[3];
                $totalLength += $contentLength;
            }
            if ($totalLength >= $length) {
                break;
            }
        }
 
    } 

    //Or not..
    else {
        if (mb_strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - strlen($ending));
        }
    }
    if (!$exact) {
        $spacepos = mb_strrpos($truncate, " ");
        if (isset($spacepos)) {
            if ($considerHtml) {
                $bits = mb_substr($truncate, $spacepos);
                preg_match_all("/<\/([a-z]+)>/", $bits, $droppedTags, PREG_SET_ORDER);
                if (!empty($droppedTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                }
            }
            $truncate = mb_substr($truncate, 0, $spacepos);
        }
    }
 
    $truncate .= $ending;
 
    if ($considerHtml) {
        foreach ($openTags as $tag) {
            $truncate .='</'.$tag.'>';
        }
    }
 
    return $truncate;
}

function Adk_formclear($toclean)
{
	global $smcFunc, $sourcedir;

	require_once($sourcedir . '/Subs-Post.php');
	$toclean = $smcFunc['htmlspecialchars']($toclean, ENT_QUOTES);
	$toclean = $smcFunc['htmltrim']($toclean, ENT_QUOTES);
	preparsecode($toclean);

	return $toclean;
}

function setActiveMenuButton(&$current_action){

	global $adkportal, $context;

	$current_action = $adkportal['adk_enable'] == 2 && empty($context['adk_stand_alone']) ? 'forum' : 'home';

	if($adkportal['adk_enable'] == 1 && (isset($_REQUEST['board']) || isset($_REQUEST['topic']) || isset($_REQUEST['page']) || isset($_REQUEST['blog'])))
		$current_action = 'forum';

	if(!empty($adkportal['enable_menu_pages']) && isset($_REQUEST['page']))
		$current_action = 'pages';
}

function adk_return_action(){

	global $adkportal, $current_load, $sourcedir;

	if(($current_load[0] == 'default') && ($adkportal['adk_enable'] == 1)){
		require_once($sourcedir . '/AdkPortal/Adkportal.php');
		return 'Adkportal';
	}
	elseif($current_load[0] == 'page'){
		require_once($sourcedir . '/AdkPortal/Adk-echomodules.php');
		return 'load_pages_adkportal';
	}
	
	return false;
}

function include_ssi_function($function_name){

	global $txt;

	$ssi_function = 'ssi_'.$function_name;

	if(function_exists($ssi_function)){

		$ssi_function();
	}
	else
		echo $txt['activate_ssi'];
}

function getComments($id_page, $limit = array()){

	//Check if page exists
	global $context, $smcFunc, $memberContext;

	$page = getPage($id_page, true, false);

	if(empty($page))
		return array();

	$query = $smcFunc['db_query']('','
		SELECT id_comment, id_member, body, date
		FROM {db_prefix}adk_pages_comments
		WHERE id_page = {int:id_page}
		ORDER BY date ASC
		'.(!empty($limit) ? 'LIMIT {int:start}, {int:end}' : ''),
		array(
			'id_page' => $id_page,
			'start' => !empty($limit) ? $limit[0] : '',
			'end' => !empty($limit) ? $limit[1] : '',
		)
	);

	$comments = array();
	$id_members = array();
	$num_replie = !empty($context['start']) ? $context['start'] : 0;
	$context['load_id_comments'] = array();

	while($row = $smcFunc['db_fetch_assoc']($query)){

		$num_replie++;

		$context['load_id_comments'][] = $row['id_comment'];

		$comments[$row['id_comment']] = array(
			'id_page' => $id_page,
			'id_comment' => $row['id_comment'],
			'body' => parse_bbc($row['body']),
			'date' => timeformat($row['date']),
			'member_info' => array(),
			'num_replie' => $num_replie,
			'is_new' => false,
		);

		$id_members[] = array('id_member' => $row['id_member'], 'id_comment' => $row['id_comment']);
	}

	$dont_load_members = array();

	//Load Memberinfo
	if(!empty($id_members)){

		foreach($id_members AS $comment){
			
			if(!in_array($comment['id_member'], $dont_load_members)){

				loadMemberData($comment['id_member'], false, 'profile');
				loadMemberContext($comment['id_member']);
				
				//Finaly, make my context string ;)
				$comments[$comment['id_comment']]['member_info'] = $memberContext[$comment['id_member']];

				$dont_load_members[] = $comment['id_member'];
			}
			else
				$comments[$comment['id_comment']]['member_info'] = $memberContext[$comment['id_member']];
		}
	}


	$smcFunc['db_free_result']($query);

	return $comments;
}
?>