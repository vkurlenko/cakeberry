<?
$arrItem = array();

// выборка по дате

if(!isset($_SESSION['gallery']['galleryY']) || $_SESSION['gallery']['galleryY'] == 9999)
	$_SESSION['gallery']['galleryY'] = '%';

if(intval($_SESSION['gallery']['galleryM']) == 0)
{
	$selectDate = $_SESSION['gallery']['galleryY'].'-';
}
else 
	$selectDate = $_SESSION['gallery']['galleryY'].'-'.$_SESSION['gallery']['galleryM'].'-';

if($_SESSION['gallery']['galleryType'] == 'video' || $_SESSION['gallery']['galleryType'] == '')
{
	// добавим в галерею видео по убыванию даты
	$sql = "SELECT * FROM  `".$_VARS['tbl_prefix']."_video`
			WHERE video_show = '1'
			AND video_create LIKE '".$selectDate."%'
			ORDER BY video_create DESC";
	
			
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_array($res))
		{
			$th_url = '';
			if($row['video_img'] == 0)
			{
				if(trim($row['video_html']) != '')
				{
					// генерируем ссылку на превьюшку
					$url = getVideoUrl(htmlspecialchars_decode(trim($row['video_html'])));			
					$th_url = getVideoThumbUrl($url);
					$th_url = '<img src="'.$th_url.'" width=125 height=84 />';
				}
			}
			else
			{
				$img = new Image();
				$img -> imgCatalogId 	= $_VARS['env']['photo_alb_video_preview'];
				$img -> imgId 			= $row['video_img'];		
				$img -> imgClass 		= "viewGallery";
				
				$img -> imgWidthMax 	= 125;
				$img -> imgHeightMax 	= 84;	
				$img -> imgMakeGrayScale= true;
				$img -> imgGrayScale 	= true;
				$img -> imgTransform	= "crop";
				$th_url = $img -> showPic();			
			}
					
			
			$arrItem[$row['video_create']][] = array('video', $row['id'], $th_url);
		}
	}
	
}



if($_SESSION['gallery']['galleryType'] == 'alb' || $_SESSION['gallery']['galleryType'] == '')
{
	// добавим альбомы
	$sql = "SELECT * FROM  `".$_VARS['tbl_prefix']."_pic_catalogue`
			WHERE alb_mark = 'gallery'
			AND alb_update LIKE '".$selectDate."%'
			ORDER BY alb_update DESC";
	$res = mysql_query($sql);
	//echo $sql;
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_array($res))
		{
			$th_url = '';	
			
			if($row['alb_img'] == 0)
			{
				$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic_".$row['alb_name']."`
						WHERE 1 
						ORDER BY id DESC
						LIMIT 0,1";
				$res_i = mysql_query($sql);
				if($res_i && mysql_num_rows($res_i) > 0)
				{
					$row_i = mysql_fetch_array($res_i);
					$imgId = $row_i['id'];
				}
			}
			else $imgId = $row['alb_img'];
			
			
			
			$img = new Image();
			$img -> imgCatalogId 	= $row['alb_name'];
			$img -> imgId 			= $imgId;
			//$img -> imgAlt 			= $imgAlt;
			$img -> imgClass 		= "";
			$img -> imgWidthMax 	= 125;
			$img -> imgHeightMax 	= 84;	
			$img -> imgMakeGrayScale= true;
			$img -> imgGrayScale 	= true;
			$img -> imgTransform	= "crop";
			
			if(file_exists($img -> absPath()))
			{
				$html = $img -> showPic();
			
			
				$th_url = $html;
				
				$arrItem[$row['alb_update']][] = array('alb', $row['alb_name'], $th_url);
			}		
		}
	}
}

//if(!empty($arrItem))

foreach($arrItem as $k => $v)
{
	foreach($v as $b)
	{
		if(is_array($b)) 
		{
			//printArray($b);
			if(!isset($_GET['param2'])) $_GET['param2'] = $b[0];
			if(!isset($_GET['param3'])) $_GET['param3'] = $b[1];
			break;
		}
	}
	break;
}
?>
