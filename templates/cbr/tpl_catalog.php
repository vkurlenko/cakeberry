<?
include "cms9/modules/framework/class.image.php";
include "blocks/functions.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?
if(isset($_GET['param2']))
	$_PAGE['p_meta_title'] = getItemCategory($_GET['param2'], 'item_name');

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
<script language="javascript" type="text/javascript" src="/js/script.sorter.js"></script>

<script language="javascript">


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
		
		
		
		
		<div class="innerDiv catalog">
			
			<?
			include "blocks/block.nav.php";
			
			// Мой заказ(n)			
			myOrderLink();
			
			
			// Избранное
			fav_link();
			
			
			$pages_num = 0;
			
			$parent = 0;
			
			if(isset($_GET['param2']))
				$parent = $_GET['param2'];
			
			if(isset($_GET['param2']))
			{
				$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
						WHERE item_parent = ".$parent."
						AND item_show = '1'";
						
				$res = mysql_query($sql);
				
				if($res && mysql_num_rows($res) > 0)
				{
					$pages_num = ceil(mysql_num_rows($res) / $_VARS['env']['limit_catalog']);
				}					
				//include "blocks/item.list.lister.php";	
			}		
			?>
			
			
			
				
			<div style="clear:both"></div>
			
			<!-- BEGIN контент -->	
			
			<div class="content">
			
				<?
				$h1_inner = "";
				if(isset($parent))
				{
					$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
							WHERE id = ".$parent;
					$res = mysql_query($sql);
					if($res && mysql_num_rows($res) > 0)
					{
						$row = mysql_fetch_array($res);
						$h1_inner = $row['item_name'];
					}
				}
				?>
			
				<h1><!--Свадебные торты--><?=$h1_inner?></h1>
				
				<?
				include "blocks/item.sorter.php";
				include "blocks/item.list.lister.php";				
				?>
				
				<div style="clear:both"></div>
				
				<?
				include "blocks/item.list.php";
				?>
				
				<div style="clear:both"></div>
				
				
				<div class="footLinks">
					<a class="backLink" href="/catalog/">Вернуться в каталог</a>				
					<?
					include "blocks/item.list.lister.php";	
					?>
				</div>
				<div style="clear:both"></div>
			
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
