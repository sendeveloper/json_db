<?php
	require_once("config.php");

	for ($i=0;$i<1;$i++)
	{
		$file_name = "adult_pharmacist_childs_{$i}.json";
		$content = file_get_contents("json/{$file_name}");
		$json_data = json_decode($content, true);
		foreach($json_data as $page_id => $data)
		{
			$pages_sql = "INSERT INTO pages (page_id, theme_type,title) VALUES ({$page_id}, 0, '{$data['title']}')";
			$mysqli->query($pages_sql);

			$content = isset($data['tabs'][0]['top_header']) ? $mysqli->escape_string($data['tabs'][0]['top_header']) : '';


			$topheader_sql = "INSERT INTO top_header (page_id, content) VALUES ({$page_id}, '$content')";
			$mysqli->query($topheader_sql);

			foreach($data['tabs'] as $tab_key => $each_tab)
			{
				$content = isset($each_tab['header']) ? $mysqli->escape_string($each_tab['header']) : '';
				$header_sql = "INSERT INTO header (page_id, tab_id, content) VALUES ({$page_id}, {$tab_key}, '$content')";
				$mysqli->query($header_sql);
			}

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
					$fieldset_sql = "INSERT INTO fieldset (page_id, content, type) VALUES ({$page_id}, '{$content}', '{$type}')";
					$mysqli->query($fieldset_sql);
				}
			}

			if (isset($data['tabs'][0]['mode']))
			{
				foreach($data['tabs'][0]['mode'] as $mode_key => $mode_field)
				{
					$content = $mysqli->escape_string($mode_field);
					$mode_sql = "INSERT INTO mode (page_id, mode_num, content) VALUES ({$page_id}, '{$mode_key}', '{$content}')";
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

					$mode_not_sql = "INSERT INTO mode_not (page_id, mode_no, content1, content2) VALUES ({$page_id}, '{$mode_no}', '{$content1}', '$content2')";
					$mysqli->query($mode_not_sql);

					foreach ($buttons as $each_button) {
						$class = $each_button['class'];
						$title = $mysqli->escape_string($each_button['title']);
						$goto = $each_button['goto'];

						$mode_not_button_sql = "INSERT INTO mode_not_buttons (page_id, class, title, goto) VALUES ({$page_id}, '{$class}', '{$title}', $goto)";
						$mysqli->query($mode_not_button_sql);
					}
				}
			}

			foreach($data['tabs'] as $tab_id => $each_tab)
			{			
				if (isset($each_tab['collapsable']))
				{
					foreach($each_tab['collapsable'] as $each_collapsable)
					{
						$title = $mysqli->escape_string($each_collapsable['title']);
						$content = $mysqli->escape_string($each_collapsable['content']);
						$mode = isset($each_collapsable['mode']) ? $each_collapsable['mode'] : '';
						$mode_above = isset($each_collapsable['mode_above']) ? $each_collapsable['mode_above'] : '';

						$collapsable_sql = "INSERT INTO collapsable (page_id, tab_id, title, content, mode, mode_above) VALUES ({$page_id}, '{$tab_id}', '{$title}', '{$content}', '{$mode}', '$mode_above')";
						$mysqli->query($collapsable_sql);

						if (isset($each_collapsable['buttons']))
						{
							$collapsable_id = $mysqli->insert_id;
							$col_buttons = $each_collapsable['buttons'];
							foreach ($col_buttons as $each_col_buttons) {
								$type = $each_col_buttons['type'];
								$title = $mysqli->escape_string($each_col_buttons['title']);
								$goto = $each_col_buttons['goto'];
								$collapsable_button_sql = "INSERT INTO collapsable_buttons (page_id, collapsable_id, type, title, goto) VALUES ({$page_id}, {$collapsable_id}, '{$type}', '{$title}', '{$goto}')";
								$mysqli->query($collapsable_button_sql);
							}
						}
					}
				}

				if (isset($each_tab['buttons']))
				{
					$buttons = $each_tab['buttons'];
					foreach($buttons as $each_button)
					{
						$class = $each_button['class'];
						$title = $mysqli->escape_string($each_button['title']);
						$goto = $each_button['goto'];
						$button_sql = "INSERT INTO buttons (page_id, tab_id, class, title, goto) VALUES ({$page_id}, {$tab_id}, '{$class}', '{$title}', '{$goto}')";
						$mysqli->query($button_sql);
					}
				}
				if (isset($each_tab['why']))
				{
					$why = $each_tab['why'];
					$why_sql = "INSERT INTO why (page_id, tab_id, content) VALUES ({$page_id}, {$tab_id}, '{$why}')";
					$mysqli->query($why_sql);
				}
			}
		}
	}
?>