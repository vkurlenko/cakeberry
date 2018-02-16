<?
/*********************/
/* каталог продуктов */
/*********************/

?>
<div class="catalogItems">

<?
$select_limit 	= $_VARS['env']['limit_catalog']; // сколько позиций на странице
$select_from 	= 0;

$select_order	= "ASC";
if(isset($_SESSION['item_order'])) $select_order = $_SESSION['item_order'];


if(isset($_GET['param3']) && is_numeric($_GET['param3']))
{
	$select_from = $_GET['param3'] * $select_limit;
}

if(isset($_GET['param2']))
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE item_parent = ".$_GET['param2']."
			AND item_show = '1'
			ORDER BY item_order ".$select_order."
			LIMIT ".$select_from.", ".$select_limit;
			
	if(isset($_SESSION['item_sort']))
	{
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
				WHERE item_parent = ".$_GET['param2']."
				AND item_show = '1'";
		$sql .= "ORDER BY ".$_SESSION['item_sort']." ".$select_order." ";
		$sql .= "LIMIT ".$select_from.", ".$select_limit;
	}
			
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			?>
			<div class="sliderCatalogItem">
				<a class="sliderCatalogItemImg" href="/item_card/<?=$row['id']?>/"><?
				$img = new Image();
				$img -> imgCatalogId 	= $_VARS['env']['pic_catalogue_services'];
				$img -> imgId 			= $row['item_photo'];
				$img -> imgAlt 			= $row['item_name'];
				$img -> imgClass 		= "";
				$img -> imgWidthMax 	= 135;
				$img -> imgHeightMax 	= 166;	
				$img -> imgMakeGrayScale= false;
				$img -> imgGrayScale 	= false;
				$img -> imgTransform	= "resize";
				$html = $img -> showPic();
				echo $html;
				?></a>
				<a class="itemName" href="/item_card/<?=$row['id']?>/"><?=$row['item_name_2']?></a>
				<div class="itemStars">
					<?=star_line_html($row['id'])?>
				</div>	
				
				<?
				$item_price = getItemPriceDef($row);
				$item_weight_def = getItemWeightDef($row['item_size']);			
				?> 
				<div class="itemPrice"><strong><?=number_format($item_price, 0, ',', ' ')?> Р</strong> (<?=$item_weight_def?> кг)</div>				
			</div>
			<?
			$i++;
			
			if($i > 4)
			{
				?><div style="clear:both"></div><?
				$i = 0;
			}
		}		
	}
}
?>

	<div style="clear:both"></div>
	
</div>