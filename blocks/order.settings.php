<?

//printArray($_GET);

// удаление продукта из корзины
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
	header('Location:/order/');
}

// сохранение способа доставки
if(isset($_GET['orderDelivVar']))
{
	if($_GET['orderDelivVar'] != '')
	{
		$_SESSION['orderDelivVar'] = $_GET['orderDelivVar'];
	}
	header('Location:/order/');
}

// сохранение расстояния доставки
if(isset($_GET['orderDelivRange']))
{
	if($_GET['orderDelivRange'] != '')
	{
		$_SESSION['orderDelivRange'] = $_GET['orderDelivRange'];
	}
	header('Location:/order/');
}

// сохранение способа дегустации
if(isset($_GET['orderTestVar']))
{
	if($_GET['orderTestVar'] != '')
	{
		$_SESSION['orderTestVar'] = $_GET['orderTestVar'];
	}
	header('Location:/order/');
}

if(isset($_GET['item_count']))
{
	if(!isset($_SESSION['item'][$_GET['param2']]['item_count']))
	{
		$_SESSION['item'][$_GET['param2']]['item_count'] = 1;
	}
	
	switch($_GET['item_count'])
	{
		case 'dec' : if($_SESSION['item'][$_GET['param2']]['item_count'] > 1) $_SESSION['item'][$_GET['param2']]['item_count']--; break;
		default : $_SESSION['item'][$_GET['param2']]['item_count']++; break;					
	}
	 
	 $item_price_all = $_SESSION['item'][$_GET['param2']]['item_count'] * $_SESSION['item'][$_GET['param2']]['item_price_one'];
	 $_SESSION['item'][$_GET['param2']]['item_price_all'] = $item_price_all;
	 
	 //printArray($_GET);
	 
	header('Location:/order/');
}



?>