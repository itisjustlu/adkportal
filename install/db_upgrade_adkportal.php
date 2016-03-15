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


$direct_install = false;

if(file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')){
	require_once(dirname(__FILE__) . '/SSI.php');
	$direct_install = true;
}
elseif (!defined('SMF'))
	die('Adk portal wasn\'t able to conect to smf');

//CreateTables
$tables = array();

//Anothers $smcFunc;
db_extend('packages');

global $smcFunc;
	
//Adk portal. Add tables
$tables = array(
	'adk_pages_comments' => array(
		'table_name' => '{db_prefix}adk_pages_comments',
		'columns' => array(
			array(
				'name' => 'id_comment',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'id_page',
				'auto' => false,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'id_member',
				'auto' => false,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'body',
				'auto' => false,
				'type' => 'text',
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'date',
				'auto' => false,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id_comment'),
				'type' => 'primary',
			),
		),
	)
);
// Create new tables, if any
foreach ($tables as $table)
	$smcFunc['db_create_table']($table['table_name'], $table['columns'], $table['indexes']);

//Add columns
$columns[] = array(
	'table_name' => '{db_prefix}members',
	'column_info' => array(
		'name' => 'adk_pages_notifications',
		'type' => 'text',
		'default' => '',
		'auto' => false,
		'unsigned' => false,
	),
	'parameters' => array(),
	'if_exists' => 'ignore',
	'error' => 'fatal',
);

$columns[] = array(
	'table_name' => 'enable_comments',
	'column_info' => array(
		'name' => 'enable_comments',
		'type' => 'int',
		'default' => '1',
		'auto' => false,
		'unsigned' => false,
		'size' => '1',
	),
	'parameters' => array(),
	'if_exists' => 'ignore',
	'error' => 'fatal',
);

//Finally Create Column
foreach($columns AS $add)
	$smcFunc['db_add_column']($add['table_name'], $add['column_info'], $add['parameters'], $add['if_exists'],$add['error']);

if($direct_install)
	echo'Done... Adk portal was installed correctly. Enjoy it!';

?>
