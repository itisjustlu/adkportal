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

	$direct_install = false;
	
	if(file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')){
		require_once(dirname(__FILE__) . '/SSI.php');
		$direct_install = true;
	}
	elseif (!defined('SMF'))
		die('Adk portal wasn\'t able to conect to smf');

//Anothers $smcFunc;
db_extend('packages');

	global $settings, $smcFunc, $boarddir;

	//Duplicando tablas tal cual.
	$tables = array(
		'advanced_images',
		'down_attachs',
		'icons',
		'news',
	);

	//Empezemos con advancedimages, es simplemente copiarla tal cual
	foreach($tables AS $v){
		//Vaciamos la tabla
		truncate_adkTable('adk_'.$v);
		//Insertamos los registros del backupPrevio
		$smcFunc['db_query']('','INSERT INTO {db_prefix}adk_'.$v.' SELECT * FROM {db_prefix}backup_'.$v);
	}

	//Restauramos la tabla Blocks, pero empezemos por vaciarla
	truncate_adkTable('adk_blocks');
	truncate_adkTable('adk_blocks_template');

	$sql = $smcFunc['db_query']('','
		SELECT id, name, echo, columna, activate, orden, img, type, empty_body, empty_title, empty_collapse, other_style, permissions 
		FROM {db_prefix}backup_blocks
		WHERE echo != "login_logout.php"
	');

	$the_array = array();

	while($row = $smcFunc['db_fetch_assoc']($sql)){
		$smcFunc['db_insert'](
			'insert',
			'{db_prefix}adk_blocks',
			array(
				'id' => 'int', 'name' => 'text', 'echo' => 'text', 'img' => 'text', 'type' => 'text', 'empty_body' => 'text', 'empty_title' => 'text', 'empty_collapse' => 'text', 'other_style' => 'text', 'permissions' => 'text',
			),
			array(
				$row['id'], $row['name'], $row['echo'], $row['img'], $row['type'], $row['empty_body'], $row['empty_title'], $row['empty_collapse'], $row['other_style'], $row['permissions'],
			),
			array('id')
		);

		if($row['activate'] == 1){
			$smcFunc['db_insert'](
				'insert',
				'{db_prefix}adk_blocks_template',
				array(
					'id_template' => 'int', 'id_block' => 'int', 'orden' => 'int', 'columna' => 'int',
				),
				array(
					1, $row['id'], $row['orden'], $row['columna'],
				),
				array('id_template')
			);
		}
	}

	$smcFunc['db_free_result']($sql);

	//Es momento de duplicar la tabla adk_down_cat
	truncate_adkTable('adk_down_cat');

	$smcFunc['db_query']('','INSERT INTO {db_prefix}adk_down_cat( id_cat, title, description, roworder, image, id_board, id_parent, total, locktopic, sortby, orderby )
	SELECT id_cat, title, description, roworder, image, id_board, id_parent, total, locktopic, sortby, orderby FROM {db_prefix}backup_down_cat');

	//Momento de adk_down_file
	truncate_adkTable('adk_down_file');

	$smcFunc['db_query']('','INSERT INTO {db_prefix}adk_down_file( id_file, id_member, date, title, description, views, totaldownloads, lastdownload, id_cat, main_image, approved, id_topic )
	SELECT id_file, id_member, date, title, description, views, totaldownloads, lastdownload, id_cat, main_image, approved, id_topic FROM {db_prefix}backup_down_file');

	//Momento de adk_pages
	truncate_adkTable('adk_pages');

	$smcFunc['db_query']('','INSERT INTO {db_prefix}adk_pages( id_page, urltext, titlepage, body, views, grupos_permitidos, type, winbg, cattitlebg )
	SELECT id_page, urltext, titlepage, body, views, grupos_permitidos, type, winbg, cattitlebg FROM {db_prefix}backup_pages');

	//Momento de adk_shoutbox
	truncate_adkTable('adk_shoutbox');

	$smcFunc['db_query']('','INSERT INTO {db_prefix}adk_shoutbox( id, date, user, message )
	SELECT id, date, user, message FROM {db_prefix}backup_shoutbox');

	//Borremos las tablas backup
	$b = array(
		'advanced_images', 'blocks', 'down_attachs', 'down_cat', 'down_file', 'down_permissions', 'icons', 'news', 'pages', 'shoutbox'
	);
	foreach($b AS $t)
		delete_adkTable('backup_'.$t);
	//Directorios a Duplicar
	$directorios = array(
		'backupPortaldownloads' => 'Adk-Downloads',
		'backupPortalimages' => 'adkportal/images',
	);
	//Duplicamos los directorios y mostramos que las entradas fueron creadas con exito
	foreach($directorios AS $i => $v){
		full_copy_post($boarddir.'/'.$i, $boarddir.'/'.$v);
		delete_adkFolder($boarddir.'/'.$i);
	}

function full_copy_post( $source, $target ) 
{ 
	if ( is_dir( $source ) ) { 
		@mkdir( $target ); 
		$d = dir( $source ); 
		while ( FALSE !== ( $entry = $d->read() ) ) {
			if ( $entry == '.' || $entry == '..' ) { 
				continue; 
			} 
			$Entry = $source . '/' . $entry; 
			if ( is_dir( $Entry ) ) { 
				full_copy_post( $Entry, $target . '/' . $entry ); 
				continue; 
			} 
			copy( $Entry, $target . '/' . $entry ); 
		}
		$d->close(); 
	}
	else { 
		copy( $source, $target ); 
	} 
}

//
function delete_adkFolder($source)
{

	if(is_dir($source)) {
		$d = dir($source);
		while(FALSE !== ($entry = $d->read())){
			if ( $entry == '.' || $entry == '..' ) { 
				continue; 
			} 
			$Entry = $source . '/' . $entry; 
			if ( is_dir( $Entry ) ) { 
				delete_adkFolder($Entry); 
				continue; 
			} 
			else
				@unlink($Entry); 
		}
		$d->close();
		//Remove this dir
		@rmdir($source);
	}
	else
		return false;
}

//Funcion para truncar la tabla mas rápido
function truncate_adkTable($table_name = '')
{
	global $smcFunc;

	if(empty($table_name))
		return false;
	$smcFunc['db_query']('','TRUNCATE {db_prefix}'.$table_name);
}

//Funcion parar borrar tabla más rápido
function delete_adkTable($table_name = '')
{
	global $smcFunc;

	if(empty($table_name))
		return false;
	$smcFunc['db_drop_table']('{db_prefix}'.$table_name, array(), 'ignore');
}

if($direct_install)
	echo'Done... Adk portal was installed correctly. Enjoy it!';

?>