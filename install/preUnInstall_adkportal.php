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
	
	db_extend('packages');

	global $settings, $smcFunc, $boarddir;

	//Tablas a Duplicar
	$duplicate = array(
		'advanced_images',
		'blocks',
		'down_attachs',
		'down_cat',
		'down_file',
		'down_permissions',
		'icons',
		'news',
		'pages',
		'shoutbox'
	);

	//Armando estructuras
	foreach($duplicate AS $table){
		$smcFunc['db_query']('','CREATE TABLE {db_prefix}backup_'.$table.' SELECT * FROM {db_prefix}adk_'.$table);
	}

	//Directorios a Duplicar
	$directorios = array(
		'Adk-Downloads' => 'downloads',
		'adkportal/images' => 'images',
	);

	//Duplicamos los directorios y mostramos que las entradas fueron creadas con exito
	foreach($directorios AS $i => $v){
		full_copy_pre($boarddir.'/'.$i, $boarddir.'/backupPortal'.$v);
	}

	$directoriosblocks = array(
		'blocks' => 'adkportal/blocks',
	);
	foreach($directoriosblocks AS $i => $v){
		delete_adkFolder_pre($boarddir.'/'.$i);
	}

function full_copy_pre( $source, $target ) { 
	if ( is_dir( $source ) ) { 
		@mkdir( $target ); 
		$d = dir( $source ); 

		while ( FALSE !== ( $entry = $d->read() ) ) {
			if ( $entry == '.' || $entry == '..' ) { 
				continue; 
			} 

			$Entry = $source . '/' . $entry; 

			if ( is_dir( $Entry ) ) { 
				full_copy_pre( $Entry, $target . '/' . $entry ); 
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

function delete_adkFolder_pre($source)
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

if($direct_install)
	echo'Done... Adk portal was installed correctly. Enjoy it!';

?>