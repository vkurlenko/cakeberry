<?
/* ajax сохранением кол-ва товара с пересчетом общей стоимости */

session_start();


if(isset($_POST['id']))
{
	if(isset($_SESSION['item'][$_POST['id']]))
	{
		if(isset($_POST['count']))
		{
			// сохраним в сессию новое кол-во товара
			$_SESSION['item'][$_POST['id']]['item_count'] = $_POST['count'];
			
			// пересчитаем и сохраним в сессии общую стоимость этого товара
			$_SESSION['item'][$_POST['id']]['item_price_all'] = $_POST['count'] * $_SESSION['item'][$_POST['id']]['item_price_one'];
		}
	}
}
?>