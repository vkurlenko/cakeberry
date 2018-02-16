<?

function formatPhone()
{
	global $_VARS;
	
	$arrPhone = array(
		'codeCountry' 	=> '',
		'codeRegion'	=> '',
		'numPhone'		=> ''
	);
	
	$str = str_replace(' ', '', $_VARS['env']['phone']);
	$strLen = strlen($str);
	
	
	
	$arrPhone['numPhone'] = substr($str, $strLen - 7);
	
	if($strLen > 7)
		$arrPhone['codeRegion'] = substr($str, $strLen - 10, 3);
		
	if($strLen > 10)
		$arrPhone['codeCountry'] = substr($str, 0, 2);
		
	return $arrPhone;
}

/*********************/
/* генератор паролей */
/*********************/
function pwdGen($n)
{
	// Символы, которые будут использоваться в пароле.
	$chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
	
	// Количество символов в пароле.	
	$max = $n;
	
	// Определяем количество символов в $chars	
	$size = strlen($chars)-1;
	
	// Определяем пустую переменную, в которую и будем записывать символы.	
	$password = null;
	
	// Создаём пароль.	
		while($max--)
		$password .= $chars[rand(0,$size)];	
	// Выводим созданный пароль. 
	return $password;
	
}

/*************************/
/* валидация полей формы */
/*************************/
function validData($type, $string)
{
	switch($type)
	{
		case 'EMAIL' : 
			if(!preg_match('(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})', $string))
			{
				return false;
			}
			else return true;
			break;
			
		case 'INT' : 
			if(!intval($string))
			{
				return false;
			}
			else return true;
			break;
		default : return true;
	}
}

/****************************************/
/* проверка авторизован ли пользователь */
/****************************************/
function userAuth()
{
	if(isset($_SESSION['user_access']) && $_SESSION['user_access'] == true)
	{
		if(isset($_SESSION['user_id']))
			return true;
		else 
			return false;
	}
	else 
		return false;
}


/*****************************************/
/* прочитаем в массив данные пользоватея */
/*****************************************/
function userData($userId)
{
	global $_VARS;
	
	$userData = array();
	
	$sql = 'SELECT * FROM `'.$_VARS['tbl_prefix'].'_users`
			WHERE id = '.$userId;
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		return $row;
	}
	else return false;	
}


/********************************/
/* подсчет окончательной цены   */
/********************************/
function calcPrice($n, $a, $m_def, $m, $b, $d)
{	
	/*
	n		- количество
	a 		- стоимость начинки за 1 кг
	m_def 	- общая масса торта
	m		- масса съедобной части
	b		- стоимость муляжа
	d		- стоимость дизайна
	*/

	//$c = $n * ($a * $m + $b * ($m_def - $m) + $d);
	$c = ($n * $a * $m) + $d;
	//echo "$n * $a * $m +$d";
	return $c;
}


/********************************/
/* рейтинг продукта (звездочки) */
/********************************/
function star_line_html($item_id)
{
	global $_VARS;	
	$star_line_html = "";
	$item_rate 		= 0;
	
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE id = ".$item_id;
	$res = mysql_query($sql);
	//echo $sql;
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		$item_rate = ceil($row['item_rate']);			
	}
	
	for($i = 1; $i < 6; $i++)
	{
		if($i <= $item_rate) $sign = "1";
		else $sign = "0";
		
		$star_line_html .= '<img src="/img/tpl/icon_star_'.$sign.'.png" width="13" height="14" />';			
	}
	
	return $star_line_html;
}
/***************************************************/


/***************************************************/
/* запись обновленного рейтинга продукта в каталог */
/***************************************************/
function setRating($item_id)
{
	global $_VARS;
	$item_rate = 0;
	
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_faq`
			WHERE faqType = 'ref_salon'
			AND faqPerson = '".$item_id."'";
	//echo $sql;
	$res3 = mysql_query($sql);
	
	if($res3 && mysql_num_rows($res3) > 0)
	{
		while($row3 = mysql_fetch_array($res3))
		{
			$item_rate += $row3['faqSign'];
		}						
		$item_rate = $item_rate / mysql_num_rows($res3);		
		
		$sql = "UPDATE `".$_VARS['tbl_prefix']."_catalog`
				set item_rate = ".$item_rate."
				WHERE id = ".$item_id;
		//echo $sql;
		$res4 = mysql_query($sql);			
	}
}
/***************************************************/



/*****************************/
/* вес продукта по умолчанию */
/*****************************/
function getItemWeightDef($itemS)
{
	$item_weight_def = 0;
	
	$item_size = getItemSize();
	
	$arrS = unserialize($itemS);
	if(isset($item_size[$arrS[0]][1]))
		$item_weight_def = $item_size[$arrS[0]][1];

	
	return $item_weight_def;
}
/*****************************/



/***********************************/
/* стоимость продукта по умолчанию */
/***********************************/
function getItemPriceDef($row)
{
	$item_price = 0;
	// стоимость начинки
	$item_material = getItemMaterial();
	
	$arrM = unserialize($row['item_material']);
	//printArray($arrM);
	if(isset($item_material[$arrM[0]][2]))
	{
		$item_material_pr = floatval($item_material[$arrM[0]][2]);
		
		// вес по умолчанию
		$item_weight_def = getItemWeightDef($row['item_size']);
		
		// стоимость муляжа
		$item_mul_pr = floatval($row['item_price_array']);
		
		// стоимость дизайна
		$item_design_pr	= floatval($row['item_price_to']);
		
		$item_price = calcPrice(1, $item_material_pr, $item_weight_def, $item_weight_def, $item_mul_pr, $item_design_pr);
	}
	
	return $item_price;
}
/***********************************/



/********************************/
/* чтение наименования продукта */
/********************************/
function getItemCategory($id, $f = 'item_name_2')
{
	global $_VARS;
	$item_cat_name = "";
	
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE id = ".$id;
	$res = mysql_query($sql);
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		$item_cat_name = $row[$f];
	}	
	
	return $item_cat_name;
}
/*********************************/


/******************************************/
/* чтение наименования категории продукта */
/******************************************/
function getItemParent($id, $f = 'item_name_2')
{
	global $_VARS;
	$item_cat_name = "";
	
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE id = (SELECT item_parent FROM `".$_VARS['tbl_prefix']."_catalog`
						WHERE id = ".$id.")";
	$res = mysql_query($sql);
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		$item_cat_name = $row[$f];
	}	
	
	return $item_cat_name;
}
/******************************************/


/****************************/
/* формируем массив НАЧИНКИ */
/****************************/
function getItemMaterial()
{
	global $_VARS;	
	$_VARS['item_material']= array();
		
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog_material`
			WHERE mat_show = '1'
			ORDER BY mat_order ASC";
			
	$res = mysql_query($sql);
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_array($res))
		{
			$_VARS['item_material'][$row['id']] = array($row['mat_name'], $row['mat_img'], $row['mat_price']);
		}
	}
	return $_VARS['item_material'];
}
/****************************/

/****************************************/
/* выводим уменьшенную картинку начинки */
/****************************************/
function getImageInner($matId)
{
	global $_VARS;
	
	//echo 'k = '.$matId;
	
	$sql = 'SELECT * FROM `'.$_VARS['tbl_prefix'].'_catalog_material`
			WHERE id = '.$matId;
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		
		$img = new Image();
		$img -> imgCatalogId 	= $_VARS['env']['pic_catalogue_inner'];
		//$img -> imgId 			= $imgId;
		$img -> imgId 			= $row['mat_img'];
		$img -> imgAlt 			= "DEFAULT";
		$img -> imgClass 		= "";
		$img -> imgWidthMax 	= 30;
		$img -> imgHeightMax 	= 18;	
		$img -> imgMakeGrayScale= false;
		$img -> imgGrayScale 	= false;
		$img -> imgTransform	= "crop";
		$html = $img -> showPic();
		
		/* увеличенное изображение начинки */
		$img = new Image();
		$img -> imgCatalogId 	= $_VARS['env']['pic_catalogue_inner'];
		$img -> imgId 			= $row['mat_img'];
		$img -> imgAlt 			= "DEFAULT";
		$img -> imgClass 		= "";
		$img -> imgWidthMax 	= 300;
		$img -> imgHeightMax 	= 200;	
		$img -> imgMakeGrayScale= false;
		$img -> imgGrayScale 	= false;
		$img -> imgTransform	= "crop";
		$html_big = $img -> showPic();
		/* /увеличенное изображение начинки */
	}
	
	
	
	return $html;
}


/***********************************************/
/* выводим картинку продукта в карточке товара */
/***********************************************/
function getImageItem($imgId, $imgAlt)
{
	global $_VARS;
	
	$img = new Image();
	$img -> imgCatalogId 	= $_VARS['env']['pic_catalogue_services'];
	$img -> imgId 			= $imgId;
	$img -> imgAlt 			= $imgAlt;
	$img -> imgClass 		= "";
	/*$img -> imgWidthMax 	= 365;
	$img -> imgHeightMax 	= 453;	*/
	$img -> imgWidthMax 	= 550;
	$img -> imgHeightMax 	= 550;	
	$img -> imgMakeGrayScale= false;
	$img -> imgGrayScale 	= false;
	$img -> imgTransform	= "resize";
	$img -> imgWaterMark	= '/img/pic/cakeberry_watermark.png';
	
	$html = $img -> showPic();
	
	return $html;
}
/***********************************************/

/*****************************/
/* формируем массив ГАБАРИТЫ */
/*****************************/
function getItemSize()
{
	global $_VARS;	
	$_VARS['item_size']= array();
		
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog_size`
			WHERE size_show = '1'
			ORDER BY size_order ASC";
	
			
	$res = mysql_query($sql);
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_array($res))
		{
			$_VARS['item_size'][$row['id']] = array($row['size_name'], $row['size_weight'], $row['size_weight_2']); // array('название', вес До, вес ОТ)
		}
	}
	
	
	return $_VARS['item_size'];
}
/****************************/

function printOption($param, $default, $minY = 1970)
{
	global $_VARS;
	$html = "";
	
	$arrMonth = array('', 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 
					'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь');
	
	switch($param)
	{
		case "D" : 	for($i = 1; $i < 32; $i++)
					{
						$sel = '';
						if($i == $default) $sel = 'selected';
						$html .= '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';		
					}
					break;
		case "M" :  for($i = 1; $i < count($arrMonth); $i++)
					{
						$sel = '';
						if($i == $default) $sel = 'selected';
						$html .= '<option value="'.$i.'" '.$sel.'>'.$arrMonth[$i].'</option>';		
					}
					break;
		case "Y" :  //for($i = 1970; $i <= date('Y'); $i++)
					for($i = date('Y'); $i >= $minY; $i--)
					{
						$sel = '';
						if($i == $default) 
							$sel = 'selected';
						$html .= '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';		
					}
					break;
		case "T" :  for($i = $_VARS['env']['time_start']; $i <= $_VARS['env']['time_stop']; $i++)
					{
						$a = array('00', '30');
						
						foreach($a as $m)
						{
							$t = $i.':'.$m;
							$sel = '';
							
							if($t == $default) 
								$sel = 'selected';
							
							if($t == $_VARS['env']['time_stop'].':30')
								continue;
								
							$html .= '<option value="'.$t.'" '.$sel.'>'.$t.'</option>';
						}
					}
					break;
		default : break;
	}
	
	
	return $html;	
}

/***********************/
/* ссылка на Избранное */
/***********************/
function fav_link()
{
	global $_VARS;
	
	if(isset($_SESSION['user_access']) && $_SESSION['user_access'] == true)
	{
		$user_id = $_SESSION['user_id'];
		$user_fav_item = 0;
		
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_users`
				WHERE id = ".$user_id;
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_array($res);
			
			if(is_array(unserialize($row['user_fav_item'])))
			{
				$user_fav_item = count(unserialize($row['user_fav_item']));
			}
			?>		
			<div class="pageLister"><a class="linkWishList" href="/wishlist/">Избранное (<?=$user_fav_item ?>)</a></div>
			<?
		}				
	}
}
/************************/
/* /ссылка на Избранное */
/************************/


function myOrderLink()
{
	$i = 0;
	if(isset($_SESSION['item']) && is_array($_SESSION['item']))
	{
		
		foreach($_SESSION['item'] as $k => $v)
		{
			if(isset($v['item_in_basket']) && $v['item_in_basket'] == true) $i++;
		}
		
		/*if($i > 0)
		{
			
		}*/				
	}
	
	?><div class="pageLister"><a class="linkWishList myOrder" href="/order/">Мой заказ (<?=$i?>)</a></div><?
}



/*************************************/
/* навигационная цепочка для Галереи */
/*************************************/
function galleryNav()
{
	global $_VARS, $_arrMonth, $arrNav;

	if(isset($_GET['param2']) && isset($_GET['param3']))
	{
		if($_GET['param2'] == 'video') 
		{
			$arrNav[] = array('', 'Видео');
			
			$sql = "SELECT video_title FROM `".$_VARS['tbl_prefix']."_video`
					WHERE id = ".$_GET['param3'];
			
			$res = mysql_query($sql);
			if($res && mysql_num_rows($res) > 0)
			{
				$row = mysql_fetch_array($res);
				$arrNav[] = array('', $row['video_title']);
			}
		}
		elseif($_GET['param2'] == 'alb') 
		{
			$arrNav[] = array('', 'Фото');
			
			$sql = "SELECT alb_title FROM `".$_VARS['tbl_prefix']."_pic_catalogue`
					WHERE id = ".$_GET['param3'];
			
			$res = mysql_query($sql);
			if($res && mysql_num_rows($res) > 0)
			{
				$row = mysql_fetch_array($res);
				$arrNav[] = array('', $row['alb_title']);
			}
		}
		else $arrNav[] = array('', 'Все материалы');		
	}	
	else
	{
		switch($_SESSION['gallery']['galleryType'])
		{
			case 'video' 	: $arrNav[] = array('', 'Видео'); break;
			case 'alb' 		: $arrNav[] = array('', 'Фото'); break;
			default 		: $arrNav[] = array('', 'Все материалы'); break;
		}
		
		$_SESSION['gallery']['galleryY'] == '%' ? $strY = 'Все года' : $strY = $_SESSION['gallery']['galleryY'];
		$arrNav[] = array('', $strY);
		
		// пропускаем "все месяцы"
		if(intval($_SESSION['gallery']['galleryM']) > 0)
			$arrNav[] = array('', $_arrMonth[intval($_SESSION['gallery']['galleryM'])]);		
	}	
	
//	printArray($arrNav);
	
	return $arrNav;
}
/**************************************/
/* /навигационная цепочка для Галереи */
/**************************************/



/***********************************/
/* параметры родительской страницы */
/***********************************/
function getParentPageName()
{
	global $_VARS, $_PAGE;
	
	$arrParent = array();
	
	if($_PAGE['p_parent_id'] > 0)
	{
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pages`
				WHERE id = ".$_PAGE['p_parent_id'];
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_array($res);
			$arrParent = array(
				'id' 		=> $row['id'],
				'p_url' 	=> $row['p_url'],
				'p_title'	=> $row['p_title']
			);
		}
	}	
	
	return $arrParent;
}
/************************************/
/* /параметры родительской страницы */
/************************************/


?>