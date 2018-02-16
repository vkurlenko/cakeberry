<?
include "cms9/modules/framework/class.image.php";
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
	
	
})

</script>

</head>

<body>
	<?
	include "blocks/fb.php";
	?>
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
		
		
		
		
		<div class="innerDiv chrono">
			
			<?
			$arrNav = array(
				array("/".$_PAGE['p_url']."/", $_PAGE['p_title'])
			);
			
			include "blocks/block.nav.php";
			?>
			
			<div class="pageLister"><a href="#">Посмотреть хронику на Facebook</a></div>
			<div style="clear:both"></div>
				
			<!-- BEGIN контент -->	
			
			<div class="content">
			
				<h1>:::pageTitle:::</h1>
				
				<!-- BEGIN выбор диапазона событий -->
				<?
				include "blocks/chrono.param.php";
				?>
				<!-- END выбор диапазона событий -->
				
				<div style="clear:both"></div>
				
				<!-- BEGIN список событий -->
				
				<?
				include "blocks/chrono.list.php";
				?>				
				
				<!-- END  список событий -->
				
			
			</div>			
			
			<!-- END контент -->	
			
			
			
			
			<!-- BEGIN нижнее меню -->
			
			<div class="menuFoot menuFootS">
			
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