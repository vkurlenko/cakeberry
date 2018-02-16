<?
/*~~~~~~~~~~~~~~~~~*/
/* карточка товара */
/*~~~~~~~~~~~~~~~~~*/

	if(isset($_GET['param2']))
	{
		$item_id		= $_GET['param2'];
		$item_cat_name 	= "";
		$item_name		= "";
		$item_code 		= "";		
		$item_descr		= "";
		$item_price 	= 0;		
		$item_pic		= "";
		$item_rate		= 5;
		$item_ref		= 0;
		$item_material  = "";
		$item_size		= "";
		$item_weight	= array(); // вес съедобной части (выбирается пользователем)
		$item_weight_def= 0; // общая масса (зависит от выбранных габаритов)
		
		$item_material_pr  	= 0; // стоимость начинки
		$item_mul_pr  		= 0; // стоимость муляжа
		$item_design_pr  	= 0; // стоимость дизайна
		
		$item_count		= 1;
		
		
		$this_url 		= "/".$_PAGE['p_url']."/".$item_id."/";
		
		$arrItemParam	= array('item_constr', 'item_color_1', 'item_color_2', 'item_material', 'item_size', 'item_weight', 'item_count');
		
		$_VARS['param'] = array(
			'item_constr'	=> '', 
			'item_color_1' 	=> '', 
			'item_color_2' 	=> '',
			'item_material' => '',
			'item_size' 	=> '',
			'item_weight' 	=> 0			
		);
		
		// массивы соответствия значение => текстовое описание параметров
		$_VARS['item_color_1']	= $_VARS['arrColor1'];
		$_VARS['item_color_2'] 	= $_VARS['arrColor2'];
		$_VARS['item_constr']  	= $_VARS['item_constr'];
		$_VARS['item_material']	= getItemMaterial();
		$_VARS['item_size']		= getItemSize();		
		
		//printArray($_VARS['item_size']);
		
				
		
		/****************************************************************/
		/* сохраняем в сессии информацию о всех просмотренных продуктах */
		/* и параметры этих продуктов, измененные пользователем 		*/
		/****************************************************************/		
		
		// открываем в сессии массив измененных параметров продуктов
		if(!isset($_SESSION['item']) || !is_array($_SESSION['item'])) 
		{
			$_SESSION['item'] = array();
		}
		
		// создадим массив параметров для данного продукта, 
		// если его еще нет
		if(!isset($_SESSION['item'][$item_id]))
		{
			$_SESSION['item'][$item_id] = array();		
		}
		
		if(!isset($_SESSION['item'][$item_id]['item_count']))
		{
			$_SESSION['item'][$item_id]['item_count'] = $item_count;
		}
		
		// внесем в массив параметров значения замененных параметров продукта	
		foreach($arrItemParam as $param)
		{
			if(isset($_GET[$param])) 
			{
				// кол-во тортов
				if($param == 'item_count')
				{					
					$count = intval($_SESSION['item'][$item_id]['item_count']); 
					
					switch($_GET[$param])
					{
						case 'dec' : if($count > 1) $count--; break;
						default : $count++; break;																
					}
					$_SESSION['item'][$item_id]['item_count'] = $count;
				}
				else 
					$_SESSION['item'][$item_id][$param] = $_GET[$param];

				header('Location: '.$this_url);
			}
		}
		
		
				
				
				
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
				WHERE id = ".$item_id;
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_array($res);		
			
			//printArray(unserialize($row['item_size']));	
			
			// прочитаем категорию продукта
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
					WHERE id = ".$row['item_parent'];
			$res2 = mysql_query($sql);
			if($res2 && mysql_num_rows($res2) > 0)
			{
				$row2 = mysql_fetch_array($res2);
				$item_cat_name = $row2['item_name_2'];
			}	
			
			// наименование продукта в ед.числе
			$item_name = $row['item_name_2'];
			
			
			// код продукта
			$item_code = $row['item_art'];
			
			// стоимость муляжа
			$item_mul_pr  		= floatval($row['item_price_array']); 
			
			// стоимость дизайна
			$item_design_pr  	= floatval($row['item_price_to']); 
			
			// параметры. пишем в сессию выбранные параметры продукта (или первые в списке по умолчанию)
			// и формируем html-код
			//printArray($arrItemParam);
			
			foreach($arrItemParam as $param)
			{
				if(isset($row[$param]) && is_array(unserialize($row[$param])))
				{
					$arr = unserialize($row[$param]);
					
					// если несколько значений данного параметра
					if(count($arr) > 1)
					{
						if($param == "item_material") 
							$_VARS['param'][$param] .= "<ul>";
						else 
							$_VARS['param'][$param] .= "<ul class='itemParam'>";
						
						// переберем все возможные значения
						$i = 0;
						foreach($arr as $k)
						{
							$i++;
							$cls = "";
							if(	(isset($_SESSION['item'][$item_id][$param]) && $_SESSION['item'][$item_id][$param] == $k) || // если значение параметра совпадает с записанным в сессии
								(!isset($_SESSION['item'][$item_id][$param]) && $i == 1)) // если нет в сессии, то выбираем первый из списка 
								{
									$cls = "active";	
									$_SESSION['item'][$item_id][$param] = $k;
									
									if($param == "item_material") 
										$item_material = $_VARS['item_material'][$k];	// начинка
										
									if($param == "item_size") 
									{
										$item_weight_def = $_VARS[$param][$k][1]; 	// общая масса									
										
										// массив возможных масс съедобной части с шагом 0.5 кг
										$item_weight = array();
										
										/*for()
										
										$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog_size`
												WHERE "*/
										
										//for($w = $item_weight_def; $w > 0; $w -= 0.5) 
										for($w = $item_weight_def; $w > $_VARS[$param][$k][2]; $w -= 0.5) 
										{											
											$item_weight[] = floatval($w);
										}
										
										//printArray($_SESSION['item'][$item_id][$param]);
										
										
									}
								}							
							
							$url = $this_url.$param."=".$k; // url для сохранения в сессии данного параметра
							
							/* формируем html-код списка значений данного параметра */
							if($param == "item_material") 
							{
								$html = getImageInner($k);
								$_VARS['param'][$param] .= "<li><a class='".$cls."' href='".$url."'>".$html."</a></li>";							
							}
							elseif($param == "item_size") 
								$_VARS['param'][$param] .= "<li class='".$cls."'><a href='".$url."'>".$_VARS[$param][$k][0]."</a></li>";
							else 
								$_VARS['param'][$param] .= "<li class='".$cls."'><a href='".$url."'>".$_VARS[$param][$k]."</a></li>";
							/* /формируем html-код списка значений данного параметра */
						}					
						
						if($param == "item_material") 
							$_VARS['param'][$param] .= "<div style='clear:both'></div>";
						
						$_VARS['param'][$param] .= "</ul>";
						
						if($param != "item_material") 
							$_VARS['param'][$param] .= "<img class='arrowDown' src='/img/tpl/arrow_down.png' width='9' height='5' />";
					}
					// если только одно значение данного параметра
					else
					{
						if($param == "item_material") 
						{
							$item_material = $_VARS['item_material'][$arr[0]];
							$html = getImageInner($arr[0]);
							$_VARS['param'][$param] .= "<ul><li><a class='active' href='#'>".$html."</a></li><div style='clear:both'></div><ul>";
						}
						elseif($param == "item_size") 
						{
							$_VARS['param'][$param] .= $_VARS[$param][$arr[0]][0];
							
							$step = 0.5;
							
							/*
							от max веса к min
							
							$item_weight_def = $_VARS[$param][$arr[0]][1]; 	// общая масса	
							//$item_weight_min = $_VARS[$param][$arr[0]][2]; // масса ОТ
							
							intval($_VARS[$param][$arr[0]][2]) == 0 ? $item_weight_min = $step : $item_weight_min = $_VARS[$param][$arr[0]][2];
															
							// массив возможных масс съедобной части с шагом 0.5 кг
							$item_weight = array();
							
							for($w = $item_weight_def; $w > $item_weight_min - $step; $w -= $step) 
							{											
								$item_weight[] = floatval($w);
							}
							
							*/
							
							/* от min веса к max */
							$item_weight_def = $_VARS[$param][$arr[0]][2]; 	// общая масса	
							
							intval($_VARS[$param][$arr[0]][1]) == 0 ? $item_weight_max = $step : $item_weight_max = $_VARS[$param][$arr[0]][1];
															
							// массив возможных масс съедобной части с шагом 0.5 кг
							$item_weight = array();
							
							for($w = $item_weight_def; $w <= $item_weight_max; $w += $step) 
							{											
								$item_weight[] = floatval($w);
							}						
						}
						else
							$_VARS['param'][$param] .= $_VARS[$param][$arr[0]];

						$_SESSION['item'][$item_id][$param] = $arr[0];
					}
				}
				else
				{
					/*$arr = array();
					$_VARS['param'][$param] .= "<ul>";
					
					printArray($_VARS['param']['item_weight']);*/
					
				}				
			}	
			
			/*~~~~~~~~~~~~~~~~~~~~~*/
			/* вес съедобной части */
			/*~~~~~~~~~~~~~~~~~~~~~*/
						
			if(is_array($item_weight))
			{
				$param = 'item_weight';
				$_VARS['param'][$param] = "<ul class='itemParam'>";
				
				$w_max = max($item_weight); // максимально допустимый вес
				//printArray($item_weight);				
				// переберем все возможные значения
				$i = 0;
				foreach($item_weight as $k)
				{
					$i++;
					$cls = "";
					if(	
						(isset($_SESSION['item'][$item_id][$param]) && $_SESSION['item'][$item_id][$param] == $k) 	|| // если значение параметра совпадает с записанным в сессии
						(!isset($_SESSION['item'][$item_id][$param]) && $i == 1) 									|| 	// если нет в сессии, то выбираем первый из списка 
						(isset($_SESSION['item'][$item_id][$param]) && $_SESSION['item'][$item_id][$param] > $w_max && $i == 1) ||  // если сохраненное значение больше макисмально возможного
						(isset($_SESSION['item'][$item_id][$param]) && !in_array($_SESSION['item'][$item_id][$param], $item_weight) && $i == 1) // если сохраненного значения нет в списке возможных значений
					)
					{
						$cls = "active";	
						$_SESSION['item'][$item_id][$param] = $k;													
					}							
					
					$url = $this_url.$param."=".$k; // url для сохранения в сессии данного параметра
					
					/* формируем html-код списка значений данного параметра */
					$_VARS['param'][$param] .= "<li class='".$cls."'><a href='".$url."'>".$k." кг</a></li>";
					/* /формируем html-код списка значений данного параметра */
				}	
				$_VARS['param'][$param] .= "</ul>";
				$_VARS['param'][$param] .= "<img class='arrowDown' src='/img/tpl/arrow_down.png' width='9' height='5' />";
			}
			/*~~~~~~~~~~~~~~~~~~~~~~*/
			/* /вес съедобной части */
			/*~~~~~~~~~~~~~~~~~~~~~~*/
			
			
			// описание 
			$item_descr = $row['item_text_1'];
			
			// цена 
			$item_price = $row['item_price_from'];
			
			// картинка				
			$item_pic = getImageItem($row['item_photo'], $row['item_name']);
			
			// рейтинг
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_faq`
					WHERE faqType = 'ref_salon'
					AND faqPerson = '".$row['id']."'";
			$res3 = mysql_query($sql);
			
			if($res3 && mysql_num_rows($res3) > 0)
			{				
				$item_ref = mysql_num_rows($res3);
			}		
			
			// количество единиц
			$item_count = $_SESSION['item'][$item_id]['item_count'];
			
			
			// стоимость начинки
			$item_material_pr = floatval($item_material[2]);
			
			// итоговая стоимость 1 торта
			$item_price = calcPrice(1, $item_material_pr, $item_weight_def, $_SESSION['item'][$item_id]['item_weight'], $item_mul_pr, $item_design_pr);
			$_SESSION['item'][$item_id]['item_price_one'] = $item_price;
			//echo $item_price;
			
			// итоговая стоимость N тортов
			$item_price_final = $item_count * $item_price;
			$_SESSION['item'][$item_id]['item_price_all'] = $item_price_final;
			
			?>
			
			
	<div class="itemImg"><?=$item_pic?></div>
					
	<div class="itemInfo">
		<div>
			<h1><?=$item_cat_name?> <br />&laquo;<?=$item_name?>&raquo;</h1>
			
			<div class="itemRef">
				<div class="itemStars">
					<span class="starLine">
						<?
						echo star_line_html($item_id);
						?>		
					</span>			
					&nbsp;&nbsp;&nbsp;<span>(<?=$item_ref?> отзывов)</span>&nbsp;&nbsp;&nbsp;
					<?
					if(userAuth() && isset($_GET['param2']))
					{
						// если пользователь авторизован
						$arrUserData = userData($_SESSION['user_id']);
						
						// id продукта
						$itemId = $_GET['param2'];
						
						// проверим покупал ли пользователь этот продукт
						if(is_array(unserialize($arrUserData['user_pay'])))
						{
							$arr = unserialize($arrUserData['user_pay']);
							
							// если да, то форма отзыва
							if(in_array($itemId, $arr))
							{
								?><a href="#">Оставить отзыв</a><?
							}
						}
						else
						{
							?><a href="#">Оставить отзыв</a><?
						}
					}
					?>
					<div style="clear:both"></div>
				</div>								
			</div>
			
			
			
			<!-- BEGIN параметры продукта -->
			
			<script language="javascript">
			$(document).ready(function(){
			
				/*****************************************/
				/* изменение кол-ва товара и пересчет его 
				/* стоимости с сохранением в сессии 	*/
				/*****************************************/
				
				var prObj 		= $('.itemPrice span');
				var countDecObj = $('.itemCountLeft, .itemCountLeft img');
				var countIncObj = $('.itemCountRight, .itemCountRight img');
				
				var prOne = <?=$_SESSION['item'][$item_id]['item_price_one']?>; // цена за единицу с данными параметрами
				var itemCount = <?=$_SESSION['item'][$item_id]['item_count']?>; // кол-во товара в сессии
				
				function ajaxSave(itemCount)
				{
					$.ajax({
						   type: "POST",
						   url: "/blocks/ajax.item.count.php",
						   data: "id=<?=$item_id?>&count="+itemCount
						   /*success: function(msg){
							 alert( "Data Saved: " + msg );
						   }*/
					 });
				}
				
							
				$(countIncObj).click(function()
				{
					itemCount++;
					var newPr = prOne * itemCount;					
					$(prObj).text(number_format(newPr, {thousands_sep: " ", decimals: 0}))	
					
					$('.itemCountStr').text(itemCount)	
					
					ajaxSave(itemCount)
									
					return false
				})
				
				$(countDecObj).click(function()
				{
					if(itemCount > 1)
					{				
						itemCount--;
						var newPr = prOne * itemCount;					
						$(prObj).text(number_format(newPr, {thousands_sep: " ", decimals: 0}))	
						
						$('.itemCountStr').text(itemCount)	
						
						ajaxSave(itemCount)
					}					
					return false
				})		
			})
			
			</script>
			
			<div class="itemCode">код продукта: <?=$item_code?></div>
			
			<div class="itemWeight"><span class="itemParamTitle">Вес<!-- съедобной части-->:</span>
				<div style='float:left; position:relative; z-index:6'><?=$_VARS['param']['item_weight']?></div>
				<!--<div class="itemParamHelp">При уменьшении веса, вес съедобной части будет меньше, а размер торта останется прежним.</div>-->
				<div style="clear:both"></div>
			</div>
			
			<div class="itemSize"><span class="itemParamTitle">Ярусы:</span>
				<div style='float:left; position:relative; z-index:5'><?=$_VARS['param']['item_size']?></div>				
				<div style="clear:both"></div>
			</div>
			<!--<div class="itemWeight">Вес общий: <?=$item_weight_def?> кг</div>-->
			
			
			
			<div class="itemForm">
				<span class="itemParamTitle">Форма:</span> 
				<div style='float:left; position:relative; z-index:4'><?=$_VARS['param']['item_constr']?></div>				
				<div style="clear:both"></div>
			</div>
			
			<div class="itemColor1">
				<span class="itemParamTitle">Цвет массива:</span> 
				<div style='float:left; position:relative; z-index:3'><?=$_VARS['param']['item_color_1']?></div>				
				<div style="clear:both"></div>
			</div>
			
			<div class="itemColor2">
				<span class="itemParamTitle">Цвет декора:</span> 
				<div style='float:left; position:relative; z-index:2'><?=$_VARS['param']['item_color_2']?>	</div>			
				<div style="clear:both"></div>
			</div>
			
			<div class="itemInner">Начинка: <?=$item_material[0]?>
				<?=$_VARS['param']['item_material']?>
			</div>
			<div class="itemDescr">Описание:<br />
				<?=$item_descr?>
			</div>
			
			<div class="itemCount">
				Количество:<span><a class="itemCountLeft" href="<?=$this_url.'item_count=dec'?>"><img src='/img/tpl/arrow_left_9x5.png' width='5' height='9' /></a></span>
							<span class="itemCountStr"><?=$item_count?></span>
							<span><a class="itemCountRight" href="<?=$this_url.'item_count=inc'?>"><img src='/img/tpl/arrow_right_9x5.png' width='5' height='9' /></a></span>
			</div>
			
			<!-- END параметры продукта -->
			
			
			<div>
			
				<!--<span class="itemPriceOld"><s><?=number_format(10000, 0, ',', ' ')?></s> Р</span>-->
							
				<div class="itemPrice"><span><?=number_format($item_price_final, 0, ',', ' ')?></span> Р</div>
				
				<div class="buttonConfirm">
					<a href="/order/<?=$item_id?>/">Заказать</a>
				</div>
				
				<?
							
				if(isset($_SESSION['user_access']) && $_SESSION['user_access'] == true)
				{
					?>
					<a class="itemToFav" href="/wishlist/<?=$item_id?>/">В избранное</a>
					<a class="itemToCalend" href="#">В календарь</a>
					<?
				}
				else
				{
					?><a class="itemUserActivate" href="#">Активировать скидку через личный вход</a><?
				}
				?>
				
				<div style="clear:both"></div>
				<?
				//printArray($_SESSION['item'][$item_id]);
				?>
				
			</div>
		</div>
	</div>
					
	<div style="clear:both"></div>
		<?
	}	
}
?>