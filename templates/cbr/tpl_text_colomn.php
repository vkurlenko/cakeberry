<?
include_once $_SERVER['DOC_ROOT']."/cms9/modules/framework/class.image.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<title>:::pageTitle:::</title>-->
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
		
		
		
		<?
		/*if($_PAGE['p_url'] == "about")
			$clsAdd = "bgAbout";
		else
			$clsAdd = '';*/
			
		if($_PAGE['p_img'] > 0)
		{
			$img = new Image();
			$img -> imgCatalogId 	= $_VARS['env']['photo_alb_background'];
			$img -> imgId 			= $_PAGE['p_img'];
			$img -> imgAlt 			= "";
			$img -> imgWidthMax 	= 1200;
			$img -> imgHeightMax 	= 10000;	
			$img -> imgMakeGrayScale= false;
			$img -> imgGrayScale 	= false;
			$img -> imgTransform	= "resize";
			
			$html = $img -> showPic();			
			$path = $img -> imgPath;
			
			$styleInnerDiv = "style='background:url(/$path) no-repeat 0 40px;'";
			$styleContentDiv = "style='min-height:".($img->imgH - 25)."px'";
			//echo $path;			
		}
		else
			$styleInnerDiv = $styleContentDiv = '';
		?>
		<div class="innerDiv bgAbout" <?=$styleInnerDiv?>>
			
			<?
			include "blocks/block.nav.php";
			// Мой заказ(n)			
			myOrderLink();
			
			
			// Избранное
			fav_link();
			?>
			<div style="clear:both"></div>	
				
			<!-- BEGIN контент -->	
			
			<div class="content" <?=$styleContentDiv?>>
			
				<h1>:::pageTitle:::</h1>
				
				<?
				// если запрос на подтверждение регистрации
				if($_PAGE['p_url'] == 'confirm')
				{
					include 'blocks/reg.confirm.php';
				}
				?>
				:::content:::			
				
				
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