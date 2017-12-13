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
			var_dump($pages_sql);
			$mysqli->query($pages_sql);
			
		}
		// print_r($json_data);
	}
?>