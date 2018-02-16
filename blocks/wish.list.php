<?
/**********************/
/* избранные продукты */
/**********************/


	// порядок сортировки
	if(isset($_SESSION['item_order'])) $select_order = $_SESSION['item_order'];
	
	
	if(isset($_GET['param3']) && is_numeric($_GET['param3']))
	{
		$select_from = $_GET['param3'] * $select_limit;
	}
	?>
	
	
	
	<div class="catalogItems wishList">	
	<?	
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE ".$select_list."
			AND item_show = '1'
			ORDER BY item_order ".$select_order."
			LIMIT ".$select_from.", ".$select_limit;
			
	if(isset($_SESSION['item_sort']))
	{
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
				WHERE ".$select_list."
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
			$i++;
			?>
			<div class="sliderCatalogItem ">
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
				
				<?
				// прочитаем категорию продукта
				$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
						WHERE id = ".$row['item_parent'];
				$res2 = mysql_query($sql);
				if($res2 && mysql_num_rows($res2) > 0)
				{
					$row2 = mysql_fetch_array($res2);
					$item_cat_name = $row2['item_name_2'];
				}	
				?>
				
				
				<a class="itemName" href="/item_card/<?=$row['id']?>/"><?=$item_cat_name.' <br>&laquo;'.$row['item_name_2'].'&raquo;'?></a>
				<div class="itemStars">
					<?=star_line_html($row['id'])?>
				</div>	
				<div class="itemPrice"><strong><?=number_format($row['item_price_from'], 0, ',', ' ')?> Р</strong> (2.0 кг)</div>
				<!--<div class="itemDescr"><?=$row['item_text_1']?></div>-->	
				<div class="buttonConfirm">
					<a href="/item_card/<?=$row['id']?>/">Заказать</a>
				</div>	
				
				<a class="linkToCalend" name="<?=$row['id']?>" href="#">Занести в календарь</a>
				<a class="linkRemove" href="/wishlist/remove=<?=$row['id']?>">Удалить из избранного</a>		
			</div>
			<?
			if($i > 3) 
			{			
				$i = 0; 
				?><div style="clear:both"></div><?
			}
		}		
	}
	?>
	
		<div style="clear:both"></div>
		
	</div>

