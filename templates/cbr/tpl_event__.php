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
<!--<script language="JavaScript" type="text/javascript" src="/js/ContentFlow/contentflow.js"></script>
<script language="javascript" src="/js/ContentFlow/ContentFlowAddOn_slideshow.js"></script>-->
<!-- /contentflow карусель -->

<link rel="stylesheet" href="/js/imageflow/imageflow.css" type="text/css" />
<script src="/js/imageflow/imageflow.packed.js" type="text/javascript"></script>


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
domReady(function()
{
	var instanceOne = new ImageFlow();
	instanceOne.init({ 
		ImageFlowID: 	'myImageFlow1',
		circular:		true,
		reflections: 	true,
		reflectPath:	'<?=DIR_JS.'/imageflow/'?>',
		reflectionP: 	0.6,
		slider: 		false,
		captions: 		false,
		opacity: 		false,
		imageFocusMax:  3,  
		xStep: 			150, // расстояние м\у кадрами
		imageFocusM: 	1, // масштаб кадра в фокусе
		percentLandscape:   100, 
		imagesHeight : 	0.5,
		imagesM:		1.2,
		aspectRatio:	5,
		imageCursor:	'pointer',
		buttons : 		true,
		onClick:        function() { return false}
	});
});

</script>

<script language="javascript">



$(document).ready(function(){
	//$('.item').click(function(){return false})
	
	// инициализация fancybox 
	//$('a.item').fancybox({
	$('img.content').fancybox({
		overlayShow		: true,
		overlayOpacity	: 0.8,
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

	<div id="mainDiv">
				
				<?
				$alb = 5;
				include "blocks/slider.event.imageflow.php";
				?>			
	
	</div>
 
</body>
</html>