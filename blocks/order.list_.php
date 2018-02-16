<?
/*******************************/
/* список продуктов для заказа */
/*******************************/


/*// удаление продукта из корзины
if(isset($_GET['del']))
{
	$item_del = $_GET['del'];
	if(isset($_SESSION['item']) && is_array($_SESSION['item']))
	{
		if(isset($_SESSION['item'][$item_del]['item_in_basket']))
		{
			$_SESSION['item'][$item_del]['item_in_basket'] = false;
		}
	}
}*/


// создадим массив id продуктов в корзине
$arrItemSelected = array();

// полная стоимость заказа
$fullPrice = 0;

// массивы соответствия значение => текстовое описание параметров
$_VARS['item_color_1'] = $_VARS['arrColor1'];
$_VARS['item_color_2'] = $_VARS['arrColor2'];
$_VARS['item_constr']  = $_VARS['item_constr'];
$_VARS['item_material']= getItemMaterial();
$_VARS['item_size']= getItemSize();

// проверим был ли просмотрен хоть один продукт 
// (в этом случае была открыта сессия-список продуктов)
//printArray($_SESSION['item']);

$_SESSION['item'] = array
(
    11 => array
        (
            'item_count' => 1,
            'item_constr' => 'circle',
            'item_color_1' => 'white',
            'item_color_2' => 'white',
            'item_material' => 1,
            'item_size' => 1,
            'item_weight' => 3,
            'item_price_one' => 369,
            'item_price_all' => 369,
            'item_in_basket' => 1
        )

);

if(isset($_SESSION['item']) && is_array($_SESSION['item']))
{
	// пометим выбранный продукт как помещенный в корзину
	if(isset($_GET['param2']))
	{
		$item_selected = $_GET['param2'];
		$_SESSION['item'][$item_selected]['item_in_basket'] = true;
		header('Location:/order/');
		//printArray($_SESSION['item'][$item_selected]);
	}
	
	// перебираем все просмотренные продукты
	foreach($_SESSION['item'] as $k => $v)
	{	
		// и выбираем из них только те, которые были отправлены в корзину
		if(isset($v['item_in_basket']) && $v['item_in_basket'] == true)
		{			
			$arrItemSelected[] = $k;			
		}
	}	
}

// если корзина не пуста, выведем списком выбранные продукты
if(!empty($arrItemSelected))
{
	$list = '';
	$i = 0;
	
	// сформируем SQL-запрос
	foreach($arrItemSelected as $k)
	{
		$i++;
		$list .= "id = ".$k;
		if($i < count($arrItemSelected)) $list .= " or ";
	}
	
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE ".$list;
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_array($res))
		{
			$item_id		= $row['id'];
			$item_cat_name 	= "";
			$item_name		= "";
			$item_code		= "";
			$item_constr	= "";
			$item_price		= 0;
			$item_pic		= "";
			
			// прочитаем параметры продукта
			$item_cat_name 	= getItemCategory($row['item_parent']);			
			$item_name 		= $row['item_name'];
			$item_code 		= $row['item_art'];		
				
			$item_constr 	= $_VARS['item_constr'][$_SESSION['item'][$item_id]['item_constr']];
			$item_color_1 	= $_VARS['item_color_1'][$_SESSION['item'][$item_id]['item_color_1']];
			$item_color_2 	= $_VARS['item_color_2'][$_SESSION['item'][$item_id]['item_color_2']];
			$item_material 	= $_VARS['item_material'][$_SESSION['item'][$item_id]['item_material']];
			$item_size 		= $_VARS['item_size'][$_SESSION['item'][$item_id]['item_size']][0];
			$item_count		= $_SESSION['item'][$item_id]['item_count'];
			
			
			$item_price = $_SESSION['item'][$item_id]['item_price_all'];
			
			$fullPrice += $item_price;
			
			$img = new Image();
			$img -> imgCatalogId 	= $_VARS['env']['pic_catalogue_services'];
			$img -> imgId 			= $row['item_photo'];
			$img -> imgAlt 			= $row['item_name'];
			$img -> imgClass 		= "";
			$img -> imgWidthMax 	= 110;
			$img -> imgHeightMax 	= 135;	
			$img -> imgMakeGrayScale= false;
			$img -> imgGrayScale 	= false;
			$img -> imgTransform	= "resize";
			$html = $img -> showPic();			
			$item_pic = $html;
			
			?>
			<div class="orderParam">
				<div class="orderP1"><?=$item_pic?></div>
				<div class="orderP2">
					<?=$item_cat_name?><br />
					&laquo;<?=$item_name?>&raquo;<br />
					<span>код продукта: <?=$item_code?></span><br />
					<span class="num">
						Количество:<span><a href="<?=$item_id.'/item_count=dec'?>"><img src='/img/tpl/arrow_left_9x5_999.png' width='5' height='9' /></a></span>
						<span><?=$item_count?></span>
						<span><a href="<?=$item_id.'/item_count=inc'?>"><img src='/img/tpl/arrow_right_9x5_999.png' width='5' height='9' /></a></span>
					</span>
				</div>
				<div class="orderP3">
					Вес: 8 кг<br />
					Габариты: <?=$item_size?><br />
					Форма: <?=$item_constr?><br />
					<a href="/item_card/<?=$item_id?>/">Редактировать</a>
				</div>
				<div class="orderP4">
					Цвет массива: <?=$item_color_1?><br />
					Цвет декора: <?=$item_color_2?><br />
					Начинка: <?=$item_material[0]?><br />
					<a href="/item_card/<?=$item_id?>/">Редактировать</a>
				</div>
				<div class="orderP5">Цена: <span><?=number_format($item_price, 0, ',', ' ')?> Р</span><br>
					<a href="/order/del=<?=$item_id?>">удалить</a>
				</div>				
				
				<div style="clear:both"></div>
			</div>
			<?
		}	
	}	
	
	// стоимость заказанных продуктов
	$fullPrice += $orderDelivPriceDef;
	$goodsPrice = $fullPrice; // стоимость только товаров
	
	//printArray($arrItemSelected);
	
	include "order.param.php";
	
}

else
{
	?><div class="orderParam">Ваша корзина пуста</div><?
}

//printArray($arrItemSelected);

?>
