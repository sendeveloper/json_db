<?php
	require_once("config.php");

	$start = 	9; 
	$end = 		10;
	$theme_type = array(
		'g','a','i','n','n','p','a','i','i','i',
		'o','n','o','i','p','e','d','a','n','i',
		'a','p','n','n','i','p','a','i','f','n',
		'o','n','p','i','n','a','n','p','n','o',
		'i','e','f','n','i','n','a','i','n','n',
		'i','n','n','i','o','h','o','i','a','n',
		'o','a','i','e','a','n','o','i'
	);
	for ($i=$start;$i<$end;$i++)
	{
		$file_name = "adult_pharmacist_childs_{$i}.json";
		$content = file_get_contents("json/{$file_name}");
		$json_data = json_decode($content, true);
		foreach($json_data as $page_id => $data)
		{
			if ($page_id != 39) continue;
			// $pages_sql = "INSERT INTO adult_pharmacist_pages (page_id, theme_type,title,category) VALUES ({$page_id}, '{$theme_type[$page_id]}', '{$data['title']}','{$data['category']}')";
			// $mysqli->query($pages_sql);

			// $content = isset($data['tabs'][0]['top_header']) ? $mysqli->escape_string($data['tabs'][0]['top_header']) : '';


			// $topheader_sql = "INSERT INTO adult_pharmacist_top_header (page_id, content) VALUES ({$page_id}, '$content')";
			// $mysqli->query($topheader_sql);

			// foreach($data['tabs'] as $tab_key => $each_tab)
			// {
			// 	$content = isset($each_tab['header']) ? $mysqli->escape_string($each_tab['header']) : '';
			// 	$header_sql = "INSERT INTO adult_pharmacist_header (page_id, tab_id, content) VALUES ({$page_id}, {$tab_key}, '$content')";
			// 	$mysqli->query($header_sql);
			// }
			
			if (isset($data['tabs'][0]['fieldset']))
			{
				$field_special = $data['tabs'][0]['fieldspecial'];
				foreach($data['tabs'][0]['fieldset'] as $field_key => $each_field)
				{
					$type = "Normal";
					if ($field_key == $field_special[0])
						$type = "All";
					if ($field_key == $field_special[1])
						$type = "Prevention";
					if ($field_key == $field_special[2])
						$type = "None";
					$content = $mysqli->escape_string($each_field);
					$fieldset_sql = "INSERT INTO adult_pharmacist_fieldset (page_id, content, type) VALUES ({$page_id}, '{$content}', '{$type}')";
					$mysqli->query($fieldset_sql);
				}
			}

			if (isset($data['tabs'][0]['mode']))
			{
				foreach($data['tabs'][0]['mode'] as $mode_key => $mode_field)
				{
					$content = $mysqli->escape_string($mode_field);
					$mode_sql = "INSERT INTO adult_pharmacist_mode (page_id, mode_num, content) VALUES ({$page_id}, '{$mode_key}', '{$content}')";
					$mysqli->query($mode_sql);
				}
			}
			if (isset($data['tabs'][0]['mode_not']))
			{
				foreach($data['tabs'][0]['mode_not'] as $each_mode_not)
				{
					$mode_no = $each_mode_not['mode_no'];
					$content1 = $mysqli->escape_string($each_mode_not['content1']);
					$content2 = $mysqli->escape_string($each_mode_not['content2']);
					$buttons = $each_mode_not['buttons'];

					$mode_not_sql = "INSERT INTO adult_pharmacist_mode_not (page_id, mode_no, content1, content2) VALUES ({$page_id}, '{$mode_no}', '{$content1}', '$content2')";
					$mysqli->query($mode_not_sql);

					foreach ($buttons as $each_button) {
						$class = $each_button['class'];
						$title = $mysqli->escape_string($each_button['title']);
						$goto = $each_button['goto'];

						$mode_not_button_sql = "INSERT INTO adult_pharmacist_mode_not_buttons (page_id, class, title, goto) VALUES ({$page_id}, '{$class}', '{$title}', $goto)";
						$mysqli->query($mode_not_button_sql);
					}
				}
			}

			foreach($data['tabs'] as $tab_id => $each_tab)
			{	
				if ($tab_id != 0) continue;
				if (isset($each_tab['collapsable']))
				{
					foreach($each_tab['collapsable'] as $each_collapsable)
					{
						if (isset($each_collapsable['field']))
						{
							$field = $mysqli->escape_string($each_collapsable['field']);
						}
						else
							$field = "";
						$title = $mysqli->escape_string($each_collapsable['title']);
						$content = $mysqli->escape_string($each_collapsable['content']);
						$mode = isset($each_collapsable['mode']) ? $each_collapsable['mode'] : '';
						$mode_above = isset($each_collapsable['mode_above']) ? $each_collapsable['mode_above'] : '';

						$collapsable_sql = "INSERT INTO adult_pharmacist_collapsable (page_id, tab_id, title, field, content, mode, mode_above) VALUES ({$page_id}, '{$tab_id}', '{$title}', '{$field}', '{$content}', '{$mode}', '$mode_above')";
						$mysqli->query($collapsable_sql);

						if (isset($each_collapsable['buttons']))
						{
							$collapsable_id = $mysqli->insert_id;
							$col_buttons = $each_collapsable['buttons'];
							foreach ($col_buttons as $each_col_buttons) {
								$type = $each_col_buttons['type'];
								$title = $mysqli->escape_string($each_col_buttons['title']);
								$goto = $each_col_buttons['goto'];
								$collapsable_button_sql = "INSERT INTO adult_pharmacist_collapsable_buttons (page_id, collapsable_id, type, title, goto) VALUES ({$page_id}, {$collapsable_id}, '{$type}', '{$title}', '{$goto}')";
								$mysqli->query($collapsable_button_sql);
							}
						}
					}
				}

			}

			// if (isset($data['tabs'][0]['ullist']))
			// {
			// 	foreach($data['tabs'][0]['ullist'] as $list_key => $list_field)
			// 	{
			// 		$content = $mysqli->escape_string($list_field['text']);
			// 		$goto = $list_field['goto'];
			// 		$mode_sql = "INSERT INTO adult_pharmacist_ullist (page_id, content, goto) VALUES ({$page_id}, '{$content}', '{$goto}')";
			// 		$mysqli->query($mode_sql);
			// 	}
			// }

			// if (isset($data['tabs'][0]['firstpage_buttons']))
			// {
			// 	foreach($data['tabs'][0]['firstpage_buttons'] as $button_key => $button_field)
			// 	{
			// 		$type = $button_field['type'];
			// 		$title = $mysqli->escape_string($button_field['title']);
			// 		$goto = $button_field['goto'];

			// 		$button_sql = "INSERT INTO adult_pharmacist_firstpage_buttons (page_id, type, title, goto) VALUES ({$page_id}, '{$type}', '{$title}', {$goto})";
			// 		$mysqli->query($button_sql);
			// 	}
			// }

			// if (isset($data['tabs'][0]['header_iregular']))
			// {
			// 	$header = $mysqli->escape_string($data['tabs'][0]['header_iregular']);
			// 	$page = isset($data['tabs'][0]['startpage']) ? $data['tabs'][0]['startpage'] : 0;
			// 	$content = $mysqli->escape_string($data['tabs'][0]['irregular']['content']);

			// 	$irregular_sql = "INSERT INTO adult_pharmacist_irregular_content (page_id, header, page, content) VALUES ({$page_id}, '{$header}', $page, '{$content}')";
			// 	$mysqli->query($irregular_sql);

			// 	$irregular_id = $mysqli->insert_id;

			// 	if (isset($data['tabs'][0]['irregular']['button']))
			// 	{
			// 		$irregular_button = $data['tabs'][0]['irregular']['button'];
			// 		$type = 0;
			// 		$title = $mysqli->escape_string($irregular_button['title']);
			// 		$class = "";
			// 		$goto = $irregular_button['goto'];
			// 		$ir_button_sql = "INSERT INTO adult_pharmacist_irregular_buttons (page_id, irregular_id, type, title, class, goto) VALUES ({$page_id}, {$irregular_id}, {$type}, '{$title}', '{$class}', {$goto})";
			// 		$mysqli->query($ir_button_sql);
			// 	}
			// 	if (isset($data['tabs'][0]['irregular']['buttongo']))
			// 	{
			// 		$irregular_buttongo = $data['tabs'][0]['irregular']['buttongo'];
			// 		foreach($irregular_buttongo as $each_buttongo)
			// 		{
			// 			$type = 1;	// Means buttongo
			// 			$title = $mysqli->escape_string($each_buttongo['title']);
			// 			$class = $each_buttongo['class'];
			// 			$goto = $each_buttongo['goto'];
			// 			$ir_button_sql = "INSERT INTO adult_pharmacist_irregular_buttons (page_id, irregular_id, type, title, class, goto) VALUES ({$page_id}, {$irregular_id}, {$type}, '{$title}', '{$class}', {$goto})";
			// 			$mysqli->query($ir_button_sql);
			// 		}
			// 	}
			// }

			// if (isset($data['tabs'][3]['buttons']))
			// {
			// 	$buttons = $data['tabs'][3]['buttons'];
			// 	foreach($buttons as $each_button)
			// 	{
			// 		$class = $each_button['class'];
			// 		$title = $mysqli->escape_string($each_button['title']);
			// 		$goto = $each_button['goto'];
			// 		$button_sql = "INSERT INTO adult_pharmacist_buttons (page_id, tab_id, class, title, goto) VALUES ({$page_id}, {$tab_id}, '{$class}', '{$title}', '{$goto}')";
			// 		$mysqli->query($button_sql);
			// 	}
			// }
			// if (isset($data['tabs'][3]['why']))
			// {
			// 	$why = $each_tab['why'];
			// 	$why_sql = "INSERT INTO adult_pharmacist_why (page_id, tab_id, content) VALUES ({$page_id}, {$tab_id}, '{$why}')";
			// 	$mysqli->query($why_sql);
			// }
		}
	}
	echo 'success - ' . $end;
?>