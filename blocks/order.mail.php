<?
// отправка заказа
include_once "mail.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'].'/modules/shop/shop.class.php';

if(!empty($_POST) && !empty($_SESSION['item']) && isset($_POST['orderPayVar']))
{
	//printArray($_POST);
	
	
	
	// поля формы и необходимость их заполнения
	$arrF = array(
		'orderDelivVar'			=> array('Вид доставки', 	'', true),
		'orderDelivRange'		=> array('Расстояние', 		'', false),
		'orderTestVar'			=> array('Способ дегустации','', true),
		'orderDelivDay'			=> array('День доставки', 	'', true),
		'orderDelivMonth'		=> array('Месяц доставки', 	'', true),
		'orderDelivTime'		=> array('Время доставки', 	'', true),
		'orderClientName'		=> array('Имя клиента', 	'', true),
		'orderClientAddress'	=> array('Адрес доставки', 	'', false),
		'orderClientMail'		=> array('Email клиента', 	'', true),
		'orderClientPhoneCode'	=> array('Код телефона', 	'', true),
		'orderClientPhoneNum'	=> array('Номер телефона', 	'', true),
		'orderClientComment'	=> array('Комментарий к заказу', 					'', false),
		'orderClientCard'		=> array('Номер карты клиента', 					'', false),
		'orderFullPriceDiscount'=> array('Стоимость заказа с доставкой и скидками', '', true),
		'orderPayVar'			=> array('Способ оплаты', 	'', true)
	);
	
	$text 		= '';		// текст письма
	$arrOrder 	= array();  // массив - детали заказа
	$arrGoods	= array();	// массив позиций заказа (для квитанции)
	$error 		= array(); 	// массив сообщений об ошибках
	$oClientPhone = array(); // массив телефон
	$oDelivDate   = array(); // массив дата и время доставки
	
	
	// только цифры
	$arrNumber = array('orderDelivDay', 'orderDelivMonth', 'orderClientPhoneCode', 'orderClientPhoneNum');
	
	$arrMonth = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 
					'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
	
	// проверим данные формы
	foreach($arrF as $k => $v)
	{
		// обязательные поля
		if($v[2] == true && empty($_POST[$k]))
		{
			$error[] = 'Не заполнено обязательное поле "'.$v[0].'"<br>';
		}
		// только цифры
		elseif(in_array($k, $arrNumber))
		{
			if(!intval($_POST[$k]))
			{
				$error[] = 'Поле "'.$v[0].'" должно содержать только цифры<br>';
			}
			else
			{
				switch($k)
				{
					case 'orderDelivDay'		: $oDelivDate['d'] = $arrOrder[$k] = $_POST[$k]; break;
					case 'orderDelivMonth' 		: $oDelivDate['m'] = $arrOrder[$k] = $arrMonth[$_POST[$k]];  break;					
					case 'orderClientPhoneCode' : $str = $_POST[$k]; $oClientPhone['c'] = $_POST[$k]; break;
					case 'orderClientPhoneNum'  : 	$str = $_POST[$k]; 
													$oClientPhone['n'] = $_POST[$k]; 
													$arrOrder[$k] = $oClientPhone['c'].$oClientPhone['n']; 
													$text .= 'Телефон : ('.$oClientPhone['c'].') '.$oClientPhone['n'].'<br>';
													break;
					default : $str = $arrOrder[$k] = $_POST[$k];  $text .= $v[0].': '.$str.'<br>'; break;
				}
				
			}			
		}
		// проверка email
		elseif($k == 'orderClientMail')
		{
			//if(!filter_var($_POST[$k], FILTER_VALIDATE_EMAIL)) 
			if(!preg_match('(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})', $_POST[$k])) 
			{
				$error[] = 'Поле "'.$v[0].'" имеет неверный формат<br>';
			}
			else
			{
				$text .= $v[0].': '.$_POST[$k].'<br>';
				$oClientMail = $arrOrder[$k] = $_POST[$k];
			}
		}
		else
		{
			switch($k)
			{
				case 'orderDelivVar' 		: $str = $arrOrder[$k] = $arrDelivVar[$_POST[$k]][0]; 	$text .= $v[0].': '.$str.'<br>'; break;
				case 'orderDelivRange' 		: $str = $arrOrder[$k] = $arrDelivRange[$_POST[$k]][0]; $text .= $v[0].': '.$str.'<br>'; break;
				case 'orderTestVar' 		: $str = $arrOrder[$k] = $arrTestVar[$_POST[$k]][0]; 	$text .= $v[0].': '.$str.'<br>'; break;
				case 'orderPayVar' 			: $str = $arrOrder[$k] = $arrPayVar[$_POST[$k]]; 		$text .= $v[0].': '.$str.'<br>'; break;
				case 'orderClientCard'		: $str = $arrOrder[$k] = implode(' ', $_POST[$k]);		$text .= $v[0].': '.$str.'<br>'; break;
				case 'orderFullPriceDiscount' : $oClientSum = $str = $arrOrder[$k] = $_POST[$k];	$text .= $v[0].': '.$str.'<br>'; break;
				case 'orderDelivTime'		: $oDelivDate['t'] = $arrOrder[$k] = $_POST[$k]; 		$text .= 'Дата доставки : '.@$oDelivDate['d'].' '.@$oDelivDate['m'].' '.@$oDelivDate['t'].'<br>';break;
				default 					: $str = $arrOrder[$k] = $_POST[$k]; $text .= $v[0].': '.$str.'<br>'; 		break;
			}
			
		}
	}
	
	// ошибки проверки формы
	if(!empty($error))
	{
		//printArray($error);
		$textError = '';
		foreach($error as $k)
		{
			$textError .= $k.'<br>';
		}		
		echo '<p class="msgError">'.$textError.'</p>';
	}
	else
	{
	
		/* сформируем список заказа */
		
		// № заказа 
		/*$f_name = DIR_BLOCKS.'/order_count.txt';
		$count 	= 0;
				
		if(file_exists($f_name))
		{
			$f = fopen($f_name, 'r+');
			$count = fgets($f);
			fclose($f);
		}
		
		$f = fopen($f_name, 'w+');
		if($f)
			$w = fputs($f, $count + 1);
		else
			echo 'Не могу создать файл-счетчик'.BR;	
		
		// строка - номер заказа
		isset($_VARS['env']['orderPrefix']) ? $order_num = $_VARS['env']['orderPrefix'] : $order_num = '';
		$order_num .= '-'.date('Ymd').'-'.sprintf('%07d', $count);
		*/
		// /№ заказа
		
		
		// массив товаров в корзине
		$arrItemSelected = array();
		
		// массивы соответствия значение => текстовое описание параметров
		$_VARS['item_color_1'] = $_VARS['arrColor1'];
		$_VARS['item_color_2'] = $_VARS['arrColor2'];
		$_VARS['item_constr']  = $_VARS['item_constr'];
		$_VARS['item_material']= getItemMaterial();
		$_VARS['item_size']	   = getItemSize();
		
		// перебираем все просмотренные продукты
		foreach($_SESSION['item'] as $k => $v)
		{	
			// и выбираем из них только те, которые были отправлены в корзину
			if(isset($v['item_in_basket']) && $v['item_in_basket'] == true)
			{			
				$arrItemSelected[] = $k;			
			}
		}	
		
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
		
		$i = 1;
		if($res && mysql_num_rows($res) > 0)
		{
			$text .= '<br><strong class="user-order-table-head">Содержание заказа</strong>
			<table border=1 class="user-order-table" >
				<tr valign=top>
					<th>№п/п</th>
					<th>Наименование</th>
					<th>Код</th>
					<th>Вес</th>
					<th>Габариты</th>
					<th>Форма</th>
					<th>Цвет <br>массива</th>
					<th>Цвет <br>декора</th>
					<th>Начинка</th>
					<th>Количество</th>
					<th>Цена</th>
				</tr>
				';
			
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
				
				$item_weight 	= $_SESSION['item'][$item_id]['item_weight'];
				$item_size 		= $_VARS['item_size'][$_SESSION['item'][$item_id]['item_size']][0];
					
				$item_constr 	= $_VARS['item_constr'][$_SESSION['item'][$item_id]['item_constr']];
				$item_color_1 	= $_VARS['item_color_1'][$_SESSION['item'][$item_id]['item_color_1']];
				$item_color_2 	= $_VARS['item_color_2'][$_SESSION['item'][$item_id]['item_color_2']];
				$item_material 	= $_VARS['item_material'][$_SESSION['item'][$item_id]['item_material']][0];
				
				$item_count     = $_SESSION['item'][$item_id]['item_count'];
				
				$item_price = $_SESSION['item'][$item_id]['item_price_one'];
				
				$text .= '<tr align=center>
					<td>'.$i.'</td>
					<td>'.$item_name.'</td>
					<td>'.$item_code.'</td>
					<td>'.$item_weight.' кг</td>
					<td>'.$item_size.'</td>
					<td>'.$item_constr.'</td>
					<td>'.$item_color_1.'</td>
					<td>'.$item_color_2.'</td>
					<td>'.$item_material.'</td>
					<td>'.$item_count.'</td>
					<td>'.$item_price.'</td>
					</tr>';		
					
				$arrOrder['items'][$i] = array(
					'item_id'		=> $item_id,
					'item_name'	 	=> $item_name,
					'item_code'		=> $item_code,
					'item_weight'	=> $item_weight,
					'item_size'		=> $item_size,
					'item_constr'	=> $item_constr,
					'item_color_1'	=> $item_color_1,
					'item_color_2'	=> $item_color_2,
					'item_material'	=> $item_material,
					'item_count'	=> $item_count,
					'item_price'	=> $item_price			
				);
					
					
				// для безнала
				$arrGoods[] = array(
					'itemName'	=> $item_name,
					'itemNum'	=> $item_count,
					'itemPrice' => intval($item_price),
					'itemSum' 	=> intval($item_price) * intval($item_count) 
				);	
				$i++;
			}	
			
			// для безнала
			$n = 'Доставка ('.$arrDelivVar[$_POST['orderDelivVar']][0];
			if($arrDelivVar[$_POST['orderDelivVar']][1] != 0)
				$n .= ', ';$arrDelivRange[$_POST['orderDelivRange']][0];
			$n .= ')';		
			
			if(isset($_SESSION['user_card']) && $_SESSION['user_card'] == '0')
				$p = $arrDelivRange[$_POST['orderDelivRange']][1] + $arrDelivVar[$_POST['orderDelivVar']][1];
			else
				$p = 0;
			$arrGoods[] = array(
					'itemName'	=> $n,
					'itemNum'	=> 1,
					'itemPrice' => $p,
					'itemSum' 	=> $p 
				);
				
			$n = 'Дегустация ('.$arrTestVar[$_POST['orderTestVar']][0].')';
			
			if(isset($_SESSION['user_card']) && $_SESSION['user_card'] == '0')
				$p = $arrTestVar[$_POST['orderTestVar']][1];
			else
				$p = 0;
				
				
			$arrGoods[] = array(
					'itemName'	=> $n,
					'itemNum'	=> 1,
					'itemPrice' => $p,
					'itemSum' 	=> $p 
				);	
			// /для безнала
				
			$text .= '</table>';
		}
		else
			header('location:/order/');			
		/* /сформируем список заказа */
		
		
		
		
		/* обновим список купленных товаров для авторизованного пользователя */
		if(!empty($_SESSION['user_id']))
		{
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_users`
					WHERE id = ".$_SESSION['user_id'];
			$res = mysql_query($sql);
			
			if($res && mysql_num_rows($res) > 0)
			{
				$row = mysql_fetch_array($res);
				
				// прочитаем список уже заказанных ранее товаров
				if($row['user_pay'] == '' || !is_array(unserialize($row['user_pay'])))
				{
					$arrUserPay = array();
				}
				else $arrUserPay = unserialize($row['user_pay']);
				
				// добавим к этому списку новые покупки
				foreach($arrItemSelected as $k)
				{
					if(!in_array($k, $arrUserPay))
					{
						$arrUserPay[] = $k;
					}
				}	
				
				// обновим запись в БД
				$sql = "UPDATE `".$_VARS['tbl_prefix']."_users`
						SET user_pay = '".serialize($arrUserPay)."'
						WHERE id = ".$_SESSION['user_id'];
				$res = mysql_query($sql);			
			}
		}		
		/* /обновим список купленных товаров для авторизованного пользователя */
	
		
		
		//printArray($arrOrder);
		
		/* добавим заказ в БД */			
						
		$order = new ORDER();
		$order -> tbl = $_VARS['tbl_prefix'].'_order';
		
		isset($_SESSION['user_id']) ? $order -> client_id = $_SESSION['user_id'] : $order -> client_id = 0;
		
		//$order -> order_num		= $order_num;
		$order -> client_name 	= $_POST['orderClientName'];		// имя клиента
		$order -> client_contact = $oClientMail.'<br>\n('.$oClientPhone['c'].') '.$oClientPhone['n'];	// контакты клиента
		$order -> order_list	= serialize($arrOrder); //$text;			// заказ
		$order -> sum_full 		= $oClientSum;		// полная стоимость заказа	
		
		if($_POST['orderPayVar'] == 'sb' && isset($_POST['clientName']))
		{
			$order -> make_blank = true;
			$order -> client_urname	= $_POST['clientName'];
			$order -> order_arr_goods = $arrGoods;
		}
								
		//$order -> addOrder();
		
		
		
		if(isset($_SESSION['order_mode']) && $_SESSION['order_mode'] == 'edit')
		{
			/* редактирование заказа */
			$order -> id = $_SESSION['order_id'];
			$order -> editOrder();
						
			unset($_SESSION['order_mode']);
			header('location:/cms9/workplace.php?page=orders');	
			/* /редактирование заказа */
		}
		else
		{
			unset($_SESSION['order_mode']);
			$order -> addOrder();
		}
				
		
		// отправим заказ
				
		$to 	= array(
				'0'=> array(
					'name'	=> 'Администратору сайта', 
					'email'	=> $_VARS['env']['mail_admin']
					)
		);
		$cc 	= array();
		$bcc 	= array();
		$read 	= array();
		$reply 	= array();
	
		$sender = 'cakeberry@cakeberry.ru';
		$senderName = $_POST['orderClientName'];
		$subject = 'Заказ '.$order -> order_num.' посетителя сайта '.$_SERVER['HTTP_HOST'];
		
		$message = '
			<html>
				<head>
				  <title>Заказ '.$order -> order_num.' посетителя сайта</title>
				</head>
				<body>
					'.$text.'
				</body>
			</html>';
				
		$obj = new sendMail($to, $sender, $subject, $message, $cc, $bcc, $senderName, $read, true, $reply, true);
		
		// результат отправки письма
		$reg_send = $obj -> sendEmail(); 
		
		if($reg_send)
		{
			
			//printArray($_POST);
			/*// если отправка успешна, очистим массивы
			$_POST = array();*/	
			
			foreach($_SESSION['item'] as $k => $v)					
			{
				//$_SESSION['item'][$k]['item_in_basket'] = false;
				unset($_SESSION['item']);
			}		
			
			if($_SESSION['user_access'])	
			{
				//header('Location:/mypage/');
			}
			else
			{
				echo '<p class="msgError">Ваш заказ упешно отправлен.</p>';
				if($_POST['orderPayVar'] == 'sb' && isset($_POST['clientName']))
				{
					?><a target="_blank" href="/blocks/order.blank.php?id=<?=$order -> id?>">Распечатать счет для оплаты по безналичному расчету</a><?
				}
			}
			
			// если отправка успешна, очистим массивы
			$_POST = array();
		}
		else 
		{
			$textError = 'Ошибка отправки заказа';		
			echo '<p class="msgError">'.$textError.'</p>';
		}
	}
}

?>