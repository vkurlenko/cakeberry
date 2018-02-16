<?
include "cms9/modules/framework/class.image.php";
include "cms9/modules/framework/class.html.php";
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

<!-- JS для карусель -->
<link href="/css/slider.calendar.css" rel="stylesheet" />
<link href="/css/slider.catalog.css" rel="stylesheet" />

<script language="javascript" type="text/javascript" src="/cms9/js/scroll_gallery/jquery.tools.min(1).js"></script>
<script language="javascript" type="text/javascript" src="/cms9/js/jquery.mousewheel-3.0.4.pack.js"></script>
<!-- /JS для карусель -->
<script language="javascript" type="text/javascript" src="/js/script.js"></script>
<script language="javascript">
$(document).ready(function(){
	
	/* инициализация карусели */
	
	$(".scrollable4, .scrollable2").scrollable();
	
	/* /инициализация карусели */
	
	$('#formUserdata input, #formUserdata textarea').click(function()
	{
		/*if($(this).selected()
		{
			$(this).select(false);
		}
		else $(this).select(true);*/
		
	})
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* диалоговое окно напоминание   */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	$('.sliderCalendarItem td').click(function()
	{
		$( "#dialog-form-note").dialog("open");
		
		// загрузим напоминания на выбранную дату		
		var dateSelected = $(this).attr('id');
		
		$('.notesList').load(
			'/blocks/ajax.load.notes.php',
			{
				date : dateSelected
			}
		);
		// /загрузим напоминания на выбранную дату	
		
		$('#formNote .prod select').attr('size', 5);
		
		// выделение выбранной даты
		var arr = dateSelected.split('.', 3);
		$('.note_date_d option').each(function()
		{
			$(this).attr('selected', false);
			if(parseInt($(this).attr('value'), 10) == parseInt(arr[0], 10)) $(this).attr('selected', true);			
		})
		
		$('.note_date_m option').each(function()
		{
			$(this).attr('selected', false);
			//alert(parseInt($(this).attr('value'), 10)+' == '+parseInt(arr[1]), 10)))
			if(parseInt($(this).attr('value'), 10) == parseInt(arr[1], 10)) $(this).attr('selected', true);			
		})
		
		$('.note_date_y option').each(function()
		{
			$(this).attr('selected', false);
			if(parseInt($(this).attr('value'), 10) == parseInt(arr[2], 10)) $(this).attr('selected', true);			
		})
		// /выделение выбранной даты
				
		return false;
	})

	$( "#dialog-form-note").dialog({			
		autoOpen: false,
		height: 365,
		width: 650,
		modal: true,			
		close: function() {
			$('.resultNote, .notesList').html('');
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
		
		
		
		
		<div class="innerDiv">
			
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
				
				<div class="userInfo">					
					<?
					include "blocks/user.data.php";
					include "blocks/form.dialog.userdata.php";
					?>							
					<div style="clear:both"></div>
				</div>			
			
			</div>			
			
			<!-- END контент -->	
			
			<!-- BEGIN карусель календарь -->
			<?
			include "blocks/slider.calendar.php";
			include "blocks/form.dialog.note.php";
			?>					
			<!-- END  карусель календарь -->
			
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
