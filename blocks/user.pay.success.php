<?
if(isset($_REQUEST["OutSum"]) && isset($_REQUEST["InvId"]))
{
	$order_s = new ORDER();
	$order_s -> tbl = $_VARS['tbl_prefix'].'_order';
	$order_s -> id	= $_REQUEST["InvId"];
	$arr = $order_s -> getOrder();
	
	
	$res = $order_s -> getPaySuccess();
	
	if($res)
	{		
		?>
		<div class="resSuccess">Оплата заказа №<?=$arr['order_num']?> на сумму <?=$_REQUEST["OutSum"]?> руб. прошла успешно.</div>
		<?		
	}
	else
	{
		?>
		<div class="resFail">Оплата заказа №<?=$arr['order_num']?> на сумму <?=$_REQUEST["OutSum"]?> руб. НЕ ПРОШЛА.</div>
		<?
	}
}
?>