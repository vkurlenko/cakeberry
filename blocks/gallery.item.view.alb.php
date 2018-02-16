<?
/* слайд-шоу альбом */

$alb_cover = '';
$alb_title = '';
$alb_descr = '';
$alb_date  = '';
$alb_start_path = '#';

if(isset($_GET['param3']))
{
	$alb_id = $_GET['param3'];

	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic_catalogue`
			WHERE alb_name = ".$alb_id;			
	$res = mysql_query($sql);
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		
		$imgCover = 0;
		
		// если обложка альбома не указан, 
		// выбираем из альбома самую новую картинку
		if($row['alb_img'] == 0)
		{
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic_".$alb_id."`
					WHERE 1 
					ORDER BY id DESC
					LIMIT 0,1";
			$res_i = mysql_query($sql);
			if($res_i && mysql_num_rows($res_i) > 0)
			{
				$row_i = mysql_fetch_array($res_i);
				$imgCover = $row_i['id'];				
			}
		}
		else $imgCover = $row['alb_img'];
		
		if($imgCover != 0)
		{
		
			$img = new Image();
			$img -> imgCatalogId 	= $alb_id;
			$img -> imgId 			= $imgCover;		
			$img -> imgClass 		= "viewGallery";
			$img -> imgRel 			= "fancy";
			$img -> imgTitle 		= "DEFAULT";
			$img -> imgWidthMax 	= 640;
			$img -> imgHeightMax 	= 360;	
			$img -> imgMakeGrayScale= false;
			$img -> imgGrayScale 	= false;
			$img -> imgTransform	= "crop";
			$alb_cover = $img -> showPic();
			
			$alb_title = $row['alb_title'];
			$alb_descr = $row['alb_text'];
			$alb_date  = $row['alb_update'];
			
			$img2 = new Image();
			$img2 -> imgCatalogId 	= $alb_id;
			$img2 -> imgId 			= $imgCover;		
			$img2 -> imgClass 		= "viewGallery";
			$img2 -> imgRel 		= "fancy";
			$img2 -> imgTitle 		= "DEFAULT";
			$img2 -> imgWidthMax 	= 1920;
			$img2 -> imgHeightMax 	= 1200;	
			$img2 -> imgMakeGrayScale= false;
			$img2 -> imgGrayScale 	= false;
			$img2 -> imgWaterMark	= '/img/pic/cakeberry_watermark.png';
			$img2 -> imgTransform	= "resize";
			$img2 -> showPic();
			$alb_start_path = $img2->imgPath;
		}
		else
		{
			?><p>Альбом не найден.</p><?
		}
		
	}
	else 
	{
		?><p>Альбом не найден.</p><?
	}
}
else 
{
	?><p>Альбом не найден.</p><?
}
?>

<div class="galleryVideo">
	<!--<img src="/img/pic/video.jpg" width="640" height="360" />--><a class="viewGallery" rel="fancy" href="/<?=$alb_start_path?>"><?=$alb_cover?></a>					
	<div class="galleryPlay">
		<img src="/img/tpl/arrow_play.png"><a href="#">Смотреть фотоальбом</a>
	</div>
</div>
<div class="galleryViewDescr">
	<div>
		<span class="galleryItemCategory">Фотоальбом:</span>&nbsp;<!--ДЕНЬ РОЖДЕНИЯ ВАСИЛИСЫ ПРЕМУДРОЙ--><span class="galleryItemTitle"><?=$alb_title?></span>
		<!--<p>Коржи для большинства тортов изготовляются из бисквитного или песочного теста, классические торты представляют собой комбинацию из тонкого песочного коржа (для более стабильного основания) и одного или нескольких бисквитных коржей.</p>-->
		<p><?=$alb_descr?></p>
		<span class="galleryItemDate"><!--12 апреля 2012--><?=@format_date_to_str($alb_date, $sel_2 = " ", $year = true )?></span>
	</div>	
</div>

<div class="slideShow" style="display:none">
	<?
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic_".$alb_id."`
			WHERE 1 
			ORDER BY id DESC";
	$res_i = mysql_query($sql);
	if($res_i && mysql_num_rows($res_i) > 0)
	{
		while($row_i = mysql_fetch_array($res_i))
		{
			if($row_i['id'] == $imgCover) continue;
			$img_title = $row_i['name'];
			$img_src  = '/pic_catalogue/'.$_VARS['tbl_prefix'].'_pic_'.$alb_id.'/'.$row_i['id'].'.'.$row_i['file_ext'];
			//echo $img_src;
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$img_src))
			{
				$imgInfo = getimagesize($_SERVER['DOCUMENT_ROOT'].$img_src);
					
				$img = new Image();
				$img -> imgCatalogId 	= $alb_id;
				$img -> imgId 			= $row_i['id'];
				//$img -> imgAlt 			= $imgAlt;
				$img -> imgClass 		= "";
				$img -> imgWidthMax 	= 1920;
				$img -> imgHeightMax 	= 1200;	
				$img -> imgMakeGrayScale= false;
				$img -> imgGrayScale 	= false;
				$img -> imgTitle 		= "DEFAULT";
				$img -> imgTransform	= "resize";
				$img -> imgWaterMark	= '/img/pic/cakeberry_watermark.png';
				
				$html = $img -> showPic();
				
				?>
				<a class="viewGallery" rel="fancy" title="<?=$img_title?>" href="/<?=$img -> imgPath;?>"><?=$html?></a>
				<?
			}						
		}		
	}
	?>
</div>