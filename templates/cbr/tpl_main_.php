<?
include "cms9/modules/framework/class.image.php";
include "blocks/functions.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; windows-1251" />
<?
HTML::insertMeta();
?>

<link href="/css/style.css" rel="stylesheet" />

<!-- css для карусель-промо -->
<link href="/css/slider.promo.css" rel="stylesheet" />
<link href="/css/slider.catalog.css" rel="stylesheet" />
<!-- /css для карусель-промо -->

<script language="javascript" type="text/javascript" src="/js/jquery.1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery.form.js"></script>
<?
// jquery UI library
include "blocks/inc.js.ui.php";
?>
<script language="javascript" type="text/javascript" src="/js/script.js"></script>
<!-- JS для карусель -->
<script language="javascript" type="text/javascript" src="/js/scrollable/jquery.tools.min.js"></script>
<!--<script language="javascript" type="text/javascript" src="/cms9/js/jquery.mousewheel-3.0.4.pack.js"></script>-->
<!-- /JS для карусель -->

<script language="javascript">
$(document).ready(function(){
	
	/* инициализация карусели */	
	$(".scrollable").scrollable(
		{
			circular: true
		}) .autoscroll(
		{
			autoplay:true,
			interval:4000
		} 
	);
	
	$(".scrollable2").scrollable();
		
	/* /инициализация карусели */
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
		
		<!-- BEGIN карусель промо-->
		
		<?
		include "blocks/slider.promo.php";
		?>
		
		<!-- END  карусель промо-->
		
		
		<!-- BEGIN блок баннеров -->
		<div class="bannerBlock">
			<!--
			<div class="bannerSmall"><a href="#"><img src="/img/pic/banner_small_1.jpg" width="565" height="147" /></a></div>
			<div class="bannerSmall"><a href="#"><img src="/img/pic/banner_small_2.jpg" width="565" height="147" /></a></div>
			-->
			:::banner_line_1:::
			:::banner_line_2:::
			:::banner_line_3:::
			:::banner_line_4:::			
			<div style="clear:both"></div>
			
		</div>
		<!-- END  блок баннеров -->
		
		
		<div class="innerDiv">
				
			<!-- BEGIN карусель каталог -->
			<?
			include "blocks/slider.catalog.php";
			?>					
			<!-- END  карусель каталог -->
			
			
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
