<h2>История заказов</h2>
<?
//include $_SERVER['DOCUMENT_ROOT'].'/modules/shop/shop.class.php';

$orders = new ORDER();

$orders -> tbl 			= $_VARS['tbl_prefix'].'_order';
$orders -> user_id 		= $_SESSION['user_id'];
$orders -> order_by 	= 'id DESC';
$orders -> order_dir 	= '';

$arr = $orders -> getAllOrders();

$status = array(
	0 => 'новый',
	1 => 'выполнен',
	4 => 'оплачен',
	5 => 'отменен'
);

?>
<style>
.list{margin-top:20px; width:100%}
.list .user-order-table-head{display:none}
.order-block{display:none}
.user-order-table{border:0; /*display:none*/}
.user-order-table th, .user-order-table td{border:1px solid #dbdbdb}
/*.order-tr:hover{background-color:#eee}
*/.status-0{color:#009900}
.status-1{color:#333}
</style>

<script language="javascript">
$(document).ready(function()
{
	$('.show-order').click(function()
	{	
		$(this).next('div').toggle()
		

		$('.order-block').each(function()
		{
			if($(this).css('display') == 'block')
			{
				$(this).prev('.show-order').text('Скрыть содержание')
				$(this).parents('tr').css('background', '#eeeeee')
			}
			else
			{
				$(this).prev('.show-order').text('Открыть содержание')
				$(this).parents('tr').css('background-color', '')
			}
			
		})
		
		return false
	})

})
</script>

<?
if(!empty($arr))
{
?>

<table class="list">
	<tr valign="top">
		<th align="left">№ заказа</th>
		<th align="left">Дата оформления</th>
		<th>Содержание</th>
		<th>Стоимость</th>
		<th>Оплачено</th>
		<th>Статус заказа</th>
	</tr>
	<?
	foreach($arr as $k => $v)
	{
		$pay_url = '';
		
		$orders -> order_num 	= $v['id'];
		$orders -> sum_full 	= $v['sum_full'];
		$orders -> id 			= $v['id'];
		$orders -> order_desc 	= 'Оплата заказа № '.$v['order_num'];
		
		$pay_url = $orders -> getPayUrl();
		
		$order_blank = $orders -> getOrderBlank();
		
		?>
			<tr valign="top" class="order-tr">
				<td><?=$v['order_num']?></td>
				<td><?=$v['order_date']?></td>
				<td width='50%'><a class="show-order" href="#">Открыть содержание</a><div class="order-block">
				<?
				$orders -> order_list = $v['order_list'];					
					echo $orders -> printOrder();
				?>
				
				</div></td>
				<td align="center"><?=$v['sum_full']?></td>
				<td align="center"><? 	
					
				
					if($v['sum_payed'] > 0) 
						echo $v['sum_payed'].'<br />';
						
					if($v['order_status'] == 0)
					{	
						if(intval($v['sum_payed']) < intval($v['sum_full'])) 
						{ 
							?><a href="<?=$pay_url?>">оплатить онлайн</a><? 
						} 
						
						
						if(is_array($order_blank))
						{
							?><br /><a target="_blank" href="/blocks/order.blank.php?id=<?=$v['id']?>">счет</a><?
						}
					}
					
					
					?></td>
				<td align="center" class="status-<?=$v['order_status']?>"><?=$status[$v['order_status']]?></td>
			</tr>
		<?
	}
	?>
	
</table>
<?
}
?>