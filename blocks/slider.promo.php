<?
/************************/
/* слайд-шоу на главной */
/************************/

$sql = "SELECT * FROM `".$_VARS['tbl_photo_name'].$_VARS['env']['slider_promo']."`
		WHERE img_show = '1'
		ORDER BY order_by ASC";
$res = mysql_query($sql);

$arrSlider = array();

?>
<div class="sliderPromo">
		
			<a class='prev browse left'></a>
			
				<div class="scrollable">
				
					<div class='items'>
					<?
					
					while($row = mysql_fetch_array($res))
					{
						?>
						<div class="sliderPromoItem item">
							<div class="view">
								<!--<img src="/img/pic/slide_promo_1.jpg" width="1200" height="422" />-->
								<?
								$img = new Image();
								$img -> imgCatalogId 	= $_VARS['env']['slider_promo'];
								$img -> imgId 			= $row['id'];
								$img -> imgAlt 			= $row['tags'];
								$img -> imgWidthMax 	= 1200;
								$img -> imgHeightMax 	= 422;	
								$img -> imgMakeGrayScale= false;
								$img -> imgGrayScale 	= false;
								$img -> imgTransform	= "resize";
								$html = $img -> showPic();
								//echo $html;
								?>
								<a href="<?=$row['url']?>"><?=$html?></a>
								
								<div class="sliderPromoItemText">
									<h1><?=$row['name']?></h1>
									<p><?=$row['tags']?> <?
									if(trim($row['url']) != '')
									{
										?><a href="<?=$row['url']?>">Подробнее</a><?
									}
									?></p>
								</div>				
							</div>
						</div>
						<?
						
					}
					?> 					
						
					</div>
				
				</div>
				
			<a class='next browse right'></a>
				
			<div style="clear:both"></div>
			
		</div>