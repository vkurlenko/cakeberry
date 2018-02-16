<?
include "cms9/modules/framework/class.image.php";
include "blocks/functions.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
if(isset($_GET['param2']))
{

	$_PAGE['p_meta_title'] = getItemParent($_GET['param2']).' &laquo;'.getItemCategory($_GET['param2'], 'item_name_2').'&raquo;';

	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
			WHERE id = ".$_GET['param2'];
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_assoc($res);
		if(trim($row['item_m_title']) != '')
			$_PAGE['p_meta_title'] = $row['item_m_title'];
			
		$_PAGE['p_meta_kwd'] = $row['item_m_kwd'];
		$_PAGE['p_meta_dscr'] = $row['item_m_dscr'];
	}
}





HTML::insertMeta();
?>

<link href="/css/style.css" rel="stylesheet" />
<link href="/css/slider.catalog.css" rel="stylesheet" />

<script language="javascript" type="text/javascript" src="/js/jquery.1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery.form.js"></script>

<?
// jquery UI library
include "blocks/inc.js.ui.php";
//$_SESSION['item'] = array();
?>

<!-- JS для карусель -->
<script language="javascript" type="text/javascript" src="/cms9/js/scroll_gallery/jquery.tools.min(1).js"></script>
<script language="javascript" type="text/javascript" src="/cms9/js/jquery.mousewheel-3.0.4.pack.js"></script>
<!-- /JS для карусель -->
<script language="javascript" type="text/javascript" src="/js/script.js"></script>
<script language="javascript">
$(document).ready(function(){
	
	/* инициализация карусели-промо */	
	$(".scrollable, .scrollable2").scrollable();	
	/* /инициализация карусели-промо */
	
	/********************************/
	/* выпадающие списки параметров */
	/********************************/
	function arrow(obj, space)
	{
		var ul = $(obj).prev('ul.itemParam').find('li.active');		
		$(obj).css({'margin-left' : $(ul).width() + space})
	}
	
	$('img.arrowDown').each(function()
	{		
		arrow($(this), 15)		
	}).click(function()
	{
		var ul = $(this).prev('ul.itemParam');			

		if($(ul).is('.itemParamOpen'))	
		{
			$(ul).removeClass('itemParamOpen')
			$('ul.itemParam').parent('div').removeClass('top')
			
			arrow($(this), 15)				
		}
		else 
		{
			$('ul.itemParam').removeClass('itemParamOpen')
			$(ul).addClass('itemParamOpen')
			$('ul.itemParam').parent('div').removeClass('top')
			$(ul).parent('div').addClass('top')
			
			var thisArrow = $(this)
			$('img.arrowDown').each(function()
			{
				arrow($(this), 15)		
			})	
			
			arrow($(thisArrow), 20)
		}
	})	

	$('*').click(function(e){
		if($(this).attr('class') != 'arrowDown')
		{
			$('ul.itemParam').removeClass('itemParamOpen')
			$('ul.itemParam').parent('div').removeClass('top')
			
			$('img.arrowDown').each(function()
			{
				arrow($(this), 15)			
			})			
		}
		
		if(e.stopPropagation) e.stopPropagation()
		else e.cancelBubble = true;		
	})
	/*********************************/
	/* /выпадающие списки параметров */
	/*********************************/
	
	

	$('.itemInner ul li').hover(function()
		{
			// покажем увеличенное изображение начинки
			var a = $(this).find('a');
			var img = $(this).find('img').attr('src');
			var alt = $(this).find('img').attr('alt');
			
			img = img.replace('30', '300');
			img = img.replace('18', '200');
					
			$(a).after('<div class="zoom"><img src="'+img+'"><br><span>'+alt+'</span></div>');
			$(this).find('.zoom').show();
		}, function()
		{
			// спрячем 
			$('.zoom').remove();
		}
	)
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* диалоговое окно оставить отзыв    */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	$('.itemRef a').click(function()
	{
		$( "#dialog-form-ref").dialog("open");
		return false;
	})

	$( "#dialog-form-ref").dialog({			
		<?
		if(!isset($autoOpen)) $autoOpen = "false";	
		//$autoOpen = "true";			
		?>
		autoOpen: <?=$autoOpen?>,
		height: 365,
		width: 650,
		modal: true,			
		close: function() {
			$('.result').html('');
		}
	});	
	  
	var options = {
	  target: ".result",
	  /*clearForm : true*/
	  resetForm : false,
	  success : function()
	  {
	  	$('#formRef textarea, #formRef select, #formRef input').attr('disabled', true);
	  }
	};

	$(".formSubmitRef").click(function()
	{
		$('.result').html('');
		$("#formRef").ajaxSubmit(options);
		return false;
	})	  
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* /диалоговое окно оставить отзыв   */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* диалоговое окно напоминание   */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	$('.itemToCalend').click(function()
	{
		$( "#dialog-form-note").dialog("open");
		$('#itemId').attr('value', '<?=$_GET['param2']?>');
		$('#itemName label').text($('.itemInfo h1').text());
		
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
		
		
		
		<div class="innerDiv itemCardPage">
			
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
			
				<div class="itemCard">
					<?
					include "blocks/item.card.php";					
					
					include "blocks/form.dialog.ref.php";
					include "blocks/form.dialog.note.php";
					?>
				</div>				
			
			</div>			
			
			<!-- END контент -->	
			
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
