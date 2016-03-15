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
	
$tables = array(
	//SMF_adk_settings
	'adk_settings' => array(
		'table_name' => '{db_prefix}adk_settings',
		'columns' => array(
			array(
				'name' => 'variable',
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'value',
				'type' => 'text',
				'null' => false,
				'unsigned' => true,
			),
		),
		'indexes' => array(
			array(
				'columns' => array('variable'),
				'type' => 'primary',
			),
		),
	),
	//SMF_adk_shoutbox
	'adk_shoutbox' => array(
		'table_name' => '{db_prefix}adk_shoutbox',
		'columns' => array(
			array(
				'name' => 'id',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'date',
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'user',
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'message',
				'type' => 'text',
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'id_member',
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
				'default' => 0,
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id'),
				'type' => 'primary',
			),
		),
	),
	//SMF_adk_news
	'adk_news' => array(
		'table_name' => '{db_prefix}adk_news',
		'columns' => array(
			array(
				'name' => 'id',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'titlepage',
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'new',
				'type' => 'text',
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'autor',
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'time',
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id'),
				'type' => 'primary',
			),
		),
	),
	//SMF_adk_icons
	'adk_icons' => array(
		'table_name' => '{db_prefix}adk_icons',
		'columns' => array(
			array(
				'name' => 'id_icon',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'icon',
				'type' => 'varchar',
				'null' => false,
				'size' => 255,
				'unsigned' => true,
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id_icon'),
				'type' => 'primary',
			),
		),
	),
	//SMF_adk_advanced_images
	'adk_advanced_images' => array(
		'table_name' => '{db_prefix}adk_advanced_images',
		'columns' => array(
			array(
				'name' => 'id',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'image',
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'url',
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id'),
				'type' => 'primary',
			),
		),
	),
	//SMF_adk_down_attachs
	'adk_down_attachs' => array(
		'table_name' => '{db_prefix}adk_down_attachs',
		'columns' => array(
			array(
				'name' => 'id_attach',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'id_file',
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'filename',
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'filesize',
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'orginalfilename',
				'type' => 'text',
				'null' => false,
				'unsigned' => true,
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id_attach'),
				'type' => 'primary',
			),
		),
	),
	//SMF_adk_blocks
	'adk_blocks' => array(
		'table_name' => '{db_prefix}adk_blocks',
		'columns' => array(
			array(
				'name' => 'id',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'name',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'echo',
				'auto' => false,
				'type' => 'text',
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'img',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'type',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'empty_body',
				'auto' => false,
				'type' => 'int',
				'size' => 2,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'empty_title',
				'auto' => false,
				'type' => 'int',
				'size' => 2,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'empty_collapse',
				'auto' => false,
				'type' => 'int',
				'size' => 2,
				'null' => false,
				'unsigned' => true,
			),
			
			array(
				'name' => 'other_style',
				'auto' => false,
				'type' => 'int',
				'size' => 2,
				'null' => false,
				'unsigned' => true,
				'default' => 0,
			),
			array(
				'name' => 'permissions',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
				'default' => '',
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id'),
				'type' => 'primary',
			),
		),
	),
	//Blocks Template
	'adk_blocks_template' => array(
		'table_name' => '{db_prefix}adk_blocks_template',
		'columns' => array(
			array(
				'name' => 'id_template',
				'auto' => false,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'id_block',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'columna',
				'auto' => false,
				'type' => 'int',
				'size' => 3,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'orden',
				'auto' => false,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
		),
		'indexes' => array(),
	),
	'adk_blocks_template_admin' => array(
		'table_name' => '{db_prefix}adk_blocks_template_admin',
		'columns' => array(
			array(
				'name' => 'id_template',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'type',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'place',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'enabled',
				'auto' => false,
				'type' => 'int',
				'size' => 1,
				'null' => false,
				'unsigned' => true,
				'default' => '1',
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id_template'),
				'type' => 'primary',
			),
		),
	),
	//SMF_adk_pages
	'adk_pages' => array(
		'table_name' => '{db_prefix}adk_pages',
		'columns' => array(
			array(
				'name' => 'id_page',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'urltext',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'titlepage',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
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
				'name' => 'views',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'grupos_permitidos',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'type',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'winbg',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'cattitlebg',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'enable_comments',
				'type' => 'int',
				'default' => '1',
				'auto' => false,
				'unsigned' => false,
				'size' => '1',
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id_page'),
				'type' => 'primary',
			),
		),
	),
	//SMF_adk_down_file
	'adk_down_file' => array(
		'table_name' => '{db_prefix}adk_down_file',
		'columns' => array(
			array(
				'name' => 'id_file',
				'auto' => true,
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
				'name' => 'date',
				'auto' => false,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'title',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'description',
				'auto' => false,
				'type' => 'text',
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'views',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'totaldownloads',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'lastdownload',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'id_cat',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'main_image',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'approved',
				'auto' => false,
				'type' => 'int',
				'size' => 1,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'id_topic',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'short_desc',
				'type' => 'varchar',
				'size' => 255,
				'default' => '',
				'auto' => false,
				'unsigned' => false,
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id_file'),
				'type' => 'primary',
			),
		),
	),
	//SMF_adk_down_cat
	'adk_down_cat' => array(
		'table_name' => '{db_prefix}adk_down_cat',
		'columns' => array(
			array(
				'name' => 'id_cat',
				'auto' => true,
				'type' => 'int',
				'size' => 10,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'title',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'description',
				'auto' => false,
				'type' => 'text',
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'roworder',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'image',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'id_board',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'id_parent',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'total',
				'auto' => false,
				'type' => 'int',
				'size' => 20,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'locktopic',
				'auto' => false,
				'type' => 'int',
				'size' => 1,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'sortby',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'orderby',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'groups_can_view',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'groups_can_add',
				'auto' => false,
				'type' => 'varchar',
				'size' => 255,
				'null' => false,
				'unsigned' => true,
			),
			array(
				'name' => 'error',
				'auto' => false,
				'type' => 'int',
				'size' => 1,
				'null' => false,
				'unsigned' => true,
				'default' => 0,
			),
		),
		'indexes' => array(
			array(
				'columns' => array('id_cat'),
				'type' => 'primary',
			),
		),
	),
	//SMF adk_pages
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

//INSERT IGNORE INTO SMF_adk_settings
$adk_settings = array(
	'adk_enable' => 1,
	'wleft' => '190px',
	'wright' => '190px',
	'adk_news' => 5,
	'top_poster' => 5,
	'ultimos_mensajes' => 5,
	'auto_news_limit_body' => 500,
	'auto_news_limit_topics' => 5,
	'auto_news_id_boards' => 1,
	'auto_news_size_img' => 350,
	'title_in_blocks' => 1,
	'enable_img_blocks' =>  1,
	'enable_pages_seo' => 0,
	'enable_download_seo' => 0,
	'path_seo' => '',
	'change_title' => '',
	'download_enable' => '0',
	'download_max_filesize' => '5000000',
	'download_images_size' => '102400',
	'download_set_files_per_page' => '20',
	'download_enable_sendpmApprove' => '0',
	'download_sendpm_body' => '',
	'download_sendpm_userId' => '',
	'download_max_attach_download' => '1',
	'adv_top_image_limit' => 10,
	'shout_title' => 'Shoutbox',
	'shout_allowed_groups' => 1,
	'shout_allowed_groups_view' => 1,
	'enable_watermark' => 0,
	'adkcolor_border' => '#99ABBF',
	'adkcolor_fondo' => '#FFFFFF',
	'adkcolor_fonttitle' => '#FFFFFF',
	'adkcolor_font' => '#444444',
	'adkcolor_link' => '#334466',
	'adkcolor_attach' => '#CEE0F4',
);

$replace_array = array();

foreach ($adk_settings as $variable => $value)
	$replace_array[] = array($variable, $value);

//Finally insert
$smcFunc['db_insert']('ignore', '{db_prefix}adk_settings', array('variable' => 'string-255', 'value' => 'string-65534'), $replace_array, array('variable'));			
	
//Alter smf_topics && smf_members
$columns[] = array(
	'table_name' => '{db_prefix}topics',
	'column_info' => array(
		'name' => 'id_new',
		'type' => 'int',
		'size' => 10,
		'default' => 0,
		'auto' => false,
		'unsigned' => false,
	),
	'parameters' => array(),
	'if_exists' => 'ignore',
	'error' => 'fatal',
);

$columns[] = array(
	'table_name' => '{db_prefix}members',
	'column_info' => array(
		'name' => 'adk_notes',
		'type' => 'varchar',
		'size' => 150,
		'default' => '',
		'auto' => false,
		'unsigned' => false,
	),
	'parameters' => array(),
	'if_exists' => 'ignore',
	'error' => 'fatal',
);

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

//Finally Create Column
foreach($columns AS $add)
	$smcFunc['db_add_column']($add['table_name'], $add['column_info'], $add['parameters'], $add['if_exists'],$add['error']);

global $language;

//Possible languages.... yeah we're a site in spanish :)
$español = array('spanish_es','spanish_latin','spanish_es-utf8','spanish_latin-utf8');

if(in_array($language,$español)){
	$MainMenu = 'Menú Principal';
	$PersonalMenu = 'Menú Personal ';
	$Usersonline = 'Usuarios en Linea';
	$TopPosters = 'Top Publicaciones';
	$LastTopics = 'Últimos Temas';
	$News = 'Noticias';
	$AutoNews = 'Noticias Automaticas';
	$Stats = 'Estadisticas';
	$RandomImage = 'Imagen Aleatoria';
	$Shoutbox = 'Shoutbox';
	$Reminders = 'Recordatorios';
	$Calendar = 'Calendario';
	$NewsandNewsLetter = 'Noticias y Boletines';

	$title = 'Adk Portal a sido instalado';
	$text = $smcFunc['htmlspecialchars']
	('
[center][img width=16 height=16]http://www.smfpersonal.net/Adkmods/title.png[/img] [size=18pt][font=tahoma][b][color=#0489B1]Adk Portal 3.0[/color][/b][/font][/size] [img width=16 height=16]http://www.smfpersonal.net/Adkmods/title.png[/img][/center]
[hr]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img][b]Fundador:[/b]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/lucasruroken-u1.html]Juarez, Lucas Javier (Lucas-ruroken)[/url]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img][b]Administrador del proyecto:[/b]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/heracles-u259.html]Clavijo, Pablo (^HeRaCLeS^)[/url]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img][b]Administrador del sitio:[/b]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/enik-u417.html]Alfaro Garcia, Marco Antonio (Enik)[/url]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img][b]Desarrollo:[/b]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/lucasruroken-u1.html]Juarez, Lucas Javier (Lucas-ruroken)[/url]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/heracles-u259.html]Clavijo, Pablo (^HeRaCLeS^)[/url]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img] [url=http://www.smfpersonal.net/about.html]Nuestro staff[/url]

[hr]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/star.png[/img] Gracias por haber instalado Adk Portal
[img width=16 height=16]http://www.smfpersonal.net/Adkmods/star.png[/img] Esperemos que disfrute del portal y sea de su agrado'
	);
}
else{
	$MainMenu = 'Main Menu';
	$PersonalMenu = 'Personal Menu';
	$Usersonline = 'Users online';
	$TopPosters = 'Top Posters';
	$LastTopics = 'Last Topics';
	$News = 'News';
	$AutoNews = 'Auto News';
	$Stats = 'Stats';
	$RandomImage = 'Random Image';
	$Shoutbox = 'Shoutbox';
	$Reminders = 'Reminders';
	$Calendar = 'Calendar';
	$NewsandNewsLetter = 'News and NewsLetter';

	$title = 'Adk Portal was installed';
	$text = $smcFunc['htmlspecialchars']
	('
[center][img width=16 height=16]http://www.smfpersonal.net/Adkmods/title.png[/img] [size=18pt][font=tahoma][b][color=#0489B1]Adk Portal 3.0[/color][/b][/font][/size] [img width=16 height=16]http://www.smfpersonal.net/Adkmods/title.png[/img][/center]
[hr]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img][b]Founder:[/b]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/lucasruroken-u1.html]Juarez, Lucas Javier (Lucas-ruroken)[/url]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img][b]Project Manager:[/b]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/heracles-u259.html]Clavijo, Pablo (^HeRaCLeS^)[/url]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img][b]Site Manager:[/b]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/enik-u417.html]Alfaro Garcia, Marco Antonio (Enik)[/url]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img][b]Developers:[/b]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/lucasruroken-u1.html]Juarez, Lucas Javier (Lucas-ruroken)[/url]
[img width=40 height=15]http://www.smfpersonal.net/Adkmods/tree.png[/img] [url=http://www.smfpersonal.net/profiles/heracles-u259.html]Clavijo, Pablo (^HeRaCLeS^)[/url]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/user.png[/img] [url=http://www.smfpersonal.net/about.html]Our staff[/url]

[hr]

[img width=16 height=16]http://www.smfpersonal.net/Adkmods/star.png[/img] Thanks for having installed Adk Portal
[img width=16 height=16]http://www.smfpersonal.net/Adkmods/star.png[/img] Hope you enjoy Adkportal and you will enjoy it'
	);
}

//Add new blocks
$adk_blocks = array(
	'main_menu' => array(
		$MainMenu,
		'menuprincipal.php',
		'page.png',
		'include',
	),
	'personal_menu' => array(
		$PersonalMenu,
		'menupersonal.php',
		'heart.png',
		'include',
	),
	'users_online' => array(
		$Usersonline,
		'whois.php',
		'online.png',
		'include',
	),
	'top_posters' => array(
		$TopPosters,
		'topposter10.php',
		'top.png',
		'include',
	),
	'last_topics' => array(
		$LastTopics,
		'ultimosmensajes.php',
		'rosette.png',
		'include',
	),
	'news_adk' => array(
		$News,
		'newsadk.php',
		'feed.png',
		'include',
	),
	'auto_news' => array(
		$AutoNews,
		'aportes_automaticos.php',
		'plugin.png',
		'include',
	),
	'stats' => array(
		$Stats,
		'estadisticas.php',
		'stats.png',
		'include',
	),
	'random_image' => array(
		$RandomImage,
		'random_image.php',
		'disk.png',
		'include',
	),
	'shoutbox' => array(
		$Shoutbox,
		'adk_shoutbox.php',
		'plugin.png',
		'include',
	),
	'LoadRemembers' => array(
		$Reminders,
		'LoadRemembers.php',
		'disk.png',
		'include',
	),
	'Calendar' => array(
		$Calendar,
		$smcFunc['htmlspecialchars']('<?php ShowMyCalendar(); ?>'),
		'disk.png',
		'php',
	),
	'news_and_news' => array(
		$NewsandNewsLetter,
		$smcFunc['htmlspecialchars']('<?php adk_bienvenidos(); ?>'),
		'feed2.png',
		'php',
	),
);

//Delete phpblocks
$php_blocks = array('newsadk.php', 'menuprincipal.php','menupersonal.php','whois.php','topposter10.php','ultimosmensajes.php','aportes_automaticos.php','estadisticas.php','random_image.php','adk_shoutbox.php','LoadRemembers.php');
$smcFunc['db_query']('','DELETE FROM {db_prefix}adk_blocks WHERE echo IN ({array_string:block_name})', array('block_name' => $php_blocks));

$rest_blocks = array('Calendar','News and NewsLetter');
$smcFunc['db_query']('','DELETE FROM {db_prefix}adk_blocks WHERE name IN ({array_string:block_name}) AND type = {text:php}', array('block_name' => $rest_blocks, 'php' => 'php'));

$adk_columns_blocks = array(
	'name' => 'text',
	'echo' => 'text',
	'img' => 'text',
	'type' => 'text',
);
//Finally insert in adk_blocks
$smcFunc['db_insert'](
	//method
	'ignore', 
	//Table name
	'{db_prefix}adk_blocks', 
	//Columns
	$adk_columns_blocks, 
	//The insert
	$adk_blocks, 
	//Ah?
	array('id')
);

//Delete previos default template
$sql = $smcFunc['db_query']('','SELECT id_template FROM {db_prefix}adk_blocks_template_admin WHERE type = {text:default}', array('default' => 'default'));
if($smcFunc['db_num_rows']($sql) == 0)
	$smcFunc['db_insert']('ignore', '{db_prefix}adk_blocks_template_admin', array('type' => 'text', 'enabled' => 'int'), array('default' => array('default', 1)), array('id_template'));

//Reset Default Template
$smcFunc['db_query']('','DELETE FROM {db_prefix}adk_blocks_template');

$sql = $smcFunc['db_query']('','SELECT id, name FROM {db_prefix}adk_blocks');

$ids = array();
while($row = $smcFunc['db_fetch_assoc']($sql))
	$ids[$row['name']] = $row['id'];

$smcFunc['db_free_result']($sql);

if(!empty($ids)){
	$adk_blocks_template = array(
		'main_menu' => array(1, $ids[$MainMenu], 1, 1),
		'personal_menu' => array(1, $ids[$PersonalMenu], 1,2),
		'top_posters' => array(1, $ids[$TopPosters], 1, 3),
		'last_topics' => array(1, $ids[$LastTopics], 2, 1),
		'news' => array(1, $ids[$News], 2, 2),
		'auto_news' => array(1, $ids[$AutoNews], 2, 3),
		'reminders' => array(1, $ids[$Reminders], 3, 1),
		'stats' => array(1, $ids[$Stats], 3, 2),
		'users_online' => array(1, $ids[$Usersonline], 3, 3),
	);

	$adk_blocks_c = array('id_template' => 'int','id_block' => 'int','columna' => 'int','orden' => 'int',);
	$smcFunc['db_insert']('ignore', '{db_prefix}adk_blocks_template', $adk_blocks_c, $adk_blocks_template, array('id_template'));
}

// -.-
$adk_icon_new = array(
	'disk.png',
	'feed.png',
	'feed2.png',
	'folder.png',
	'heart.png',
	'help2.png',
	'money_dollar.png',
	'online.png',
	'package.png',
	'page.png',
	'plugin.png',
	'rosette.png',
	'sport_raquet.png',
	'sport_soccer.png',
	'sport_tennis.png',
	'stats.png',
	'table_save.png',
	'tag_purple.png',
	'top.png',
	'welcome.png',
);

//DELETE PREVIOUS ICONS
$smcFunc['db_query']('','DELETE FROM {db_prefix}adk_icons WHERE icon IN ({array_string:icons})',array('icons' => $adk_icon_new));

$adk_icons = array();
foreach($adk_icon_new AS $icon) 
	$adk_icons[] = array($icon);

//Insert into adk_icons
$smcFunc['db_insert'](
	//method
	'ignore', 
	//Table name
	'{db_prefix}adk_icons', 
	//Columns
	array('icon' => 'text'), 
	//The insert
	$adk_icons, 
	//Ah?
	array('id')
);


//Add a new news :P
$the_array_info = array(
	'titlepage' => 'text',
	'new' => 'text',
	'autor' => 'text',
	'time' => 'int',
);
$the_array_insert = array(
	$title,
	$text,
	'Adk Team',
	time(),
);

$smcFunc['db_insert']('insert',
	'{db_prefix}adk_news',
	//Load The Array Info
	$the_array_info,
	//Insert Now;)
	$the_array_insert,
	array('id')
);

if($direct_install)
	echo'Done... Adk portal was installed correctly. Enjoy it!';

?>