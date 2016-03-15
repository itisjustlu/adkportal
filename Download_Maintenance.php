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

//My Requires
require_once('SSI.php');

//Global
global $smcFunc;
	
$sql = $smcFunc['db_query']('','
	SELECT id_cat 
	FROM {db_prefix}adk_down_cat'
);
	
while($row = $smcFunc['db_fetch_assoc']($sql))
	NewTotalCategoryUpdate($row['id_cat']);
	
$smcFunc['db_free_result']($sql);
	
echo'Done. Good Work...!';


function NewTotalCategoryUpdate($ID_CAT)
{
	global $smcFunc;
	
	$dbresult = $smcFunc['db_query']('','
		SELECT
		COUNT(*) AS total
		FROM {db_prefix}adk_down_file
		WHERE id_cat = {int:cat} AND approved = {int:a}',
		array(
			'cat' => $ID_CAT,
			'a' => 1,
		)
	);

	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$total = $row['total'];
	$smcFunc['db_free_result']($dbresult);

	// Update the count
	$dbresult = $smcFunc['db_query']('','
		UPDATE {db_prefix}adk_down_cat 
		SET total = {int:t} WHERE id_cat = {int:cat} LIMIT 1',
		array(
			't' => $total,
			'cat' => $ID_CAT
		)
	);

}

?>