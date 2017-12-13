<?php

require_once("config.php");
$table_names = ['buttons', 'collapsable', 'collapsable_buttons', 'fieldset', 'firstpage_buttons',
				'header', 'iregular_content', 'irregular_buttons', 'mode', 'mode_not', 'mode_not_buttons', 'pages', 'startpage', 'top_header', 'ullist', 'why'];
foreach($table_names as $name)
{
	$mysqli->query("DELETE FROM {$name}");
}
echo 'success';
?>