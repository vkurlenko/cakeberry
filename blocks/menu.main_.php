<?
/****************/
/* главное меню */
/****************/

$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pages`
		WHERE p_main_menu = '1'
		AND p_show = '1'
		ORDER BY p_order ASC";
		
$res = mysql_query($sql);

$arrMenuMain = array();

while($row = mysql_fetch_array($res))
{
	if($row['p_redirect'] != "") $url = $row['p_redirect'];
	else $url = $row['p_url'];
	
	$arrMenuMain[$row['p_title']] = $url;
}


?>
		<ul class="menuMain">
			
			<?
			$i = 0;
			foreach($arrMenuMain as $k => $v)
			{
				$i++;
				$cls = "";
				if($i == 1) $cls = "first";
				if($i == (count($arrMenuMain) - 1)) $cls = "listPreLI";
				if($i == count($arrMenuMain)) $cls = "listLI";
				?>
				<li class="<?=$cls?>"><a href="/<?=$v?>/"><?=$k?></a></li>
				<?
			}
			?>
			
			<div style="clear:both"></div>
		</ul>	