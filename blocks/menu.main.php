<?
/****************/
/* главное меню */
/****************/

$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pages`
		WHERE p_main_menu = '1'
		AND p_show = '1'
		ORDER BY p_order ASC";
		
$res = mysql_query($sql);

$parent_url = '';
$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pages`
		WHERE id = ".$_PAGE['p_parent_id'];

$res1= mysql_query($sql);
if($res1 && mysql_num_rows($res1) > 0)
{
	$row1 = mysql_fetch_array($res1);
	$parent_url = $row1['p_url'];
}

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
				$clsA = '';
				if($i == 1) $cls = "first";
				if($i == count($arrMenuMain) - 1) $cls = "prelastLi";
				if($i == count($arrMenuMain)) $cls = "lastLi";
				
				if($v == $_PAGE['p_url'] || $v == $parent_url) $clsA = 'activeA';
				else
				{
					
				}
				
				?>
				<li class="<?=$cls?>"><a class="<?=$clsA?>" href="/<?=$v?>/"><?=$k?></a></li>
				<?
			}
			?>
			
			<div style="clear:both"></div>
		</ul>	