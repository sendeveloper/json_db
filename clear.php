<?php

require_once("config.php");
$table_names = ['adult_pharmacist_buttons', 'adult_pharmacist_collapsable', 
				'adult_pharmacist_collapsable_buttons', 'adult_pharmacist_fieldset', 
				'adult_pharmacist_firstpage_buttons', 'header', 
				'adult_pharmacist_irregular_content', 'adult_pharmacist_irregular_buttons', 
				'adult_pharmacist_mode', 'adult_pharmacist_mode_not', 
				'adult_pharmacist_mode_not_buttons', 'adult_pharmacist_pages', 
				'adult_pharmacist_top_header', 'adult_pharmacist_ullist', 
				'adult_pharmacist_why'];
foreach($table_names as $name)
{
	$mysqli->query("DELETE FROM {$name}");
}
echo 'success';
?>