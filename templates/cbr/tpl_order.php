<?
include_once "cms9/modules/framework/class.image.php";
include_once "blocks/functions.php";
include_once "blocks/vars.php";
include_once "blocks/order.settings.php";

//$_SESSION['item'] = array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
HTML::insertMeta();
?>

<link href="/css/style.css" rel="stylesheet" />

<script language="javascript" type="text/javascript" src="/js/jquery.1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery.form.js"></script>
<?
// jquery UI library
include "blocks/inc.js.ui.php";
?>
<script language="javascript" type="text/javascript" src="/js/script.js"></script>
<script language="javascript">
$(document).ready(function(){
	$('.orderConfirm a').click(function()
	{
		$('#orderForm').submit();
		return false;
	})
	
	
	/********************************/
	/* выравнивание высоты textarea */
	/********************************/	
	var h = $('.orderP10').height();	
	
	var obj = $('.orderP11 textarea');
	
	var objH = h - getRealSize($(obj).css('margin-top')) 
				- $('.orderP11 span').height() 
				- getRealSize($('.orderP11').css('margin-top')) 
				- getRealSize($('.orderForm td').css('padding-top')) * 2
				+ getRealSize($('.orderP10').css('margin-top'));
	
	$(obj).height(objH);
	/*********************************/
	/* /выравнивание высоты textarea */
	/*********************************/	
	
	
})

</script>

</head>

<body>

	<div id="mainDiv">
	
		<!-- BEGIN верхний блок -->		
		<div class="topBlock">
			<? 
			include "blocks/block.top.php";
			?>
		</div>		
		<!-- END верхний блок -->
		
		
		<!-- BEGIN главное меню -->				
		<?
		include "blocks/menu.main.php";
		?>			
		<!-- END  главное меню -->
		
		
		
		
		<div class="innerDiv order">
			
			<?
			include "blocks/block.nav.php";
			
			// Мой заказ(n)			
			myOrderLink();
			
			
			// Избранное
			fav_link();
			?>
			
			
			<div style="clear:both"></div>
				
			<!-- BEGIN контент -->	
			
			<div class="content">
			
				<h1>:::pageTitle:::</h1>				
				
				<?
				
				include "blocks/order.mail.php";
				//printArray($_SESSION);
				include "blocks/order.list.php";
				
				//printArray($_SESSION['item']);
				?>								
			</div>					
			
			<!-- END контент -->	
			
			
			
			
			<!-- BEGIN нижнее меню -->
			
			<div class="menuFoot">
			
				<?
				include "blocks/menu.foot.php";
				?>
				
				<div style="clear:both"></div>		
			</div>
			
			<!-- END  нижнее меню -->
		</div>
		
		<!-- BEGIN подвал -->
		
		<div class="footer">
			<?
			include "blocks/block.foot.php";
			?>
			
			<div style="clear:both"></div>
			
		</div>
		
		<!-- END подвал -->
		
			
	
	</div>
 
</body>
</html>
