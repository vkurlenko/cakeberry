<?
if(!isset($_SESSION['gallery']) || !is_array($_SESSION['gallery']))
{
	$_SESSION['gallery'] = array(
		'galleryType' 	=> '',
		'galleryM'		=> 0,
		'galleryY'		=> 9999 //date('Y')
	);
}

if(!empty($_POST) && isset($_POST['gallerySubmit']))
{
	$_SESSION['gallery'] = array(
		'galleryType' 	=> $_POST['galleryType'],
		'galleryM'		=> $_POST['galleryM'],
		'galleryY'		=> $_POST['galleryY']
	);
	
	header('Location: /gallery/');
}	


include_once 'blocks/vars.php';
include_once 'blocks/functions.php';

include_once 'cms9/modules/common/video/functions.php';
include_once 'cms9/modules/framework/class.image.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
$str = $_PAGE['p_title'];
foreach(galleryNav() as $k => $v)
{
	$str .= ' &gt; '.$v[1];
}
unset($arrNav);

$_PAGE['p_title'] = $str;

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
<link href="/css/slider.gallery.css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="/cms9/js/scroll_gallery/jquery.tools.min(1).js"></script>
<script language="javascript" type="text/javascript" src="/cms9/js/jquery.mousewheel-3.0.4.pack.js"></script>
<!-- /JS для карусель -->

<!-- fancybox -->
<!--<link href="/css/fancy/jquery.fancybox-1.3.4.css" rel="stylesheet" />-->
<link href="/css/fancy2/jquery.fancybox.css" rel="stylesheet" />
<!--<script language="JavaScript" type="text/javascript" src="/js/fancy/jquery.fancybox-1.3.4.pack.js"></script>
--><script language="JavaScript" type="text/javascript" src="/js/fancy2/jquery.fancybox.js"></script>
<!-- /fancybox -->
<script language="javascript" type="text/javascript" src="/js/script.js"></script>
<style>
.active{position:relative; display:block}
.frame{position:absolute; border:4px solid #906; width:117px; height:76px; z-index:5; top:0}
</style>

<script language="javascript">
$(document).ready(function(){
	
	/* инициализация карусели */
	$(".scrollable3").scrollable();	
	
	// удалим пустые контейнеры
	$('.item .sliderGalleryItem').each(function()
	{		
		if($(this).html() == '')
			$(this).remove();
	})
	$('.item').each(function()
	{		
		if($(this).html() == '')
			$(this).remove();
	})
	// /удалим пустые контейнеры
	/* /инициализация карусели */
	
	
	// инициализация fancybox 
	$('a.viewGallery').each(function()
	{
		var title = $(this).find('img').attr('title');
		$(this).attr('title', title);
	})
	
	var fancyOptions = {
		overlayShow		: true,
		autoPlay 		: true,
		loop 			: true,
		overlayOpacity	: 0.8,
		padding 		: 0,
		overlayColor	: '#fff',
		helpers : {
    		title : {type : 'outside'}
    	},
		openEffect	: 'fade',
		closeEffect	: 'fade',
		nextEffect	: 'fade',
		prevEffect	: 'fade',
		arrows 		: true,
		autoScale	: true		
  	};
	
	$('a[rel=fancy]').fancybox(fancyOptions);
	
	
	$('.galleryPlay a, .galleryPlay img').click(function()
	{
		$.fancybox.open($('a[rel=fancy]'), fancyOptions);
		return false;
	})
	/*******************/
	/* ролловер тумбов */
	/*******************/
	
	var img_prefix = "125x84";	
	var thumb = $('.sliderGalleryItem a.noactive img');
	
	$('.sliderGalleryItem a.active img').each(function(){
		var src = $(this).attr('src');
		var new_src = src.replace(img_prefix+'-mono.', img_prefix+'.');		
		var img = new Image();
		img.src = new_src;
		$(this).attr('src', new_src);		
	})
	
	$(thumb).each(function(){
		var src = $(this).attr('src');
		var new_src = src.replace(img_prefix+'-mono.', img_prefix+'.');		
		var img = new Image();
		img.src = new_src;	
	})
	
	$(thumb).each(function()
	{
		var src = $(this).attr('src');
		var new_src = src.replace("-"+img_prefix+'-mono.', '.');		
		var img = new Image();
		img.src = new_src;		
	})		
	
	$(thumb).mouseover(function(){
		var src = $(this).attr('src');
		var new_src = src.replace(img_prefix+'-mono.', img_prefix+'.');
		$(this).attr('src', new_src);
		
	}).mouseout(function(){
		var src = $(this).attr('src');
		var new_src = src.replace(img_prefix+'.', img_prefix+'-mono.');
		$(this).attr('src', new_src);
	});
	
	
	$('.iconVideo').mouseover(function(){
		var src = $(this).prev(thumb).attr('src');
		var new_src = src.replace(img_prefix+'-mono.', img_prefix+'.');
		$(this).prev(thumb).attr('src', new_src);
		
	}).mouseout(function(){
		var src = $(this).prev(thumb).attr('src');
		var new_src = src.replace(img_prefix+'.', img_prefix+'-mono.');
		$(this).prev(thumb).attr('src', new_src);
	});
	
		
	/********************/
	/* /ролловер тумбов */
	/********************/
	
	// рамка над активным элементом
	$('.active').append('<div class="frame"></div>');
	
	// для ролика с yuotube делаем запрет показа похожих
	var youtube_src = $('iframe').attr('src');
	$('iframe').attr('src', youtube_src + '?rel=0');
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
		
		
		
		
		<div class="innerDiv chrono">
			
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
				
				<!-- BEGIN выбор диапазона событий -->
				<?
				include 'blocks/gallery.options.php';
				?>
				
				<!-- END выбор диапазона событий -->
				
				<div style="clear:both"></div>
				
				<!-- BEGIN галерея -->
				<div class="galleryView">
					<?
					include 'blocks/gallery.item.collection.php';
					
					if(isset($_GET['param2']))
					{
						if($_GET['param2'] == 'video') 
							include 'blocks/gallery.item.view.video.php';
						elseif($_GET['param2'] == 'alb') 
							include 'blocks/gallery.item.view.alb.php';
						else{}
					}					
					?>
					
					<div style="clear:both"></div>				
				
				</div>
				
				<!-- BEGIN карусель галерея -->
				<?
				include "blocks/slider.gallery.php";
				?>					
				<!-- END  карусель галерея -->
							
				
				<!-- END галерея -->
				
			
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