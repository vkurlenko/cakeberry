<?
/* var */
$arrSliders = array();



/* function */
function getIMG($row)
{
	global $_VARS;
	
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
	
	return $html;
}


/* prepair */



/*$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
		WHERE item_show_main = '1'
		AND item_show = '1'
		AND item_parent = 0
		ORDER BY item_order ASC";*/
if($_PAGE['p_url'] == 'item_card' && isset($_GET['param2']))	
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE id = (SELECT `item_parent` FROM `".$_VARS['tbl_prefix']."_catalog` WHERE id = ".$_GET['param2'].")";
}
else
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE item_show_main = '1'
			AND item_show = '1'
			AND item_parent = 0
			ORDER BY item_order ASC";
}	
		
$res = mysql_query($sql);

if($res && mysql_num_rows($res) > 0)
{
	while($row = mysql_fetch_assoc($res))
	{
		$arrSliders[] = array(
			'parent_id' => $row['id'],
			'title'		=> $row['item_maker']
		);
	}
}


foreach($arrSliders as $k => $v)
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE item_parent = ".$v['parent_id']."
			AND item_show = '1'
			ORDER BY item_order ASC";
	
	$res = mysql_query($sql);
	
	$arrSliders[$k]['product'] = array();
	if($res && mysql_num_rows($res) > 0)
	{
		
		
		while($row = mysql_fetch_assoc($res))
		{
		
			$a1 = array();
			$a1['id'] = $row['id']; // id продукта
			$a1['img'] = getIMG($row); // картинка
			$a1['name'] = $row['item_name_2']; // наименование
			$a1['rate'] = star_line_html($row['id']); // рейтинг (звезды)
			$a1['price'] = number_format(getItemPriceDef($row), 0, ',', ' '); // цена
			$a1['weight'] = getItemWeightDef($row['item_size']); // вес	
			
			$arrSliders[$k]['product'][] = $a1;
		}
	}
}

/* code */
foreach($arrSliders as $k)
{
?>

<div class="sliderCatalog">

	<h2><?=$k['title']?></h2>
	
	<a class='prev browse left'></a>

		<div class="scrollable2">
	
			<div class='items'>
			
				<div class="item">					
					<?
					$i = $j = 0;
					foreach($k['product'] as $k1 => $v1)
					{
						$i++;
						$j++;
						?>
						<div class="sliderCatalogItem">
							<a href="/item_card/<?=$v1['id']?>/"><?=$v1['img'];?></a>
							<span class="itemName"><?=$v1['name']?></span>
							<div class="itemStars"><?=$v1['rate']?></div>							
							<?
							/*// стоимость начинки
							$item_material = getItemMaterial();
							$arrM = unserialize($row['item_material']);
							$item_material_pr = floatval($item_material[$arrM[0]][2]);
							
							// вес по умолчанию
							$item_size = getItemSize();
							$arrS = unserialize($row['item_size']);
							$item_weight_def = $item_size[$arrS[0]][1];
							
							// стоимость муляжа
							$item_mul_pr = floatval($row['item_price_array']);
							
							// стоимость дизайна
							$item_design_pr	= floatval($row['item_price_to']);
							
							
							$item_price = calcPrice(1, $item_material_pr, $item_weight_def, $item_weight_def, $item_mul_pr, $item_design_pr);*/
							
							//$item_price = getItemPriceDef($row);
							
							//$item_weight_def = getItemWeightDef($row['item_size']);
							?>
							<div class="itemPrice"><strong><?=$v1['price']?> Р</strong> (<?=$v1['weight']?> кг)</div>				
						</div>
						<?							
						if($i > 5 && $j < count($k['product']))
						{
							$i = 0;
							?>
							</div>
			
							<div class="item">
							<?
						}
					}
					?>					
				</div>			
			</div>		
		</div>
		
	<a class='next browse right'></a>
	<div style="clear:both"></div>		
</div>
<?
}
?>