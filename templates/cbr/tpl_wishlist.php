<?
include "cms9/modules/framework/class.image.php";
include "blocks/functions.php";
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
<script language="javascript" type="text/javascript" src="/js/script.sorter.js"></script>
<script language="javascript">
$(document).ready(function(){		
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* диалоговое окно напоминание   */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	$('.linkToCalend').click(function()
	{
		$('#itemId').attr('value', $(this).attr('name')); // id продукта
		$('#itemName label').text($(this).parent('.sliderCatalogItem').find('.itemName').text());
		$("#dialog-form-note").dialog("open");
		return false;
	})

	$( "#dialog-form-note").dialog({			
		<?
		if(!isset($autoOpen)) $autoOpen = "false";	
		//$autoOpen = "true";			
		?>
		autoOpen: <?=$autoOpen?>,
		height: 365,
		width: 650,
		modal: true,			
		close: function() {
			$('.resultNote').html('');
		}
	});	
	  
	var optionsNote = {
	  target: ".resultNote",
	  /*clearForm : true*/
	  resetForm : true,
	  success : function()
	  {
	  	//$('#formRef textarea, #formRef select, #formRef input').attr('disabled', true);
	  }
	};

	$(".formSubmitNote").click(function()
	{
		$('.resultNote').html('');
		$("#formNote").ajaxSubmit(optionsNote);
		return false;
	})	  
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* /диалоговое окно напоминание   */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	
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
		
		
		
		
		<div class="innerDiv catalog">
			
			<?
			include "blocks/block.nav.php";
			
			// Мой заказ(n)			
			myOrderLink();
			
			
			// Избранное
			fav_link();
			?>		
			
			<?
			$pages_num = 0;
			$select_limit 	= $_VARS['env']['limit_wishlist']; // сколько позиций на странице
			$select_from 	= 0;
			$select_order	= "ASC"; // порядок сортировки
			$arrWishList 	= array(); // создадим массив избранных продуктов 
			
			if(isset($_SESSION['user_access']) && $_SESSION['user_access'] == true)
			{
				
				$user_id = $_SESSION['user_id']; // id данного пользователя
		
				// прочитаем запись данного пользователя
				$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_users`
						WHERE id = ".$user_id;
				$res = mysql_query($sql);
				
				if($res && mysql_num_rows($res) > 0)
				{
					$row = mysql_fetch_array($res);
					
					// массив избранных продуктов
					if(trim($row['user_fav_item']) != "" && is_array(@unserialize($row['user_fav_item'])))
					{
						$arrWishList = unserialize($row['user_fav_item']);
					}		
				}
				
				// внесем в список избранных данный продукт, если его там нет
				if(isset($_GET['param2']))
				{									
					if(isset($_GET['param2']) && !in_array($_GET['param2'], $arrWishList)) 
					{
						$arrWishList[] = $_GET['param2'];
						
						// и обновим запись в БД
						$sql = "UPDATE `".$_VARS['tbl_prefix']."_users`
								SET user_fav_item = '".serialize($arrWishList)."'
								WHERE id = ".$user_id;
						$res = mysql_query($sql);
					}
				}
				
				// удаление продукта из избранного
				if(isset($_GET['remove']) && in_array($_GET['remove'], $arrWishList))
				{
					foreach($arrWishList as $k => $v)
					{
						if($v == $_GET['remove'])
						{
							unset($arrWishList[$k]);
							break;
						}
					}
					
					// обновим запись в БД
					$sql = "UPDATE `".$_VARS['tbl_prefix']."_users`
							SET user_fav_item = '".serialize($arrWishList)."'
							WHERE id = ".$user_id;
					$res = mysql_query($sql);
				}				
				
				$select_list = ""; // строка запроса (список выбираемых из каталога продуктов)
				$i = 0;
				
				// сформируем запрос
				foreach($arrWishList as $k)
				{
					$i++;
					$select_list .= "id = ".$k;
					if($i < count($arrWishList)) $select_list .= " OR ";
				}
				
				
				$pages_num = ceil(count($arrWishList) / $select_limit);					
				include "blocks/item.list.lister.php";										
				?>
				
					
				<div style="clear:both"></div>
				
				<!-- BEGIN контент -->	
				
				<div class="content">
				
					<h1>:::pageTitle:::</h1>
					
					<?
					include "blocks/item.sorter.php";
					?>
					
					<div style="clear:both"></div>
					
					<?
					include "blocks/wish.list.php";
					
					include "blocks/form.dialog.note.php";
					?>
					
					<div style="clear:both"></div>
					
				
				</div>			
				
				<!-- END контент -->
			<?
			}
			?>

		
			
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
