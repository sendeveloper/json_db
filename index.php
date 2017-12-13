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
				}
			}
		}
	}
?>