<?php
/**
 * Adk Portal
 * Version: 3.0
 * Official support: http://www.smfpersonal.net
 * Author: Adk Team
 * Copyright: 2009 - 2014 � SMFPersonal
 * Developers:
 * 		Juarez, Lucas Javier
 * 		Clavijo, Pablo
 *
 * version smf 2.0*
 */

	global $scripturl;

/* Set the admin sections*/     /* Main */
$txt['adkmod_adkportal'] = 'Adk Portal';
$txt['adkmod_news'] = 'Noticias';
$txt['adkmod_settings'] = 'Opciones';
$txt['adkmod_icons'] = '�conos';
$txt['adkmod_stand'] = 'Configuraci�n Modo Independiente';
$txt['adkmod_smf_personal'] = 'Ayuda sobre Adk portal';

//Admin Blocks
$txt['adkmod_block_templates'] = 'Plantillas';
$txt['adkmod_block_manage'] = 'Administrar Bloques';
$txt['adkmod_block_title'] = 'Bloques';
$txt['adkmod_block_settings'] = 'Configurar bloques';
$txt['adkmod_block_add'] = 'Agregar Bloque';
$txt['adkmod_block_add_news'] = 'Agregar noticia';
$txt['adkmod_block_upload'] = 'Subir Bloque';
$txt['adkmod_block_download'] = 'Descargar bloques';

//Admin Modules
$txt['adkmod_modules_manage'] = 'Administrar M�dulos';
$txt['adkmod_modules_intro'] = 'Inicio';
$txt['adkmod_modules_pages'] = 'P�ginas';
$txt['adkmod_modules_contacto'] = 'Cont�ctenos';
$txt['adkmod_modules_images'] = 'Im�genes avanzadas';
$txt['adkmod_modules_manage_images'] = 'Administrar im�genes';

//Admin Downloads
$txt['adkmod_eds_manage'] = 'Administrar Descargas';
$txt['adkmod_eds_settings'] = 'Opciones';
$txt['adkmod_eds_add'] = 'Agregar categor�a';
$txt['adkmod_eds_categories'] = 'Categorias';
$txt['adkmod_eds_approve'] = 'Aprobar descargas';

//Admin Seo
$txt['adkmod_seo_manage'] = 'Administrar Seo';
$txt['adkmod_seo_htaccess'] = 'Crear Htaccess';
$txt['adkmod_seo_robots'] = 'Crear Robots.txt';

//Permissions
$txt['permissiongroup_adkportal'] = 'Adk Portal';
$txt['permissiongroup_simple_adkportal'] = 'Adk Portal';
$txt['permissionname_adk_portal'] = 'Administrar Adkportal';
$txt['permissionhelp_adk_portal'] = 'Permite al usuario administrar adk portal y sus m�dulos';
$txt['permissiongroup_adkdownloads'] = 'Sistema de Descargas (Adk portal)';
$txt['permissiongroup_simple_adkdownloads'] = 'Sistema de Descargas (Adk Portal)';
$txt['cannot_adk_downloads_add'] = 'Usted no tiene permisos para agregar descargas.';
$txt['permissionname_adk_downloads_autoapprove'] = 'Aprobar autom�ticamente sus descargas';
$txt['permissionhelp_adk_downloads_autoapprove'] = 'Las descargas del usuario no necesitan ser vistas por un administrador.';
$txt['permissionname_adk_downloads_manage'] = 'Administrar sistema de descargas';
$txt['permissionhelp_adk_downloads_manage'] = 'Permite al usuario administrar el sistema de descargas.';
$txt['cannot_adk_downloads_manage'] = 'Usted no tiene permisos para administrar el centro de descargas.';
$txt['cannot_adk_portal'] = 'Usted no tiene permissos para administrar Adk portal.';

//Set the portal txt :P
$txt['adkmod_portal'] = 'Portal';

//Set some strings
$txt['adkmod_expand'] = 'Expandir columna';
$txt['adkmod_collapse'] = 'Colapsar columna';

//Buttons
$txt['adkmod_downloads'] = 'Descargas';
$txt['adkmod_forum'] = 'Foro';
$txt['adkmod_pages'] = 'P�ginas';

//Javascript blocks
$txt['adkmod_shoutbox_sending'] = 'Enviando';
$txt['adkmod_shoutbox_all_field'] = 'Por favor complete todos los campos';
$txt['adkmod_shoutbox_shout_it'] = 'Enviar!!';

//Blocks
$txt['adkmod_block_no_post_see'] = 'No hay mensajes que usted pueda ver';
$txt['adkmod_block_no_read'] = 'Mensajes sin leer';
$txt['adkmod_block_alls'] = 'Todos';
$txt['adkmod_block_last'] = '�ltima Visita';
$txt['adkmod_block_new_replies'] = 'Solo Respuestas';
$txt['adkmod_block_hi'] = 'Hola';
$txt['adkmod_block_guest'] = 'Visitante';
$txt['adkmod_block_none_images'] = 'No hay im�genes disponibles';
$txt['adkmod_block_who_title'] = '�Qui�n est� en l�nea?';
$txt['adkmod_block_remove_message'] = '�Seguro de realizar esta acci�n? Esta operaci�n es irreversible.';
$txt['adkmod_block_borrar'] = 'Eliminar';
$txt['adkmod_block_editar'] = 'Editar';
$txt['adkmod_block_added_portal'] = 'Agregado al portal';
$txt['adkmod_block_posts'] = 'Mensaje';
$txt['adkmod_block_last_updated'] = '�ltima actualizaci�n';
$txt['adkmod_block_shout_now_allowed'] = 'Usted no tiene permisos para ver el shoutbox.';
$txt['adkmod_block_add_this_topic'] = 'Agregar al portal';
$txt['adkmod_block_remove_this_topic'] = 'Eliminar del portal';
$txt['adkmod_block_open_smileys'] = 'Abrir smileys';
$txt['adkmod_block_karma'] = 'Karma';
$txt['adkmod_block_reminder'] = 'Agregue un recordatorio, para no olvidar que deseaba hacer :)';
$txt['adkmod_block_readmore'] = 'Leer m�s';
$txt['adkmod_block_notext'] = '<div style="text-align: center; margin-bottom: 5px;">No hay mensajes en el Shoutbox<br />Se el primero en comentar!</div>';
$txt['adkmod_block_nopost'] = '<div style="text-align: center;"><strong>No hay mensajes en el Shoutbox</strong>
<br /><br /><strong>[</strong> <a href="'.$scripturl.'/">Ir al �ndice</a> <strong>]</strong> 
</div>';
$txt['activate_ssi'] = 'Por favor incluye el archivo SSI desde la adminstraci�n';

//Who's integration
$txt['who_adk_portal'] = 'Viendo el portal del sitio.';
$txt['who_adk_forum'] = 'Viendo el �ndice del foro.';
$txt['who_adk_credits'] = 'Viendo los cr�ditos de Adkportal.';
$txt['who_adk_contact'] = 'Viendo la secci�n de contactos.';
$txt['who_adk_down_cat'] = 'Viendo una categor�a de la secci�n descargas.';
$txt['who_adk_down'] = 'Viendo una categoria de la seccion descargas';
$txt['who_adk_down_profile'] = 'Viendo un perfil de descargas';
$txt['who_adk_down_stats'] = 'Viendo las estadisticas de las descargas';
$txt['who_adk_down_search'] = 'Realizando una busqueda en las descargas';
$txt['who_adk_down_search2'] = 'Viendo los resultados de una busqueda en las descargas';
$txt['who_adk_down_add'] = 'Agregando una nueva descarga';
$txt['who_adk_down_edit'] = 'Editando una nueva descarga';
$txt['who_adk_down_system'] = 'Viendo la seccion de descargas';
$txt['who_adk_page'] = 'Viendo una p�gina del sitio';
$txt['who_adk_shoutbox'] = 'Viendo el Shoutbox';
$txt['who_adk_index_pages'] = 'Viendo el �ndice de p�ginas';

$txt['adkmod_Adkportaldonate'] = '<div style="text-align:center;" class="smalltext"><a href="http://www.smfpersonal.net/index.php?action=about;sa=contritube-spanish" target="blank">Contribuir con el proyecto</a></div>';

//Fatal errors
$txt['adkfatal_wrong_icon_id'] = 'El �cono que est� intentando eliminar no existe.';
$txt['adkfatal_not_select_image_icon'] = 'La imagen que est� intentando subir es inv�lida.';
$txt['adkfatal_page_not_exist'] = 'La p�gina que esta intentando visualizar no existe';
$txt['adkfatal_shout_now_allowed'] = 'Usted no tiene permisos para ver este m�dulo.';
$txt['adkfatal_adding_news_false'] = 'El tema que esta intentando agregar no existe.';
$txt['adkfatal_form_error'] = 'Se ha producido un error en el formulario. Por favor complete todos los campos';
$txt['adkfatal_empty_title'] = 'Se ha producido un error. El campo t�tulo es requerido.';
$txt['adkfatal_empty_body'] = 'Se ha producido un error. El cuerpo del mensaje es requerido.';
$txt['adkfatal_adk_not_page_id'] = 'La p�gina que esta intentando visualizar no existe.';
$txt['adkfatal_require_url'] = 'Por favor inserte un url';
$txt['adkfatal_require_image'] = 'Se ha producido un error. Usted necesita colocar una imagen por url o subirla desde su PC.';
$txt['adkfatal_this_module_doesnt_exist'] = 'Este m�dulo no esta activado.';
$txt['adkfatal_require_catid'] = 'La categor�a que est� intentando ver no existe.';
$txt['adkfatal_empty_id_profile'] = 'El perfil que est� intentando ver no existe.';
$txt['adkfatal_user_not_have_nadanose'] = 'Este usuario no tiene descargas.';
$txt['adkfatal_please_select_cat'] = 'Usted esta intentando a�adir una descarga desde un lugar incorrecto.';
$txt['adkfatal_this_category_not_exist'] = 'Esta categor�a no existe.';
$txt['adkfatal_not_writable_dir'] = 'El directorio no tiene permisos. Por favor contacte con un administrador.';
$txt['adkfatal_please_add_a_title'] = 'Usted necesita agregar un t�tulo a la descarga.';
$txt['adkfatal_please_add_a_body'] = 'Usted necesita completar el campo de descripci�n.';
$txt['adkfatal_empty_attach'] = 'Usted necesita agregar archivos a la descarga.';
$txt['adkfatal_require_id_file'] = 'La descarga que est� intentando editar no existe.';
$txt['adkfatal_not_permission'] = 'Usted no tiene permisos para realizar esta acci�n.';
$txt['adkfatal_big_size'] = 'Uno de los adjuntos supera el tama�o m�ximo.';
$txt['adkfatal_this_download_not_exist'] = 'La descarga que esta intentando ver no existe.';
$txt['adkfatal_this_download_not_approved'] = 'La descarga que est� intentando ver no est� aprobada';
$txt['adkfatal_invalid_picture'] = 'Im�gen inv�lida.';
$txt['adkfatal_cannot_view'] = 'Usted no tiene permisos para ver esta secci�n';
$txt['adkfatal_cat_title_false'] = 'Las categor�as nuevas requieren un t�tulo.';
$txt['adkfatal_invalid_id_category'] = 'La categor�a que usted esta tratando de acceder no existe.';
$txt['adkfatal_weight_height_false'] = 'El ancho m�ximo permitido para el �cono es de 128px';
$txt['adkfatal_top_karma_error'] = 'Se ha producido un error. Usted necesita poner la cantidad de usuarios a mostrar en el bloque.';
$txt['adkfatal_auto_news_error'] = 'Se ha producido un error. Usted necesita seleccionar los foros a mostrar en el bloque.';
$txt['adkfatal_please_add_a_body_message'] = 'Se ha producido un error. El cuerpo del mensaje esta vacio.';
$txt['adkfatal_insert_multi_id'] = 'Se ha producido un error. Usted necesita seleccionar al menos un bloque.';
$txt['adkfatal_empty_block_id'] = 'Se ha producido un error. El bloque que esta intentando visualizar no existe.';
$txt['adkfatal_empty_news_id'] = 'Se ha producido un error. La noticia que esta intentando visualizar no existe.';
$txt['adkfatal_lang_error_not_block'] = 'Usted no est� subiendo ningun bloque.';
$txt['adkfatal_extension'] = 'Este archivo no es PHP';
$txt['adkfatal_invalid_type'] = 'Esta acci�n es inv�lida';
$txt['adkfatal_empty_place'] = 'Usted necesita insertar un lugar para la plantilla';
$txt['adkfatal_template_invalid_id'] = 'Se ha producido un error. La plantilla que est� intentando visualizar no existe.';
$txt['adkfatal_exists_this_template'] = 'La plantilla que esta intentando crear ya existe';
$txt['adkfatal_you_can_not_modify_portal_template'] = 'Usted no puede eliminar o deshabilitar la plantilla del portal';
$txt['adkfatal_smf_p_blocks_not'] = 'Usted est� intentado descargar un bloque que no existe.';
$txt['adkfatal_enable_blog_please'] = 'Usted necesita activar Adk blog';
$txt['adkfatal_captcha_invalid'] = 'Error en la verificaci�n visual';
$txt['adkfatal_module_not_enable'] = 'Este m�dulo no esta habilitado.';
$txt['adkfatal_guest_not_add'] = 'Los invitados no pueden agregar nuevas descargas.';
$txt['adkfatal_not_zero_data'] = 'Se ha producido un error al guardar los datos.<br />Los siguientes campos no pueden estar vacios o con valor cero (0)<br />
<strong>Numero de noticias por pagina<br />
Cantidad de temas a mostrar<br />
Limite de temas a mostrar en el bloque<br />
Limite de usuarios en el bloque top posters</strong>';

// eds
$txt['adkeds_nocategory'] = 'No hay categor�as creadas';

?>