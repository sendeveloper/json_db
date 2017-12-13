<?php
require_once("config.php");
if (isset($_REQUEST['id']))
{
	$data = array();
	$id = $_REQUEST['id'];
	$page_sql = "SELECT * FROM pages WHERE page_id={$id}";
	if ($page_result = $mysqli->query($page_sql)) {
		if ($page_result->num_rows>0)
		{
			$page_row = $page_result->fetch_assoc();
			$data['title'] = $page_row['title'];
			$data['tabs_title'] = array("Take This", "Know This", "What If", "Comments");
			$data['tabs'] = array();
			for ($i=0;$i<4;$i++)
			{
				$data['tabs'][$i] = array();
				$data['tabs'][$i]['header'] = '';
				$data['tabs'][$i]['collapsable'] = array();
			}
			$data['tabs'][0]['top_header'] = '';
			$top_header_sql = "SELECT * from top_header WHERE page_id={$id}";
			if ($top_result = $mysqli->query($top_header_sql))
			{
				if ($top_result->num_rows > 0)
				{
					$top_row = $top_result->fetch_assoc();
					$data['tabs'][0]['top_header'] = $top_row['content'];
				}
			}

			$irregular_sql = "SELECT * from irregular_content WHERE page_id={$id}";
			if ($irregular_result = $mysqli->query($irregular_sql))
			{
				if ($irregular_result->num_rows > 0)
				{
					$irregular_row = $irregular_result->fetch_assoc();
					$ir_id = $irregular_row['id'];
					$data['tabs'][0]['header_iregular'] = $irregular_row['header'];
					$data['tabs'][0]['startpage'] = $irregular_row['page'];
					$data['tabs'][0]['irregular'] = array();
					$data['tabs'][0]['irregular']['page'] = $irregular_row['page'];
					$data['tabs'][0]['irregular']['content'] = $irregular_row['content'];

					$ir_button_sql = "SELECT * from irregular_buttons WHERE page_id={$id} and irregular_id={$ir_id}";
					if ($ir_button_result = $mysqli->query($ir_button_sql))
					{
						if ($ir_button_result->num_rows > 0)
						{
							$ir_button_row = $ir_button_result->fetch_assoc();
							$type = $ir_button_row['type'];
							if ($type == 0){
								$data['tabs'][0]['irregular']['button'] = array();
								$data['tabs'][0]['irregular']['button']['title'] = $ir_button_row['title'];
								$data['tabs'][0]['irregular']['button']['goto'] = $ir_button_row['goto'];
							}else
							{
								$data['tabs'][0]['irregular']['buttongo'] = array();
								$data['tabs'][0]['irregular']['buttongo'][0] = array();
								$data['tabs'][0]['irregular']['buttongo'][0]['title'] = $ir_button_row['title'];
								$data['tabs'][0]['irregular']['buttongo'][0]['class'] = $ir_button_row['class'];
								$data['tabs'][0]['irregular']['buttongo'][0]['goto'] = $ir_button_row['goto'];
							}
						}
					}

				}
			}

			$header_sql = "SELECT * from header WHERE page_id={$id}";
			if ($header_result = $mysqli->query($header_sql))
			{
				if ($header_result->num_rows > 0)
				{
					while($header_row = $header_result->fetch_assoc())
					{
						$tab_id = $header_row['tab_id'];
						$data['tabs'][$tab_id]['header'] = $header_row['content'];
					}
				}
			}

			$firstpage_buttons_sql = "SELECT * from firstpage_buttons WHERE page_id={$id}";
			if ($firstpage_buttons_result = $mysqli->query($firstpage_buttons_sql))
			{
				if ($firstpage_buttons_result->num_rows > 0)
				{
					$data['tabs'][0]['firstpage_buttons'] = array();
					while($firstpage_buttons_row = $firstpage_buttons_result->fetch_assoc())
					{
						$each = array();
						$each['type'] = $firstpage_buttons_row['type'];
						$each['title'] = $firstpage_buttons_row['title'];
						$each['goto'] = $firstpage_buttons_row['goto'] . '';
						$data['tabs'][0]['firstpage_buttons'][] = $each;
					}
				}
			}

			

			$data['tabs'][0]['fieldset'] = array();
			$data['tabs'][0]['fieldspecial'] = array();
			$field_sql = "SELECT * from fieldset WHERE page_id={$id}";
			if ($field_result = $mysqli->query($field_sql))
			{
				if ($field_result->num_rows > 0)
				{
					$index = 0;
					while($field_row = $field_result->fetch_assoc())
					{
						$data['tabs'][0]['fieldset'][] = $field_row['content'];
						if ($field_row['type'] == "All")
							$data['tabs'][0]['fieldspecial'][0] = $index;
						if ($field_row['type'] == "Prevention")
							$data['tabs'][0]['fieldspecial'][1] = $index;
						if ($field_row['type'] == "None")
							$data['tabs'][0]['fieldspecial'][2] = $index;
						$index++;
					}
					if (!isset($data['tabs'][0]['fieldspecial'][0]))
						$data['tabs'][0]['fieldspecial'][0] = $data['tabs'][0]['fieldspecial'][1];
				}
			}
			$data['tabs'][0]['mode'] = array();
			$mode_sql = "SELECT * from mode WHERE page_id={$id}";
			if ($mode_result = $mysqli->query($mode_sql))
			{
				if ($mode_result->num_rows > 0)
				{
					while($mode_row = $mode_result->fetch_assoc())
					{

						$data['tabs'][0]['mode'][ $mode_row['mode_num'] . "" ] = $mode_row['content'];
					}
				}
			}
			$data['tabs'][0]['mode_not'] = array();
			$mode_not_sql = "SELECT * from mode_not WHERE page_id={$id}";
			if ($mode_not_result = $mysqli->query($mode_not_sql))
			{
				if ($mode_not_result->num_rows > 0)
				{
					$each = array();
					while($mode_not_row = $mode_not_result->fetch_assoc())
					{
						$each['mode_no'] = $mode_not_row['mode_no'];
						$each['content1'] = $mode_not_row['content1'];
						$each['content2'] = $mode_not_row['content2'];
						$each['buttons'] = array();
						$mode_button_sql = "SELECT * from mode_not_buttons WHERE page_id={$id}";
						if ($mode_button_result = $mysqli->query($mode_button_sql))
						{
							if ($mode_button_result->num_rows > 0)
							{
								while($mode_button_row = $mode_button_result->fetch_assoc())
								{
									$each_button = array();
									$each_button['class'] = $mode_button_row['class'];
									$each_button['title'] = $mode_button_row['title'];
									$each_button['goto'] = $mode_button_row['goto'] . "";
									$each['buttons'][] = $each_button;
								}
							}
						}
						$data['tabs'][0]['mode_not'][] = $each;
					}
				}
			}
			

			$collapsable_sql = "SELECT * from collapsable WHERE page_id={$id}";
			if ($collapsable_result = $mysqli->query($collapsable_sql))
			{
				if ($collapsable_result->num_rows > 0)
				{
					$tab1_count = 0; $tab2_count = 0;
					while($collapsable_row = $collapsable_result->fetch_assoc())
					{
						$col_id = $collapsable_row['id'];
						$tab_id = $collapsable_row['tab_id'];
						if ($tab_id == 0)
							$tab1_count ++;
						else if ($tab_id == 1)
							$tab2_count ++;
						$each = array();
						$each['title'] = $collapsable_row['title'];
						$each['content'] = $collapsable_row['content'];
						if ($collapsable_row['mode_above'] != "" && strlen($collapsable_row['mode_above']) > 0)
							$each['mode_above'] = $collapsable_row['mode_above'];
						else
							$each['mode'] = $collapsable_row['mode'];

						$col_button_sql = "SELECT * FROM `collapsable_buttons` WHERE page_id={$id} and collapsable_id={$col_id}";
						if ($col_button_result = $mysqli->query($col_button_sql))
						{
							if ($col_button_result->num_rows > 0)
							{
								$each['buttons'] = array();
								while($col_button_row = $col_button_result->fetch_assoc())
								{
									$each_button = array();
									$each_button['type'] = $col_button_row['type'];
									$each_button['title'] = $col_button_row['title'];
									$each_button['goto'] = $col_button_row['goto'];
									$each['buttons'][] = $each_button;
								}
							}
						}

						$data['tabs'][$tab_id]['collapsable'][] = $each;
					}
					$data['tabs'][1]['colstart'] = $tab1_count;
					$data['tabs'][2]['colstart'] = $tab1_count + $tab2_count;
				}
			}

			$data['tabs'][3]['buttons'] = array();
			$button_sql = "SELECT * from buttons WHERE page_id={$id} and tab_id=3";
			if ($button_result = $mysqli->query($button_sql))
			{
				if ($button_result->num_rows > 0)
				{
					while($button_row = $button_result->fetch_assoc())
					{
						$each['class'] = $button_row['class'];
						$each['title'] = $button_row['title'];
						$each['goto'] = $button_row['goto'];
						$data['tabs'][3]['buttons'][] = $each;
					}
				}
			}
			$data['tabs'][3]['why'] = '';
			$why_sql = "SELECT * from why WHERE page_id={$id} and tab_id=3";
			if ($why_result = $mysqli->query($why_sql))
			{
				if ($why_result->num_rows > 0)
				{
					while($why_row = $why_result->fetch_assoc())
					{
						$data['tabs'][3]['why'] = $why_row['content'];
					}
				}
			}
			$data['footer'] = '<p>&nbsp;</p><p>&nbsp;</p>';
		}
	}
	echo json_encode($data);
}
?>