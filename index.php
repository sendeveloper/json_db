<?php
	require_once("config.php");

	for ($i=0;$i<1;$i++)
	{
		$file_name = "adult_pharmacist_childs_{$i}.json";
		$content = file_get_contents("json/{$file_name}");
		$json_data = json_decode($content, true);
		foreach($json_data as $key => $data)
		{
			$pages_sql = "INSERT INTO pages (page_id, theme_type,title) VALUES ({$key}, 0, '{$data['title']}')";
			$mysqli->query($pages_sql);

			$content = isset($data['tabs'][0]['top_header']) ? $mysqli->escape_string($data['tabs'][0]['top_header']) : '';


			$topheader_sql = "INSERT INTO top_header (page_id, content) VALUES ({$key}, '$content')";
			$mysqli->query($topheader_sql);

			foreach($data['tabs'] as $tab_key => $each_tab)
			{
				$content = isset($each_tab['header']) ? $mysqli->escape_string($each_tab['header']) : '';
				$header_sql = "INSERT INTO header (page_id, tab_id, content) VALUES ({$key}, {$tab_key}, '$content')";
				$mysqli->query($header_sql);
			}
		}
	}
?>