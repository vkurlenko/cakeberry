<?
include "cms9/modules/framework/class.image.php";

include 'blocks/functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
if(!empty($arr))
{
	$arr = getParentPageName();
	$_PAGE['p_title'] = $arr['p_title'];
	
	if(isset($_GET['param2']))
	{
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_news`
				WHERE id = ".$_GET['param2'];
		$res = mysql_query($sql);
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_array($res);
			$_PAGE['p_title'] .= ' &gt; '.$row['news_title'];
		}
	}
}	

HTML::insertMeta();
?>

<link href="/css/style.css" rel="stylesheet" />

<!-- contentflow карусель -->
<script language="JavaScript" type="text/javascript" src="/js/ContentFlow/contentflow.js"></script>
<script language="javascript" src="/js/ContentFlow/ContentFlowAddOn_slideshow.js"></script>
<!-- /contentflow карусель -->

<script language="javascript" type="text/javascript" src="/js/jquery.1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery.form.js"></script>
<?
// jquery UI library
include "blocks/inc.js.ui.php";
?>
<script language="javascript" type="text/javascript" src="/js/script.js"></script>
<!-- fancybox -->
<link href="/css/fancy/jquery.fancybox-1.3.4.css" rel="stylesheet" />
<script language="JavaScript" type="text/javascript" src="/js/fancy/jquery.fancybox-1.3.4.pack.js"></script>
<!-- /fancybox -->

<script language="javascript">



$(document).ready(function(){
	//$('.item').click(function(){return false})
	
	// инициализация fancybox 
	$('a.item').fancybox({
		overlayShow		: true,
		overlayOpacity	: 0.9,
		padding 		: 0,
		overlayColor	: '#fff',
		titlePosition 	: 'inside',	
		autoScale		: true,	
		titleFormat		: function(title) 
		{
			return '<span id="fancybox-title-over">' + title + '</span>';
		}
  	})
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
		if(isset($_GET['param2']))
		{
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_news`
					WHERE id = ".$_GET['param2'];
			$res = mysql_query($sql);
			if($res && mysql_num_rows($res) > 0)
			{
				$row = mysql_fetch_array($res);				
			
		?>
		
		<div class="innerDiv chrono">
			
			<?
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pages`
					WHERE id = ".$_PAGE['p_parent_id'];
			$res2 = mysql_query($sql);
			
			$row2 = mysql_fetch_array($res2);
						
			
			$arrNav = array(
				array("/".$row2['p_url']."/", $row2['p_title']),
				array("", $row['news_title'])
			);
			
			include "blocks/block.nav.php";
			?>
			
			<?
			if($row['news_src'] != "")
			{
				?>
				<div class="pageLister"><a href="<?=$row['news_src']?>">Посмотреть этот материал на Facebook</a></div>
				<?
			}
			?>
			<div style="clear:both"></div>
			
				
			<!-- BEGIN контент -->	
			
			<div class="content">
			
				<h1><?=$row['news_title']?></h1>
				
				<div class="eventLister">
					<p>Читайте также и другие материалы:</p>
					<?
					$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_news`
							WHERE news_date < '".$row['news_date']."'
							ORDER BY news_date DESC
							LIMIT 0,1";
					$res2 = mysql_query($sql);
					if($res2 && mysql_num_rows($res2) > 0)
					{
						$row2 = mysql_fetch_array($res2);
						?><a href="/event/<?=$row2['id']?>/">Предыдущий</a><?
					}
					
					$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_news`
							WHERE news_date > '".$row['news_date']."'
							ORDER BY news_date ASC
							LIMIT 0,1";
					$res2 = mysql_query($sql);
					if($res2 && mysql_num_rows($res2) > 0)
					{
						$row2 = mysql_fetch_array($res2);
						?><a href="/event/<?=$row2['id']?>/">Следующий</a><?
					}					
					?>
					
					
				</div>
				
				<div style="clear:both"></div>
				
				<div class="eventText">
					<div class="eventHalf">
					<?=$row['news_text_2']?>
					</div>	
					<div style="clear:both"></div>
				</div>
				
				<?
				$alb = $row['news_alb'];
				include "blocks/slider.event.php";
				?>	
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
		
		<?
			}
		}
		?>
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