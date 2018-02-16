<?
/***********************/
/* карусель на главной */
/***********************/

if($alb > 0)
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic_".$alb."`
			WHERE 1
			ORDER BY order_by ASC";
	$res = mysql_query($sql);

	if($res && mysql_num_rows($res) > 0)
	{
		?>
		<div id="contentFlow" class="ContentFlow" style="height:300px;border:1px solid #dbdbdb; border-radius:4px 4px 4px 4px; -webkit-border-radius:7px 7px 7px 7px; -moz-border-radius:7px 7px 7px 7px; -khtml-border-radius:7px 7px 7px 7px;">
		<h2>Фоторепортаж</h2>
		<div id="myImageFlow1" class="imageflow" style="height:200px"><?
			while($row = mysql_fetch_array($res))
			{
					/* $tpl = 'pic_catalogue/cbr_pic_'.$alb;
					$img = new Image();
					$img -> imgCatalogId 	= $alb;
					$img -> imgId 			= $row['id'];
					$img -> imgAlt 			= $row['name'];
					$img -> imgLongdesc 	= '/'.$tpl.'/'.$row['id'].'.jpg';
					$img -> imgRel	 		= "fancy";
					$img -> imgClass 		= "content";
					$img -> imgWidthMax 	= 330;
					$img -> imgHeightMax 	= 200;	
					$img -> imgMakeGrayScale= false;
					$img -> imgGrayScale 	= false;
					$img -> imgTransform	= "resize";
					$html = $img -> showPic();
					//$html = $img -> relPath();
					//echo '<a href="/pic_catalogue/cbr_pic_'.$alb.'/'.$row['id'].'.jpg" rel="fancy" title="'.$row['name'].'" class="item viewGallery">'.$html.'</a>'.NL;							
					echo $html.NL;	 */						
			}
			?>
			
			
			<img id='' src='/pic_catalogue/cbr_pic_5/1-330x200.jpg' class='content' rel='fancy' h='/pic_catalogue/cbr_pic_5/1.jpg' width="267" height="200" title='' alt='IMG_7912.jpg' align='' />
<img id='' src='/pic_catalogue/cbr_pic_5/2-330x200.jpg' class='content' rel='fancy' longdesc='/pic_catalogue/cbr_pic_5/2.jpg' width="267" height="200" title='' alt='IMG_7913.jpg' align='' />
<img id='' src='/pic_catalogue/cbr_pic_5/3-330x200.jpg' class='content' rel='fancy' longdesc='/pic_catalogue/cbr_pic_5/3.jpg' width="267" height="200" title='' alt='IMG_7915.jpg' align='' />

			
			
			
			</div></div><?
	}
}


?>
