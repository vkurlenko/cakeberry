<?
$block_table_name = $_VARS['tbl_prefix']."_sertif";
$tags = "<strong><a><br><span><img><embed><em>";

function StripP($string)
{
	$string = trim($string);
	$string = str_replace("<p>&nbsp;</p>","",$string);
	$string = str_replace("<p>","",$string);
	$string = str_replace("</p>","<br/>",$string);
	
	return $string;	
}

function AddBlock($block_marker, $block_name, $block_image_id, $block_image_alt, $block_bg_color, $block_text_color, $block_text_value, $block_text_value_en, $block_html)
	{
	global $block_table_name;
	global $tags;
	if($block_html == "on")
	{
		$block_text_value = strip_tags($block_text_value, $tags);
		$block_text_value_en = strip_tags($block_text_value_en, $tags);
		$block_html = 1;
	}
	else $block_html = 0;
	
	$sql = "insert into `$block_table_name` (block_marker, block_name, block_bg_color, block_text_color, block_text_value, block_text_value_en, block_html )
	values ('$block_marker', '$block_name', '$block_bg_color', '$block_text_color', '$block_text_value', '$block_text_value_en', '$block_html')";
	
	$res = mysql_query($sql);
	return $res;
	}
	
function GetBlocks()
{
	global $block_table_name, $_ICON, $arrSalon, $_VARS;
	$sql = "select * from `$block_table_name` where 1 order by id desc";
	$res = mysql_query($sql);
	if($res)
	{
		?>
		<table border=0 cellpadding=5  class="list">
			<tr>				
				<th>Название сертификата</th>
				<th>Салон</th>
				<th>Услуга</th>
				<th>Активен</th>
				<th>edit</th>
				<th>del</th>
			</tr>
		<?
		while($row = mysql_fetch_array($res))
		{
			?><tr>
				<td><a href=?page=sertif&editItem&id=<?=$row['id'];?>><strong><?=$row['sertif_name'];?></strong></a></td>
				<td><?=$arrSalon[$row['sertif_salon']];?></td>
				<td>
				<?
				$sql = "select * from `".$_VARS['tbl_prefix']."_catalog_items` where id=".$row['sertif_service'];
				$res_serv = mysql_query($sql);
				$row_serv = mysql_fetch_array($res_serv);
				echo $row_serv['item_name'];
				?></td>
				<td align="center"><? if(trim($row['sertif_active']) != '0') $icon = "user_ok"; else  $icon = "user_block";?><img src='<?=$_ICON[$icon]?>'></td>
				<td><a href=?page=sertif&editItem&id=<?=$row['id'];?>><img src='<?=$_ICON["edit"]?>'></a></td>
				<td><a style='color:red' href="javascript:if (confirm('Удалить сертификат?')){document.location='?page=sertif&del_block&id=<?=$row['id'];?>'}"><img src='<?=$_ICON["del"]?>'></a></td>
			</tr>
			<?
		}
		?>
		</table>
		<?
	}
}


function ReadBlock($id)
{
	global $block_table_name;
	$sql = "select * from `$block_table_name` where id=$id";
	return SqlParseRes($sql);
}
?>